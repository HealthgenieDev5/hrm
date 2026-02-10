<?php

namespace App\Controllers\Approval;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\OdRequestsModel;
use App\Controllers\BaseController;

class Od extends BaseController
{

    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index()
    {

        $current_user = $this->session->get('current_user');

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl', 'manager'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        #if( admin_module_accessible( $current_user['role'], 'od', 'approval' ) ){
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        $data = [
            'page_title'     => 'OD Approval',
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
            $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

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

            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.date_of_leaving is null');
            $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
            $EmployeeModel->groupEnd();

            $EmployeeModel->orderby('c.company_short_name', 'ASC');
            $EmployeeModel->orderby('d.department_name', 'ASC');
            $EmployeeModel->orderby('employees.first_name', 'ASC');
            $Employees = $EmployeeModel->findAll();
            $data['Employees'] = $Employees;
            $data['EmployeesSQL'] = $EmployeeModel->getLastQuery()->getQuery();
        }

        $OdRequestsModel = new OdRequestsModel();
        $data['statuses'] = $OdRequestsModel->distinct()->select('status')->orderBy('status', 'ASC')->findAll();


        /*echo '<pre>';
            print_r($data);
            die();*/
        // $data['od_requests'] = $od_requests;


        return view('Administrative/OdApproval', $data);
        #}else{
        #return redirect()->to(base_url('/unauthorised'));
        #}

    }

    public function getAllOdApprovalRequests()
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

        $OdRequestsModel = new OdRequestsModel();
        $OdRequestsModel
            ->select("od_requests.id as od_request_id")
            ->select("d.department_name as department_name")
            ->select("od_requests.estimated_from_date_time as estimated_from_date_time")
            ->select("od_requests.estimated_to_date_time as estimated_to_date_time")
            ->select("od_requests.actual_from_date_time as actual_from_date_time")
            ->select("od_requests.actual_to_date_time as actual_to_date_time")
            ->select("od_requests.international as international")
            ->select("od_requests.duty_location as duty_location")
            ->select("od_requests.reason as reason")
            ->select("od_requests.status as status")
            ->select("od_requests.date_time as date_time")
            ->select("od_requests.reviewed_date_time as reviewed_date_time")
            ->select("od_requests.remarks as remarks")
            ->select("od_requests.updated_date_time as updated_date_time")
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by")
            ->join("employees as e1", "e1.id = od_requests.employee_id", "left")
            ->join("departments as d", "d.id = e1.department_id", "left")
            ->join("employees as e2", "e2.id = e1.reporting_manager_id", "left")
            ->join("employees as e3", "e3.id = d.hod_employee_id", "left")
            ->join("employees as e4", "e4.id = od_requests.reviewed_by", "left")
            ->join("employees as e5", "e5.id = od_requests.duty_assigner", "left");

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $OdRequestsModel->groupStart();
        $OdRequestsModel->where('e1.date_of_leaving is null');
        $OdRequestsModel->orWhere('e1.date_of_leaving >= (' . $date_45_days_before . ')');
        $OdRequestsModel->groupEnd();

        if ($reporting_to_me == 'yes') {
            $OdRequestsModel->groupStart();
            $OdRequestsModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $OdRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $OdRequestsModel->where('e1.reporting_manager_id !=', $current_user['employee_id']);
            $OdRequestsModel->groupStart();
            $OdRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $OdRequestsModel->orWhereIn("'" . $current_user['role'] . "'", ['admin', 'superuser', 'hr']);
            $OdRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $OdRequestsModel->groupStart();
            $OdRequestsModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $OdRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $OdRequestsModel->orWhereIn("'" . $current_user['role'] . "'", ['admin', 'superuser', 'hr']);
            $OdRequestsModel->groupEnd();
        }

