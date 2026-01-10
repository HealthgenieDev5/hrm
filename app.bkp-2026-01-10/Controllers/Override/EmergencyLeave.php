<?php

namespace App\Controllers\Override;

use App\Models\EmployeeModel;
use App\Models\LeaveBalanceModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;
use App\Models\LeaveCreditHistoryModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class EmergencyLeave extends BaseController
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
        $logged_in_user = $this->session->get('current_user');
        $logged_in_user_employee_id = $this->session->get('current_user')['employee_id'];

        /*if( $logged_in_user_employee_id !== '40' ){*/
        if (!in_array($this->session->get('current_user')['employee_id'], ['40', '52'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title' => 'Emergency Leave Request',
            'employees'  => $this->getAllEmployees(),
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'current_method'        => $this->request->getUri()->getSegment(1),
        ];

        return view('DeveloperAccess/EmergencyLeave', $data);
    }

    public function getAllEmployees()
    {
        $EmployeeModel = new EmployeeModel();
        $AllEmployees =
            $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function getLeaveBalanceCurrentMonth()
    {
        $filter = $this->request->getPost('filter');
        /*print_r($filter);
        die();*/
        parse_str($filter, $params);
        $employee_id    = isset($params['employee_id']) ? $params['employee_id'] : "";

        if (!empty($employee_id)) {
            $CurrentMonthLeaveBalance = ProcessorHelper::getLeaveBalance($employee_id);
        } else {
            $CurrentMonthLeaveBalance = array();
        }

        echo json_encode($CurrentMonthLeaveBalance);
    }

    public function createLeaveRequest()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'current_employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Employee id is required',
                        'is_not_unique' => 'This Emloyee ID does not exist in our database Please contact administrator'
                    ]
                ],
                'from_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'From Date is required',
                    ]
                ],
                'to_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'To Date is required',
                    ]
                ],
                'number_of_days'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Number of days is required',
                    ]
                ],
                'type_of_leave'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select type of leave',
                    ]
                ],
                'day_type'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select half day or full day',
                    ]
                ],
                'address_d_l'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Address During Leave is required',
                    ]
                ],
                'emergency_contact_d_l'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Emergency Contact During Leave is required',
                    ]
                ],
                'reason_of_leave'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Reason of Leave is required',
                    ]
                ],
                'attachment'  =>  [
                    'rules'         =>  'ext_in[attachment,jpg,jpeg,png,pdf]|max_size[attachment,5120]',
                    'errors'        =>  [
                        'ext_in'    => 'Allowed only jpg, jpeg, png, and pdf.<br>(Try uploading another file)',
                        'max_size'  => 'Max 5 mb is accepted',
                    ]
                ],
            ]
        );

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
            return $this->response->setJSON($response_array);
            die();
        }

        $current_employee_id = $this->request->getPost('current_employee_id');

        #check if half day EL
        if ($this->request->getPost('day_type') == '0.5' && $this->request->getPost('type_of_leave') == 'EL') {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = "Half Day EL is not allowed";
            return $this->response->setJSON($response_array);
            die();
        }

        if ($this->request->getPost('day_type') == '0.5' && $this->request->getPost('from_date') !== $this->request->getPost('to_date')) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = "Select same date if requesting for half day";
            return $this->response->setJSON($response_array);
            die();
        }

        #check existing requests 
        $LeaveRequestsModel = new LeaveRequestsModel();
        $existingLeaveRequestsOnSelectedDates = $LeaveRequestsModel
            ->select('count(leave_requests.id) as existing_leave_count')
            ->where('leave_requests.employee_id =', $current_employee_id)
            ->whereIn('leave_requests.status', ['approved', 'pending'])
            ->where(
                '( 
                               (leave_requests.from_date between "' . $this->request->getPost('from_date') . '" and "' . $this->request->getPost('to_date') . '") 
                            or (leave_requests.to_date between "' . $this->request->getPost('from_date') . '" and "' . $this->request->getPost('to_date') . '") 
                            or ("' . $this->request->getPost('from_date') . '" between leave_requests.from_date and leave_requests.to_date ) 
                            or ("' . $this->request->getPost('to_date') . '" between leave_requests.from_date and leave_requests.to_date )
                            )'
            )
            ->first()['existing_leave_count'];

        if ($existingLeaveRequestsOnSelectedDates > 0) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = "Your leave request is already present for selected dates";
            return $this->response->setJSON($response_array);
            die();
        }
        #check if the balance is not higher than number of days         

        #what if the leave request is for next month
        $CurrentMonthLeaveBalance = 0;
        $CurrentMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalance($current_employee_id);

        foreach ($CurrentMonthLeaveBalanceDataArray as $i => $CurrentMonthLeaveBalanceData) {

            if ($CurrentMonthLeaveBalanceData['leave_code'] == $this->request->getPost('type_of_leave')) {
                $CurrentMonthLeaveBalance = $CurrentMonthLeaveBalanceData['balance'];
                $CurrentMonthLeaveBalanceRowID = $CurrentMonthLeaveBalanceData['id'] ?? null;
                $CurrentMonthLeaveBalanceRow_leave_id = $CurrentMonthLeaveBalanceData['leave_id'] ?? null;
            }
        }

        $NextMonthLeaveBalance = 0;
        $NextMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalanceNextMonth($current_employee_id);

        foreach ($NextMonthLeaveBalanceDataArray as $i => $NextMonthLeaveBalanceData) {

            if ($NextMonthLeaveBalanceData['leave_code'] == $this->request->getPost('type_of_leave')) {
                $NextMonthLeaveBalance = $NextMonthLeaveBalanceData['eligible_balance'];
            }
        }

        #only for comp off, Checking only from current date not from from_date. but not sure is it necessary or not, because the same code is on line 482
        if ($this->request->getPost('type_of_leave') == 'COMP OFF' && $CurrentMonthLeaveBalance < $this->request->getPost('number_of_days')) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = "Number of days is higher than the balance of selected leave type";
            $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
            return $this->response->setJSON($response_array);
            die();
        }
        #end only for comp off but not sure is it necessary or not, because the same code is on line 482

        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $from_month = date('m', strtotime($from_date));
        $to_month = date('m', strtotime($to_date));
        $from_year = date('Y', strtotime($from_date));
        $to_year = date('Y', strtotime($to_date));
        $current_month = date('m');
        $current_year = date('Y');

        if ($from_month == $current_month && $to_month == $current_month) {

            $number_of_days_current_month = $this->request->getPost('number_of_days');
            $number_of_days_next_month = 0;
            if ($this->request->getPost('type_of_leave') != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = "Number of days is higher than the balance of selected leave type";
                $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
                return $this->response->setJSON($response_array);
                die();
            }
            $number_of_days_to_deduct = $number_of_days_current_month;
        } elseif ($from_month !== $current_month && $to_month !== $current_month) {

            #check if both dates are from next year
            if ($from_year == $current_year && $to_year == $current_year) {

                if ($this->request->getPost('type_of_leave') == 'EL') {
                    $number_of_days_current_month = 0;
                    $number_of_days_next_month = $this->request->getPost('number_of_days');
                    if ($this->request->getPost('type_of_leave') != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Next Month";
                        $response_array['response_data'] = $NextMonthLeaveBalanceDataArray;
                        return $this->response->setJSON($response_array);
                        die();
                    }
                    $number_of_days_to_deduct = $number_of_days_next_month;
                } else {
                    $number_of_days_current_month = 0;
                    $number_of_days_next_month = $this->request->getPost('number_of_days');
                    if ($this->request->getPost('type_of_leave') != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Next Month";
                        $response_array['response_data'] = $NextMonthLeaveBalanceDataArray;
                        return $this->response->setJSON($response_array);
                        die();
                    }
                    $number_of_days_to_deduct = 0;
                }
            } else {

                $number_of_days_current_month = 0;
                $number_of_days_next_month = $this->request->getPost('number_of_days');
                if ($this->request->getPost('type_of_leave') != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Next Month";
                    $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
                    return $this->response->setJSON($response_array);
                    die();
                }
                $number_of_days_to_deduct = 0;
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
                if ($this->request->getPost('type_of_leave') == 'EL') {
                    if ($CurrentMonthLeaveBalance < ($this->request->getPost('number_of_days'))) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Current and Next Month";
                        $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
                        return $this->response->setJSON($response_array);
                        die();
                    }
                    $number_of_days_to_deduct = $this->request->getPost('number_of_days');
                } else {
                    if ($this->request->getPost('type_of_leave') != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Current Month";
                        $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
                        return $this->response->setJSON($response_array);
                        die();
                    }
                    if ($this->request->getPost('type_of_leave') != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Next Month";
                        $response_array['response_data'] = $NextMonthLeaveBalanceDataArray;
                        return $this->response->setJSON($response_array);
                        die();
                    }
                    $number_of_days_to_deduct = $number_of_days_current_month;
                }
            } else {
                if ($this->request->getPost('type_of_leave') != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Current Month";
                    $response_array['response_data'] = $CurrentMonthLeaveBalanceDataArray;
                    return $this->response->setJSON($response_array);
                    die();
                }
                if ($this->request->getPost('type_of_leave') != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = "Number of days is higher than the balance of selected leave type in Next Month";
                    $response_array['response_data'] = $NextMonthLeaveBalanceDataArray;
                    return $this->response->setJSON($response_array);
                    die();
                }
                $number_of_days_to_deduct = $number_of_days_current_month;
            }
        }



        #update current month balance
        $CurrentMonthLeaveBalanceUpdated = $CurrentMonthLeaveBalance - $number_of_days_to_deduct;

        if (!empty($CurrentMonthLeaveBalanceRowID)) {
            $current_month_leave_balance_data = ['balance' => $CurrentMonthLeaveBalanceUpdated];
            $LeaveBalanceModel = new LeaveBalanceModel();

            $updateCurrentMonthLeaveBalanceQuery = $LeaveBalanceModel->update($CurrentMonthLeaveBalanceRowID, $current_month_leave_balance_data);

            if ($updateCurrentMonthLeaveBalanceQuery) {

                $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();

                $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                    'employee_id'   => $current_employee_id,
                    'leave_id'      => $CurrentMonthLeaveBalanceRow_leave_id,
                    'leave_amount'  => $number_of_days_to_deduct,
                    'type'          => 'debit',
                    'remarks'       => 'Leave deducted but request not created yet',
                ]);
                if ($leaveCreditHistoryQuery) {
                    $CreditHistoryRecordID = $LeaveCreditHistoryModel->getInsertID();
                    $data = [
                        'employee_id'           => $current_employee_id,
                        'from_date'             => $this->request->getPost('from_date'),
                        'to_date'               => $this->request->getPost('to_date'),
                        'type_of_leave'         => $this->request->getPost('type_of_leave'),
                        'day_type'              => ($this->request->getPost('day_type') == '0.5') ? 'HALF' : 'FULL',
                        'backend_request'       => 'yes',
                        'number_of_days'        => $this->request->getPost('number_of_days'),
                        'address_d_l'           => $this->request->getPost('address_d_l'),
                        'emergency_contact_d_l' => $this->request->getPost('emergency_contact_d_l'),
                        'reason_of_leave'       => $this->request->getPost('reason_of_leave') . '<br>This request is created from backend manually using developer access',
                    ];
                    $attachment = $this->request->getFile('attachment');
                    if ($attachment->isValid() && ! $attachment->hasMoved()) {
                        $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                        $uploaded = $attachment->move($upload_folder);
                        if ($uploaded) {
                            $data['attachment'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                        }
                    }

                    $LeaveRequestsModel = new LeaveRequestsModel();
                    $query = $LeaveRequestsModel->insert($data);
                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB:Error <br> Please contact administrator. Error while creatring new request';
                    } else {
                        $lastInsertID = $LeaveRequestsModel->getInsertID();

                        $LeaveCreditHistoryModel->update($CreditHistoryRecordID, ['remarks' => 'Leave apllied request id' . $lastInsertID]);

                        $LeaveRequestsModel = new LeaveRequestsModel();
                        $LeaveRequest = $LeaveRequestsModel
                            ->select('leave_requests.*')
                            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                            ->select('e.internal_employee_id as internal_employee_id')
                            ->select('d.department_name as department_name')
                            ->select('e2.work_email as reporting_manager_email')
                            ->select('e3.work_email as hod_email')
                            ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                            ->join('departments as d', 'd.id = e.department_id', 'left')
                            ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
                            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                            ->where('leave_requests.id =', $lastInsertID)
                            ->first();

                        $email = \Config\Services::email();
                        $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');

                        if ($current_employee_id != '40') {
                            if (!empty($LeaveRequest['reporting_manager_email'])) {
                                $to_emails[] = $LeaveRequest['reporting_manager_email'];
                            }
                            if (!empty($LeaveRequest['hod_email'])) {
                                $to_emails[] = $LeaveRequest['hod_email'];
                            }
                        }

                        // $email->setTo($to_emails);
                        // $email->setSubject('New Leave Request');


                        // $email->setMessage(
                        //     '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                        //         <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                        //             <tbody>
                        //                 <tr>
                        //                     <td align="center" valign="center" style="text-align:center; padding: 40px">
                        //                         <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                        //                             <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                        //                         </a>
                        //                     </td>
                        //                 </tr>
                        //                 <tr>
                        //                     <td align="left" valign="center">
                        //                         <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                        //                             <!--begin:Email content-->
                        //                             <div style="padding-bottom: 30px; font-size: 17px;">
                        //                                 <strong>You have recieved a leave request</strong>
                        //                             </div>
                        //                             <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                        //                             <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                        //                             <div style="padding-bottom: 10px">This request is created from backend manually using developer access</div>

                        //                             <div style="padding-bottom: 10px; text-align:center;">
                        //                                 <a href="' . base_url("/backend/administrative/leaveapproval?action=approve&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009EF7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Approve</a>

                        //                                 <a href="' . base_url("/backend/administrative/leaveapproval?action=reject&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#dc3545;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Reject</a>
                        //                             </div>

                        //                             <!--end:Email content-->
                        //                             <div style="padding-bottom: 10px">Kind regards,
                        //                             <br>HRM Team.
                        //                             <tr>
                        //                                 <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                        //                                     <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                        //                                     <p>Copyright ©
                        //                                     <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                        //                                 </td>
                        //                             </tr></br></div>
                        //                         </div>
                        //                     </td>
                        //                 </tr>
                        //             </tbody>
                        //         </table>
                        //     </div>'
                        // );

                        // if ($email->send()) {
                        //     #####begin::send notification to Nazrul#####
                        //     $notification_email = \Config\Services::email();
                        //     $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        //     $to_emails = array('developer3@healthgenie.in');
                        //     $notification_email->setTo($to_emails);
                        //     $notification_email->setSubject('Leave applied using Developer Access by ' . $this->session->get("current_user")["employee_id"]);
                        //     $notification_email->setMessage(
                        //         '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
                        //             <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                        //                 <tbody>
                        //                     <tr>
                        //                         <td align="center" valign="center" style="text-align:center; padding: 40px">
                        //                             <a href="' . base_url('public') . '" rel="noopener" target="_blank">
                        //                                 <img alt="Logo" src="' . base_url('public') . '/assets/media/logos/logo-healthgenie.png" />
                        //                             </a>
                        //                         </td>
                        //                     </tr>
                        //                     <tr>
                        //                         <td align="left" valign="center">
                        //                             <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                        //                                 <!--begin:Email content-->
                        //                                 <div style="padding-bottom: 30px; font-size: 17px;">
                        //                                     <strong>A New leave request is applied using developer access by ' . $this->session->get("current_user")["employee_id"] . '</strong>
                        //                                 </div>
                        //                                 <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                        //                                 <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                        //                                 <div style="padding-bottom: 10px">This request is created from backend manually using developer access</div>

                        //                                 <div style="padding-bottom: 10px; text-align:center;">
                        //                                     <a href="' . base_url("/backend/administrative/leaveapproval?action=approve&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009EF7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Approve</a>

                        //                                     <a href="' . base_url("/backend/administrative/leaveapproval?action=reject&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#dc3545;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Reject</a>
                        //                                 </div>

                        //                                 <!--end:Email content-->
                        //                                 <div style="padding-bottom: 10px">Kind regards,
                        //                                 <br>HRM Team.
                        //                                 <tr>
                        //                                     <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                        //                                         <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                        //                                         <p>Copyright ©
                        //                                         <a href="' . base_url('public') . '" rel="noopener" target="_blank">Healthgenie/Gstc</a>.</p>
                        //                                     </td>
                        //                                 </tr></br></div>
                        //                             </div>
                        //                         </td>
                        //                     </tr>
                        //                 </tbody>
                        //             </table>
                        //         </div>'
                        //     );
                        //     $notification_email->send();
                        //     #####end::send notification to Nazrul#####
                        //     $response_array['response_type'] = 'success';
                        //     $response_array['response_description'] = 'Request Submitted Successfully Request ID <span class="text-danger">#' . $lastInsertID . '</span>';
                        // } else {
                        //     $response_array['response_type'] = 'success';
                        //     $response_array['response_description'] = 'Request Submitted Successfully, but Email is not sent to the reporting manager Request ID <span class="text-danger">#' . $lastInsertID . '</span>';
                        // }

                        #update credit history record with request id  
                        $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                        $UpdateCreditHistoryRecordData = ['remarks'       => 'Leave applied request id ' . $lastInsertID];
                        $UpdateCreditHistoryRecordQuery = $LeaveCreditHistoryModel->update($CreditHistoryRecordID, $UpdateCreditHistoryRecordData);
                        #update credit history record with request id  
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Request Submitted Successfully Request ID <span class="text-danger">#' . $lastInsertID . '</span>';
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator. error while creating record in credit history';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator. error while updating current month balance';
            }
        } elseif ($this->request->getPost('type_of_leave') == 'COMP OFF' || $this->request->getPost('type_of_leave') == 'UL') {
            $data = [
                'employee_id'           => $current_employee_id,
                'from_date'             => $this->request->getPost('from_date'),
                'to_date'               => $this->request->getPost('to_date'),
                'type_of_leave'         => $this->request->getPost('type_of_leave'),
                'day_type'              => ($this->request->getPost('day_type') == '0.5') ? 'HALF' : 'FULL',
                'number_of_days'        => $this->request->getPost('number_of_days'),
                'address_d_l'           => $this->request->getPost('address_d_l'),
                'emergency_contact_d_l' => $this->request->getPost('emergency_contact_d_l'),
                'reason_of_leave'       => $this->request->getPost('reason_of_leave') . '<br>This request is created from backend manually using developer access',
            ];
            $attachment = $this->request->getFile('attachment');
            if ($attachment->isValid() && ! $attachment->hasMoved()) {
                $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                $uploaded = $attachment->move($upload_folder);
                if ($uploaded) {
                    $data['attachment'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                }
            }

            $LeaveRequestsModel = new LeaveRequestsModel();
            $query = $LeaveRequestsModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator. Error while creatring new request';
            } else {
                $lastInsertID = $LeaveRequestsModel->getInsertID();
                $LeaveRequestsModel = new LeaveRequestsModel();
                $LeaveRequest = $LeaveRequestsModel
                    ->select('leave_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->join('employees as e', 'e.id = leave_requests.employee_id', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->where('leave_requests.id =', $lastInsertID)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');

                if ($current_employee_id != '40') {
                    if (!empty($LeaveRequest['reporting_manager_email'])) {
                        $to_emails[] = $LeaveRequest['reporting_manager_email'];
                    }
                    if (!empty($LeaveRequest['hod_email'])) {
                        $to_emails[] = $LeaveRequest['hod_email'];
                    }
                }

                $email->setTo($to_emails);
                $email->setSubject('New Leave Request');


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
                                                <strong>You have recieved a leave request</strong>
                                            </div>
                                            <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $LeaveRequest["employee_name"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $LeaveRequest["department_name"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">From Date:</span> ' . $LeaveRequest["from_date"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">To Date:</span> ' . $LeaveRequest["to_date"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">Number of days:</span> ' . $LeaveRequest["number_of_days"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">Leave Code:</span> ' . $LeaveRequest["type_of_leave"] . '</div>
                                            <div style="padding-bottom: 10px"><span style="color:#aeaeae">Emergency Contact:</span> ' . $LeaveRequest["emergency_contact_d_l"] . '</div>
                                            <div style="padding-bottom: 10px">This request is created from backend manually using developer access</div>

                                            <div style="padding-bottom: 10px; text-align:center;">
                                                <a href="' . base_url("/backend/administrative/leaveapproval?action=approve&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009EF7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Approve</a>

                                                <a href="' . base_url("/backend/administrative/leaveapproval?action=reject&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#dc3545;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Reject</a>
                                            </div>
                                            
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
                    $response_array['response_description'] = 'Request Submitted Successfully Request ID <span class="text-danger">#' . $lastInsertID . '</span>';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Request Submitted Successfully, but Email is not sent to the reporting manager Request ID <span class="text-danger">#' . $lastInsertID . '</span>';
                }
            }
        }
        #update next month balance#        

        return $this->response->setJSON($response_array);
        die();
    }
}
