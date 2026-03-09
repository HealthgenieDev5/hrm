<?php

namespace App\Controllers\Override;

use App\Models\LeaveModel;
use App\Models\EmployeeModel;
use App\Models\LeaveBalanceModel;
use App\Controllers\BaseController;
use App\Models\LeaveCreditHistoryModel;
use App\Models\LeaveBalanceOverrideHistoryModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class LeaveBalance extends BaseController
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

        if (!in_array($current_user['employee_id'], ['40', '52'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title'            => 'Override Leave Balance',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'    => $this->getAllEmployees(),
            'leave_types'    => $this->getAllLeaveTypes(),
        ];
        return view('LeaveBalanceOverride/LeaveBalanceOverride', $data);
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
            ->select('(select balance from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "CL") as cl_balance')
            ->select('(select id from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "CL" order by id desc) as cl_balance_id')
            ->select('(select balance from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "EL") as el_balance')
            ->select('(select id from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "EL" order by id desc) as el_balance_id')
            ->select('(select balance from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "RH") as rh_balance')
            ->select('(select id from leave_balance where employee_id = employees.id and year=YEAR(CURDATE()) and month=MONTH(CURDATE()) and leave_code = "RH" order by id desc) as rh_balance_id')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            /*->where('employees.id !=', $this->session->get('current_user')['employee_id'])*/
            ->where('employees.status =', 'active')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();

        if (!empty($AllEmployees)) {
            return $AllEmployees;
        } else {
            return null;
        }
    }

    public function getAllLeaveTypes()
    {
        $LeaveModel = new LeaveModel();
        $AllLeaveTypes =  $LeaveModel->findAll();

        if (!empty($AllLeaveTypes)) {
            return $AllLeaveTypes;
        } else {
            return null;
        }
    }

    public function overrideLeaveBalance()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'employee_id'  =>  [
                    'rules'         =>  'required|is_not_unique[employees.id]',
                    'errors'        =>  [
                        'required'  => 'Please select an employee',
                        'is_not_unique' => 'This Employee does not exist in our database'
                    ]
                ],
                'leave_type'  =>  [
                    'rules'         =>  'required|is_not_unique[leaves.leave_code]',
                    'errors'        =>  [
                        'required'  => 'Please select a leave code',
                        'is_not_unique' => 'This leave code does not exist in our database'
                    ]
                ],
                'new_balance'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter new balance',
                    ]
                ],
                'custom_remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter your remarks',
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
            $employee_id = $this->request->getPost('employee_id');
            $leave_code = $this->request->getPost('leave_type');
            $balance = $this->request->getPost('new_balance');
            $custom_remarks = $this->request->getPost('custom_remarks');

            $data = [
                'employee_id' => $employee_id,
                'leave_code' => $leave_code,
                'balance' => $balance,
            ];
            $AllLeaveTypes = $this->getAllLeaveTypes();
            foreach ($AllLeaveTypes as $LeaveTypeRow) {
                if ($LeaveTypeRow['leave_code'] == $leave_code) {
                    $data['leave_id'] = $LeaveTypeRow['id'];
                }
            }

            $LeaveBalanceModel = new LeaveBalanceModel();
            $LeaveBalanceModel
                ->where('leave_code=', $leave_code)
                ->where('employee_id=', $employee_id)
                ->where('year=', date('Y'))
                ->where('month=', date('m'));
            $ExistingLeaveBalance = $LeaveBalanceModel->first();

            if (!empty($ExistingLeaveBalance)) {
                $LeaveBalanceModel = new LeaveBalanceModel();
                $updateExistingLeaveBalance = $LeaveBalanceModel->update($ExistingLeaveBalance['id'], $data);
                if ($updateExistingLeaveBalance) {

                    #insert override history
                    $LeaveBalanceOverrideHistoryModel = new LeaveBalanceOverrideHistoryModel();
                    $LeaveBalanceOverrideHistoryModel->insert(
                        array(
                            'employee_id' => $employee_id,
                            'leave_code' => $leave_code,
                            'previous_balance' => $ExistingLeaveBalance['balance'],
                            'new_balance' => $balance,
                            'overriden_by' => $this->session->get("current_user")["employee_id"],
                            'remarks' => $custom_remarks
                        )
                    );

                    #debit all balance
                    $debitData = [
                        'employee_id'   => $data['employee_id'],
                        'leave_id'      => $data['leave_id'],
                        'leave_amount'  => $ExistingLeaveBalance['balance'],
                        'type'          => 'debit',
                        'remarks'       => $data['leave_code'] . " Deducted on " . date('Y-m-d h:i A') . " via manual override by " . $this->session->get('current_user')['name'] . " emp_code(" . $this->session->get('current_user')['internal_employee_id'] . ")",
                    ];
                    $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                    $debitLeaveFromCreditHistoryQuery = $LeaveCreditHistoryModel->insert($debitData);
                    if ($debitLeaveFromCreditHistoryQuery) {
                        #credit new balance
                        $creditData = [
                            'employee_id'   => $data['employee_id'],
                            'leave_id'      => $data['leave_id'],
                            'leave_amount'  => $data['balance'],
                            'type'          => 'credit',
                            'remarks'       => $data['leave_code'] . " Added on " . date('Y-m-d h:i A') . " via manual override by " . $this->session->get('current_user')['name'] . " emp_code(" . $this->session->get('current_user')['internal_employee_id'] . ")",
                            'custom_remarks' => $custom_remarks,
                        ];
                        $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                        $creditLeaveFromCreditHistoryQuery = $LeaveCreditHistoryModel->insert($creditData);
                        if ($creditLeaveFromCreditHistoryQuery) {
                            $lastCreditHistoryInsertID = $LeaveCreditHistoryModel->getInsertID();
                            $response_array['response_type'] = 'success';
                            $response_array['response_description'] = 'Leave balance updated';

                            #####begin::send notification to Nazrul#####
                            $notification_email = \Config\Services::email();
                            $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                            $to_emails = array('developer3@healthgenie.in');
                            $notification_email->setTo($to_emails);
                            $notification_email->setSubject('Leave balance updated using Overrides by ' . $this->session->get("current_user")["name"]);
                            $notification_email->setMessage(
                                '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
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
                                                            <strong>Leave balance updated using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                                        </div>
                                                        <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                        <div style="padding-bottom: 10px">' . $debitData["leave_amount"] . $debitData["remarks"] . '</div>
                                                        <div style="padding-bottom: 10px">' . $creditData["leave_amount"] . $creditData["remarks"] . '</div>
                                                        <div style="padding-bottom: 10px">details can be found in credit history at id ' . $lastCreditHistoryInsertID . '</div>
                                                        
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
                                </div>'
                            );
                            $notification_email->send();
                            #####end::send notification to Nazrul#####

                        } else {
                            $response_array['response_type'] = 'error';
                            $response_array['response_description'] = 'DB Error: Unable to insert credit row in credit history, however the leave balance is updated, and deduction row added in leave credit history. Please contact the developer immediately';
                        }
                    } else {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB Error: Unable to insert debit row in credit history, however the leave balance is updated, Please contact the developer immediately';
                    }
                } else {

                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error: Unable to update leave balance';
                }
            } else {

                $data['year'] = date('Y');
                $data['month'] = date('m');

                $insertNewLeaveBalance = $LeaveBalanceModel->insert($data);
                if ($insertNewLeaveBalance) {

                    #insert override history
                    $LeaveBalanceOverrideHistoryModel = new LeaveBalanceOverrideHistoryModel();
                    $LeaveBalanceOverrideHistoryModel->insert(
                        array(
                            'employee_id' => $employee_id,
                            'leave_code' => $leave_code,
                            'previous_balance' => 0,
                            'new_balance' => $balance,
                            'overriden_by' => $this->session->get("current_user")["employee_id"],
                            'remarks' => $custom_remarks
                        )
                    );

                    #credit new balance
                    $creditData = [
                        'employee_id'   => $data['employee_id'],
                        'leave_id'      => $data['leave_id'],
                        'leave_amount'  => $data['balance'],
                        'type'          => 'credit',
                        'remarks'       => $data['leave_code'] . " Added on " . date('Y-m-d h:i A') . " via manual override by " . $this->session->get('current_user')['name'] . " emp_code(" . $this->session->get('current_user')['internal_employee_id'] . ")",
                    ];
                    $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                    $creditLeaveFromCreditHistoryQuery = $LeaveCreditHistoryModel->insert($creditData);
                    if ($creditLeaveFromCreditHistoryQuery) {
                        $lastCreditHistoryInsertID = $LeaveCreditHistoryModel->getInsertID();
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Leave balance updated';

                        #####begin::send notification to Nazrul#####
                        $notification_email = \Config\Services::email();
                        $notification_email->setFrom('app.hrm@healthgenie.in', 'HRM');
                        $to_emails = array('developer3@healthgenie.in');
                        $notification_email->setTo($to_emails);
                        $notification_email->setSubject('Leave balance updated using Overrides by ' . $this->session->get("current_user")["name"]);
                        $notification_email->setMessage(
                            '<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
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
                                                        <strong>Leave balance updated using Overrides by ' . $this->session->get("current_user")["name"] . '</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px">' . $creditData["remarks"] . '</div>
                                                    <div style="padding-bottom: 10px">details can be found in credit history at id ' . $lastCreditHistoryInsertID . '</div>
                                                    
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
                            </div>'
                        );
                        $notification_email->send();
                        #####end::send notification to Nazrul#####
                    } else {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'DB Error: Unable to insert credit row in credit history, however the leave balance row is created, Please contact the developer immediately';
                    }
                } else {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB Error: Unable to create leave balance row';
                }
            }
        }
        return $this->response->setJSON($response_array);
    }

    public function getAllBalance()
    {

        $EmployeeModel = new EmployeeModel();
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $AllEmployees =
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
            // ->where('employees.designation_id !=', '75')
            ->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')")
            ->groupEnd()

            ->orderBy('employees.first_name', 'ASC')
            ->findAll();
        foreach ($AllEmployees as $index => $employeerow) {
            $show_used_rh = true;
            $leave_balance = ProcessorHelper::getLeaveBalance($employeerow['id'], $show_used_rh);
            $AllEmployees[$index]['leave_balance'] = $leave_balance;
        }

        $data = [
            'page_title'            => 'All Leave Balance',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $AllEmployees,
        ];

        return view('LeaveBalanceOverride/LeaveBalanceAll', $data);
    }

    public function getLeaveBalanceOverrideHistory()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $employee_id = isset($params['employee_id']) ? $params['employee_id'] : "";



        $LeaveBalanceOverrideHistoryModel = new LeaveBalanceOverrideHistoryModel();
        $LeaveBalanceOverrideHistoryModel
            ->select('leave_balance_overrides.*')
            ->select("trim(concat(e1.first_name, ' ', e1.last_name)) as overriden_by_name")
            ->join('employees as e1', 'e1.id=leave_balance_overrides.overriden_by', 'left')
            ->where('leave_balance_overrides.employee_id =', $employee_id)
            ->orderBy('leave_balance_overrides.id', 'DESC');
        $allHistory = $LeaveBalanceOverrideHistoryModel->findAll();

        if (!empty($allHistory)) {
            foreach ($allHistory as $i => $d) {
                $allHistory[$i]['date_time'] = date('d M, Y h:i A', strtotime($d['date_time']));
                #$allHistory[$i]['attachment'] = !empty($d['attachment']) ? base_url('public').$d['attachment'] : '';
            }
        }
        echo json_encode($allHistory);
    }
}