        if ($current_user['employee_id'] != '40') {
            $OdRequestsModel->where('e1.id !=', $current_user['employee_id']);
        }


        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $OdRequestsModel->whereIn('e1.company_id', $company_id);
        }
        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $OdRequestsModel->whereIn('e1.department_id', $department_id);
        }
        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $OdRequestsModel->whereIn('e1.id', $employee_id);
        }
        if (!empty($status) && !in_array('all_status', $status)) {
            $OdRequestsModel->whereIn('od_requests.status', $status);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $OdRequestsModel->groupStart();
            $OdRequestsModel->where("(date(od_requests.estimated_from_date_time) between '" . $from_date . "' and '" . $to_date . "')");
            $OdRequestsModel->orWhere("(date(od_requests.estimated_to_date_time) between '" . $from_date . "' and '" . $to_date . "')");
            $OdRequestsModel->orWhere("(date(od_requests.actual_from_date_time) between '" . $from_date . "' and '" . $to_date . "')");
            $OdRequestsModel->orWhere("(date(od_requests.actual_to_date_time) between '" . $from_date . "' and '" . $to_date . "')");
            $OdRequestsModel->orWhere("('" . $from_date . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))");
            $OdRequestsModel->orWhere("('" . $to_date . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))");
            $OdRequestsModel->orWhere("('" . $from_date . "' between date(od_requests.actual_from_date_time) and date(od_requests.actual_to_date_time))");
            $OdRequestsModel->orWhere("('" . $to_date . "' between date(od_requests.actual_from_date_time) and date(od_requests.actual_to_date_time))");
            $OdRequestsModel->groupEnd();
        }

        $od_requests = $OdRequestsModel->findAll();


        foreach ($od_requests as $index => $dataRow) {
            #send first cell value as empty, the datatable will generate checkboxes itselt
            $od_requests[$index]['checkbox'] = '';
            #send last cell value as button group like this
            $od_requests[$index]['actions'] = '';

            if (isset($dataRow['actual_from_date_time']) && !empty($dataRow['actual_from_date_time']) && isset($dataRow['actual_to_date_time']) && !empty($dataRow['actual_to_date_time'])) {
                $actual_from_date_time = date_create($dataRow['actual_from_date_time']);
                $actual_to_date_time = date_create($dataRow['actual_to_date_time']);
                $interval = date_diff($actual_from_date_time, $actual_to_date_time);
            } elseif (isset($dataRow['estimated_from_date_time']) && !empty($dataRow['estimated_from_date_time']) && isset($dataRow['estimated_to_date_time']) && !empty($dataRow['estimated_to_date_time'])) {
                $estimated_from_date_time = date_create($dataRow['estimated_from_date_time']);
                $estimated_to_date_time = date_create($dataRow['estimated_to_date_time']);
                $interval = date_diff($estimated_from_date_time, $estimated_to_date_time);
            }

            $hours = 0;
            $hours += (int)$interval->format('%d') * 24;
            $hours += (int)$interval->format('%h');
            $minutes = 0;
            $minutes += (int)$interval->format('%i');
            $minutes += round((int)$interval->format('%s') / 60);
            $od_requests[$index]['interval'] = json_encode($interval);
            $od_requests[$index]['interval'] = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

            if ($dataRow['assigned_by'] == $current_user['employee_id']) {
                $od_requests[$index]['assigned_by'] = 'Self';
            }

            if (isset($dataRow['estimated_from_date_time']) && !empty($dataRow['estimated_from_date_time']) && isset($dataRow['date_time']) && !empty($dataRow['date_time'])) {
                if (strtotime($dataRow['estimated_from_date_time']) >= strtotime($dataRow['date_time'])) {
                    $od_requests[$index]['pre_post'] = 'Pre';
                } else {
                    $od_requests[$index]['pre_post'] = 'Post';
                }
            }

            if ((isset($dataRow['date_time']) && !empty($dataRow['date_time'])) && (!isset($dataRow['reviewed_date_time']) && empty($dataRow['reviewed_date_time']))) {
                $RequestedOn = date_create($dataRow['date_time']);
                $CheckingOn = date_create(date('Y-m-d'));
                $PendingDays = date_diff($RequestedOn, $CheckingOn);
                $od_requests[$index]['pending_days'] = (int)$PendingDays->format('%d') + 1;
            } else {
                $od_requests[$index]['pending_days'] = '';
            }

            $estimated_from_date_time_formatted = !empty($dataRow['estimated_from_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['estimated_from_date_time'])), 'time' => date('h:i A', strtotime($dataRow['estimated_from_date_time']))] : '-';
            $estimated_from_date_time_ordering = !empty($dataRow['estimated_from_date_time']) ? strtotime($dataRow['estimated_from_date_time']) : '0';
            $od_requests[$index]['estimated_from_date_time'] = array('formatted' => $estimated_from_date_time_formatted, 'ordering' => $estimated_from_date_time_ordering);

            $estimated_to_date_time_formatted = !empty($dataRow['estimated_to_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['estimated_to_date_time'])), 'time' => date('h:i A', strtotime($dataRow['estimated_to_date_time']))] : '-';
            $estimated_to_date_time_ordering = !empty($dataRow['estimated_to_date_time']) ? strtotime($dataRow['estimated_to_date_time']) : '0';
            $od_requests[$index]['estimated_to_date_time'] = array('formatted' => $estimated_to_date_time_formatted, 'ordering' => $estimated_to_date_time_ordering);

            $actual_from_date_time_formatted = !empty($dataRow['actual_from_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['actual_from_date_time'])), 'time' => date('h:i A', strtotime($dataRow['actual_from_date_time']))] : '-';
            $actual_from_date_time_ordering = !empty($dataRow['actual_from_date_time']) ? strtotime($dataRow['actual_from_date_time']) : '0';
            $od_requests[$index]['actual_from_date_time'] = array('formatted' => $actual_from_date_time_formatted, 'ordering' => $actual_from_date_time_ordering);

            $actual_to_date_time_formatted = !empty($dataRow['actual_to_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['actual_to_date_time'])), 'time' => date('h:i A', strtotime($dataRow['actual_to_date_time']))] : '-';
            $actual_to_date_time_ordering = !empty($dataRow['actual_to_date_time']) ? strtotime($dataRow['actual_to_date_time']) : '0';
            $od_requests[$index]['actual_to_date_time'] = array('formatted' => $actual_to_date_time_formatted, 'ordering' => $actual_to_date_time_ordering);

            $date_time_formatted = !empty($dataRow['date_time']) ? ['date' => date('d M Y', strtotime($dataRow['date_time'])), 'time' => date('h:i A', strtotime($dataRow['date_time']))] : '-';
            $date_time_ordering = !empty($dataRow['date_time']) ? strtotime($dataRow['date_time']) : '0';
            $od_requests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            $reviewed_date_time_formatted = !empty($dataRow['reviewed_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['reviewed_date_time'])), 'time' => date('h:i A', strtotime($dataRow['reviewed_date_time']))] : '-';
            $reviewed_date_time_ordering = !empty($dataRow['reviewed_date_time']) ? strtotime($dataRow['reviewed_date_time']) : '0';
            $od_requests[$index]['reviewed_date_time'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

            $updated_date_time_formatted = !empty($dataRow['updated_date_time']) ? ['date' => date('d M Y', strtotime($dataRow['updated_date_time'])), 'time' => date('h:i A', strtotime($dataRow['updated_date_time']))] : '-';
            $updated_date_time_ordering = !empty($dataRow['updated_date_time']) ? strtotime($dataRow['updated_date_time']) : '0';
            $od_requests[$index]['updated_date_time'] = array('formatted' => $updated_date_time_formatted, 'ordering' => $updated_date_time_ordering);
        }
        echo json_encode($od_requests);
    }

    public function getOdRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'od_id'  =>  [
                    'rules'         =>  'required|is_not_unique[od_requests.id]',
                    'errors'        =>  [
                        'required'  => 'OD ID is required',
                        'is_not_unique' => 'This OD is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('od_id');
        } else {
            $od_id   = $this->request->getPost('od_id');
            $od_request_sql = "select 
            o.id as od_id, 
            d.department_name as department_name, 
            o.estimated_from_date_time as estimated_from_date_time, 
            o.estimated_to_date_time as estimated_to_date_time, 
            o.actual_from_date_time as actual_from_date_time, 
            o.actual_to_date_time as actual_to_date_time, 
            o.duty_location as duty_location, 
            o.reason as reason, 
            o.status as status, 
            o.date_time as date_time, 
            o.reviewed_date_time as reviewed_date_time, 
            o.remarks as remarks, 
            o.updated_date_time as updated_date_time, 
            trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name, 
            trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name, 
            trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name, 
            trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name, 
            trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by 
            from od_requests o 
            left join employees e1 on e1.id = o.employee_id 
            left join departments d on d.id = e1.department_id 
            left join employees e2 on e2.id = e1.reporting_manager_id 
            left join employees e3 on e3.id = d.hod_employee_id 
            left join employees e4 on e4.id = o.reviewed_by 
            left join employees e5 on e5.id = o.duty_assigner 
            where o.id = '" . $od_id . "'";
            $CustomModel = new CustomModel();
            $od_request = $CustomModel->CustomQuery($od_request_sql)->getResultArray()[0];
            $od_request['estimated_from_date_time'] = !empty($od_request['estimated_from_date_time']) ? date('d-M-Y h:i A', strtotime($od_request['estimated_from_date_time'])) : '';
            $od_request['estimated_to_date_time'] = !empty($od_request['estimated_to_date_time']) ? date('d-M-Y h:i A', strtotime($od_request['estimated_to_date_time'])) : '';
            $od_request['actual_from_date_time_display'] = !empty($od_request['actual_from_date_time']) ? date('d-M-Y h:i A', strtotime($od_request['actual_from_date_time'])) : '';
            $od_request['actual_to_date_time_display'] = !empty($od_request['actual_to_date_time']) ? date('d-M-Y h:i A', strtotime($od_request['actual_to_date_time'])) : '';

            $od_request['actual_from_date_time'] = !empty($od_request['actual_from_date_time']) ? date('Y-m-d H:i', strtotime($od_request['actual_from_date_time'])) : (!empty($od_request['estimated_from_date_time']) ? date('Y-m-d H:i', strtotime($od_request['estimated_from_date_time'])) : '');
            $od_request['actual_to_date_time'] = !empty($od_request['actual_to_date_time']) ? date('Y-m-d H:i', strtotime($od_request['actual_to_date_time'])) : (!empty($od_request['estimated_to_date_time']) ? date('Y-m-d H:i', strtotime($od_request['estimated_to_date_time'])) : '');




            $od_request['date_time'] = !empty($od_request) ? date('d-M-Y h:i A', strtotime($od_request['date_time'])) : '';
            if (empty($od_request)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'OD Found';
                $response_array['response_data']['od_data'] = $od_request;
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function approveOdRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'od_id'  =>  [
                    'rules'         =>  'required|is_not_unique[od_requests.id]',
                    'errors'        =>  [
                        'required'  => 'OD Id is required',
                        'is_not_unique' => 'This OD does not exist in our database'
                    ]
                ],
                'actual_from_date_time'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select actual from date and time',
                    ]
                ],
                'actual_to_date_time'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select actual to date and time',
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $od_id   = $this->request->getPost('od_id');

            $OdRequestsModel = new OdRequestsModel();
            $theOD = $OdRequestsModel->find($od_id);
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_od = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_od = "yes";
            } else {
                $can_review_hr_od = "no";
            }
            if (in_array($theOD['employee_id'], [80, 84, 52, 95, 93, 99, 293]) && $can_review_hr_od == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can approve this OD Request';
                return $this->response->setJSON($response_array);
                die();
            }


            $data = [
                'actual_from_date_time' => !empty($this->request->getPost('actual_from_date_time')) ? date('Y-m-d H:i:s', strtotime($this->request->getPost('actual_from_date_time'))) : null,
                'actual_to_date_time'   => !empty($this->request->getPost('actual_to_date_time')) ? date('Y-m-d H:i:s', strtotime($this->request->getPost('actual_to_date_time'))) : null,
                'status'                => 'approved',
                'reviewed_by'           => $this->session->get('current_user')['employee_id'],
                'reviewed_date_time'    => date('Y-m-d H:i:s'),
                'remarks'               => $this->request->getPost('remarks'),
            ];
            $OdRequestsModel = new OdRequestsModel();
            $query = $OdRequestsModel->update($od_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $OdRequestsModel = new OdRequestsModel();
                $ODRequest = $OdRequestsModel
                    ->select('od_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as assigned_by_name")
                    ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
                    ->select('e.work_email as employee_email')
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->join('employees as e', 'e.id = od_requests.employee_id', 'left')
                    ->join('employees as e1', 'e1.id = od_requests.duty_assigner', 'left')
                    ->join('employees as e2', 'e2.id = od_requests.reviewed_by', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->where('od_requests.id =', $od_id)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($ODRequest['employee_email'])) {
                    $to_emails[] = $ODRequest['employee_email'];
                }
                $email->setTo($to_emails);
                $email->setSubject('OD Request Approved');

                /*$email->setMessage('
                        You OD request has been approved, 
                        <br>
                        The details are below. 
                        <br> 
                        <div>
                            <p>Employee Name: '.$ODRequest["employee_name"].'</p>
                            <p>Employee Code: '.$ODRequest["internal_employee_id"].'</p>
                            <p>Department: '.$ODRequest["department_name"].'</p>
                            <p>Estimated From: '.$ODRequest["estimated_from_date_time"].'</p>
                            <p>Estimated To: '.$ODRequest["estimated_to_date_time"].'</p>
                            <p>Actual From: '.$ODRequest["actual_from_date_time"].'</p>
                            <p>Actual To: '.$ODRequest["actual_to_date_time"].'</p>
                            <p>Duty Location: '.$ODRequest["duty_location"].'</p>
                            <p>Assigned By: '.$ODRequest["assigned_by_name"].'</p>
                            <p>Reason: '.$ODRequest["reason"].'</p>
                            <p>Status: '.$ODRequest["status"].'</p>
                            <p>Reviewed By: '.$ODRequest["reviewed_by_name"].'</p>
                            <p>Remarks: '.$ODRequest["remarks"].'</p>
                        </div>
                    ');*/

                $email->setMessage('
                            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <!--begin:Email content-->
                                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                                        <strong>You OD request has been approved by ' . $ODRequest["reviewed_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the OD request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $ODRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Code:</span> ' . $ODRequest["internal_employee_id"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $ODRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated From:</span> ' . $ODRequest["estimated_from_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated To:</span> ' . $ODRequest["estimated_to_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Duty Location:</span> ' . $ODRequest["duty_location"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Assigned By:</span> ' . $ODRequest["assigned_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reason:</span> ' . $ODRequest["reason"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . $ODRequest["status"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reviewed By:</span> ' . $ODRequest["reviewed_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $ODRequest["remarks"] . '</div>
                                                    <!--end:Email content-->
                                                    <div style="padding-bottom: 10px">Kind regards,
                                                    <br>HRM Team.
                                                    <tr>
                                                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                            <p>Copyright ©
                                                            <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                        </td>
                                                    </tr></br></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            ');

                if ($email->send()) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Approved';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Approved, but email not send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function rejectOdRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'od_id'  =>  [
                    'rules'         =>  'required|is_not_unique[od_requests.id]',
                    'errors'        =>  [
                        'required'  => 'OD Id is required',
                        'is_not_unique' => 'This OD does not exist in our database'
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $od_id   = $this->request->getPost('od_id');


            $OdRequestsModel = new OdRequestsModel();
            $theOD = $OdRequestsModel->find($od_id);
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_od = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_od = "yes";
            } else {
                $can_review_hr_od = "no";
            }
            if (in_array($theOD['employee_id'], [80, 84, 223, 52, 95, 93, 99, 293]) && $can_review_hr_od == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can reject this OD Request';
                return $this->response->setJSON($response_array);
                die();
            }


            $data = [
                'status'                => 'rejected',
                'reviewed_by'           => $this->session->get('current_user')['employee_id'],
                'reviewed_date_time'    => date('Y-m-d H:i:s'),
                'remarks'               => $this->request->getPost('remarks'),
            ];
            $OdRequestsModel = new OdRequestsModel();
            $query = $OdRequestsModel->update($od_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $OdRequestsModel = new OdRequestsModel();
                $ODRequest = $OdRequestsModel
                    ->select('od_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as assigned_by_name")
                    ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
                    ->select('e.work_email as employee_email')
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->join('employees as e', 'e.id = od_requests.employee_id', 'left')
                    ->join('employees as e1', 'e1.id = od_requests.duty_assigner', 'left')
                    ->join('employees as e2', 'e2.id = od_requests.reviewed_by', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->where('od_requests.id =', $od_id)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($ODRequest['employee_email'])) {
                    $to_emails[] = $ODRequest['employee_email'];
                }
                $email->setTo($to_emails);
                $email->setSubject('OD Request Rejected');
                /*$email->setMessage('
                        You OD request has been rejected, 
                        <br>
                        The details are below. 
                        <br> 
                        <div>
                            <p>Employee Name: '.$ODRequest["employee_name"].'</p>
                            <p>Employee Code: '.$ODRequest["internal_employee_id"].'</p>
                            <p>Department: '.$ODRequest["department_name"].'</p>
                            <p>Estimated From: '.$ODRequest["estimated_from_date_time"].'</p>
                            <p>Estimated To: '.$ODRequest["estimated_to_date_time"].'</p>
                            <p>Actual From: '.$ODRequest["actual_from_date_time"].'</p>
                            <p>Actual To: '.$ODRequest["actual_to_date_time"].'</p>
                            <p>Duty Location: '.$ODRequest["duty_location"].'</p>
                            <p>Assigned By: '.$ODRequest["assigned_by_name"].'</p>
                            <p>Reason: '.$ODRequest["reason"].'</p>
                            <p>Status: '.$ODRequest["status"].'</p>
                            <p>Reviewed By: '.$ODRequest["reviewed_by_name"].'</p>
                            <p>Remarks: '.$ODRequest["remarks"].'</p>
                        </div>
                    ');*/

                $email->setMessage('
                            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <!--begin:Email content-->
                                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                                        <strong>You OD request has been rejected by ' . $ODRequest["reviewed_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the OD request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $ODRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Code:</span> ' . $ODRequest["internal_employee_id"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $ODRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated From:</span> ' . $ODRequest["estimated_from_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated To:</span> ' . $ODRequest["estimated_to_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Duty Location:</span> ' . $ODRequest["duty_location"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Assigned By:</span> ' . $ODRequest["assigned_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reason:</span> ' . $ODRequest["reason"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . $ODRequest["status"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reviewed By:</span> ' . $ODRequest["reviewed_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $ODRequest["remarks"] . '</div>
                                                    <!--end:Email content-->
                                                    <div style="padding-bottom: 10px">Kind regards,
                                                    <br>HRM Team.
                                                    <tr>
                                                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                            <p>Copyright ©
                                                            <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                        </td>
                                                    </tr></br></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            ');
                if ($email->send()) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Rejected';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Rejected, but email not send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function cancelOdRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'od_id'  =>  [
                    'rules'         =>  'required|is_not_unique[od_requests.id]',
                    'errors'        =>  [
                        'required'  => 'OD Id is required',
                        'is_not_unique' => 'This OD does not exist in our database'
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {
            $od_id   = $this->request->getPost('od_id');


            $OdRequestsModel = new OdRequestsModel();
            $theOD = $OdRequestsModel->find($od_id);
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_od = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_od = "yes";
            } else {
                $can_review_hr_od = "no";
            }
            if (in_array($theOD['employee_id'], [80, 84, 223, 52, 95, 93, 99, 293]) && $can_review_hr_od == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can cancel this OD Request';
                return $this->response->setJSON($response_array);
                die();
            }


            $data = [
                'status'                => 'canceled',
                'reviewed_by'           => $this->session->get('current_user')['employee_id'],
                'reviewed_date_time'    => date('Y-m-d H:i:s'),
                'remarks'               => $this->request->getPost('remarks'),
            ];
            $OdRequestsModel = new OdRequestsModel();
            $query = $OdRequestsModel->update($od_id, $data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $OdRequestsModel = new OdRequestsModel();
                $ODRequest = $OdRequestsModel
                    ->select('od_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as assigned_by_name")
                    ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
                    ->select('e.work_email as employee_email')
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->join('employees as e', 'e.id = od_requests.employee_id', 'left')
                    ->join('employees as e1', 'e1.id = od_requests.duty_assigner', 'left')
                    ->join('employees as e2', 'e2.id = od_requests.reviewed_by', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->where('od_requests.id =', $od_id)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($ODRequest['employee_email'])) {
                    $to_emails[] = $ODRequest['employee_email'];
                }
                $email->setTo($to_emails);
                $email->setSubject('OD Request Canceled');
                /*$email->setMessage('
                        You OD request has been canceled, 
                        <br>
                        The details are below. 
                        <br> 
                        <div>
                            <p>Employee Name: '.$ODRequest["employee_name"].'</p>
                            <p>Employee Code: '.$ODRequest["internal_employee_id"].'</p>
                            <p>Department: '.$ODRequest["department_name"].'</p>
                            <p>Estimated From: '.$ODRequest["estimated_from_date_time"].'</p>
                            <p>Estimated To: '.$ODRequest["estimated_to_date_time"].'</p>
                            <p>Actual From: '.$ODRequest["actual_from_date_time"].'</p>
                            <p>Actual To: '.$ODRequest["actual_to_date_time"].'</p>
                            <p>Duty Location: '.$ODRequest["duty_location"].'</p>
                            <p>Assigned By: '.$ODRequest["assigned_by_name"].'</p>
                            <p>Reason: '.$ODRequest["reason"].'</p>
                            <p>Status: '.$ODRequest["status"].'</p>
                            <p>Reviewed By: '.$ODRequest["reviewed_by_name"].'</p>
                            <p>Remarks: '.$ODRequest["remarks"].'</p>
                        </div>
                    ');*/

                $email->setMessage('
                            <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                                    <tbody>
                                        <tr>
                                            <td align="center" valign="center" style="text-align:center; padding: 40px">
                                                <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                                                    <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="center">
                                                <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                                    <!--begin:Email content-->
                                                    <div style="padding-bottom: 30px; font-size: 17px;">
                                                        <strong>You OD request has been canceled by ' . $ODRequest["reviewed_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the OD request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $ODRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Code:</span> ' . $ODRequest["internal_employee_id"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $ODRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated From:</span> ' . $ODRequest["estimated_from_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated To:</span> ' . $ODRequest["estimated_to_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Duty Location:</span> ' . $ODRequest["duty_location"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Assigned By:</span> ' . $ODRequest["assigned_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reason:</span> ' . $ODRequest["reason"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . $ODRequest["status"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reviewed By:</span> ' . $ODRequest["reviewed_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $ODRequest["remarks"] . '</div>
                                                    <!--end:Email content-->
                                                    <div style="padding-bottom: 10px">Kind regards,
                                                    <br>HRM Team.
                                                    <tr>
                                                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                                                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                                                            <p>Copyright ©
                                                            <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                                                        </td>
                                                    </tr></br></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            ');
                if ($email->send()) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Canceled';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'OD Canceled, but email not send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }
}
