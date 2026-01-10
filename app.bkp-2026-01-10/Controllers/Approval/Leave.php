<?php

namespace App\Controllers\Approval;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;
use App\Models\LeaveBalanceModel;
use App\Models\LeaveCreditHistoryModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class Leave extends BaseController
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

        #if( admin_module_accessible( $current_user['role'], 'leave', 'approval' ) ){
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        $data = [
            'page_title'     => 'Leave Approval',
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
            $date_365_days_before = date('Y-m-d', strtotime('-365 days'));

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
            $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $date_365_days_before . "')");
            $EmployeeModel->groupEnd();

            $EmployeeModel->orderby('c.company_short_name', 'ASC');
            $EmployeeModel->orderby('d.department_name', 'ASC');
            $EmployeeModel->orderby('employees.first_name', 'ASC');
            $Employees = $EmployeeModel->findAll();
            $data['Employees'] = $Employees;
        }

        $LeaveRequestsModel = new LeaveRequestsModel();
        $data['statuses'] = $LeaveRequestsModel->distinct()->select('status')->orderBy('status', 'ASC')->findAll();

        $all_leave_types = $LeaveRequestsModel->distinct()->select('type_of_leave')->where('leave_requests.type_of_leave !=', 'COMP OFF')->orderBy('type_of_leave', 'ASC')->findAll();
        if (in_array($current_user['role'], ['hr'])) {
            $all_leave_types = $LeaveRequestsModel->distinct()->select('type_of_leave')->orderBy('type_of_leave', 'ASC')->findAll();
        }
        $all_leave_types[] = ['type_of_leave' => 'SICK LEAVE'];
        $data['leave_types'] = $all_leave_types;


        return view('Administrative/LeaveApproval', $data);
        #}else{
        #return redirect()->to(base_url('/unauthorised'));
        #}

    }

    public function getAllLeaveApprovalRequests()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $from_date      = isset($params['from_date']) ? $params['from_date'] : '';
        $status         = isset($params['status']) ? $params['status'] : '';
        $leave_type     = isset($params['leave_type']) ? $params['leave_type'] : '';

        $to_date        = isset($params['to_date']) ? $params['to_date'] : '';
        $reporting_to_me = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $current_user = $this->session->get('current_user');

        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];

        $LeaveRequestsModel = new LeaveRequestsModel();
        $LeaveRequestsModel
            ->select('leave_requests.id as leave_request_id')
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
            ->select('d.department_name as department_name')
            ->select('leave_requests.type_of_leave as type_of_leave')
            ->select('leave_requests.sick_leave as sick_leave')
            ->select('leave_requests.backend_request as backend_request')
            ->select('leave_requests.number_of_days as number_of_days')
            ->select('leave_requests.day_type as day_type')
            ->select('leave_requests.from_date as from_date')
            ->select('leave_requests.to_date as to_date')
            ->select('leave_requests.status as status')
            ->select('leave_requests.reason_of_leave as reason_of_leave')
            ->select('leave_requests.date_time as date_time')
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select('leave_requests.reviewed_date as reviewed_date')
            ->select('leave_requests.remarks as remarks')
            ->select('leave_requests.attachment as attachment')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e.department_id', 'left')
            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = leave_requests.reviewed_by', 'left');

        if (!in_array($current_user_role, ['hr']) && !in_array($current_user['employee_id'], ['40'])) {
            $LeaveRequestsModel->where('leave_requests.type_of_leave !=', 'COMP OFF');
        }

        if ($reporting_to_me == 'yes') {
            $LeaveRequestsModel->groupStart();
            $LeaveRequestsModel->where('e.reporting_manager_id =', $current_user['employee_id']);
            $LeaveRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $LeaveRequestsModel->where('e.reporting_manager_id !=', $current_user['employee_id']);
            $LeaveRequestsModel->groupStart();
            $LeaveRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $LeaveRequestsModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $LeaveRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $LeaveRequestsModel->groupStart();
            $LeaveRequestsModel->where('e.reporting_manager_id =', $current_user['employee_id']);
            $LeaveRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $LeaveRequestsModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $LeaveRequestsModel->groupEnd();
        }

        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $LeaveRequestsModel->whereIn('e.company_id', $company_id);
        }
        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $LeaveRequestsModel->whereIn('e.department_id', $department_id);
        }
        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $LeaveRequestsModel->whereIn('e.id', $employee_id);
        }
        if (!empty($status) && !in_array('all_status', $status)) {
            $LeaveRequestsModel->whereIn('leave_requests.status', $status);
        }

        if (!empty($leave_type) && !in_array('all_leave_type', $leave_type)) {
            $LeaveRequestsModel->groupStart();
            $LeaveRequestsModel->whereIn('leave_requests.type_of_leave', $leave_type);
            if (in_array('SICK LEAVE', $leave_type)) {
                $LeaveRequestsModel->orGroupStart();
                $LeaveRequestsModel->where('leave_requests.type_of_leave', 'EL');
                $LeaveRequestsModel->where('leave_requests.sick_leave', 'yes');
                $LeaveRequestsModel->groupEnd();
            } elseif (!in_array('SICK LEAVE', $leave_type) && in_array('EL', $leave_type)) {
                $LeaveRequestsModel->whereNotIn('leave_requests.type_of_leave', ['EL']);
                $LeaveRequestsModel->orGroupStart();
                $LeaveRequestsModel->where('leave_requests.type_of_leave', 'EL');
                $LeaveRequestsModel->where('leave_requests.sick_leave', 'no');
                $LeaveRequestsModel->groupEnd();
            }
            $LeaveRequestsModel->groupEnd();
        }



        if (!empty($from_date) && !empty($to_date)) {
            $LeaveRequestsModel->groupStart();
            $LeaveRequestsModel->where("leave_requests.from_date between '" . $from_date . "' and '" . $to_date . "'");
            $LeaveRequestsModel->orWhere("leave_requests.to_date between '" . $from_date . "' and '" . $to_date . "'");
            $LeaveRequestsModel->orWhere("'" . $from_date . "' between leave_requests.from_date and leave_requests.to_date");
            $LeaveRequestsModel->orWhere("'" . $to_date . "' between leave_requests.from_date and leave_requests.to_date");
            // $LeaveRequestsModel->orWhere("date(leave_requests.date_time) between '".$from_date."' and '".$to_date."'");
            $LeaveRequestsModel->groupEnd();
        }

        if ($current_user['employee_id'] !== '40') {
            $leave_requests = $LeaveRequestsModel->where('e.id !=', $current_user['employee_id']);
        }

        $leave_requests = $LeaveRequestsModel->findAll();

        // echo '<pre>';
        // print_r($LeaveRequestsModel->getLastQuery()->getQuery());
        // echo '</pre>';
        // die();

        foreach ($leave_requests as $index => $dataRow) {
            $leave_requests[$index]['actions'] = '';

            $from_date_formatted = !empty($dataRow['from_date']) ? date('d M Y', strtotime($dataRow['from_date'])) : '-';
            $from_date_ordering = !empty($dataRow['from_date']) ? strtotime($dataRow['from_date']) : '0';
            $leave_requests[$index]['from_date'] = array('formatted' => $from_date_formatted, 'ordering' => $from_date_ordering);

            $to_date_formatted = !empty($dataRow['to_date']) ? date('d M Y', strtotime($dataRow['to_date'])) : '-';
            $to_date_ordering = !empty($dataRow['to_date']) ? strtotime($dataRow['to_date']) : '0';
            $leave_requests[$index]['to_date'] = array('formatted' => $to_date_formatted, 'ordering' => $to_date_ordering);

            $date_time_formatted = !empty($dataRow['date_time']) ? date('d M Y h:i A', strtotime($dataRow['date_time'])) : '-';
            $date_time_ordering = !empty($dataRow['date_time']) ? strtotime($dataRow['date_time']) : '0';
            $leave_requests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            $reviewed_date_formatted = !empty($dataRow['reviewed_date']) ? date('d M Y', strtotime($dataRow['reviewed_date'])) : '-';
            $reviewed_date_ordering = !empty($dataRow['reviewed_date']) ? strtotime($dataRow['reviewed_date']) : '0';
            $leave_requests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);
        }
        echo json_encode($leave_requests);
    }

    public function getLeaveRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leave_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('leave_id');
        } else {
            $leave_id   = $this->request->getVar('leave_id');
            $leave_request_sql = "select 
            lr.id as leave_request_id, 
            trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
            d.department_name as department_name, 
            c.company_short_name as company_short_name, 
            lr.from_date as from_date, 
            lr.to_date as to_date, 
            lr.number_of_days as number_of_days, 
            lr.type_of_leave as type_of_leave, 
            lr.sick_leave as sick_leave, 
            lr.backend_request as backend_request, 
            lr.day_type as day_type, 
            lr.reason_of_leave as reason_of_leave, 
            lr.status as status, 
            lr.date_time as date_time, 
            trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name,
            lr.reviewed_date as reviewed_date,  
            lr.remarks as remarks, 
            e2.id as reporting_manager_id,
            trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name, 
            trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name, 
            (SELECT balance FROM leave_balance WHERE employee_id = lr.employee_id and leave_code = 'CL' and month = month(lr.from_date) and year = year(lr.from_date) ) as cl_balance, 
            (SELECT balance FROM leave_balance WHERE employee_id = lr.employee_id and leave_code = 'EL' and month = month(lr.from_date) and year = year(lr.from_date) ) as el_balance 
            from leave_requests lr 
            left join employees e on e.id = lr.employee_id 
            left join departments d on d.id = e.department_id 
            left join companies c on c.id = e.company_id 
            left join employees e2 on e2.id = e.reporting_manager_id 
            left join employees e3 on e3.id = d.hod_employee_id 
            left join employees e4 on e4.id = lr.reviewed_by 
            where lr.id='" . $leave_id . "'";

            $CustomModel = new CustomModel();
            $leave_request = $CustomModel->CustomQuery($leave_request_sql)->getResultArray()[0];

            $leave_request['from_date'] = !empty($leave_request['from_date']) ? date('d-M-Y', strtotime($leave_request['from_date'])) : '';
            $leave_request['to_date'] = !empty($leave_request['to_date']) ? date('d-M-Y', strtotime($leave_request['to_date'])) : '';
            $leave_request['date_time'] = !empty($leave_request['date_time']) ? date('d-M-Y h:i A', strtotime($leave_request['date_time'])) : '';
            $leave_request['reviewed_date'] = !empty($leave_request['reviewed_date']) ? date('d-M-Y', strtotime($leave_request['reviewed_date'])) : '';
            $leave_request['balance_month_year'] = !empty($leave_request['from_date']) ? date('M, Y', strtotime($leave_request['from_date'])) : '';

            if (empty($leave_request)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Leave Found';
                $response_array['response_data']['leave_data'] = $leave_request;
            }
        }
        #echo json_encode($response_array);
        return $this->response->setJSON($response_array);
    }

    public function approveLeaveRequest()
    {
        $response_array = array();
        /*$response_array['response_type'] = 'error';
        $response_array['response_description'] = 'Temporarily disabled';
        return $this->response->setJSON($response_array);
        die();*/

        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leave_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
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
            $leave_id   = $this->request->getPost('leave_id');
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            $LeaveRequestsModel = new LeaveRequestsModel();
            $theLeaveRequest = $LeaveRequestsModel->find($leave_id);
            $current_status = $theLeaveRequest['status'];

            $EmployeeModel = new EmployeeModel();
            $Employee = $EmployeeModel->find($theLeaveRequest['employee_id']);
            $reporting_manager_id = $Employee['reporting_manager_id'];

            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == $reporting_manager_id) {
                $can_review_hr_leave = "yes";
            } else {
                $can_review_hr_leave = "no";
            }

            if (in_array($theLeaveRequest['employee_id'], [80, 84, 223, 52, 95, 93, 99, 293]) && $can_review_hr_leave == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can approve this leave request';
            } elseif ($current_status !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This leave is not in pending status';
            } else {

                $current_date = date('Y-m-d');
                $data = [
                    'status'                => 'approved',
                    'reviewed_by'           => $current_user_employee_id,
                    'reviewed_date'         => $current_date,
                    'remarks'               => $this->request->getPost('remarks'),
                ];
                $LeaveRequestsModel = new LeaveRequestsModel();
                $query = $LeaveRequestsModel->update($leave_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {


                    #Deduct CL EL from leave balance
                    #Deduct CL EL from leave balance
                    #Deduct CL EL from leave balance
                    #Deduct CL EL from leave balance
                    #Deduct CL EL from leave balance


                    $LeaveRequestsModel = new LeaveRequestsModel();
                    #send Email
                    $LeaveRequest = $LeaveRequestsModel
                        ->select('leave_requests.*')
                        ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                        ->select('e.internal_employee_id as internal_employee_id')
                        ->select('e.work_email as employee_email')
                        ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as approved_by_name")
                        ->select('d.department_name as department_name')
                        ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                        ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                        ->join('departments as d', 'd.id = e.department_id', 'left')
                        ->where('leave_requests.id =', $leave_id)
                        ->first();

                    $email = \Config\Services::email();
                    $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                    #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                    $to_emails = array('developer3@healthgenie.in');
                    if (!empty($LeaveRequest['employee_email'])) {
                        $to_emails[] = $LeaveRequest['employee_email'];
                    }
                    $email->setTo($to_emails);
                    $email->setSubject('Leave Request Approved');
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
                                                        <strong>You leave request has been approved by ' . $LeaveRequest["approved_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $LeaveRequest["remarks"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . ucfirst($LeaveRequest["status"]) . '</div>
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
                            </div>');
                    if ($email->send()) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Leave Approved';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Leave Approved, but email not send';
                    }
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function rejectLeaveRequest()
    {
        $response_array = array();

        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leave_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
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
            $leave_id   = $this->request->getPost('leave_id');
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            $LeaveRequestsModel = new LeaveRequestsModel();
            // $current_status = $LeaveRequestsModel->select('status')->where('id', $leave_id)->first()['status'];
            $theLeaveRequest = $LeaveRequestsModel->find($leave_id);
            $current_status = $theLeaveRequest['status'];

            $EmployeeModel = new EmployeeModel();
            $Employee = $EmployeeModel->find($theLeaveRequest['employee_id']);
            $reporting_manager_id = $Employee['reporting_manager_id'];

            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == $reporting_manager_id) {
                $can_review_hr_leave = "yes";
            } else {
                $can_review_hr_leave = "no";
            }

            if (in_array($theLeaveRequest['employee_id'], [80, 84, 223, 52, 95, 93, 99, 293]) && $can_review_hr_leave == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can reject this leave request';
            } elseif ($current_status !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This leave is not in pending status';
            } else {
                $current_date = date('Y-m-d');
                #######new Code#######
                $LeaveRequestsModel = new LeaveRequestsModel();
                #send Email
                $LeaveRequest = $LeaveRequestsModel
                    ->select('leave_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('e.work_email as employee_email')
                    ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as rejected_by_name")
                    ->select('d.department_name as department_name')
                    ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                    ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->where('leave_requests.id =', $leave_id)
                    ->first();

                $current_employee_id = $LeaveRequest['employee_id'];
                $type_of_leave = $LeaveRequest['type_of_leave'];

                if ($type_of_leave == 'COMP OFF') {
                    $data = [
                        'status'                => 'rejected',
                        'reviewed_by'           => $current_user_employee_id,
                        'reviewed_date'         => $current_date,
                        'remarks'               => $this->request->getPost('remarks'),
                    ];
                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $query = $LeaveRequestsModel->update($leave_id, $data);
                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        $LeaveRequestsModel = new LeaveRequestsModel();
                        $LeaveRequest = $LeaveRequestsModel
                            ->select('leave_requests.*')
                            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                            ->select('e.internal_employee_id as internal_employee_id')
                            ->select('e.work_email as employee_email')
                            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as rejected_by_name")
                            ->select('d.department_name as department_name')
                            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                            ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                            ->join('departments as d', 'd.id = e.department_id', 'left')
                            ->where('leave_requests.id =', $leave_id)
                            ->first();

                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');
                        if (!empty($LeaveRequest['employee_email'])) {
                            $to_emails[] = $LeaveRequest['employee_email'];
                        }
                        $email->setTo($to_emails);
                        $email->setSubject('Leave Request Rejected');
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
                                                    <strong>You leave request has been rejected by ' . $LeaveRequest["rejected_by_name"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $LeaveRequest["remarks"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . ucfirst($LeaveRequest["status"]) . '</div>
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
                        </div>');
                        if ($email->send()) {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Rejected';
                        } else {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Rejected, but email not send';
                        }
                    }

                    return $this->response->setJSON($response_array);
                    die();
                }

                $CurrentMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalance($LeaveRequest['employee_id']);

                $CurrentMonthLeaveBalance = 0;
                if (!empty($CurrentMonthLeaveBalanceDataArray)) {
                    foreach ($CurrentMonthLeaveBalanceDataArray as $i => $CurrentMonthLeaveBalanceData) {
                        if ($CurrentMonthLeaveBalanceData['leave_code'] == $LeaveRequest['type_of_leave']) {
                            $CurrentMonthLeaveBalance = $CurrentMonthLeaveBalanceData['balance'];
                            $CurrentMonthLeaveBalanceRowID = $CurrentMonthLeaveBalanceData['id'];
                            $CurrentMonthLeaveBalanceRow_leave_id = $CurrentMonthLeaveBalanceData['leave_id'];
                        }
                    }
                }

                $from_date  = $LeaveRequest['from_date'];
                $to_date    = $LeaveRequest['to_date'];
                $from_month = date('m', strtotime($from_date));
                $to_month   = date('m', strtotime($to_date));
                $from_year  = date('Y', strtotime($from_date));
                $to_year    = date('Y', strtotime($to_date));
                $current_month = date('m');
                $current_year = date('Y');

                if ($from_month == $current_month && $to_month == $current_month) {
                    $to_return = $LeaveRequest['number_of_days'];
                } elseif ($from_month !== $current_month && $to_month !== $current_month) {
                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = 0;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = 0;
                    }
                } elseif ($from_month == $current_month && $to_month !== $current_month) {
                    $number_of_days_current_month = 0;
                    $from_date_created = date_create($from_date);
                    $last_date_of_from_month = date('Y-m-t', strtotime($from_date));
                    $last_date_created = date_create($last_date_of_from_month);
                    $diff = date_diff($from_date_created, $last_date_created);
                    $number_of_days_current_month = $diff->format("%R%a") + 1;

                    $number_of_days_next_month = 0;
                    $first_date_of_next_month = date('Y-m-01', strtotime($to_date));
                    $first_date_of_next_month_created = date_create($first_date_of_next_month);
                    $to_date_created = date_create($to_date);
                    $diff = date_diff($first_date_of_next_month_created, $to_date_created);
                    $number_of_days_next_month = $diff->format("%R%a") + 1;


                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = $number_of_days_current_month;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = $number_of_days_current_month;
                    }
                } elseif ($from_month !== $current_month && $to_month == $current_month) {
                    /*die('I am here');*/
                    $number_of_days_current_month = 0;
                    $from_date_created = date_create($from_date);
                    $last_date_of_from_month = date('Y-m-t', strtotime($from_date));
                    $last_date_created = date_create($last_date_of_from_month);
                    $diff = date_diff($from_date_created, $last_date_created);
                    $number_of_days_current_month = $diff->format("%R%a") + 1;

                    $number_of_days_next_month = 0;
                    $first_date_of_next_month = date('Y-m-01', strtotime($to_date));
                    $first_date_of_next_month_created = date_create($first_date_of_next_month);
                    $to_date_created = date_create($to_date);
                    $diff = date_diff($first_date_of_next_month_created, $to_date_created);
                    $number_of_days_next_month = $diff->format("%R%a") + 1;

                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = $number_of_days_current_month;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = $number_of_days_current_month;
                    }
                }

                $new_balance = $CurrentMonthLeaveBalance + $to_return;
                #######new Code#######
                if (!empty($CurrentMonthLeaveBalanceRowID)) {
                    $data = [
                        'status'                => 'rejected',
                        'reviewed_by'           => $current_user_employee_id,
                        'reviewed_date'         => $current_date,
                        'remarks'               => $this->request->getPost('remarks'),
                    ];
                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $query = $LeaveRequestsModel->update($leave_id, $data);
                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        if (!empty($CurrentMonthLeaveBalanceRowID)) {
                            $leave_balance_data = ['balance' => $new_balance];
                            $LeaveBalanceModel = new LeaveBalanceModel();
                            $updateLeaveBalanceQuery = $LeaveBalanceModel->update($CurrentMonthLeaveBalanceRowID, $leave_balance_data);
                            if ($updateLeaveBalanceQuery) {
                                $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                                $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                                    'employee_id'   => $current_employee_id,
                                    'leave_id'      => $CurrentMonthLeaveBalanceRow_leave_id,
                                    'leave_amount'  => abs($to_return),
                                    'type'          => 'credit',
                                    'remarks'       => 'Leave returned from request id ' . $LeaveRequest['id'],
                                ]);
                            }
                        }

                        $LeaveRequestsModel = new LeaveRequestsModel();
                        $LeaveRequest = $LeaveRequestsModel
                            ->select('leave_requests.*')
                            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                            ->select('e.internal_employee_id as internal_employee_id')
                            ->select('e.work_email as employee_email')
                            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as rejected_by_name")
                            ->select('d.department_name as department_name')
                            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                            ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                            ->join('departments as d', 'd.id = e.department_id', 'left')
                            ->where('leave_requests.id =', $leave_id)
                            ->first();

                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');
                        if (!empty($LeaveRequest['employee_email'])) {
                            $to_emails[] = $LeaveRequest['employee_email'];
                        }
                        $email->setTo($to_emails);
                        $email->setSubject('Leave Request Rejected');
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
                                                    <strong>You leave request has been rejected by ' . $LeaveRequest["rejected_by_name"] . '</strong>
                                                </div>
                                                <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $LeaveRequest["remarks"] . '</div>
                                                <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . ucfirst($LeaveRequest["status"]) . '</div>
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
                        </div>');
                        if ($email->send()) {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Rejected';
                        } else {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Rejected, but email not send';
                        }
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = '<p class="text-danger">Cannot Reject</p>Leave Balance of current month is not updated yet, Pleae contact developer';
                    return $this->response->setJSON($response_array);
                    die();
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function cancelLeaveRequest()
    {
        $response_array = array();

        $validation = $this->validate(
            [
                'leave_id'  =>  [
                    'rules'         =>  'required|is_not_unique[leave_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Leave ID is required',
                        'is_not_unique' => 'This Leave is does not exist in our database'
                    ]
                ],
                'remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Remarks is required',
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
            $leave_id   = $this->request->getPost('leave_id');
            $LeaveRequestsModel = new LeaveRequestsModel();
            $current_user_employee_id = session()->get('current_user')['employee_id'];
            $current_user_role = session()->get('current_user')['role'];
            $theLeaveRequest = $LeaveRequestsModel->find($leave_id);

            $EmployeeModel = new EmployeeModel();
            $Employee = $EmployeeModel->find($theLeaveRequest['employee_id']);
            $reporting_manager_id = $Employee['reporting_manager_id'];

            if (in_array($current_user_role, ['hod', 'superuser'])) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == 293) {
                $can_review_hr_leave = "yes";
            } elseif ($current_user_employee_id == $reporting_manager_id) {
                $can_review_hr_leave = "yes";
            } else {
                $can_review_hr_leave = "no";
            }

            if (in_array($theLeaveRequest['employee_id'], [80, 84, 223, 52, 95, 93, 99, 293]) && $can_review_hr_leave == 'no') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'SECURITY ERROR: Only HR Manager can cancel this leave request';
                $response_array['response_data']['validation'] = null;
                return $this->response->setJSON($response_array);
                die();
            } else {
                $current_date = date('Y-m-d');
                ###########new Code###########
                $LeaveRequestsModel = new LeaveRequestsModel();
                #send Email
                $LeaveRequest = $LeaveRequestsModel
                    ->select('leave_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('e.work_email as employee_email')
                    ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as cancelled_by_name")
                    ->select('d.department_name as department_name')
                    ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                    ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->where('leave_requests.id =', $leave_id)
                    ->first();
                $current_employee_id = $LeaveRequest['employee_id'];
                $type_of_leave = $LeaveRequest['type_of_leave'];
                if ($type_of_leave == 'COMP OFF') {
                    $data = [
                        'status'                => 'cancelled',
                        'reviewed_by'           => $current_user_employee_id,
                        'reviewed_date'         => $current_date,
                        'remarks'               => $this->request->getPost('remarks'),
                    ];
                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $query = $LeaveRequestsModel->update($leave_id, $data);

                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        $LeaveRequestsModel = new LeaveRequestsModel();
                        $LeaveRequest = $LeaveRequestsModel
                            ->select('leave_requests.*')
                            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                            ->select('e.internal_employee_id as internal_employee_id')
                            ->select('e.work_email as employee_email')
                            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as cancelled_by_name")
                            ->select('d.department_name as department_name')
                            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                            ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                            ->join('departments as d', 'd.id = e.department_id', 'left')
                            ->where('leave_requests.id =', $leave_id)
                            ->first();

                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');
                        if (!empty($LeaveRequest['employee_email'])) {
                            $to_emails[] = $LeaveRequest['employee_email'];
                        }
                        $email->setTo($to_emails);
                        $email->setSubject('Leave Request Cancelled');
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
                                                        <strong>You leave request has been cancelled by ' . $LeaveRequest["cancelled_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $LeaveRequest["remarks"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . ucfirst($LeaveRequest["status"]) . '</div>
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
                            </div>');

                        if ($email->send()) {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Cancelled';
                        } else {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Cancelled, but email not send';
                        }
                    }

                    return $this->response->setJSON($response_array);
                    die();
                }


                $CurrentMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalance($LeaveRequest['employee_id']);

                $CurrentMonthLeaveBalance = 0;
                if (!empty($CurrentMonthLeaveBalanceDataArray)) {
                    foreach ($CurrentMonthLeaveBalanceDataArray as $i => $CurrentMonthLeaveBalanceData) {
                        if ($CurrentMonthLeaveBalanceData['leave_code'] == $LeaveRequest['type_of_leave']) {
                            $CurrentMonthLeaveBalance = $CurrentMonthLeaveBalanceData['balance'];
                            $CurrentMonthLeaveBalanceRowID = $CurrentMonthLeaveBalanceData['id'];
                            $CurrentMonthLeaveBalanceRow_leave_id = $CurrentMonthLeaveBalanceData['leave_id'];
                        }
                    }
                }

                $from_date  = $LeaveRequest['from_date'];
                $to_date    = $LeaveRequest['to_date'];
                $from_month = date('m', strtotime($from_date));
                $to_month   = date('m', strtotime($to_date));
                $from_year  = date('Y', strtotime($from_date));
                $to_year    = date('Y', strtotime($to_date));
                $current_month = date('m');
                $current_year = date('Y');

                if ($from_month == $current_month && $to_month == $current_month) {
                    $to_return = $LeaveRequest['number_of_days'];
                } elseif ($from_month !== $current_month && $to_month !== $current_month) {
                    #Problem in Returning CL Balance
                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = 0;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = 0;
                    }
                } elseif ($from_month == $current_month && $to_month !== $current_month) {
                    $number_of_days_current_month = 0;
                    $from_date_created = date_create($from_date);
                    $last_date_of_from_month = date('Y-m-t', strtotime($from_date));
                    $last_date_created = date_create($last_date_of_from_month);
                    $diff = date_diff($from_date_created, $last_date_created);
                    $number_of_days_current_month = $diff->format("%R%a") + 1;

                    $number_of_days_next_month = 0;
                    $first_date_of_next_month = date('Y-m-01', strtotime($to_date));
                    $first_date_of_next_month_created = date_create($first_date_of_next_month);
                    $to_date_created = date_create($to_date);
                    $diff = date_diff($first_date_of_next_month_created, $to_date_created);
                    $number_of_days_next_month = $diff->format("%R%a") + 1;

                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = $number_of_days_current_month;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = $number_of_days_current_month;
                    }
                } elseif ($from_month !== $current_month && $to_month == $current_month) {
                    $number_of_days_current_month = 0;
                    $from_date_created = date_create($from_date);
                    $last_date_of_from_month = date('Y-m-t', strtotime($from_date));
                    $last_date_created = date_create($last_date_of_from_month);
                    $diff = date_diff($from_date_created, $last_date_created);
                    $number_of_days_current_month = $diff->format("%R%a") + 1;

                    $number_of_days_next_month = 0;
                    $first_date_of_next_month = date('Y-m-01', strtotime($to_date));
                    $first_date_of_next_month_created = date_create($first_date_of_next_month);
                    $to_date_created = date_create($to_date);
                    $diff = date_diff($first_date_of_next_month_created, $to_date_created);
                    $number_of_days_next_month = $diff->format("%R%a") + 1;

                    if ($from_year == $current_year && $to_year == $current_year) {
                        if ($LeaveRequest['type_of_leave'] == 'EL') {
                            $to_return = $LeaveRequest['number_of_days'];
                        } else {
                            #Problem in Returning CL Balance
                            /*$response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'We are at right place';
                            return $this->response->setJSON($response_array);
                            die();
                            $to_return = $number_of_days_current_month;*/
                            $to_return = $LeaveRequest['number_of_days'];
                        }
                    } else {
                        $to_return = $number_of_days_current_month;
                    }
                }
                $new_balance = $CurrentMonthLeaveBalance + $to_return;
                #New code 2023-05-09
                if ($LeaveRequest['type_of_leave'] == 'CL') {
                    $new_balance = $new_balance >= 3 ? 3 : $new_balance;
                } elseif ($LeaveRequest['type_of_leave'] == 'EL') {
                    $new_balance = $new_balance >= 30 ? 30 : $new_balance;
                }
                #New code 2023-05-09
                ###########new Code###########
                if (!empty($CurrentMonthLeaveBalanceRowID)) {
                    $data = [
                        'status'                => 'cancelled',
                        'reviewed_by'           => $current_user_employee_id,
                        'reviewed_date'         => $current_date,
                        'remarks'               => $this->request->getPost('remarks'),
                    ];
                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $query = $LeaveRequestsModel->update($leave_id, $data);

                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                    } else {
                        if (!empty($CurrentMonthLeaveBalanceRowID)) {
                            $leave_balance_data = ['balance' => $new_balance];
                            $LeaveBalanceModel = new LeaveBalanceModel();
                            $updateLeaveBalanceQuery = $LeaveBalanceModel->update($CurrentMonthLeaveBalanceRowID, $leave_balance_data);
                            if ($updateLeaveBalanceQuery) {
                                $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                                $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                                    'employee_id'   => $current_employee_id,
                                    'leave_id'      => $CurrentMonthLeaveBalanceRow_leave_id,
                                    'leave_amount'  => abs($to_return),
                                    'type'          => 'credit',
                                    'remarks'       => 'Leave returned from request id ' . $LeaveRequest['id'],
                                ]);
                            }
                        }
                        $LeaveRequestsModel = new LeaveRequestsModel();
                        $LeaveRequest = $LeaveRequestsModel
                            ->select('leave_requests.*')
                            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                            ->select('e.internal_employee_id as internal_employee_id')
                            ->select('e.work_email as employee_email')
                            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as cancelled_by_name")
                            ->select('d.department_name as department_name')
                            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                            ->join('employees as e2', 'e2.id = leave_requests.reviewed_by', 'left')
                            ->join('departments as d', 'd.id = e.department_id', 'left')
                            ->where('leave_requests.id =', $leave_id)
                            ->first();

                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');
                        if (!empty($LeaveRequest['employee_email'])) {
                            $to_emails[] = $LeaveRequest['employee_email'];
                        }
                        $email->setTo($to_emails);
                        $email->setSubject('Leave Request Cancelled');
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
                                                        <strong>You leave request has been cancelled by ' . $LeaveRequest["cancelled_by_name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Remarks:</span> ' . $LeaveRequest["remarks"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Status:</span> ' . ucfirst($LeaveRequest["status"]) . '</div>
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
                            </div>');

                        if ($email->send()) {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Cancelled';
                        } else {
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave Cancelled, but email not send';
                        }
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = '<p class="text-danger">Cannot Cancel</p>Leave Balance of current month is not updated yet, Pleae contact developer11';
                    return $this->response->setJSON($response_array);
                    die();
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
