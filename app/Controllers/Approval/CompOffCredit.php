<?php

namespace App\Controllers\Approval;

use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\CompOffCreditModel;
use App\Controllers\BaseController;
use App\Controllers\Attendance\Processor;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class CompOffCredit extends BaseController
{

    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index()
    {

        $current_user = $this->session->get('current_user');

        $allowed = 'no';
        if (in_array($this->session->get('current_user')['employee_id'], [1])) {
            $allowed = 'yes';
        } elseif (in_array($this->session->get('current_user')['role'], ['superuser', 'hr', 'HOD', 'tl', 'manager'])) {
            $allowed = 'yes';
        }

        if ($allowed !== 'yes') {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        $data = [
            'page_title'     => 'Comp Off Credit Request Approval',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'Companies'             => $Companies,
        ];

        if (isset($_REQUEST['company']) && !empty($_REQUEST['company'])) {
            $company = "'" . implode("', '", $_REQUEST['company']) . "'";
            $DepartmentModel = new DepartmentModel();
            $DepartmentModel->select('departments.*')->select('c.company_short_name as company_short_name')->join('companies as c', 'c.id = departments.company_id', 'left');
            if (!in_array('all_companies', $_REQUEST['company'])) {
                $DepartmentModel->where('departments.company_id in (' . $company . ')');
            }
            $DepartmentModel->orderby('c.company_short_name', 'ASC');
            $Departments = $DepartmentModel->findAll();
            $data['Departments'] = $Departments;
        }

        if (isset($_REQUEST['department']) && !empty($_REQUEST['department'])) {
            $department = "'" . implode("', '", $_REQUEST['department']) . "'";
            $EmployeeModel = new EmployeeModel();
            $EmployeeModel
                ->select('employees.*')
                ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
                ->select('c.company_short_name as company_short_name')
                ->select('d.department_name as department_name')
                ->join('companies as c', 'c.id = employees.company_id', 'left')
                ->join('departments as d', 'd.id = employees.department_id', 'left');

            if (!in_array('all_departments', $_REQUEST['department'])) {
                $EmployeeModel->where('employees.department_id in (' . $department . ')');
            }

            $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.date_of_leaving is null');
            $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
            $EmployeeModel->groupEnd();

            $EmployeeModel->orderby('c.company_short_name', 'ASC');
            $EmployeeModel->orderby('d.department_name', 'ASC');
            $EmployeeModel->orderby('employees.first_name', 'ASC');
            $Employees = $EmployeeModel->findAll();
            $data['Employees'] = $Employees;
        }

        $CompOffCreditModel = new CompOffCreditModel();
        $data['statuses'] = $CompOffCreditModel->distinct()->select('status')->orderBy('status', 'ASC')->findAll();

        return view('Administrative/CompOffCreditApprovalRequests', $data);
    }

    public function getAllCompOffCreditApprovalRequests()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $from_date      = isset($params['from_date']) ? $params['from_date'] : '';
        $status         = isset($params['status']) ? $params['status'] : '';
        $to_date        = isset($params['to_date']) ? $params['to_date'] : '';
        $reporting_to_me   = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $current_user = $this->session->get('current_user');

        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];

        $CompOffCreditModel = new CompOffCreditModel();

        $CompOffCreditModel
            ->select('comp_off_credit_requests .*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name, ' - ', e1.machine ) ) as employee_name")
            ->select('e1.internal_employee_id as internal_employee_id')
            ->select('d.department_name as department_name')
            ->select('c.company_short_name as company_short_name')
            ->select('e2.id as reporting_manager_id')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")

            ->select('e3.id as department_hod_id')

            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as department_hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by_name")
            ->select("trim( concat( e6.first_name, ' ', e6.last_name ) ) as company_hod_name")
            ->select("trim( concat( e7.first_name, ' ', e7.last_name ) ) as stage_1_reviewed_by_name")
            ->select('c.company_hod as company_hod')
            ->select('e1.machine as employee_machine_location')
            ->join('employees as e1', 'e1.id = comp_off_credit_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = comp_off_credit_requests.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = comp_off_credit_requests.assigned_by', 'left')
            ->join('companies as c', 'c.id = e1.company_id', 'left')
            ->join('employees as e6', 'e6.id = c.company_hod', 'left')
            ->join('employees as e7', 'e7.id = comp_off_credit_requests.stage_1_reviewed_by', 'left');

        if ($reporting_to_me == 'yes') {
            $CompOffCreditModel->groupStart();
            $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $CompOffCreditModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $CompOffCreditModel->where('e1.reporting_manager_id !=', $current_user['employee_id']);
            $CompOffCreditModel->groupStart();
            $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $CompOffCreditModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $CompOffCreditModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $CompOffCreditModel->groupStart();
            $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $CompOffCreditModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $CompOffCreditModel->orWhereIn("'" . $current_user['employee_id'] . "'", ['54']);
            $CompOffCreditModel->groupEnd();
        }


        // if ($current_user['employee_id'] == '54') {
        //     $CompOffCreditModel->groupStart();

        //         // Show ALL pending requests where he's the reporting manager (any machine location)
        //         // $CompOffCreditModel->groupStart();
        //         // $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
        //         // $CompOffCreditModel->where('comp_off_credit_requests.status', 'pending');
        //         // $CompOffCreditModel->groupEnd();

        //         // OR show stage_1 requests from Noida employees only (machine='hn')
        //         $CompOffCreditModel->orGroupStart();
        //             // $CompOffCreditModel->where('comp_off_credit_requests.status', 'stage_1');
        //             // $CompOffCreditModel->where('e1.machine', 'hn');
        //             $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
        //             $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
        //             // $CompOffCreditModel->orWhere('c.company_hod =', $current_user['employee_id']);
        //         $CompOffCreditModel->groupEnd();

        //     $CompOffCreditModel->groupEnd();

        // } elseif ($current_user['employee_id'] == '1') {
        //     $CompOffCreditModel->groupStart();
        //     $CompOffCreditModel->where('e1.machine !=', 'hn');
        //     $CompOffCreditModel->groupEnd();
        // } elseif ($reporting_to_me == 'yes') {
        //     $CompOffCreditModel->groupStart();
        //     $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
        //     $CompOffCreditModel->groupEnd();
        // } elseif ($reporting_to_me == 'no') {
        //     $CompOffCreditModel->where('e1.reporting_manager_id !=', $current_user['employee_id']);
        //     $CompOffCreditModel->groupStart();
        //     $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
        //     $CompOffCreditModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
        //     $CompOffCreditModel->groupEnd();
        // } elseif ($reporting_to_me == 'no rule') {
        //     $CompOffCreditModel->groupStart();
        //     $CompOffCreditModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
        //     $CompOffCreditModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
        //     $CompOffCreditModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
        //     $CompOffCreditModel->groupEnd();
        // }



        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $CompOffCreditModel->whereIn('e1.company_id', $company_id);
        }
        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $CompOffCreditModel->whereIn('e1.department_id', $department_id);
        }
        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $CompOffCreditModel->whereIn('e1.id', $employee_id);
        }
        if (!empty($status) && !in_array('all_status', $status)) {
            //$CompOffCreditModel->whereIn('comp_off_credit_requests.status', $status);
            // Handle custom status filters for Manu
            $regular_statuses = [];
            $has_stage_1_aryan = false;
            $has_approved_aryan = false;

            foreach ($status as $stat) {
                if ($stat == 'stage_1_aryan') {
                    $has_stage_1_aryan = true;
                } elseif ($stat == 'approved_aryan') {
                    $has_approved_aryan = true;
                } else {
                    $regular_statuses[] = $stat;
                }
            }

            // Build complex where clause if we have custom filters
            if ($has_stage_1_aryan || $has_approved_aryan) {
                $CompOffCreditModel->groupStart();

                // Add regular status filters
                if (!empty($regular_statuses)) {
                    $CompOffCreditModel->groupStart();
                    $CompOffCreditModel->whereIn('comp_off_credit_requests.status', $regular_statuses);
                    $CompOffCreditModel->groupEnd();
                }

                // Add custom filters with OR conditions
                if ($has_stage_1_aryan) {
                    if (!empty($regular_statuses)) {
                        $CompOffCreditModel->orGroupStart();
                    } else {
                        $CompOffCreditModel->groupStart();
                    }
                    $CompOffCreditModel->where('comp_off_credit_requests.status', 'stage_1');
                    // Requests in Aryan's queue: HN location OR Aryan is reporting manager
                    $CompOffCreditModel->groupStart();
                    $CompOffCreditModel->where('e1.machine', 'hn');
                    $CompOffCreditModel->orWhere('e1.reporting_manager_id', '54');
                    $CompOffCreditModel->groupEnd();
                    $CompOffCreditModel->groupEnd();
                }

                if ($has_approved_aryan) {
                    $CompOffCreditModel->orGroupStart();
                    $CompOffCreditModel->where('comp_off_credit_requests.status', 'approved');
                    $CompOffCreditModel->where('comp_off_credit_requests.reviewed_by', '54');
                    $CompOffCreditModel->groupEnd();
                }

                $CompOffCreditModel->groupEnd();
            } else {
                // Regular status filtering
                $CompOffCreditModel->whereIn('comp_off_credit_requests.status', $status);
            }
        }
        if (!empty($from_date) && !empty($to_date)) {
            $CompOffCreditModel->groupStart();
            $CompOffCreditModel->where("comp_off_credit_requests.working_date between '" . $from_date . "' and '" . $to_date . "'");
            $CompOffCreditModel->groupEnd();
        }

        if ($current_user['employee_id'] != '40') {
            $CompOffCreditModel->where('e1.id !=', $current_user['employee_id']);
        }

        $CompOffCreditModel->orderBy('comp_off_credit_requests.date_time', 'DESC');

        $CompOffCreditRequests = $CompOffCreditModel->findAll();

        foreach ($CompOffCreditRequests as $index => $dataRow) {

            $CompOffCreditRequests[$index]['actions'] = '';

            $working_date_formatted = !empty($dataRow['working_date']) ? date('d M Y', strtotime($dataRow['working_date'])) : '';
            $working_date_ordering = !empty($dataRow['working_date']) ? strtotime($dataRow['working_date']) : '0';

            $CompOffCreditRequests[$index]['working_date'] = array('formatted' => $working_date_formatted, 'ordering' => $working_date_ordering);

            //begin:: 2024-07-02


            $PunchingDataAll = Processor::getProcessedPunchingData($dataRow['employee_id'], $dataRow['working_date'], $dataRow['working_date'], false);
            $PunchingData = isset($PunchingDataAll) && !empty($PunchingDataAll) ? $PunchingDataAll[0] : null;

            $shift          = isset($PunchingData) && !empty($PunchingData) ? $PunchingData['shift'] : null;
            $shift_start    = isset($shift['shift_start']) && !empty($shift['shift_start']) ? date('h:i A', strtotime($shift['shift_start'])) : null;
            $shift_end      = isset($shift['shift_end']) && !empty($shift['shift_end']) ? date('h:i A', strtotime($shift['shift_end'])) : null;
            $in_time_including_od = !empty($PunchingData['in_time_including_od']) ? date('h:i A', strtotime($PunchingData['in_time_including_od'])) : "";
            $out_time_including_od = !empty($PunchingData['out_time_including_od']) ? date('h:i A', strtotime($PunchingData['out_time_including_od'])) : "";

            $shift_hours = ProcessorHelper::get_time_difference($shift_start, $shift_end);
            $total_work_hours = ProcessorHelper::get_time_difference($in_time_including_od, $out_time_including_od);

            $day_status = "";
            if (in_array($PunchingData['status'], ['W/O', 'RH', 'F/O', 'SPL HL', 'HL', 'NH'])) {
                $day_status = " <span class='text-danger'>(" . $PunchingData['status'] . ")</span> ";
            }


            $CompOffCreditRequests[$index]['shift_start'] = $shift_start;
            $CompOffCreditRequests[$index]['shift_end'] = $shift_end;
            $CompOffCreditRequests[$index]['in_time_including_od'] = $in_time_including_od;
            $CompOffCreditRequests[$index]['out_time_including_od'] = $out_time_including_od;
            $CompOffCreditRequests[$index]['day_status'] = $day_status;
            $CompOffCreditRequests[$index]['shift_hours'] = $shift_hours;
            $CompOffCreditRequests[$index]['total_work_hours'] = $total_work_hours;
            //end:: 2024-07-02


            $working_day = !empty($dataRow['working_date']) ? date('l', strtotime($dataRow['working_date'])) : '';
            $CompOffCreditRequests[$index]['working_day'] = $working_day;

            $date_time_formatted = !empty($dataRow['date_time']) ? date('d M Y h:i A', strtotime($dataRow['date_time'])) : '';
            $date_time_ordering = !empty($dataRow['date_time']) ? strtotime($dataRow['date_time']) : '0';
            $CompOffCreditRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            $reviewed_date_formatted = !empty($dataRow['reviewed_date']) ? date('d M Y', strtotime($dataRow['reviewed_date'])) : '';
            $reviewed_date_ordering = !empty($dataRow['reviewed_date']) ? strtotime($dataRow['reviewed_date']) : '0';
            $CompOffCreditRequests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);

            $stage_1_reviewed_date_formatted = !empty($dataRow['stage_1_reviewed_date']) ? date('d M Y', strtotime($dataRow['stage_1_reviewed_date'])) : '';
            $stage_1_reviewed_date_ordering = !empty($dataRow['stage_1_reviewed_date']) ? strtotime($dataRow['stage_1_reviewed_date']) : '0';
            $CompOffCreditRequests[$index]['stage_1_reviewed_date'] = array('formatted' => $stage_1_reviewed_date_formatted, 'ordering' => $stage_1_reviewed_date_ordering);
        }
        $FilteredCompOffCreditRequests = [];

        foreach ($CompOffCreditRequests as $index => $dataRow) {

            // if ($current_user['employee_id'] == '1') {
            //     if ($dataRow['employee_machine_location'] == 'hn') {
            //         continue;
            //     } elseif ($dataRow['status'] == 'pending' && $dataRow['reporting_manager_id'] != '1') {
            //         continue;
            //     }
            // }
            //  else

            if ($current_user['employee_id'] == '54') {
                if (
                    $dataRow['employee_machine_location'] != 'hn' && $dataRow['reporting_manager_id'] != '54'

                ) {
                    continue;
                }
                // elseif ($dataRow['status'] == 'pending' && $dataRow['reporting_manager_id'] != '1') {
                //     continue;
                // }
            }


            $FilteredCompOffCreditRequests[] = $dataRow;
        }

        echo json_encode($FilteredCompOffCreditRequests);
    }



    public function GetCompOffCreditApprovalRequest()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'comp_off_credit_request_id'  =>  [
                    'rules'         =>  'required|is_not_unique[comp_off_credit_requests.id]',
                    'errors'        =>  [
                        'required'  => 'COMPOFF Request ID is required',
                        'is_not_unique' => 'This Request does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('comp_off_credit_request_id');
        } else {
            $comp_off_credit_request_id   = $this->request->getVar('comp_off_credit_request_id');

            $CompOffCreditModel = new CompOffCreditModel();

            $CompOffCreditRequest = $CompOffCreditModel
                ->select('comp_off_credit_requests.*')
                ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
                ->select('e1.shift_id as shift_id')
                ->select('e1.internal_employee_id as internal_employee_id')
                ->select('d.department_name as department_name')
                ->select('c.company_short_name as company_short_name')
                ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
                ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
                ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
                ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by_name")
                ->select("trim( concat( e6.first_name, ' ', e6.last_name ) ) as stage_1_reviewed_by_name")
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = e1.shift_id) as Monday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = e1.shift_id) as Tuesday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = e1.shift_id) as Wednesday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = e1.shift_id) as Thursday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = e1.shift_id) as Friday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = e1.shift_id) as Saturday')
                ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = e1.shift_id) as Sunday')
                ->join('employees as e1', 'e1.id = comp_off_credit_requests.employee_id', 'left')
                ->join('departments as d', 'd.id = e1.department_id', 'left')
                ->join('companies as c', 'c.id = e1.company_id', 'left')
                ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
                ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                ->join('employees as e4', 'e4.id = comp_off_credit_requests.reviewed_by', 'left')
                ->join('employees as e5', 'e5.id = comp_off_credit_requests.assigned_by', 'left')
                ->join('employees as e6', 'e6.id = comp_off_credit_requests.stage_1_reviewed_by', 'left')
                ->where('comp_off_credit_requests.id =', $comp_off_credit_request_id)
                ->first();



            $PunchingDataAll = Processor::getProcessedPunchingData($CompOffCreditRequest['employee_id'], $CompOffCreditRequest['working_date'], $CompOffCreditRequest['working_date'], false);

            $PunchingData = isset($PunchingDataAll) && !empty($PunchingDataAll) ? $PunchingDataAll[0] : null;
            $shift          = isset($PunchingData) && !empty($PunchingData) ? $PunchingData['shift'] : null;
            $shift_start    = isset($shift['shift_start']) && !empty($shift['shift_start']) ? date('h:i A', strtotime($shift['shift_start'])) : null;

            $shift_end      = isset($shift['shift_end']) && !empty($shift['shift_end']) ? date('h:i A', strtotime($shift['shift_end'])) : null;
            $in_time_including_od = !empty($PunchingData['in_time_including_od']) ? date('h:i A', strtotime($PunchingData['in_time_including_od'])) : "";
            $out_time_including_od = !empty($PunchingData['out_time_including_od']) ? date('h:i A', strtotime($PunchingData['out_time_including_od'])) : "";

            $shift_hours = ProcessorHelper::get_time_difference($shift_start, $shift_end);
            $total_work_hours = ProcessorHelper::get_time_difference($in_time_including_od, $out_time_including_od);




            $day_status = "";
            if (in_array($PunchingData['status'], ['W/O', 'RH', 'F/O', 'SPL HL', 'HL', 'NH'])) {
                $day_status = " <span class='text-danger'>(" . $PunchingData['status'] . ")</span> ";
            }


            $CompOffCreditRequest['shift_start'] = $shift_start;
            $CompOffCreditRequest['shift_end'] = $shift_end;
            $CompOffCreditRequest['in_time_including_od'] = $in_time_including_od;
            $CompOffCreditRequest['out_time_including_od'] = $out_time_including_od;
            $CompOffCreditRequest['day_status'] = $day_status;

            $CompOffCreditRequest['shift_hours'] = $shift_hours;
            $CompOffCreditRequest['total_work_hours'] = $total_work_hours;


            $CompOffCreditRequest['working_date'] = !empty($CompOffCreditRequest['working_date']) ? date('d M, Y', strtotime($CompOffCreditRequest['working_date'])) : '';
            $CompOffCreditRequest['working_day'] = !empty($CompOffCreditRequest['working_date']) ? date('l', strtotime($CompOffCreditRequest['working_date'])) : '';

            $CompOffCreditRequest['date_time'] = !empty($CompOffCreditRequest['date_time']) ? date('d M, Y h:i A', strtotime($CompOffCreditRequest['date_time'])) : '';
            $CompOffCreditRequest['reviewed_date'] = !empty($CompOffCreditRequest['reviewed_date']) ? date('d M, Y', strtotime($CompOffCreditRequest['reviewed_date'])) : '';


            if (empty($CompOffCreditRequest)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'COMPOFF Credit Request Found';
                $response_array['response_data']['comp_off_credit_request_data'] = $CompOffCreditRequest;
            }
        }

        #echo json_encode($response_array);
        return $this->response->setJSON($response_array);
    }



    public function UpdateCompOffCreditApprovalRequest()
    {
        $response_array = array();
        $rules = [
            'comp_off_credit_request_id'  =>  [
                'rules'         =>  'required|is_not_unique[comp_off_credit_requests.id]',
                'errors'        =>  [
                    'required'  => 'COMPOFF Credit Request ID is required',
                    'is_not_unique' => 'This Request does not exist in our database'
                ]
            ],
            'status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Click on Approve or Reject Button',
                ]
            ]
        ];

        if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'rejected') {
            if ($this->session->get('current_user')['employee_id'] == '1') {
                $rules['remarks'] =  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please specify why are you rejecting this request',
                    ]
                ];
            } else {
                $rules['stage_1_remarks'] =  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please specify why are you rejecting this request',
                    ]
                ];
            }
        } elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 'approved') {

            if (!isset($_REQUEST['minutes'])) {
                $rules['exchange'] =  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select Half Day or Full Day or Minutes for exchange<br>Minutes can be given in addition to Half Day or Full Day or separately',
                    ]
                ];
            } elseif (!isset($_REQUEST['exchange'])) {
                $rules['minutes'] =  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select Half Day or Full Day or Minutes for exchange<br>Minutes can be given in addition to Half Day or Full Day or separately',
                    ]
                ];
            }
        }
        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {

            // print_r($_REQUEST);
            // die();
            $comp_off_credit_request_id   = $this->request->getPost('comp_off_credit_request_id');

            $CompOffCreditModel = new CompOffCreditModel();
            $current_status = $CompOffCreditModel->select('status')->where('id', $comp_off_credit_request_id)->first()['status'];
            if (($this->session->get('current_user')['employee_id'] == '1' && !in_array($current_status, ['pending', 'stage_1'])) ||
                ($this->session->get('current_user')['employee_id'] == '54' && !in_array($current_status, ['pending', 'stage_1']))
            ) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This request is not in correct status for approval';
            } elseif ($this->session->get('current_user')['employee_id'] !== '1' && $this->session->get('current_user')['employee_id'] !== '54' && $current_status !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This request is not in pending status';
            } else {
                $current_user_employee_id = $this->session->get('current_user')['employee_id'];
                $current_date = date('Y-m-d');

                if ($current_user_employee_id == '1' || $current_user_employee_id == '54') {
                    $data = [
                        'status'                => $this->request->getPost('status'),
                        'reviewed_by'           => $current_user_employee_id,
                        'reviewed_date'         => $current_date,
                        'remarks'               => $this->request->getPost('remarks'),
                        'exchange'              => !empty($this->request->getPost('exchange')) ? $this->request->getPost('exchange') : 0,
                    ];
                } else {
                    $data = [
                        'status'                => $this->request->getPost('status'),
                        'stage_1_reviewed_by'   => $current_user_employee_id,
                        'stage_1_reviewed_date' => $current_date,
                        'stage_1_remarks'       => $this->request->getPost('stage_1_remarks'),
                        'exchange'              => !empty($this->request->getPost('exchange')) ? $this->request->getPost('exchange') : 0,
                    ];
                }


                if (!empty($this->request->getPost('minutes'))) {
                    $data['minutes'] = $this->request->getPost('minutes');
                } else {
                    $data['minutes'] = null;
                }

                $CompOffCreditModel = new CompOffCreditModel();
                $query = $CompOffCreditModel->update($comp_off_credit_request_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'COMPOFF Credit Request ' . ucfirst($this->request->getPost("status"));
                }
            }
        }
        return $this->response->setJSON($response_array);
    }


    public function CancelCompOffCreditRequest()
    {
        $response_array = array();

        // HR only authorization
        if (
            !in_array($this->session->get('current_user')['role'], ['hr'])
            &&
            !in_array($this->session->get('current_user')['employee_id'], ['40'])
        ) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Unauthorized. Only HR can cancel requests.';
            return $this->response->setJSON($response_array);
        }

        $validation = $this->validate(
            [
                'comp_off_credit_request_id'  =>  [
                    'rules'         =>  'required|is_not_unique[comp_off_credit_requests.id]',
                    'errors'        =>  [
                        'required'  => 'COMPOFF Request ID is required',
                        'is_not_unique' => 'This Request does not exist in our database'
                    ]
                ],
                'cancellation_remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please specify reason for cancellation'
                    ]
                ]
            ]
        );

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $comp_off_credit_request_id = $this->request->getPost('comp_off_credit_request_id');
            $cancellation_remarks = $this->request->getPost('cancellation_remarks');
            $current_user_employee_id = $this->session->get('current_user')['employee_id'];

            $CompOffCreditModel = new CompOffCreditModel();
            $request_data = $CompOffCreditModel->select('status, employee_id')->where('id', $comp_off_credit_request_id)->first();

            if (empty($request_data)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Request not found';
            } elseif ($request_data['status'] !== 'stage_1') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Only requests in stage_1 status can be cancelled';
            } elseif ($request_data['employee_id'] == $current_user_employee_id) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'HR cannot cancel their own requests';
            } else {
                $current_date = date('Y-m-d');
                $data = [
                    'status' => 'cancelled',
                    'reviewed_by' => $current_user_employee_id,
                    'reviewed_date' => $current_date,
                    'remarks' => $cancellation_remarks
                ];

                $query = $CompOffCreditModel->update($comp_off_credit_request_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'COMPOFF Credit Request Cancelled';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function getCompOffPendingCounts()
    {
        $response_array = array();
        $ninty_days_ago = date('Y-m-d', strtotime("-90 days"));

        // Count pending from Manu (employee_id = 1) - stage_1 requests from non-hn employees
        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditModel
            ->select('comp_off_credit_requests.*')
            ->select('e.machine as employee_machine_location')
            ->select('e.reporting_manager_id as reporting_manager_id')
            ->join('employees as e', 'e.id = comp_off_credit_requests.employee_id', 'left')
            ->where('comp_off_credit_requests.status', 'stage_1')
            ->where("comp_off_credit_requests.working_date between '" . $ninty_days_ago . "' and '" . date('Y-m-d') . "'")
            ->where('e.machine !=', 'hn');

        $manu_requests = $CompOffCreditModel->findAll();
        $pending_from_manu = count($manu_requests);

        // Count pending from Aryan (employee_id = 54) - stage_1 requests from hn employees OR his direct reports
        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditModel
            ->select('comp_off_credit_requests.*')
            ->select('e.machine as employee_machine_location')
            ->select('e.reporting_manager_id as reporting_manager_id')
            ->join('employees as e', 'e.id = comp_off_credit_requests.employee_id', 'left')
            ->where('comp_off_credit_requests.status', 'stage_1')
            ->where("comp_off_credit_requests.working_date between '" . $ninty_days_ago . "' and '" . date('Y-m-d') . "'");

        $aryan_requests_all = $CompOffCreditModel->findAll();

        // Filter: only include requests where (machine = 'hn' OR reporting_manager_id = 54)
        $pending_from_aryan = 0;
        foreach ($aryan_requests_all as $request) {
            if ($request['employee_machine_location'] == 'hn' || $request['reporting_manager_id'] == '54') {
                $pending_from_aryan++;
            }
        }

        $response_array['response_type'] = 'success';
        $response_array['pending_from_manu'] = $pending_from_manu;
        $response_array['pending_from_aryan'] = $pending_from_aryan;

        return $this->response->setJSON($response_array);
    }
}
