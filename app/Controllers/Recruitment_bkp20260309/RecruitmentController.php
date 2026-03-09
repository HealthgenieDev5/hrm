<?php

namespace App\Controllers\Recruitment;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Recruitment\RcJobListingModel;
use App\Models\Recruitment\RcPositionModel;
use App\Models\EmployeeModel;
use App\Models\CompanyModel;
use App\Models\DepartmentModel;
use App\Models\Recruitment\RcJobListingRevisionModel;
use App\Models\Recruitment\RcJobListingCommentModel;
use App\Models\Recruitment\RcJobListingNotificationModel;
use App\Models\Recruitment\RcJobClosureApprovalModel;
//use App\Models\Recruitment\RcJobListingCommentReadsModel;
use App\Models\Recruitment\RcRecruitmentTaskModel;
use App\Models\Recruitment\RcRecruitmentTaskAssigneeModel;
use App\Models\Recruitment\RcRecruitmentTaskRevisionModel;
use App\Models\ShiftModel;
use App\Models\ShiftPerDayModel;
use PhpParser\Node\Expr\AssignOp\Concat;
use \Dompdf\Options;

class RecruitmentController extends BaseController
{
    public $session;
    public $uri;
    public $jobListingModel;
    public $jobListingNotificationModel;
    public $hrExecutive = 52;
    public $hrManagerId = 293;


    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
        $this->jobListingModel = new RcJobListingModel();
        $this->jobListingNotificationModel = new RcJobListingNotificationModel();
        $this->uri       = service('uri');
    }


    public function index()
    {
        $current_user = $this->session->get('current_user');

        if (!in_array($current_user['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {

            return redirect()->to(base_url('/unauthorised'));
        }



        $positionModel = new RcPositionModel();
        $companyModel = new CompanyModel();
        $employeeModel = new EmployeeModel();
        $departmentModel = new DepartmentModel();
        $position = $positionModel->findAll();
        $companies = $companyModel->getAllCompanies();
        $employees = $this->getAllEmployees();
        $departments = $departmentModel->findAll();

        $data = [
            'positions' => $position,
            'companies' => $companies,
            'employees' => $employees,
            'departments' => $departments,
            'page_title' => 'Job Listing',
            'form_action' => base_url('recruitment/job-listing/store')
        ];
        return view('Recruitment/JobListingForm', $data);
    }

    public function getAllEmployees()
    {
        $EmployeeModel = new EmployeeModel();
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->groupStart()
            ->where('employees.date_of_leaving is null')
            ->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')")
            ->groupEnd()
            ->orderBy('employees.first_name', 'ASC');
        $AllEmployees = $EmployeeModel->findAll();
        return $AllEmployees;
    }

    public function getShiftTimings()
    {
        $shiftModel = new ShiftModel();
        $ShiftPerDayModel = new ShiftPerDayModel();
        $shifts = $shiftModel->findAll();
        $shiftData = [];
        if (!empty($shifts)) {
            foreach ($shifts as $shift) {
                $arrShiftTime = $ShiftPerDayModel
                    ->where('shift_id', $shift['id'])
                    ->where('day', 'monday')
                    ->first();

                if ($arrShiftTime['shift_start'] && $arrShiftTime['shift_end']) {
                    $startTime = date('h:i A', strtotime($arrShiftTime['shift_start']));
                    $endTime = date('h:i A', strtotime($arrShiftTime['shift_end']));
                    $timing = $startTime . ' - ' . $endTime;
                }

                $shiftData[] = [
                    'id' => $shift['id'],
                    'text' => $timing . ' (' . $shift['shift_name'] . ')',
                    'timing' => $timing
                ];
            }
        }





        return $this->response->setJSON($shiftData);
    }


    public function store()
    {
        if ($data = $this->request->getPost()) {
            $data['created_by'] = $this->session->get('current_user')['employee_id'];
            $data['job_opening_date'] = NULL; //date("Y-m-d");

            if (!empty($this->request->getPost('kras_file_remove'))) {
                $fileName['kra_distribution_file']['file'] = '';
            } else {
                $attachment = $this->request->getFile('kras_file');
                if ($attachment->isValid() && !$attachment->hasMoved()) {
                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                    $kras_file_uploaded = $attachment->move($upload_folder);
                    if ($kras_file_uploaded) {
                        $fileName['kra_distribution_file']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                    }
                } else {
                    $fileName['kra_distribution_file']['file'] = '';
                }
            }
            $data['attachment'] = json_encode($fileName);

            $technicalTests = [];
            if ($this->request->getPost('technical_test') === 'Yes') {
                $technicalTestsArray = $this->request->getPost('technical_tests');
                if ($technicalTestsArray && is_array($technicalTestsArray)) {
                    foreach ($technicalTestsArray as $test) {
                        if (!empty($test['technical_test'])) {
                            $technicalTests[] = $test['technical_test'];
                        }
                    }
                }
            }

            $data['technical_test_required'] = json_encode([
                'required' => $this->request->getPost('technical_test') ?: 'No',
                'tests' => $technicalTests
            ]);

            // OLD CODE - Commented out for file upload implementation
            // $otherTests = [];
            // if ($this->request->getPost('other_test') === 'Yes') {
            //     $otherTestsArray = $this->request->getPost('other_tests');
            //     if ($otherTestsArray && is_array($otherTestsArray)) {
            //         foreach ($otherTestsArray as $test) {
            //             if (!empty($test['other_test'])) {
            //                 $otherTests[] = $test['other_test'];
            //             }
            //         }
            //     }
            // }
            //
            // $data['other_test_required'] = json_encode([
            //     'required' => $this->request->getPost('other_test') ?: 'No',
            //     'tests' => $otherTests
            // ]);

            // NEW CODE - With file upload support
            $otherTests = [];
            if ($this->request->getPost('other_test') === 'Yes') {
                $otherTestsArray = $this->request->getPost('other_tests');
                $allFiles = $this->request->getFiles();

                if ($otherTestsArray && is_array($otherTestsArray)) {
                    foreach ($otherTestsArray as $index => $test) {
                        if (!empty($test['other_test'])) {
                            $testData = [
                                'name' => $test['other_test'],
                                'file' => ''
                            ];

                            // Check if file exists in the nested structure created by FormRepeater
                            $file = null;
                            if (isset($allFiles['other_tests'][$index]['other_test_file'])) {
                                $file = $allFiles['other_tests'][$index]['other_test_file'];
                            } elseif (isset($test['other_test_file'])) {
                                $file = $test['other_test_file'];
                            }

                            if ($file && $file->isValid() && !$file->hasMoved()) {
                                $validExtensions = ['pdf', 'doc', 'docx'];
                                $maxSize = 5 * 1024 * 1024; // 5MB

                                if (
                                    in_array($file->getExtension(), $validExtensions) &&
                                    $file->getSize() <= $maxSize
                                ) {
                                    $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                                    if (!is_dir($upload_folder)) {
                                        mkdir($upload_folder, 0777, true);
                                    }

                                    $newFileName = $file->getRandomName();
                                    if ($file->move($upload_folder, $newFileName)) {
                                        $testData['file'] = str_replace(
                                            WRITEPATH,
                                            "/",
                                            $upload_folder . '/' . $newFileName
                                        );
                                    }
                                }
                            }

                            $otherTests[] = $testData;
                        }
                    }
                }
            }

            $data['other_test_required'] = json_encode([
                'required' => $this->request->getPost('other_test') ?: 'No',
                'tests' => $otherTests
            ]);

            $data['iq_test_required'] = json_encode([
                'required' => $this->request->getPost('iq_test') ?: 'No'
            ]);

            $data['eng_test_required'] = json_encode([
                'required' => $this->request->getPost('english_test') ?: 'No'
            ]);

            $data['operation_test_required'] = json_encode([
                'required' => $this->request->getPost('operation_test') ?: 'No'
            ]);

            $data['shift_timing'] = $this->request->getPost('shift_timing');

            $data['job_description'] = $this->request->getPost('job_description');

            unset(
                $data['technical_test'],
                $data['technical_tests'],
                $data['iq_test'],
                $data['english_test'],
                $data['operation_test'],
                $data['other_test'],
                $data['other_tests']
            );

            $numberOfVacancies = (int) ($data['no_of_vacancy'] ?? 1);
            $numberOfVacancies = max(1, $numberOfVacancies);

            $data['no_of_vacancy'] = 1;

            $createdJobIds = [];

            for ($i = 0; $i < $numberOfVacancies; $i++) {
                if ($jobId = $this->jobListingModel->insert($data)) {
                    $createdJobIds[] = $jobId;

                    $notificationData = [
                        'job_listing_id' => $jobId,
                        'user_id'        => $this->hrExecutive,
                        'read_at'        => null
                    ];
                    $notificationModel = new RcJobListingNotificationModel();
                    $notificationModel->insert($notificationData);
                } else {
                    return redirect()->back()->withInput()->with('errors', $this->jobListingModel->errors());
                }
            }


            if (!empty($createdJobIds)) {
                $successMessage = count($createdJobIds) > 1
                    ? 'Job listings created successfully (' . count($createdJobIds) . ' positions).'
                    : 'Job listing created successfully.';

                if ($numberOfVacancies == 1) {
                    return redirect()->to(base_url('/recruitment/job-listing/view/' . $createdJobIds[0]))->with('success', $successMessage);
                }
                return redirect()->to(base_url('/recruitment/job-listing/all'))->with('success', $successMessage);
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create job listings.');
            }
        }

        return redirect()->back()->with('error', 'Invalid request method.');
    }

    // private function downloadAttachment($filePath)
    // {
    //     if (file_exists(WRITEPATH . $filePath)) {
    //         return $this->response->download(WRITEPATH . $filePath, null)->setFileName(basename($filePath));
    //     } else {
    //         return redirect()->back()->with('error', 'File not found.');
    //     }
    // }

    public function jobListView()
    {
        $job_listings = $this->getJobListData();
        if ($job_listings === null || empty($job_listings)) {
            //return redirect()->back()->with('error', 'No job listings found.');
            return redirect()->to(base_url('recruitment/job-listing'))->with('error', 'No job listings found.');
        }
        $companyModel = new CompanyModel();
        $departmentModel = new DepartmentModel();
        $companies = $companyModel->getAllCompanies();
        $departments = $departmentModel->findAll();

        $data = [
            'job_listings' => $job_listings,
            'page_title' => 'Job Listings',
            'companies' => $companies,
            'departments' => $departments,
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'current_method'        => $this->request->getUri()->getSegment(2),
        ];
        // print_r($data);
        // die;

        return view('Recruitment/JobListView', $data);
        // return view('Recruitment/JobListView', [
        //     'page_title' => 'All Job Listings'
        // ]);
    }

    public function getJobListData()
    {
        $job_listings = $this->jobListingModel
            ->select('rc_job_listing.*')
            ->select('companies.company_short_name as company_name')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select('created_by_designation.designation_name as created_by_designation')
            ->select("CONCAT(reporting_to.first_name, ' ', reporting_to.last_name) as reporting_to_name")
            ->select('reporting_to_designation.designation_name as reporting_to_designation')
            ->select("CONCAT(review_schedule_3m.first_name, ' ', review_schedule_3m.last_name) as review_schedule_3m_name")
            ->select('review_3m_designation.designation_name as review_schedule_3m_designation')
            ->select("CONCAT(review_schedule_6m.first_name, ' ', review_schedule_6m.last_name) as review_schedule_6m_name")
            ->select('review_6m_designation.designation_name as review_schedule_6m_designation')
            ->select("CONCAT(review_schedule_12m.first_name, ' ', review_schedule_12m.last_name) as review_schedule_12m_name")
            ->select('review_12m_designation.designation_name as review_schedule_12m_designation')
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.created_by', 'left')
            ->join('designations as created_by_designation', 'created_by_designation.id = employees.designation_id', 'left')
            ->join('employees as reporting_to', 'reporting_to.id = rc_job_listing.reporting_to', 'left')
            ->join('designations as reporting_to_designation', 'reporting_to_designation.id = reporting_to.designation_id', 'left')
            ->join('employees as review_schedule_3m', 'review_schedule_3m.id = rc_job_listing.review_schedule_3m', 'left')
            ->join('designations as review_3m_designation', 'review_3m_designation.id = review_schedule_3m.designation_id', 'left')
            ->join('employees as review_schedule_6m', 'review_schedule_6m.id = rc_job_listing.review_schedule_6m', 'left')
            ->join('designations as review_6m_designation', 'review_6m_designation.id = review_schedule_6m.designation_id', 'left')
            ->join('employees as review_schedule_12m', 'review_schedule_12m.id = rc_job_listing.review_schedule_12m', 'left')
            ->join('designations as review_12m_designation', 'review_12m_designation.id = review_schedule_12m.designation_id', 'left')
            ->asObject()
            ->findAll();




        return $job_listings;
    }


    public function edit($id)
    {
        $companyModel = new CompanyModel();
        $departmentModel = new DepartmentModel();
        $job = $this->jobListingModel
            ->select('rc_job_listing.*, companies.company_short_name as company_name')
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->where('rc_job_listing.id', $id)
            ->asObject()
            ->first();

        if (!$job) {
            return redirect()->back()->with('error', 'Job not found.');
        }

        $data = [
            'job' => $job,
            'page_title' => 'Edit Job Listing',
            'companies' => $companyModel->findAll(),
            'departments' => $departmentModel->findAll(),
            'employees' => $this->getAllEmployees(),
            'form_action' => base_url('recruitment/job-listing/update/' . $id)
        ];
        return view('Recruitment/EditJobView', $data);
    }



    public function update($id)
    {
        $data = $this->request->getPost();
        $oldJob = (object) $this->jobListingModel->find($id);

        if (!$oldJob) {
            return redirect()->back()->with('error', 'Job not found.');
        }


        $attachment = $this->request->getFile('kras_file');
        $removeFile = $this->request->getPost('remove_kras_file');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }
            $newFileName = $attachment->getRandomName();
            $kras_file_uploaded = $attachment->move($upload_folder, $newFileName);

            if ($kras_file_uploaded) {
                if (!empty($oldJob->attachment)) {
                    $oldAttachments = json_decode($oldJob->attachment, true);
                    if (isset($oldAttachments['kra_distribution_file']['file']) && !empty($oldAttachments['kra_distribution_file']['file'])) {
                        $oldFilePath = WRITEPATH . ltrim($oldAttachments['kra_distribution_file']['file'], '/');
                        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                }
                $fileNameArray['kra_distribution_file']['file'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $newFileName);
                $data['attachment'] = json_encode($fileNameArray);
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to upload KRA distribution file.');
            }
        } elseif ($removeFile && !empty($oldJob->attachment)) {
            $oldAttachments = json_decode($oldJob->attachment, true);
            if (isset($oldAttachments['kra_distribution_file']['file']) && !empty($oldAttachments['kra_distribution_file']['file'])) {
                $oldFilePath = WRITEPATH . ltrim($oldAttachments['kra_distribution_file']['file'], '/');
                if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $data['attachment'] = null;
        } else {
            $data['attachment'] = $oldJob->attachment;
        }

        // Handle Other Test files
        $otherTestsArray = $this->request->getPost('other_tests');
        $allFiles = $this->request->getFiles();
        $removeOtherTestFiles = $this->request->getPost('remove_other_test_file') ?? [];

        $otherTests = [];
        $oldOtherTestData = json_decode($oldJob->other_test_required ?? '{}', true);
        $oldTests = $oldOtherTestData['tests'] ?? [];

        if ($this->request->getPost('other_test') === 'Yes' && $otherTestsArray && is_array($otherTestsArray)) {
            foreach ($otherTestsArray as $index => $test) {
                if (!empty($test['other_test'])) {
                    $testData = [
                        'name' => $test['other_test'],
                        'file' => ''
                    ];

                    // Check if marked for deletion
                    if (isset($removeOtherTestFiles[$index]) && $removeOtherTestFiles[$index] == '1') {
                        if (isset($oldTests[$index])) {
                            $oldTestItem = $oldTests[$index];
                            $oldFile = is_array($oldTestItem) ? ($oldTestItem['file'] ?? '') : '';

                            if (!empty($oldFile)) {
                                $oldFilePath = WRITEPATH . ltrim($oldFile, '/');
                                if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                                    unlink($oldFilePath);
                                }
                            }
                        }
                    }
                    // Check if new file uploaded (in nested structure from FormRepeater)
                    else {
                        $file = null;
                        if (isset($allFiles['other_tests'][$index]['other_test_file'])) {
                            $file = $allFiles['other_tests'][$index]['other_test_file'];
                        } elseif (isset($test['other_test_file'])) {
                            $file = $test['other_test_file'];
                        }

                        if ($file && $file->isValid() && !$file->hasMoved()) {
                            $validExtensions = ['pdf', 'doc', 'docx'];
                            $maxSize = 5 * 1024 * 1024; // 5MB

                            if (
                                in_array($file->getExtension(), $validExtensions) &&
                                $file->getSize() <= $maxSize
                            ) {

                                $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                                if (!is_dir($upload_folder)) {
                                    mkdir($upload_folder, 0777, true);
                                }

                                $newFileName = $file->getRandomName();
                                if ($file->move($upload_folder, $newFileName)) {
                                    // Delete old file if exists
                                    if (isset($oldTests[$index])) {
                                        $oldTestItem = $oldTests[$index];
                                        $oldFile = is_array($oldTestItem) ? ($oldTestItem['file'] ?? '') : '';

                                        if (!empty($oldFile)) {
                                            $oldFilePath = WRITEPATH . ltrim($oldFile, '/');
                                            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                                                unlink($oldFilePath);
                                            }
                                        }
                                    }

                                    $testData['file'] = str_replace(
                                        WRITEPATH,
                                        "/",
                                        $upload_folder . '/' . $newFileName
                                    );
                                }
                            }
                        }
                        // Keep existing file if no new file uploaded
                        elseif (isset($oldTests[$index])) {
                            $oldTestItem = $oldTests[$index];
                            $testData['file'] = is_array($oldTestItem) ? ($oldTestItem['file'] ?? '') : '';
                        }
                    }

                    $otherTests[] = $testData;
                }
            }
        }

        $data['other_test_required'] = json_encode([
            'required' => $this->request->getPost('other_test') ?: 'No',
            'tests' => $otherTests
        ]);


        // $data['job_description'] = json_encode([
        //     'points' => $this->request->getPost('job_description')
        // ]);

        $revisionModel = new RcJobListingRevisionModel();
        $jobModel = $this->jobListingModel;
        $allowedFields = $jobModel->allowedFields;
        $current_user = $this->session->get('current_user')['employee_id'];

        foreach ($data as $field => $newValue) {
            if ($field === 'csrf_test_name' || !in_array($field, $allowedFields)) {
                continue;
            }

            $oldValue = $oldJob->$field ?? null;

            if (in_array($field, ['requirement', 'job_description', 'attachment']) && is_string($newValue)) {
                $decodedOld = json_decode($oldValue, true) ?? [];
                $decodedNew = json_decode($newValue, true) ?? [];

                if ($decodedOld == $decodedNew) continue;

                $oldValue = json_encode($decodedOld, JSON_UNESCAPED_UNICODE);
                $newValue = json_encode($decodedNew, JSON_UNESCAPED_UNICODE);
            } else {
                if (is_numeric($oldValue) && is_numeric($newValue)) {
                    if ((float)$oldValue == (float)$newValue) continue;
                } else {
                    $oldValueStr = is_array($oldValue) ? json_encode($oldValue) : (string) $oldValue;
                    $newValueStr = is_array($newValue) ? json_encode($newValue) : (string) $newValue;

                    if ($oldValueStr === $newValueStr) continue;
                }
            }

            $revisionModel->insert([
                'listing_id' => $id,
                'field_name' => $field,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'updated_by' => $current_user,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->jobListingModel->update($id, $data);

        return redirect()->to(base_url('/recruitment/job-listing/edit/' . $id))
            ->with('success', 'Job listing updated successfully.');
    }

    public function downloadAttachment($id)
    {
        $job = $this->jobListingModel->asObject()->find($id);

        if (!$job || empty($job->attachment)) {
            return redirect()->back()->with('error', 'Attachment not found.');
        }

        $attachments = json_decode($job->attachment, true);

        if (!isset($attachments['kra_distribution_file']['file'])) {
            return redirect()->back()->with('error', 'Attachment file path not found in job data.');
        }

        $filePath = $attachments['kra_distribution_file']['file'];
        $fullPath = WRITEPATH . ltrim($filePath, '/');

        log_message('debug', 'Attempting to download file from: ' . $fullPath);
        log_message('debug', 'File exists: ' . (file_exists($fullPath) ? 'true' : 'false'));

        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return $this->response->download($fullPath, null)->setFileName(basename($filePath));
    }


    // public function downloadOtherTest($jobId, $testIndex)
    // {
    //     $job = $this->jobListingModel->asObject()->find($jobId);

    //     if (!$job || empty($job->other_test_required)) {
    //         return redirect()->back()->with('error', 'Test file not found.');
    //     }

    //     $otherTestData = json_decode($job->other_test_required, true);
    //     $tests = $otherTestData['tests'] ?? [];

    //     if (!isset($tests[$testIndex])) {
    //         return redirect()->back()->with('error', 'Test index not found.');
    //     }

    //     $testItem = $tests[$testIndex];
    //     $filePath = is_array($testItem) ? ($testItem['file'] ?? '') : '';

    //     if (empty($filePath)) {
    //         return redirect()->back()->with('error', 'No file attached to this test.');
    //     }

    //     $fullPath = WRITEPATH . ltrim($filePath, '/');

    //     if (!file_exists($fullPath) || !is_file($fullPath)) {
    //         return redirect()->back()->with('error', 'File not found on server.');
    //     }

    //     return $this->response->download($fullPath, null)->setFileName(basename($filePath));
    // }


    public function downloadJobOpeningPdf($id)
    {
        $job_listings = $this->jobListingModel
            ->select('rc_job_listing.*')
            ->select('companies.company_short_name as company_name')
            ->select('companies.logo_url as logo')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select('created_by_designation.designation_name as created_by_designation')
            ->select("CONCAT(reporting_to.first_name, ' ', reporting_to.last_name) as reporting_to_name")
            ->select('reporting_to_designation.designation_name as reporting_to_designation')
            ->select("CONCAT(review_schedule_3m.first_name, ' ', review_schedule_3m.last_name) as review_schedule_3m_name")
            ->select('review_3m_designation.designation_name as review_schedule_3m_designation')
            ->select("CONCAT(review_schedule_6m.first_name, ' ', review_schedule_6m.last_name) as review_schedule_6m_name")
            ->select('review_6m_designation.designation_name as review_schedule_6m_designation')
            ->select("CONCAT(review_schedule_12m.first_name, ' ', review_schedule_12m.last_name) as review_schedule_12m_name")
            ->select('review_12m_designation.designation_name as review_schedule_12m_designation')
            ->select("CONCAT(hr_exec_approver.first_name, ' ', hr_exec_approver.last_name) as hr_executive_approver_name")
            ->select("CONCAT(hod_approver.first_name, ' ', hod_approver.last_name) as hod_approver_name")
            ->select("CONCAT(hr_manager_approver.first_name, ' ', hr_manager_approver.last_name) as hr_manager_approver_name")
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.created_by', 'left')
            ->join('designations as created_by_designation', 'created_by_designation.id = employees.designation_id', 'left')
            ->join('employees as reporting_to', 'reporting_to.id = rc_job_listing.reporting_to', 'left')
            ->join('designations as reporting_to_designation', 'reporting_to_designation.id = reporting_to.designation_id', 'left')
            ->join('employees as review_schedule_3m', 'review_schedule_3m.id = rc_job_listing.review_schedule_3m', 'left')
            ->join('designations as review_3m_designation', 'review_3m_designation.id = review_schedule_3m.designation_id', 'left')
            ->join('employees as review_schedule_6m', 'review_schedule_6m.id = rc_job_listing.review_schedule_6m', 'left')
            ->join('designations as review_6m_designation', 'review_6m_designation.id = review_schedule_6m.designation_id', 'left')
            ->join('employees as review_schedule_12m', 'review_schedule_12m.id = rc_job_listing.review_schedule_12m', 'left')
            ->join('designations as review_12m_designation', 'review_12m_designation.id = review_schedule_12m.designation_id', 'left')
            ->join('employees as hr_exec_approver', 'hr_exec_approver.id = rc_job_listing.approved_by_hr_executive', 'left')
            ->join('employees as hod_approver', 'hod_approver.id = rc_job_listing.approved_by_hod', 'left')
            ->join('employees as hr_manager_approver', 'hr_manager_approver.id = rc_job_listing.approved_by_hr_manager', 'left')
            ->where('rc_job_listing.id', $id)
            ->asObject()
            ->first();
        $data = [
            'job' => $job_listings,
            'page_title' => 'Job Opening PDF'
        ];

        $options = new options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);

        $content = view('Recruitment/JobOpeningPdf', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'job_opening_' . $id . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        //return $pdf->stream($filename, ['Attachment' => true]);
    }

    public function downloadJobClosurePdf($id)
    {
        // Get comprehensive job details
        $job = $this->jobListingModel
            ->select('rc_job_listing.*, companies.company_short_name as company_name, companies.logo_url as logo')
            ->select('departments.department_name')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select('created_by_designation.designation_name as created_by_designation')
            ->select("CONCAT(reporting_to.first_name, ' ', reporting_to.last_name) as reporting_to_name")
            ->select('reporting_to_designation.designation_name as reporting_to_designation')
            ->select("CONCAT(review_schedule_3m.first_name, ' ', review_schedule_3m.last_name) as review_schedule_3m_name")
            ->select("CONCAT(review_schedule_6m.first_name, ' ', review_schedule_6m.last_name) as review_schedule_6m_name")
            ->select("CONCAT(review_schedule_12m.first_name, ' ', review_schedule_12m.last_name) as review_schedule_12m_name")
            ->select("CONCAT(hr_exec_approver.first_name, ' ', hr_exec_approver.last_name) as hr_executive_approver_name")
            ->select("CONCAT(hod_approver.first_name, ' ', hod_approver.last_name) as hod_approver_name")
            ->select("CONCAT(hr_manager_approver.first_name, ' ', hr_manager_approver.last_name) as hr_manager_approver_name")
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.created_by', 'left')
            ->join('designations as created_by_designation', 'created_by_designation.id = employees.designation_id', 'left')
            ->join('employees as reporting_to', 'reporting_to.id = rc_job_listing.reporting_to', 'left')
            ->join('designations as reporting_to_designation', 'reporting_to_designation.id = reporting_to.designation_id', 'left')
            ->join('employees as review_schedule_3m', 'review_schedule_3m.id = rc_job_listing.review_schedule_3m', 'left')
            ->join('employees as review_schedule_6m', 'review_schedule_6m.id = rc_job_listing.review_schedule_6m', 'left')
            ->join('employees as review_schedule_12m', 'review_schedule_12m.id = rc_job_listing.review_schedule_12m', 'left')
            ->join('employees as hr_exec_approver', 'hr_exec_approver.id = rc_job_listing.approved_by_hr_executive', 'left')
            ->join('employees as hod_approver', 'hod_approver.id = rc_job_listing.approved_by_hod', 'left')
            ->join('employees as hr_manager_approver', 'hr_manager_approver.id = rc_job_listing.approved_by_hr_manager', 'left')
            ->where('rc_job_listing.id', $id)
            ->asObject()
            ->first();

        if (!$job) {
            return redirect()->back()->with('error', 'Job not found.');
        }

        // Get closure details
        $closureModel = new RcJobClosureApprovalModel();
        $closureDetails = $closureModel
            ->select('rc_job_closure_approvals.*')
            ->select("CONCAT(selected_candidate.first_name, ' ', selected_candidate.last_name) as selected_candidate_name")
            ->select('selected_candidate.internal_employee_id as selected_candidate_id')
            ->select('selected_candidate.joining_date as selected_candidate_joining_date')
            ->select("CONCAT(replacement_employee.first_name, ' ', replacement_employee.last_name) as replacement_employee_name")
            ->select('replacement_employee.internal_employee_id as replacement_employee_id')
            ->select("CONCAT(best_performer.first_name, ' ', best_performer.last_name) as best_performer_name")
            ->select("CONCAT(worst_performer.first_name, ' ', worst_performer.last_name) as worst_performer_name")
            ->select("CONCAT(hr_approver.first_name, ' ', hr_approver.last_name) as hr_approver_name")
            ->select("CONCAT(manager_approver.first_name, ' ', manager_approver.last_name) as manager_approver_name")
            ->join('employees as selected_candidate', 'selected_candidate.id = rc_job_closure_approvals.selected_candidate_id', 'left')
            ->join('employees as replacement_employee', 'replacement_employee.id = rc_job_closure_approvals.replacement_of_employee_id', 'left')
            ->join('employees as best_performer', 'best_performer.id = rc_job_closure_approvals.best_performer_id', 'left')
            ->join('employees as worst_performer', 'worst_performer.id = rc_job_closure_approvals.worst_performer_id', 'left')
            ->join('employees as hr_approver', 'hr_approver.id = rc_job_closure_approvals.hr_approved_by', 'left')
            ->join('employees as manager_approver', 'manager_approver.id = rc_job_closure_approvals.manager_approved_by', 'left')
            ->where('rc_job_closure_approvals.job_listing_id', $id)
            ->asObject()
            ->first();

        if (!$closureDetails) {
            return redirect()->back()->with('error', 'Job closure details not found.');
        }

        $data = [
            'job' => $job,
            'closure' => $closureDetails,
            'page_title' => 'Job Closure PDF - ' . $job->job_title
        ];

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);

        $content = view('Recruitment/JobClosurePdf', $data);

        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'job_closure_' . $job->job_title . '_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }




    public function view($id)
    {
        $job = $this->jobListingModel
            ->select('rc_job_listing.*, companies.company_short_name as company_name, departments.department_name, CONCAT(employees.first_name, " ", employees.last_name) as reporting_to_name')
            ->select("CONCAT(created_by.first_name, ' ', created_by.last_name) as created_by_name")
            ->select('reporting_to_designation.designation_name as reporting_to_designation')
            ->select("CONCAT(review_schedule_3m.first_name, ' ', review_schedule_3m.last_name) as review_schedule_3m_name")
            ->select("CONCAT(review_schedule_6m.first_name, ' ', review_schedule_6m.last_name) as review_schedule_6m_name")
            ->select("CONCAT(review_schedule_12m.first_name, ' ', review_schedule_12m.last_name) as review_schedule_12m_name")
            ->select('departments.hod_employee_id as department_hod_id')
            ->select("CONCAT(hod_employee.first_name, ' ', hod_employee.last_name) as department_hod_name")
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.reporting_to', 'left')
            ->join('employees as created_by', 'created_by.id = rc_job_listing.created_by', 'left')
            ->join('employees as hod_employee', 'hod_employee.id = departments.hod_employee_id', 'left')
            ->join('designations as reporting_to_designation', 'reporting_to_designation.id = employees.designation_id', 'left')
            ->join('employees as review_schedule_3m', 'review_schedule_3m.id = rc_job_listing.review_schedule_3m', 'left')
            ->join('employees as review_schedule_6m', 'review_schedule_6m.id = rc_job_listing.review_schedule_6m', 'left')
            ->join('employees as review_schedule_12m', 'review_schedule_12m.id = rc_job_listing.review_schedule_12m', 'left')
            ->where('rc_job_listing.id', $id)
            ->asObject()
            ->first();

        if (!$job) {
            return redirect()->back()->with('error', 'Job not found.');
        }

        // Mark resolutions as closed when user visits the page
        $this->markResolutionsAsClosed($id);

        $commentModel = new RcJobListingCommentModel();

        $comments = $commentModel
            ->select('rc_job_listing_comments.*')
            ->select('CONCAT(s.first_name, " ", s.last_name) AS sender_name')
            // ->select('r.receiver_id, r.is_read, r.read_at, r.delivered_at')
            // ->select('CONCAT(rv.first_name, " ", rv.last_name) AS receiver_name')
            ->join('employees AS s', 's.id = rc_job_listing_comments.sender_id', 'left')
            // ->join('rc_job_listing_comment_reads AS r', 'r.comment_id = rc_job_listing_comments.id', 'left')
            // ->join('employees AS rv', 'rv.id = r.receiver_id', 'left')
            ->where('rc_job_listing_comments.listing_id', $id)
            ->orderBy('rc_job_listing_comments.created_at', 'DESC')
            ->findAll();
        // Get employees for closure dropdowns
        $employees = $this->getAllEmployees();

        $data = [
            'job' => $job,
            'comments' => $comments,
            'employees' => $employees,
            'page_title' => 'Job Listing Details',
            'hr_executive' => $this->hrExecutive,
            'hr_manager' => $this->hrManagerId
        ];

        //$data['approvalCounts'] = getApprovalCounts($this->session->get('current_user')['employee_id']);

        //    $current_user = $this->session->get('current_user');
        //    $approvalCounts = $this->getApprovalCounts($current_user['employee_id']);
        //    $data['approvalCounts'] = $approvalCounts;


        return view('Recruitment/JobSingleView', $data);
    }

    // public function addComment($id)
    // {
    //     $commentModel = new RcJobListingCommentModel();
    //     $data = [
    //         'listing_id' => $id,
    //         'author_id' => $this->session->get('current_user')['employee_id'],
    //         'content' => $this->request->getPost('comment'),
    //         'type' => $this->request->getPost('type'),
    //     ];

    //     if ($commentModel->insert($data)) {
    //         return redirect()->to(base_url('/recruitment/job-listing/view/' . $id))->with('success', 'Comment added successfully.');
    //     } else {
    //         return redirect()->back()->withInput()->with('errors', $commentModel->errors());
    //     }
    // }

    public function approve()
    {
        $jobId        = $this->request->getPost('job_id');
        $approvalType = $this->request->getPost('approval_type');
        $currentUserId = $this->session->get('current_user')['employee_id'];

        if (!$jobId || !$approvalType) {
            return $this->fail('Job ID and approval type are required');
        }

        $job = $this->jobListingModel
            ->select('rc_job_listing.*, departments.hod_employee_id as department_hod_id')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->where('rc_job_listing.id', $jobId)
            ->asArray()
            ->first();

        if (!$job) {
            return $this->fail('Job listing not found');
        }

        $updateData = [];
        $notifyUserId = null;

        switch ($approvalType) {
            case 'hr_executive':
                if ($currentUserId != $this->hrExecutive) return $this->fail('Only HR Executive can approve at this level');
                if (!empty($job['approved_by_hr_executive'])) return $this->fail('Already approved by HR Executive');

                $updateData = [
                    'approved_by_hr_executive' => $currentUserId
                ];

                // Auto-approve HOD step if the job creator is the department HOD
                if (!empty($job['department_hod_id']) && $job['created_by'] == $job['department_hod_id']) {
                    $updateData['approved_by_hod'] = $job['department_hod_id'];
                    $notifyUserId = $this->hrManagerId;
                } else {
                    $notifyUserId = $job['department_hod_id'];
                }
                break;

            case 'hod':
                if ($currentUserId != $job['department_hod_id']) return $this->fail('Only the designated HOD can approve');
                if (empty($job['approved_by_hr_executive'])) return $this->fail('HR Executive approval required first');
                if (!empty($job['approved_by_hod'])) return $this->fail('Already approved by HOD');

                $updateData['approved_by_hod'] = $currentUserId;
                $notifyUserId = $this->hrManagerId; // HR Manager
                break;

            case 'hr_manager':
                if ($currentUserId != $this->hrManagerId) return $this->fail('Only HR Manager can approve');
                if (empty($job['approved_by_hr_executive'])) return $this->fail('HR Executive approval required first');
                if (empty($job['approved_by_hod'])) return $this->fail('HOD approval required first');
                if (!empty($job['approved_by_hr_manager'])) return $this->fail('Already approved by HR Manager');

                // OLD CODE - Commented out: Job opening date was manually entered by HR Manager
                // $jobOpeningDate = $this->request->getPost('job_opening_date');
                // if (!$jobOpeningDate) return $this->fail('Job opening date is required');

                // NEW CODE: HR Manager approval date is automatically used as the official Job Opening Date
                $jobOpeningDate = date('Y-m-d');

                $updateData = [
                    'approved_by_hr_manager' => $currentUserId,
                    'job_opening_date' => $jobOpeningDate,
                    'status' => 'open'
                ];
                break;

            default:
                return $this->fail('Invalid approval type');
        }

        if ($this->jobListingModel->update($jobId, $updateData)) {
            if ($notifyUserId) {
                (new RcJobListingNotificationModel())->insert([
                    'job_listing_id' => $jobId,
                    'user_id'        => $notifyUserId,
                    'read_at'        => null
                ]);
            }

            return $this->respondSuccess("Job listing approved successfully", ['approval_type' => $approvalType]);
        }

        return $this->fail('Failed to update job listing');
    }

    private function fail(string $message)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message]);
    }

    private function respondSuccess(string $message, array $extra = [])
    {
        return $this->response->setJSON(array_merge(
            ['status' => 'success', 'message' => $message],
            $extra
        ));
    }

    // private function getPendingApprovalCounts(int $userId): array
    // {
    //     $counts = [
    //         'pending_by_hr_executive' => 0,
    //         'pending_by_hod' => 0,
    //         'pending_by_hr_manager' => 0,
    //     ];

    //     $jobListingModel = new RcJobListingModel();



    //     // Pending by HR Executive
    //     $counts['pending_by_hr_executive'] = $jobListingModel
    //         ->where('approved_by_hr_executive IS NULL')
    //         ->countAllResults();

    //     // Pending by HOD
    //     $counts['pending_by_hod'] = $jobListingModel
    //         ->where('approved_by_hr_executive IS NOT NULL')
    //         ->where('approved_by_hod IS NULL')
    //         ->countAllResults();

    //     // Pending by HR Manager
    //     $counts['pending_by_hr_manager'] = $jobListingModel
    //         ->where('approved_by_hr_executive IS NOT NULL')
    //         ->where('approved_by_hod IS NOT NULL')
    //         ->where('approved_by_hr_manager IS NULL')
    //         ->countAllResults();

    //     return $counts;
    // }



    public function getJobListAjax()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id = isset($params['company']) ? $params['company'] : '';
        $department_id = isset($params['department']) ? $params['department'] : '';
        $job_type = isset($params['job_type']) ? $params['job_type'] : '';
        $status = isset($params['status']) ? $params['status'] : '';
        $from_date = isset($params['from_date']) ? $params['from_date'] : '';
        $to_date = isset($params['to_date']) ? $params['to_date'] : '';

        $current_user = $this->session->get('current_user');

        $jobListingModel = $this->jobListingModel;
        $jobListingModel
            ->select('rc_job_listing.*')
            ->select('companies.company_short_name as company_name')
            ->select('departments.department_name')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select("CONCAT(reporting_to.first_name, ' ', reporting_to.last_name) as reporting_to_name")
            ->select("CONCAT(hod.first_name, ' ', hod.last_name) as hod_name")
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->join('employees', 'employees.id = rc_job_listing.created_by', 'left')
            ->join('employees as reporting_to', 'reporting_to.id = rc_job_listing.reporting_to', 'left')
            ->join('employees as hod', 'hod.id = departments.hod_employee_id', 'left');

        if (!empty($company_id) && is_array($company_id)) {
            if (!in_array('all_companies', $company_id)) {
                $jobListingModel->whereIn('rc_job_listing.company_id', $company_id);
            }
        }

        if (!empty($department_id) && is_array($department_id)) {
            if (!in_array('all_departments', $department_id)) {
                $jobListingModel->whereIn('rc_job_listing.department_id', $department_id);
            }
        }

        if (!empty($job_type) && is_array($job_type)) {
            if (!in_array('all_job_types', $job_type)) {
                $jobListingModel->whereIn('rc_job_listing.type_of_job', $job_type);
            }
        }

        if (!empty($status) && is_array($status)) {
            if (!in_array('all_status', $status)) {
                $jobListingModel->whereIn('rc_job_listing.status', $status);
            }
        }

        if (!empty($from_date) && !empty($to_date)) {
            $jobListingModel->where('rc_job_listing.job_opening_date >=', $from_date);
            $jobListingModel->where('rc_job_listing.job_opening_date <=', $to_date);
        }

        $jobListingModel->orderBy('rc_job_listing.created_at', 'DESC');

        $job_listings = $jobListingModel->asObject()->findAll();

        $formatted_data = [];
        foreach ($job_listings as $job) {
            $status = strtolower($job->status);
            $badgeClass = match ($status) {
                'active', 'open' => 'primary',
                'in progress' => 'success',
                'closed' => 'danger',
                'pending' => 'warning',
                'draft' => 'secondary',
                default => 'info'
            };

            $jobOpeningDate = !empty($job->job_opening_date) && $job->job_opening_date !== '0000-00-00'
                ? date('d M, Y', strtotime($job->job_opening_date))
                : 'Not Set';

            $tests = [];
            $technicalTestRequired = json_decode($job->technical_test_required, true);
            $iqTestRequired = json_decode($job->iq_test_required, true);
            $engTestRequired = json_decode($job->eng_test_required, true);
            $operationTestRequired = json_decode($job->operation_test_required, true);
            $otherTestRequired = json_decode($job->other_test_required, true);


            if (!empty($technicalTestRequired) && $technicalTestRequired['required'] !== 'No') $tests[] = 'Tech';
            if (!empty($job->iq_test_required) && $iqTestRequired['required'] !== 'No') $tests[] = 'IQ';
            if (!empty($job->eng_test_required) && $engTestRequired['required'] !== 'No') $tests[] = 'Eng';
            if (!empty($job->operation_test_required) && $operationTestRequired['required'] !== 'No') $tests[] = 'Op';
            if (!empty($job->other_test_required) && $otherTestRequired['required'] !== 'No') $tests[] = 'Other';
            $testsString = !empty($tests) ? implode(', ', $tests) : 'None';

            $formatted_data[] = [
                'id' => $job->id,
                'job_title' => $job->job_title ?? '',
                'company_name' => $job->company_name ?? 'N/A',
                'department_name' => $job->department_name ?? 'N/A',
                'hod_name' => $job->hod_name ?? 'N/A',
                'type_of_job' => $job->type_of_job ?? 'N/A',
                'min_budget' => $job->min_budget ?? 0,
                'max_budget' => $job->max_budget ?? 0,
                'min_experience' => $job->min_experience ?? 0,
                'max_experience' => $job->max_experience ?? 0,
                'no_of_vacancy' => $job->no_of_vacancy ?? '0',
                'status' => ucwords($job->status ?? ''),
                'status_badge_class' => $badgeClass,
                'job_opening_date' => $jobOpeningDate,
                'interview_location' => $job->interview_location ?? 'N/A',
                'seating_location' => ucwords($job->seating_location) ?? 'N/A',
                'system_required' => ucwords($job->system_required ?? 'No'),
                'system_required_badge' => ($job->system_required === 'yes') ? 'success' : 'danger',
                'tests_required' => $testsString,
                'shift_timing' => $job->shift_timing ?? 'N/A',
                'educational_qualification' => $job->educational_qualification ?? 'N/A',
                'specific_industry' => $job->specific_industry ?? 'N/A',
                'expected_closure_date' => $job->expected_closure_date ?? 'N/A',
                'created_by' => isset($job->created_by) ? (int) $job->created_by : null,
                'created_by_name' => $job->created_by_name ?? 'N/A',
                'created_at' => !empty($job->created_at) ? date('d M, Y', strtotime($job->created_at)) : 'N/A',
                'approved_by_hr_manager' => $job->approved_by_hr_manager ?? null
            ];
        }

        return $this->response->setJSON($formatted_data);
    }



    // public function close($id)
    // {
    //     $job = $this->jobListingModel->find($id);
    //     if ($job) {
    //         $data = [
    //             'status' => 'closed',
    //             'job_closing_date' => date('Y-m-d')
    //         ];
    //         $this->jobListingModel->update($id, $data);
    //         return redirect()->to(base_url('/recruitment/job-listing/all'))->with('success', 'Job listing closed successfully.||Job listing closed successfully!');
    //     } else {
    //         return redirect()->to(base_url('/recruitment/job-listing/all'))->with('error', 'Job listing not found.');
    //     }
    // }

    // public function reject($id)
    // {
    //     $job = $this->jobListingModel->find($id);
    //     if ($job) {
    //         $data = [
    //             'status' => 'rejected',
    //             'job_closing_date' => date('Y-m-d')
    //         ];
    //         $this->jobListingModel->update($id, $data);
    //         return redirect()->to(base_url('/recruitment/job-listing/all'))->with('success', 'Job listing reject successfully.');
    //     } else {
    //         return redirect()->to(base_url('/recruitment/job-listing/all'))->with('error', 'Job listing not found.');
    //     }
    // }


    public function updateRemarks()
    {
        $id = $this->request->getPost('jobId');
        $remarks = $this->request->getPost('remarks');
        $status = $this->request->getPost('action');
        $job = $this->jobListingModel->find($id);
        if ($status == 'closed') {
            $data['job_closing_date'] = date('Y-m-d');
        }
        $data = [
            'remarks' => $remarks,
            'status' => $status
        ];

        if ($this->jobListingModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Remarks and status updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update remarks and status'
            ]);
        }
    }

    public function getJobListingNotifications()
    {
        $currentUserId = $this->session->get('current_user')['employee_id'];
        $allPendingJobs = [];

        $notifiedJobIds = $this->jobListingNotificationModel
            ->where('user_id', $currentUserId)
            ->where('read_at IS NULL')
            ->findColumn('job_listing_id');

        if (!empty($notifiedJobIds)) {
            $notifiedJobs = $this->jobListingModel
                ->select('rc_job_listing.id, rc_job_listing.job_title, departments.department_name')
                ->select("CONCAT(created_by.first_name, ' ', created_by.last_name) as created_by_name")
                ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
                ->join('employees as created_by', 'created_by.id = rc_job_listing.created_by', 'left')
                ->whereIn('rc_job_listing.id', $notifiedJobIds)
                //  ->where('rc_job_listing.status', 'pending')
                ->asArray()
                ->findAll();
            $allPendingJobs = array_merge($allPendingJobs, $notifiedJobs);
        }



        // 2. Get jobs pending HOD approval for the current user
        // $hodJobQuery = new RcJobListingModel();
        // $hodPendingJobs = $hodJobQuery->select('rc_job_listing.id, rc_job_listing.job_title')
        //     ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
        //     ->where('departments.hod_employee_id', $currentUserId)
        //     ->where('rc_job_listing.approved_by_hod IS NULL')
        //     ->where('rc_job_listing.approved_by_hr_executive IS NOT NULL') // HOD approves after HR
        //     ->where('rc_job_listing.status', 'active')
        //     ->asArray()
        //     ->findAll();

        // if (!empty($hodPendingJobs)) {
        //     $allPendingJobs = array_merge($allPendingJobs, $hodPendingJobs);
        // }

        if (!empty($allPendingJobs)) {
            // Remove duplicates in case a user is both HR and HOD for a job
            $uniquePendingJobs = array_map("unserialize", array_unique(array_map("serialize", $allPendingJobs)));
            return $this->response->setJSON(['status' => 'show_modal', 'jobs' => array_values($uniquePendingJobs)]);
        }

        return $this->response->setJSON(['status' => 'no_notification']);
    }


    public function markJobListingAsRead()
    {
        $current_user_id = $this->session->get('current_user')['employee_id'];
        $job_ids = $this->request->getPost('job_id');

        if (empty($job_ids) || !is_array($job_ids)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No job IDs provided.']);
        }

        $this->jobListingNotificationModel
            ->where('user_id', $current_user_id)
            ->whereIn('job_listing_id', $job_ids)
            ->set(['read_at' => date('Y-m-d H:i:s')])
            ->update();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Notifications marked as read.']);
    }

    private function markResolutionsAsClosed($jobId)
    {
        $currentUserId = $this->session->get('current_user')['employee_id'];
        $commentModel = new RcJobListingCommentModel();

        $jobListing = $this->jobListingModel
            ->select('rc_job_listing.*, departments.hod_employee_id')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->where('rc_job_listing.id', $jobId)
            ->first();

        if (!$jobListing) {
            return false;
        }

        $activeResolutions = $commentModel
            ->where('listing_id', $jobId)
            ->where('type', 'resolution')
            ->where('status', 'active')
            ->findAll();

        $closedCount = 0;
        foreach ($activeResolutions as $resolution) {
            $expectedReceiver = $this->determineNotificationReceiver($jobListing, $resolution['sender_id']);

            if ($expectedReceiver == $currentUserId && $resolution['sender_id'] != $currentUserId) {
                $commentModel->update($resolution['id'], [
                    'status' => 'closed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $closedCount++;
            }
        }

        // if ($closedCount > 0) {
        //     log_message('info', "Marked {$closedCount} resolutions as closed for user {$currentUserId} on job {$jobId}");
        // }

        return $closedCount;
    }

    private function determineNotificationReceiver($jobListing, $senderId)
    {
        $hrExecutive = $this->hrExecutive;
        $createdBy = $jobListing['created_by'];
        $hodId = $jobListing['hod_employee_id'];
        $approvedByHrExecutive = $jobListing['approved_by_hr_executive'];
        $approvedByHod = $jobListing['approved_by_hod'] ?? null;
        $approvedByHrManager = $jobListing['approved_by_hr_manager'];

        // Stage 1: Before HR Executive approval
        if (empty($approvedByHrExecutive)) {
            if ($senderId == $hrExecutive) {
                return $createdBy;
            } else {
                return $hrExecutive;
            }
        }

        // Stage 2: HR Executive approved, waiting for HOD approval
        if (!empty($approvedByHrExecutive) && empty($approvedByHod)) {
            if ($senderId == $hrExecutive) {
                return $hodId;
            } else {
                return $hrExecutive;
            }
        }

        // Stage 3: Both HR Executive and HOD approved, waiting for HR Manager approval
        if (!empty($approvedByHrExecutive) && !empty($approvedByHod) && empty($approvedByHrManager)) {
            if ($senderId == $hrExecutive) {
                return $hodId;
            } else {
                return $hrExecutive;
            }
        }

        // Stage 4: All approvals completed
        if (!empty($approvedByHrManager)) {
            if ($senderId == $hrExecutive) {
                return $hodId;
            } else {
                return $hrExecutive;
            }
        }

        return $hrExecutive;
    }

    public function initiateJobClosure()
    {
        $jobId = $this->request->getPost('job_id');
        $currentUser = $this->session->get('current_user')['employee_id'];

        // Validate HR Executive permission
        if ($currentUser != $this->hrExecutive) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized to initiate job closure'
            ]);
        }

        if (empty($this->request->getPost('selected_candidate_id'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Selected candidate is required'
            ]);
        }

        if (empty($this->request->getPost('closure_notes'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Closure notes are required'
            ]);
        }

        $job = $this->jobListingModel->find($jobId);
        if (!$job) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job not found'
            ]);
        }

        if ($job['status'] == 'partially closed' || $job['status'] == 'closed') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job is already closed or in closure process'
            ]);
        }

        $closureModel = new RcJobClosureApprovalModel();
        $existingClosure = $closureModel->getByJobListingId($jobId);
        if ($existingClosure) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Closure process already initiated for this job'
            ]);
        }

        $data = [
            'job_listing_id' => $jobId,
            'selected_candidate_id' => $this->request->getPost('selected_candidate_id'),
            'replacement_of_employee_id' => $this->request->getPost('replacement_of_employee_id') ?: null,
            'hr_assessment_notes' => $this->request->getPost('closure_notes'),
            'hr_approved_by' => $currentUser,
            'hr_approved_at' => date('Y-m-d H:i:s'),
            'current_step' => 'pending_manager_closure'
        ];

        if ($closureModel->insert($data)) {
            $this->jobListingModel->update($jobId, ['status' => 'partially closed']);

            $jobDetails = $this->jobListingModel
                ->select('rc_job_listing.reporting_to, departments.hod_employee_id')
                ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
                ->where('rc_job_listing.id', $jobId)
                ->first();

            if ($jobDetails && $jobDetails['reporting_to']) {
                $notificationData = [
                    'job_listing_id' => $jobId,
                    'user_id'        => $jobDetails['reporting_to'],
                    'read_at'        => null
                ];
                $this->jobListingNotificationModel->insert($notificationData);
            }


            return $this->response->setJSON([
                'success' => true,
                'message' => 'Job partially closed successfully. Awaiting manager finalization.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to initiate closure'
        ]);
    }

    public function finalizeJobClosure()
    {
        $jobId = $this->request->getPost('job_id');
        $currentUser = $this->session->get('current_user')['employee_id'];

        $job = $this->jobListingModel->getJobWithDetails($jobId);
        if (!$job || ($currentUser != $job->department_hod_id && $currentUser != $job->reporting_to)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized to finalize job closure'
            ]);
        }

        $confirmClosure = $this->request->getPost('confirm_closure');
        if ($confirmClosure != 'yes') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job closure was not confirmed. Please select "Yes, finalize closure" to complete the process.'
            ]);
        }

        $requiredFields = ['strengths', 'weaknesses', 'current_team_size', 'keep_posting_open', 'notice_period_compliance'];
        foreach ($requiredFields as $field) {
            if (empty($this->request->getPost($field))) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Required field '{$field}' is missing"
                ]);
            }
        }

        if ($this->request->getPost('keep_posting_open') == 'yes' && empty($this->request->getPost('keep_posting_reason'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Reason for keeping posting open is required'
            ]);
        }

        if ($this->request->getPost('need_replacement') == 'yes' && empty($this->request->getPost('replacement_details'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Replacement details are required when replacement is needed'
            ]);
        }

        if ($this->request->getPost('notice_period_compliance') == 'yes' && empty($this->request->getPost('doubtful_notice_members'))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please mention the names of doubtful team members for notice period compliance'
            ]);
        }

        $closureModel = new RcJobClosureApprovalModel();
        $closureRecord = $closureModel->where('job_listing_id', $jobId)->first();

        if ($closureRecord) {
            $updateData = [
                'strengths' => $this->request->getPost('strengths'),
                'weaknesses' => $this->request->getPost('weaknesses'),
                'current_team_size' => (int)$this->request->getPost('current_team_size'),
                'best_performer_id' => $this->request->getPost('best_performer_id') ?: null,
                'worst_performer_id' => $this->request->getPost('worst_performer_id') ?: null,

                'need_replacement' => $this->request->getPost('need_replacement') ?: null,
                'replacement_details' => $this->request->getPost('replacement_details') ?: null,

                'keep_posting_open' => $this->request->getPost('keep_posting_open'),
                'keep_posting_reason' => $this->request->getPost('keep_posting_reason') ?: null,

                'manager_comments' => $this->request->getPost('manager_comments') ?: null,
                'notice_period_compliance' => $this->request->getPost('notice_period_compliance'),
                'doubtful_notice_members' => $this->request->getPost('doubtful_notice_members') ?: null,
                'manager_approved_by' => $currentUser,
                'manager_approved_at' => date('Y-m-d H:i:s'),
                'current_step' => 'completed',
                'final_closure_date' => date('Y-m-d H:i:s')
            ];

            if ($closureModel->update($closureRecord['id'], $updateData)) {
                $finalStatus = ($this->request->getPost('keep_posting_open') == 'yes') ? 'open' : 'closed';

                $jobUpdateData = [
                    'status' => $finalStatus,
                    'job_closing_date' => date('Y-m-d')
                ];

                if ($finalStatus == 'open') {
                    unset($jobUpdateData['job_closing_date']);
                }

                $this->jobListingModel->update($jobId, $jobUpdateData);

                $message = ($finalStatus == 'open')
                    ? 'Job closure finalized successfully. Posting remains open as requested.'
                    : 'Job closure finalized successfully. Job is now closed.';

                return $this->response->setJSON([
                    'success' => true,
                    'message' => $message,
                    'final_status' => $finalStatus
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to finalize closure'
        ]);
    }

    public function getClosureDetails($jobId)
    {
        $currentUser = $this->session->get('current_user')['employee_id'];

        if (!in_array($currentUser, [$this->hrExecutive, $this->hrManagerId]) && $currentUser != $this->getJobReportingManager($jobId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $closureModel = new RcJobClosureApprovalModel();
        $closureDetails = $closureModel->getClosureWithJobDetails($jobId);

        if ($closureDetails) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $closureDetails
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Closure details not found'
        ]);
    }

    private function getJobReportingManager($jobId)
    {
        $job = $this->jobListingModel->getJobWithDetails($jobId);
        return $job ? ($job->department_hod_id ?: $job->reporting_to) : null;
    }

    // ─── Recruitment Task Assignment ─────────────────────────────────────────

    private const TASK_TYPES = [
        'Source Candidates',
        'Screen Resumes / CVs',
        'Schedule Interviews',
        'Conduct Telephonic Screening',
        'Send Job Offer Letter',
        'Background Verification',
        'Collect Interview Feedback',
        'Job Portal Posting / Update',
        'Follow-up with Candidates',
        'Reference Check',
        'Coordinate with Department HOD',
    ];

    /**
     * GET /recruitment/job-listing/tasks/hr-employees
     * Returns active HR employees (excluding ID 52) for Select2.
     */
    public function getHrEmployees()
    {
        $currentUser = $this->session->get('current_user');
        if (!in_array($currentUser['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $employeeModel = new EmployeeModel();
        $date45 = date('Y-m-d', strtotime('-45 days'));

        $employees = $employeeModel
            ->select("employees.id, TRIM(CONCAT(employees.first_name, ' ', employees.last_name)) as employee_name")
            ->where('employees.role', 'hr')
            ->where('employees.id !=', $this->hrExecutive)
            ->groupStart()
            ->where('employees.date_of_leaving IS NULL')
            ->orWhere("employees.date_of_leaving >=", $date45)
            ->groupEnd()
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();

        $result = array_map(fn($e) => ['id' => $e['id'], 'text' => $e['employee_name']], $employees);

        return $this->response->setJSON(['success' => true, 'data' => $result]);
    }

    /**
     * POST /recruitment/job-listing/tasks/assign
     * Creates a task header + one assignee row per selected HR member.
     */
    public function assignTask()
    {
        $currentUserId = (int) $this->session->get('current_user')['employee_id'];

        if ($currentUserId !== $this->hrExecutive) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only the HR Executive can assign tasks.'])->setStatusCode(403);
        }

        $jobListingId = (int) $this->request->getPost('job_listing_id');
        $taskType     = $this->request->getPost('task_type');
        $remarks      = $this->request->getPost('remarks');
        $dueDate      = $this->request->getPost('due_date');
        $assignedToIds = $this->request->getPost('assigned_to');

        if (!$jobListingId || !$taskType || empty($assignedToIds) || !$dueDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'task_type, assigned_to[], and due_date are required.']);
        }

        if (!in_array($taskType, self::TASK_TYPES)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid task type.']);
        }

        // Job must be fully approved
        $job = $this->jobListingModel->find($jobListingId);
        if (!$job || empty($job['approved_by_hr_manager'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job is not fully approved yet.']);
        }

        // Validate each assignee: active HR, not ID 52
        $date45 = date('Y-m-d', strtotime('-45 days'));
        $employeeModel = new EmployeeModel();
        foreach ($assignedToIds as $assigneeId) {
            $assigneeId = (int) $assigneeId;
            if ($assigneeId === $this->hrExecutive) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot assign task to the HR Executive themselves.']);
            }
            $emp = $employeeModel
                ->where('id', $assigneeId)
                ->where('role', 'hr')
                ->groupStart()
                ->where('date_of_leaving IS NULL')
                ->orWhere('date_of_leaving >=', $date45)
                ->groupEnd()
                ->first();
            if (!$emp) {
                return $this->response->setJSON(['success' => false, 'message' => "Assignee ID {$assigneeId} is not a valid active HR employee."]);
            }
        }

        $taskModel = new RcRecruitmentTaskModel();
        $taskId = $taskModel->insert([
            'job_listing_id' => $jobListingId,
            'task_type'      => $taskType,
            'remarks'        => $remarks ?: null,
            'assigned_date'  => date('Y-m-d'),
            'due_date'       => $dueDate,
            'assigned_by'    => $this->hrExecutive,
        ]);

        if (!$taskId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create task.']);
        }

        $assigneeModel  = new RcRecruitmentTaskAssigneeModel();
        $revisionModel  = new RcRecruitmentTaskRevisionModel();

        // Task-level creation revision
        $revisionModel->insert([
            'task_id'     => $taskId,
            'assignee_id' => null,
            'field_name'  => 'task_created',
            'old_value'   => null,
            'new_value'   => $taskType,
            'updated_by'  => $currentUserId,
        ]);

        foreach ($assignedToIds as $assigneeId) {
            $newAssigneeRowId = $assigneeModel->insert([
                'task_id'     => $taskId,
                'assigned_to' => (int) $assigneeId,
                'status'      => 'pending',
            ]);

            $revisionModel->insert([
                'task_id'     => $taskId,
                'assignee_id' => $newAssigneeRowId,
                'field_name'  => 'assignee_added',
                'old_value'   => null,
                'new_value'   => (string) (int) $assigneeId,
                'updated_by'  => $currentUserId,
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Task assigned successfully.', 'task_id' => $taskId]);
    }

    /**
     * GET /recruitment/job-listing/tasks/(:num)
     * Returns tasks with nested assignees for a job.
     */
    public function getJobTasks($jobId)
    {
        $currentUser = $this->session->get('current_user');
        if (!in_array($currentUser['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $taskModel = new RcRecruitmentTaskModel();
        $tasks = $taskModel->getTasksForJob((int) $jobId);

        return $this->response->setJSON(['success' => true, 'data' => $tasks]);
    }

    /**
     * POST /recruitment/job-listing/tasks/update-status
     * Assignee updates their own status on a task.
     */
    public function updateTaskStatus()
    {
        $currentUserId = (int) $this->session->get('current_user')['employee_id'];
        $assigneeRecordId = (int) $this->request->getPost('assignee_record_id');
        $newStatus = $this->request->getPost('status');

        if (!in_array($newStatus, ['pending', 'in_progress', 'completed'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status value.']);
        }

        $assigneeModel = new RcRecruitmentTaskAssigneeModel();
        $record = $assigneeModel->find($assigneeRecordId);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assignee record not found.']);
        }

        if ((int) $record['assigned_to'] !== $currentUserId) {
            return $this->response->setJSON(['success' => false, 'message' => 'You can only update your own task status.'])->setStatusCode(403);
        }

        $oldStatus = $record['status'];
        $assigneeModel->update($assigneeRecordId, ['status' => $newStatus]);

        if ($oldStatus !== $newStatus) {
            $revisionModel = new RcRecruitmentTaskRevisionModel();
            $revisionModel->insert([
                'task_id'     => (int) $record['task_id'],
                'assignee_id' => $assigneeRecordId,
                'field_name'  => 'status',
                'old_value'   => $oldStatus,
                'new_value'   => $newStatus,
                'updated_by'  => $currentUserId,
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * POST /recruitment/job-listing/tasks/reassign
     * HR Executive reassigns a task to a different HR member.
     */
    public function reassignTask()
    {
        $currentUserId = (int) $this->session->get('current_user')['employee_id'];

        if ($currentUserId !== $this->hrExecutive) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only the HR Executive can reassign tasks.'])->setStatusCode(403);
        }

        $assigneeRecordId = (int) $this->request->getPost('assignee_record_id');
        $newAssignedTo    = (int) $this->request->getPost('new_assigned_to');

        if (!$assigneeRecordId || !$newAssignedTo) {
            return $this->response->setJSON(['success' => false, 'message' => 'assignee_record_id and new_assigned_to are required.']);
        }

        if ($newAssignedTo === $this->hrExecutive) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot reassign to the HR Executive themselves.']);
        }

        // Validate new assignee is active HR
        $date45 = date('Y-m-d', strtotime('-45 days'));
        $employeeModel = new EmployeeModel();
        $emp = $employeeModel
            ->where('id', $newAssignedTo)
            ->where('role', 'hr')
            ->groupStart()
            ->where('date_of_leaving IS NULL')
            ->orWhere('date_of_leaving >=', $date45)
            ->groupEnd()
            ->first();

        if (!$emp) {
            return $this->response->setJSON(['success' => false, 'message' => 'New assignee is not a valid active HR employee.']);
        }

        $assigneeModel = new RcRecruitmentTaskAssigneeModel();
        $record = $assigneeModel->find($assigneeRecordId);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assignee record not found.']);
        }

        $oldAssignedTo = (string) $record['assigned_to'];
        $oldStatus     = $record['status'];

        $assigneeModel->update($assigneeRecordId, [
            'assigned_to' => $newAssignedTo,
            'status'      => 'pending',
        ]);

        $revisionModel = new RcRecruitmentTaskRevisionModel();
        $revisionModel->insert([
            'task_id'     => (int) $record['task_id'],
            'assignee_id' => $assigneeRecordId,
            'field_name'  => 'assigned_to',
            'old_value'   => $oldAssignedTo,
            'new_value'   => (string) $newAssignedTo,
            'updated_by'  => $currentUserId,
        ]);

        if ($oldStatus !== 'pending') {
            $revisionModel->insert([
                'task_id'     => (int) $record['task_id'],
                'assignee_id' => $assigneeRecordId,
                'field_name'  => 'status',
                'old_value'   => $oldStatus,
                'new_value'   => 'pending',
                'updated_by'  => $currentUserId,
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Task reassigned successfully.']);
    }

    /**
     * POST /recruitment/job-listing/tasks/edit
     * HR Executive edits an existing task's type, remarks, and due date.
     */
    public function editTask()
    {
        $currentUserId = (int) $this->session->get('current_user')['employee_id'];

        if ($currentUserId !== $this->hrExecutive) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only the HR Executive can edit tasks.'])->setStatusCode(403);
        }

        $taskId   = (int) $this->request->getPost('task_id');
        $taskType = $this->request->getPost('task_type');
        $remarks  = $this->request->getPost('remarks');
        $dueDate  = $this->request->getPost('due_date');

        if (!$taskId || !$taskType || !$dueDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'task_id, task_type, and due_date are required.']);
        }

        if (!in_array($taskType, self::TASK_TYPES)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid task type.']);
        }

        $taskModel = new RcRecruitmentTaskModel();
        $task = $taskModel->find($taskId);

        if (!$task) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task not found.']);
        }

        $newValues = [
            'task_type' => $taskType,
            'remarks'   => $remarks ?: null,
            'due_date'  => $dueDate,
        ];

        // Collect changed fields before updating
        $revisionModel = new RcRecruitmentTaskRevisionModel();
        foreach (['task_type', 'remarks', 'due_date'] as $field) {
            $oldVal = $task[$field] !== '' ? $task[$field] : null;
            $newVal = $newValues[$field] !== '' ? $newValues[$field] : null;
            if ($oldVal !== $newVal) {
                $revisionModel->insert([
                    'task_id'     => $taskId,
                    'assignee_id' => null,
                    'field_name'  => $field,
                    'old_value'   => $oldVal,
                    'new_value'   => $newVal,
                    'updated_by'  => $currentUserId,
                ]);
            }
        }

        $taskModel->update($taskId, $newValues);

        return $this->response->setJSON(['success' => true, 'message' => 'Task updated successfully.']);
    }

    /**
     * GET /recruitment/job-listing/tasks/revisions/(:num)
     * Returns the revision history for a task.
     */
    public function getTaskRevisions(int $taskId)
    {
        $currentUser = $this->session->get('current_user');
        if (!in_array($currentUser['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $db = \Config\Database::connect();

        $revisions = $db->table('rc_recruitment_task_revisions r')
            ->select([
                'r.id',
                'r.task_id',
                'r.assignee_id',
                'r.field_name',
                'r.old_value',
                'r.new_value',
                'r.updated_by',
                'r.created_at',
                "TRIM(CONCAT(actor.first_name, ' ', actor.last_name)) AS updated_by_name",
                "TRIM(CONCAT(old_emp.first_name, ' ', old_emp.last_name)) AS old_employee_name",
                "TRIM(CONCAT(new_emp.first_name, ' ', new_emp.last_name)) AS new_employee_name",
            ])
            ->join('employees actor', 'actor.id = r.updated_by', 'left')
            ->join('employees old_emp', "old_emp.id = r.old_value AND r.field_name IN ('assigned_to', 'assignee_added')", 'left')
            ->join('employees new_emp', "new_emp.id = r.new_value AND r.field_name IN ('assigned_to', 'assignee_added')", 'left')
            ->where('r.task_id', $taskId)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $revisions]);
    }

    // ─── Recruitment Task Dashboard ───────────────────────────────────────────

    /**
     * GET /recruitment/task-dashboard
     * Renders the recruitment task dashboard view.
     */
    public function taskDashboard()
    {
        $currentUser = $this->session->get('current_user');
        if (!in_array($currentUser['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        return view('Recruitment/TaskDashboard', [
            'page_title'      => 'Recruitment Task Dashboard',
            'task_types'      => self::TASK_TYPES,
            'hr_executive'    => $this->hrExecutive,
            'current_user_id' => (int) $currentUser['employee_id'],
        ]);
    }

    /**
     * GET /recruitment/task-dashboard/tasks
     * Returns tasks with nested assignees and summary counts (AJAX).
     */
    public function getDashboardTasks()
    {
        $currentUser = $this->session->get('current_user');
        if (!in_array($currentUser['role'], ['superuser', 'hr', 'manager', 'hod', 'tl'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $currentUserId = (int) $currentUser['employee_id'];
        $isManager     = ($currentUserId === $this->hrExecutive);

        // Parse and validate query params
        $statusParam = $this->request->getGet('status') ?: 'pending,in_progress';
        $rawStatuses = array_filter(array_map('trim', explode(',', $statusParam)));
        $validStatuses = ['pending', 'in_progress', 'completed'];
        $statuses = array_values(array_filter($rawStatuses, fn($s) => in_array($s, $validStatuses)));

        $taskType    = $this->request->getGet('task_type') ?: null;
        $dueDateFrom = $this->request->getGet('due_date_from') ?: null;
        $dueDateTo   = $this->request->getGet('due_date_to') ?: null;
        $assignedTo  = $isManager ? (int) ($this->request->getGet('assigned_to') ?: 0) : 0;

        $db = \Config\Database::connect();

        $builder = $db->table('rc_recruitment_tasks t')
            ->select([
                't.id AS task_id',
                't.job_listing_id',
                't.task_type',
                't.remarks',
                't.assigned_date',
                't.due_date',
                't.assigned_by',
                "TRIM(CONCAT(assigner.first_name, ' ', assigner.last_name)) AS assigned_by_name",
                'j.job_title',
                'c.company_short_name AS company_name',
                'd.department_name',
            ])
            ->join('rc_job_listing j',   'j.id = t.job_listing_id',   'left')
            ->join('companies c',         'c.id = j.company_id',       'left')
            ->join('departments d',       'd.id = j.department_id',    'left')
            ->join('employees assigner',  'assigner.id = t.assigned_by', 'left');

        // Build whitelisted status list for safe EXISTS sub-query
        $statusList = !empty($statuses) ? implode("','", $statuses) : '';

        if (!$isManager) {
            // HR: only tasks where they are an assignee with a matching status
            $statusClause = $statusList ? "AND rta.status IN ('{$statusList}')" : '';
            $builder->where("EXISTS (
                SELECT 1 FROM rc_recruitment_task_assignees rta
                WHERE rta.task_id = t.id
                  AND rta.assigned_to = {$currentUserId}
                  {$statusClause}
            )");
        } else {
            // Manager: optionally filter by assigned_to and/or status (any assignee)
            $assigneeWhere = '';
            if ($assignedTo) {
                $assigneeWhere .= " AND rta.assigned_to = {$assignedTo}";
            }
            if ($statusList) {
                $assigneeWhere .= " AND rta.status IN ('{$statusList}')";
            }
            if ($assigneeWhere) {
                $builder->where("EXISTS (
                    SELECT 1 FROM rc_recruitment_task_assignees rta
                    WHERE rta.task_id = t.id
                    {$assigneeWhere}
                )");
            }
        }

        if ($taskType && in_array($taskType, self::TASK_TYPES)) {
            $builder->where('t.task_type', $taskType);
        }
        if ($dueDateFrom) {
            $builder->where('t.due_date >=', $dueDateFrom);
        }
        if ($dueDateTo) {
            $builder->where('t.due_date <=', $dueDateTo);
        }

        $builder->orderBy('t.due_date', 'ASC')->orderBy('t.created_at', 'DESC');
        $tasks = $builder->get()->getResultArray();

        if (empty($tasks)) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => [],
                'summary' => ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'overdue' => 0],
            ]);
        }

        $taskIds = array_column($tasks, 'task_id');

        // Query 2: fetch all assignees for these task IDs in one shot (avoids N+1)
        $assigneeModel = new RcRecruitmentTaskAssigneeModel();
        $allAssignees = $assigneeModel
            ->select('rc_recruitment_task_assignees.*')
            ->select("TRIM(CONCAT(e.first_name, ' ', e.last_name)) AS assigned_to_name")
            ->join('employees as e', 'e.id = rc_recruitment_task_assignees.assigned_to', 'left')
            ->whereIn('rc_recruitment_task_assignees.task_id', $taskIds)
            ->findAll();

        // Group assignees by task_id
        $assigneeMap = [];
        foreach ($allAssignees as $assignee) {
            $assigneeMap[$assignee['task_id']][] = $assignee;
        }

        $today  = date('Y-m-d');
        $result = [];
        foreach ($tasks as $task) {
            $taskAssignees = $assigneeMap[$task['task_id']] ?? [];
            $allDone = !empty($taskAssignees) && array_reduce(
                $taskAssignees,
                fn($carry, $a) => $carry && ($a['status'] === 'completed'),
                true
            );
            $isOverdue = $task['due_date'] < $today && !$allDone;

            $result[] = [
                'task_id'          => (int) $task['task_id'],
                'job_listing_id'   => (int) $task['job_listing_id'],
                'task_type'        => $task['task_type'],
                'remarks'          => $task['remarks'],
                'assigned_date'    => $task['assigned_date'],
                'due_date'         => $task['due_date'],
                'assigned_by'      => (int) $task['assigned_by'],
                'assigned_by_name' => $task['assigned_by_name'],
                'job_title'        => $task['job_title'],
                'company_name'     => $task['company_name'],
                'department_name'  => $task['department_name'],
                'assignees'        => $taskAssignees,
                'is_overdue'       => $isOverdue,
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $result,
            'summary' => $this->buildSummary($result),
        ]);
    }

    /**
     * Builds summary counts from the already-hydrated task result array.
     */
    private function buildSummary(array $tasks): array
    {
        $total      = count($tasks);
        $pending    = 0;
        $inProgress = 0;
        $overdue    = 0;

        foreach ($tasks as $task) {
            if ($task['is_overdue']) {
                $overdue++;
            }
            $hasPending    = false;
            $hasInProgress = false;
            foreach ($task['assignees'] as $a) {
                if ($a['status'] === 'pending')     $hasPending    = true;
                if ($a['status'] === 'in_progress') $hasInProgress = true;
            }
            if ($hasPending)    $pending++;
            if ($hasInProgress) $inProgress++;
        }

        return [
            'total'       => $total,
            'pending'     => $pending,
            'in_progress' => $inProgress,
            'overdue'     => $overdue,
        ];
    }

    /**
     * GET /recruitment/task-dashboard/job-listings
     * Returns Select2-compatible list of approved open job listings (manager only).
     */
    public function getApprovedJobListings()
    {
        $currentUser = $this->session->get('current_user');
        if ((int) $currentUser['employee_id'] !== $this->hrExecutive) {
            return $this->response->setJSON(['success' => false, 'results' => []])->setStatusCode(403);
        }

        $q = $this->request->getGet('q') ?: '';

        $builder = $this->jobListingModel
            ->select('rc_job_listing.id, rc_job_listing.job_title, companies.company_short_name')
            ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
            ->where('rc_job_listing.approved_by_hr_manager IS NOT NULL')
            ->whereIn('rc_job_listing.status', ['open', 'partially closed'])
            ->orderBy('rc_job_listing.created_at', 'DESC')
            ->limit(50);

        if ($q) {
            $builder->like('rc_job_listing.job_title', $q);
        }

        $jobs = $builder->findAll();

        $results = array_map(fn($j) => [
            'id'   => $j['id'],
            'text' => $j['job_title'] . ' — ' . ($j['company_short_name'] ?? ''),
        ], $jobs);

        return $this->response->setJSON(['success' => true, 'results' => $results]);
    }
}
