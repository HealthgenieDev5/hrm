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
//use App\Models\Recruitment\RcJobListingCommentReadsModel;
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

        if (!in_array($current_user['employee_id'], ['40', '52', '93', '461'])) {
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

            $otherTests = [];
            if ($this->request->getPost('other_test') === 'Yes') {
                $otherTestsArray = $this->request->getPost('other_tests');
                if ($otherTestsArray && is_array($otherTestsArray)) {
                    foreach ($otherTestsArray as $test) {
                        if (!empty($test['other_test'])) {
                            $otherTests[] = $test['other_test'];
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

            if ($jobId = $this->jobListingModel->insert($data)) {
                $notificationData = [
                    'job_listing_id' => $jobId,
                    'user_id'        => $this->hrExecutive,
                    'read_at'        => null
                ];
                // Although we are using the model property, it's safer to instantiate to avoid state issues.
                $notificationModel = new RcJobListingNotificationModel();
                $notificationModel->insert($notificationData);
                return redirect()->to(base_url('/recruitment/job-listing/view/' . $jobId))->with('success', 'Job listing created successfully.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->jobListingModel->errors());
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
        $fullPath = WRITEPATH . ltrim($filePath, '/'); // Ensure leading slash is removed for WRITEPATH

        log_message('debug', 'Attempting to download file from: ' . $fullPath);
        log_message('debug', 'File exists: ' . (file_exists($fullPath) ? 'true' : 'false'));

        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return $this->response->download($fullPath, null)->setFileName(basename($filePath));
    }


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
            ->where('rc_job_listing.id', $id)
            ->asObject()
            ->first();
        $data = [
            'job' => $job_listings,
            'page_title' => 'Job Closure PDF'
        ];

        $options = new options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);

        $content = view('Recruitment/JobClosurePdf', $data);

        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'job_closure_' . $id . '.pdf';
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

        $commentModel = new RcJobListingCommentModel();
        // $comments = $commentModel
        //     ->select('rc_job_listing_comments.*, CONCAT(employees.first_name, " ", employees.last_name) as sender_name')
        //     ->select('rc_job_listing_comment_reads.receiver_id as receiver_id')
        //     ->select('rc_job_listing_comment_reads.is_read as is_read')
        //     ->select('rc_job_listing_comment_reads. read_at as read_at')
        //     ->select('rc_job_listing_comment_reads.delivered_at as delivered_at')
        //     ->join('employees', 'employees.id = rc_job_listing_comments.sender_id', 'left')
        //     ->join('rc_job_listing_comment_reads.comment_id = rc_job_listing_comments.id')
        //     ->where('listing_id', $id)
        //     ->orderBy('created_at', 'DESC')
        //     ->findAll();


        // $comments = $commentModel
        //     ->select('rc_job_listing_comments.*')
        //     ->select('CONCAT(s.first_name, " ", s.last_name) AS sender_name')
        //     // ->select('r.receiver_id, r.is_read, r.read_at, r.delivered_at')
        //     // ->select('CONCAT(rv.first_name, " ", rv.last_name) AS receiver_name')
        //     ->join('employees AS s', 's.id = rc_job_listing_comments.sender_id', 'left')
        //     // ->join('rc_job_listing_comment_reads AS r', 'r.comment_id = rc_job_listing_comments.id', 'left')
        //     // ->join('employees AS rv', 'rv.id = r.receiver_id', 'left')
        //     ->where('rc_job_listing_comments.listing_id', $id)
        //     ->orderBy('rc_job_listing_comments.created_at', 'DESC')
        //     ->findAll();
        // $data = [
        //     'job' => $job,
        //     'comments' => $comments,
        //     'page_title' => 'Job Listing Details',
        // ];


        $comments = $commentModel
            ->select('rc_job_listing_comments.*')
            ->select('CONCAT(s.first_name, " ", s.last_name) AS sender_name')
            ->join('employees AS s', 's.id = rc_job_listing_comments.sender_id', 'left')
            ->where('rc_job_listing_comments.listing_id', $id)
            ->orderBy('rc_job_listing_comments.created_at', 'DESC')
            ->findAll();
        $data = [
            'job' => $job,
            'comments' => $comments,
            'page_title' => 'Job Listing Details',
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

                $jobOpeningDate = $this->request->getPost('job_opening_date');
                if (!$jobOpeningDate) return $this->fail('Job opening date is required');

                $updateData = [
                    'approved_by_hr_executive' => $currentUserId,
                    'job_opening_date' => $jobOpeningDate
                ];
                $notifyUserId = $job['department_hod_id'];
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

                $updateData['approved_by_hr_manager'] = $currentUserId;
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

    private function getPendingApprovalCounts(int $userId): array
    {
        $counts = [
            'pending_by_hr_executive' => 0,
            'pending_by_hod' => 0,
            'pending_by_hr_manager' => 0,
        ];

        $jobListingModel = new RcJobListingModel();



        // Pending by HR Executive
        $counts['pending_by_hr_executive'] = $jobListingModel
            ->where('approved_by_hr_executive IS NULL')
            ->countAllResults();

        // Pending by HOD
        $counts['pending_by_hod'] = $jobListingModel
            ->where('approved_by_hr_executive IS NOT NULL')
            ->where('approved_by_hod IS NULL')
            ->countAllResults();

        // Pending by HR Manager
        $counts['pending_by_hr_manager'] = $jobListingModel
            ->where('approved_by_hr_executive IS NOT NULL')
            ->where('approved_by_hod IS NOT NULL')
            ->where('approved_by_hr_manager IS NULL')
            ->countAllResults();

        return $counts;
    }


    // public function approve()
    // {

    //     $jobId = $this->request->getPost('job_id');
    //     $approvalType = $this->request->getPost('approval_type');
    //     $currentUserId = $this->session->get('current_user')['employee_id'];

    //     if (empty($jobId) || empty($approvalType)) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Job ID and approval type are required'
    //         ]);
    //     }

    //     $job = $this->jobListingModel
    //         ->select('rc_job_listing.*, departments.hod_employee_id as department_hod_id')
    //         ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
    //         ->where('rc_job_listing.id', $jobId)
    //         ->asArray()
    //         ->first();

    //     if (!$job) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Job listing not found'
    //         ]);
    //     }

    //     $updateData = [];

    //     if ($approvalType === 'hr_executive') {
    //         if ($currentUserId != $this->hrExecutive) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Only HR Executive can approve at this level'
    //             ]);
    //         }

    //         if (!empty($job['approved_by_hr_executive'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Job listing already approved by HR Executive'
    //             ]);
    //         }

    //         $jobOpeningDate = $this->request->getPost('job_opening_date');
    //         if (empty($jobOpeningDate)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Job opening date is required'
    //             ]);
    //         }

    //         $updateData['approved_by_hr_executive'] = $currentUserId;
    //         $updateData['job_opening_date'] = $jobOpeningDate;

    //         if (!empty($job['department_hod_id'])) {
    //             $notificationData = [
    //                 'job_listing_id' => $jobId,
    //                 'user_id'        => $job['department_hod_id'],
    //                 'read_at'        => null
    //             ];
    //             $notificationModel = new RcJobListingNotificationModel();
    //             $notificationModel->insert($notificationData);
    //         }
    //     } elseif ($approvalType === 'hod') {
    //         // Check if current user is the HOD for this department
    //         if ($currentUserId != $job['department_hod_id']) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Only the designated HOD can approve at this level'
    //             ]);
    //         }

    //         if (empty($job['approved_by_hr_executive'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'HR Executive approval required first'
    //             ]);
    //         }

    //         if (!empty($job['approved_by_hod'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Job listing already approved by HOD'
    //             ]);
    //         }

    //         $updateData['approved_by_hod'] = $currentUserId;

    //         $notificationData = [
    //             'job_listing_id' => $jobId,
    //             'user_id'        => $this->hrManagerId, 
    //             'read_at'        => null
    //         ];
    //         $notificationModel = new RcJobListingNotificationModel();
    //         $notificationModel->insert($notificationData);
    //     } elseif ($approvalType === 'hr_manager') {
    //         if ($currentUserId != 293) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Only HR Manager can approve at this level'
    //             ]);
    //         }

    //         if (empty($job['approved_by_hr_executive'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'HR Executive approval required first'
    //             ]);
    //         }

    //         if (empty($job['approved_by_hod'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'HOD approval required first'
    //             ]);
    //         }

    //         if (!empty($job['approved_by_hr_manager'])) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Job listing already approved by HR Manager'
    //             ]);
    //         }

    //         $updateData['approved_by_hr_manager'] = $currentUserId;
    //     } else {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Invalid approval type'
    //         ]);
    //     }

    //     if ($this->jobListingModel->update($jobId, $updateData)) {
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Job listing approved successfully',
    //             'approval_type' => $approvalType
    //         ]);
    //     } else {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Failed to update job listing'
    //         ]);
    //     }
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
                'created_at' => !empty($job->created_at) ? date('d M, Y', strtotime($job->created_at)) : 'N/A'
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
                ->where('rc_job_listing.status', 'pending')
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
}
