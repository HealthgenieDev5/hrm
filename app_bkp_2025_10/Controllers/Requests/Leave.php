<?php

namespace App\Controllers\Requests;

use App\Models\UserModel;
use App\Models\CustomModel;
use App\Models\LeaveBalanceModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\LeaveCreditHistoryModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;

class Leave extends BaseController
{
    public $session;
    public $uri;
    public $type_of_leave_requested;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function index()
    {
        $current_user = $this->session->get('current_user');
        $UserModel = new UserModel();
        $current_employee_id = $this->session->get('current_user')['employee_id'];

        $LeaveBalance = ProcessorHelper::getLeaveBalance($current_user["employee_id"]);
        $data = [
            'page_title'            => 'My Leaves',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'leave_balance'         => $LeaveBalance,
        ];
        return view('User/Leaves', $data);
    }


    private function setPostData($key, $value)
    {
        $_POST[$key] = $value;
    }

    public function createLeaveRequest()
    {

        $response_array = array();
        $current_employee_id = $this->session->get('current_user')['employee_id'];

        #Begin::Normal Validation
        if (!$this->normalValidation()) {
            return $this->response->setJSON($this->createResponseData("error", "Sorry, looks like there are some errors,<br>Click ok to view errors", ["validation" => $this->validator->getErrors()]));
            die();
        }
        $postData = $_REQUEST;
        $postData['current_employee_id'] = $current_employee_id;
        $postData['prior_to_date_validation'] = true;
        #End::Normal Validation

        #Begin::Set type of leave when sick leave is selected
        #$postData['type_of_leave_requested'] = $this->request->getPost('type_of_leave') == 'SICK LEAVE' ? 'EL' : $this->request->getPost('type_of_leave');
        $postData['type_of_leave_requested'] = $this->request->getPost('type_of_leave') == 'SICK LEAVE' ? 'EL' : $this->request->getPost('type_of_leave');
        #End::Set type of leave when sick leave is selected

        #Begin:: Require medical proof in case of sick leave
        $attachment = $this->request->getFile('attachment');
        if (!$attachment->isValid() && $postData['type_of_leave'] == 'SICK LEAVE') {
            return $this->response->setJSON($this->createResponseData("error", "Please attach valid medical proof"));
            die();
        }

        if ($attachment->isValid() && $postData['type_of_leave'] == 'SICK LEAVE') {
            $attachment = $this->request->getFile('attachment');
            if ($attachment->isValid() && ! $attachment->hasMoved()) {
                $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                $uploaded = $attachment->move($upload_folder);
                if ($uploaded) {
                    $postData['attachment'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                }
            }
        }
        #End:: Require medical proof in case of sick leave

        #Begin::Check for any restrictions
        $resctrictionFound = $this->resctrictionFound($postData);
        if ($resctrictionFound) {
            return $this->response->setJSON($resctrictionFound);
            die();
        }
        #End::Check for any restrictions

        #Begin:: check if there is an RH between the Leave Dates
        // $RhDates = null;
        // $Balances = $this->getBalances($postData);
        // $CurrentMonthLeaveBalanceDataArray = $Balances['current_month']['data'];
        // foreach ($CurrentMonthLeaveBalanceDataArray as $k => $v) {
        //     if ($v['leave_code'] == 'RH') {
        //         $RhDates = $v['rh_dates'];
        //     }
        // }
        // $FoundRHDate = null;
        // if (!empty($RhDates)) {
        //     foreach ($RhDates as $RhDate) {
        //         if (
        //             empty($FoundRHDate)
        //             &&
        //             (
        //                 strtotime($RhDate) >= strtotime($postData['from_date'])
        //                 &&
        //                 strtotime($RhDate) <= strtotime($postData['to_date'])
        //             )
        //         ) {
        //             $FoundRHDate = $RhDate;
        //         }
        //     }
        // }
        #Begin::divide leave requests in two
        // if (!empty($FoundRHDate)) {

        //     #Begin::dry run to check whether the balances are enough to create both leave requests
        //     $tempPostData = $postData;
        //     $tempPostData['number_of_days'] = $postData['number_of_days'] - 1;
        //     $findDeductionDays__dry_run = $this->findDeductionDays($tempPostData);
        //     if (isset($findDeductionDays__dry_run) && !empty($findDeductionDays__dry_run) && $findDeductionDays__dry_run['type'] != 'success') {
        //         return $this->response->setJSON($findDeductionDays__dry_run['data']);
        //         die();
        //     }
        //     #Begin::dry run to check whether the balances are enough to create both leave requests


        //     $postData1 = $postData;
        //     $postData1['from_date'] = $postData['from_date'];
        //     $postData1['to_date'] = date('Y-m-d', strtotime($FoundRHDate . " -1 day"));
        //     $postData1['number_of_days'] = number_of_days_between($postData['from_date'], date('Y-m-d', strtotime($FoundRHDate . " -1 day")));
        //     $postData1['prior_to_date_validation'] = false;

        //     $postData2 = $postData;
        //     $postData2['from_date'] = date('Y-m-d', strtotime($FoundRHDate . " +1 day"));
        //     $postData2['to_date'] = $postData['to_date'];
        //     $postData2['number_of_days'] = number_of_days_between(date('Y-m-d', strtotime($FoundRHDate . " +1 day")), $postData['to_date']);
        //     $postData2['prior_to_date_validation'] = false;

        //     if (
        //         strtotime($postData1['from_date']) > strtotime($postData1['to_date'])
        //         ||
        //         strtotime($postData2['from_date']) > strtotime($postData2['to_date'])
        //     ) {
        //         $FoundRHDateFormatted = date('j F Y', strtotime($FoundRHDate));
        //         return $this->response->setJSON($this->createResponseData("error", $FoundRHDateFormatted . " is your RH, Exclude this from leave request and try again"));
        //         die();
        //     }



        //     $firstLeaveResponse = $this->createLeaveRequestProcessNow($postData1);
        //     $secondLeaveResponse = $this->createLeaveRequestProcessNow($postData2);

        //     if (
        //         (isset($firstLeaveResponse['response_type']) && $firstLeaveResponse['response_type'] == 'success')
        //         &&
        //         (isset($secondLeaveResponse['response_type']) && $secondLeaveResponse['response_type'] == 'success')
        //     ) {
        //         $final_response_message = 'There was a RH between your selected dates therefore, we have created 2 separated request excluding RH. Below are request IDs of both, <br>' . $firstLeaveResponse["response_data"] . ' and ' . $secondLeaveResponse["response_data"];
        //         return $this->response->setJSON($this->createResponseData("success", $final_response_message));
        //     } else {
        //         $final_response_message = 'There was a RH between your selected dates therefore, we have tried to created 2 separated request excluding RH. but there was an error, please cancel your leave request and try again';
        //         return $this->response->setJSON($this->createResponseData("error", $final_response_message));
        //     }
        //     die();
        // } else {
        $firstLeaveResponse = $this->createLeaveRequestProcessNow($postData);
        return $this->response->setJSON($firstLeaveResponse);
        die();
        // }
        #End::Run first Request
        #End:: check if there is an RH between the Leave Dates

    }

    public function createLeaveRequestProcessNow($postData)
    {
        $response_array = array();
        $current_employee_id = $postData['current_employee_id'];

        #Begin::Find Leave balance, Leave balance row id, and leave balance row id leave id
        $Balances = $this->getBalances($postData);
        $CurrentMonthLeaveBalanceDataArray = $Balances['current_month']['data'];
        $CurrentMonthLeaveBalance = $Balances['current_month']['LeaveBalance'];
        $CurrentMonthLeaveBalanceRowID = $Balances['current_month']['LeaveBalanceRowID'];
        $CurrentMonthLeaveBalanceRow_leave_id = $Balances['current_month']['LeaveBalanceRow_leave_id'];
        $NextMonthLeaveBalanceDataArray = $Balances['next_month']['data'];
        $NextMonthLeaveBalance = $Balances['next_month']['LeaveBalance'];
        $NextMonthLeaveBalanceRowID = $Balances['next_month']['LeaveBalanceRowID'];
        $NextMonthLeaveBalanceRow_leave_id = $Balances['next_month']['LeaveBalanceRow_leave_id'];
        #End::Find Leave balance, Leave balance row id, and leave balance row id leave id

        #Begin::Check for any restrictions
        $resctrictionFound = $this->resctrictionFound($postData);
        if ($resctrictionFound) {
            return $resctrictionFound;
            die();
        }
        #End::Check for any restrictions

        #Begin::only for comp off, Checking only from current date not from from_date. but not sure is it necessary or not
        if ($postData['type_of_leave_requested'] == 'COMP OFF' && $CurrentMonthLeaveBalance < $postData['number_of_days']) {
            return $this->createResponseData("error", "Number of days is higher than the balance of selected leave type", $CurrentMonthLeaveBalanceDataArray);
            die();
        }
        #end:: only for comp off but not sure is it necessary or not

        #Begin:: Find numbder of days to deduct from balance
        $findDeductionDays = $this->findDeductionDays($postData);
        if ($findDeductionDays['type'] !== 'success') {
            return $findDeductionDays['data'];
            die();
        }
        $number_of_days_to_deduct = $findDeductionDays['data'];
        #Begin:: Find numbder of days to deduct from balance



        #Begin:: Check Sick Leave Balance
        $verifySickLeaveBalance = $this->verifySickLeaveBalance($postData, $number_of_days_to_deduct);
        if ($verifySickLeaveBalance) {
            return $verifySickLeaveBalance;
            die();
        }
        #End:: Check Sick Leave Balance

        #Begin:: Update Balances and Create leave request
        $CurrentMonthLeaveBalanceUpdated = $CurrentMonthLeaveBalance - $number_of_days_to_deduct;
        $preparedMessage = '';
        if (!empty($CurrentMonthLeaveBalanceRowID)) {
            $current_month_leave_balance_data = ['balance' => $CurrentMonthLeaveBalanceUpdated];
            $LeaveBalanceModel = new LeaveBalanceModel();
            $updateCurrentMonthLeaveBalanceQuery = $LeaveBalanceModel->update($CurrentMonthLeaveBalanceRowID, $current_month_leave_balance_data);
            if ($updateCurrentMonthLeaveBalanceQuery) {
                $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                    'employee_id'   => $postData['current_employee_id'],
                    'leave_id'      => $CurrentMonthLeaveBalanceRow_leave_id,
                    'leave_amount'  => abs($number_of_days_to_deduct),
                    'type'          => 'debit',
                    'remarks'       => 'Leave applied request id ',
                ]);
                if ($leaveCreditHistoryQuery) {
                    $CreditHistoryRecordID = $LeaveCreditHistoryModel->getInsertID();
                    $data = [
                        'employee_id'           => $postData['current_employee_id'],
                        'from_date'             => $postData['from_date'],
                        'to_date'               => $postData['to_date'],
                        'type_of_leave'         => $postData['type_of_leave_requested'],
                        'day_type'              => ($postData['day_type'] == '0.5') ? 'HALF' : 'FULL',
                        'number_of_days'        => $postData['number_of_days'],
                        'address_d_l'           => $postData['address_d_l'],
                        'emergency_contact_d_l' => $postData['emergency_contact_d_l'],
                        'reason_of_leave'       => $postData['reason_of_leave'],
                        'sick_leave'            => $postData['type_of_leave'] == 'SICK LEAVE' ? 'yes' : 'no',
                        'attachment'            => (isset($postData['attachment']) && !empty($postData['attachment'])) ? $postData['attachment'] : '',
                    ];

                    if ($postData['type_of_leave_requested'] == 'CL') {
                        $data['status'] = 'approved';
                        $data['reviewed_by'] = $postData['current_employee_id'];
                        $data['reviewed_date'] = date('Y-m-d H:i:s');
                        $data['remarks'] = 'Auto Approved';
                    }

                    $LeaveRequestsModel = new LeaveRequestsModel();

                    $query = $LeaveRequestsModel->insert($data);
                    if (!$query) {
                        return $this->createResponseData("error", "DB:Error <br> Please contact administrator. Error while creatring new request 3", $LeaveRequestsModel->getLastQuery()->getQuery());
                        die();
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
                        if ($postData['current_employee_id'] != '40') {
                            if (!empty($LeaveRequest['reporting_manager_email'])) {
                                $to_emails[] = $LeaveRequest['reporting_manager_email'];
                            }
                            if (!empty($LeaveRequest['hod_email'])) {
                                $to_emails[] = $LeaveRequest['hod_email'];
                            }
                        }
                        $email->setTo($to_emails);
                        $email->setSubject('New Leave Request');
                        $preparedMessage = $this->prepareEmailContent(
                            'You have recieved a leave request',
                            'Details of the leave request are mentioned below.',
                            [
                                'Employee Name' => $LeaveRequest["employee_name"],
                                'Department' => $LeaveRequest["department_name"],
                                'From Date' => $LeaveRequest["from_date"],
                                'To Date' => $LeaveRequest["to_date"],
                                'Number of days' => $LeaveRequest["number_of_days"],
                                'Leave Code' => $LeaveRequest["type_of_leave"],
                                'Emergency Contact' => $LeaveRequest["emergency_contact_d_l"],
                            ],
                            [
                                array('url' => base_url("/backend/administrative/leaveapproval?action=approve&id=") . $lastInsertID, 'label' => 'Approve', 'class' => 'success'),
                                array('url' => base_url("/backend/administrative/leaveapproval?action=reject&id=") . $lastInsertID, 'label' => 'Reject', 'class' => 'danger'),
                            ]
                        );
                        $email->setMessage($preparedMessage);
                        if ($email->send()) {
                            #update credit history record with request id  
                            $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                            $UpdateCreditHistoryRecordData = ['remarks' => 'Leave applied request id ' . $lastInsertID];
                            $UpdateCreditHistoryRecordQuery = $LeaveCreditHistoryModel->update($CreditHistoryRecordID, $UpdateCreditHistoryRecordData);
                            #update credit history record with request id
                            return $this->createResponseData("success", 'Request Submitted Successfully Request ID <span class="text-danger">#' . $lastInsertID . '</span>', $lastInsertID);
                            die();
                        } else {
                            #update credit history record with request id  
                            $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                            $UpdateCreditHistoryRecordData = ['remarks' => 'Leave applied request id ' . $lastInsertID];
                            $UpdateCreditHistoryRecordQuery = $LeaveCreditHistoryModel->update($CreditHistoryRecordID, $UpdateCreditHistoryRecordData);
                            #update credit history record with request id
                            return $this->createResponseData(
                                "success",
                                'Request Submitted Successfully, but Email is not sent to the reporting manager Request ID <span class="text-danger">#' . $lastInsertID . '</span>',
                                $lastInsertID
                            );
                            die();
                        }
                    }
                } else {
                    return $this->createResponseData("error", "DB:Error <br> Please contact administrator. error while creating record in credit history 2");
                    die();
                }
            } else {
                return $this->createResponseData("error", "DB:Error <br> Please contact administrator. error while updating current month balance 1");
                die();
            }
        } elseif ($postData['type_of_leave_requested'] == 'COMP OFF' || $postData['type_of_leave_requested'] == 'UL') {
            $data = [
                'employee_id'           => $postData['current_employee_id'],
                'from_date'             => $postData['from_date'],
                'to_date'               => $postData['to_date'],
                'type_of_leave'         => $postData['type_of_leave_requested'],
                'day_type'              => ($postData['day_type'] == '0.5') ? 'HALF' : 'FULL',
                'number_of_days'        => $postData['number_of_days'],
                'address_d_l'           => $postData['address_d_l'],
                'emergency_contact_d_l' => $postData['emergency_contact_d_l'],
                'reason_of_leave'       => $postData['reason_of_leave'],
                'attachment'            => (isset($postData['attachment']) && !empty($postData['attachment'])) ? $postData['attachment'] : '',
            ];

            $LeaveRequestsModel = new LeaveRequestsModel();
            $query = $LeaveRequestsModel->insert($data);
            if (!$query) {
                return $this->createResponseData("error", "DB:Error <br> Please contact administrator. Error while creatring new request 4");
                die();
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
                if ($postData['current_employee_id'] != '40') {
                    if (!empty($LeaveRequest['reporting_manager_email'])) {
                        $to_emails[] = $LeaveRequest['reporting_manager_email'];
                    }
                    if (!empty($LeaveRequest['hod_email'])) {
                        $to_emails[] = $LeaveRequest['hod_email'];
                    }
                }
                $email->setTo($to_emails);
                $email->setSubject('New Leave Request');
                $preparedMessage = $this->prepareEmailContent(
                    'You have recieved a leave request',
                    'Details of the leave request are mentioned below.',
                    [
                        'Employee Name' => $LeaveRequest["employee_name"],
                        'Department' => $LeaveRequest["department_name"],
                        'From Date' => $LeaveRequest["from_date"],
                        'To Date' => $LeaveRequest["to_date"],
                        'Number of days' => $LeaveRequest["number_of_days"],
                        'Leave Code' => $LeaveRequest["type_of_leave"],
                        'Emergency Contact' => $LeaveRequest["emergency_contact_d_l"],
                    ],
                    [
                        array('url' => base_url("/backend/administrative/leaveapproval?action=approve&id=") . $lastInsertID, 'label' => 'Approve', 'class' => 'success'),
                        array('url' => base_url("/backend/administrative/leaveapproval?action=reject&id=") . $lastInsertID, 'label' => 'Reject', 'class' => 'danger'),
                    ]
                );
                $email->setMessage($preparedMessage);
                if ($email->send()) {
                    return $this->createResponseData("success", 'Request Submitted Successfully Request ID <span class="text-danger">#' . $lastInsertID . '</span>', $lastInsertID);
                    die();
                } else {
                    return $this->createResponseData("success", 'Request Submitted Successfully, but Email is not sent to the reporting manager Request ID <span class="text-danger">#' . $lastInsertID . '</span>', $lastInsertID);
                    die();
                }
            }
        } else {
            return $this->createResponseData("error", "Technical:Error <br> Please contact developer. error while creating comp off leave request");
            die();
        }
        #End:: Update Balances and Create leave request
        return $response_array;
        die();
    }

    public function normalValidation()
    {
        return $this->validate(
            [
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
    }

    public function createResponseData($response_type, $response_description, $response_data = null)
    {
        $response_array = array();
        $response_array['response_type'] = $response_type;
        $response_array['response_description'] = $response_description;
        $response_array['response_data'] = $response_data;
        return $response_array;
    }

    public function resctrictionFound($postData)
    {
        $day_type = $postData['day_type'];
        if (empty($postData)) {
            return $this->createResponseData("error", "No Data passed for processing");
            die();
        }

        #Begin::check if half day CL or COMP OFF on 11 Nov 2023
        if ($postData['day_type'] == '0.5' && $postData['type_of_leave_requested'] == 'CL' && $postData['from_date'] == '2023-11-11') {
            return $this->createResponseData("error", "Half Day CL is not allowed, on Diwali Celebration day, 2023-11-11");
        }
        if ($postData['day_type'] == '0.5' && $postData['type_of_leave_requested'] == 'COMP OFF' && $postData['from_date'] == '2023-11-11') {
            return $this->createResponseData("error", "Half Day COMP OFF is not allowed, on Diwali Celebration day, 2023-11-11");
        }
        #End::check if half day CL or COMP OFF on 11 Nov 2023

        #Begin::check if half day EL
        if ($postData['type_of_leave_requested'] == 'EL' && $postData['type_of_leave'] != 'SICK LEAVE') {
            if ($postData['day_type'] == '0.5') {
                return $this->createResponseData("error", "Half Day EL is not allowed");
            }
        }
        #End::check if half day EL

        #Begin::check minimum 3 EL
        if ($postData['type_of_leave_requested'] == 'EL' && $postData['type_of_leave'] != 'SICK LEAVE' && $postData['prior_to_date_validation'] == true) {
            if (strtotime($postData['from_date']) >= strtotime('2023-02-01') && $postData['number_of_days'] < 3) {
                return $this->createResponseData("error", "Minimum 3 EL is required from 1st Jan");
            }
        }
        #End::check minimum 3 EL

        #Begin::check how many days prior
        $currentdate = date_create(date('Y-m-d'));
        $fromdate = date_create($postData['from_date']);
        $diff = date_diff($currentdate, $fromdate);
        $prior_days = $diff->format("%a") - 1;
        $temp_number_of_days = $postData['number_of_days'];
        $temp_number_of_days = $temp_number_of_days > 10 ? 10 : $temp_number_of_days;
        if ($postData['type_of_leave_requested'] == 'EL' && $postData['type_of_leave'] != 'SICK LEAVE') {
            if ($prior_days < $temp_number_of_days && $postData['prior_to_date_validation'] == true) {
                return $this->createResponseData("error", "You can apply " . $postData['number_of_days'] . " El minimum " . $temp_number_of_days . " before excluding Current date and Leave Start Date");
            }
        }
        #End::check how many days prior

        #Begin:: Restrict if applying for half day but dates are different
        if ($postData['day_type'] == '0.5' && $postData['from_date'] !== $postData['to_date']) {
            return $this->createResponseData("error", "Select same date if requesting for half day");
        }
        #End:: Restrict if applying for half day but dates are different

        #Begin::check existing requests 
        $LeaveRequestsModel = new LeaveRequestsModel();
        $existingLeaveRequestsOnSelectedDates = $LeaveRequestsModel
            ->select('count(leave_requests.id) as existing_leave_count')
            ->where('leave_requests.employee_id =', $postData['current_employee_id'])
            ->whereIn('leave_requests.status', ['approved', 'pending'])
            ->where(
                '( 
                                   (leave_requests.from_date between "' . $postData['from_date'] . '" and "' . $postData['to_date'] . '") 
                                or (leave_requests.to_date between "' . $postData['from_date'] . '" and "' . $postData['to_date'] . '") 
                                or ("' . $postData['from_date'] . '" between leave_requests.from_date and leave_requests.to_date ) 
                                or ("' . $postData['to_date'] . '" between leave_requests.from_date and leave_requests.to_date )
                                )'
            )
            ->first()['existing_leave_count'];
        if ($existingLeaveRequestsOnSelectedDates > 0) {
            return $this->createResponseData("error", "Your leave request is already present for selected dates");
        }
        #End::check existing requests 

        return false;
    }

    public function getBalances($postData)
    {
        $balancesArray = array(
            'current_month' => [
                'data' => null,
                'LeaveBalance' => null,
                'LeaveBalanceRowID' => null,
                'LeaveBalanceRow_leave_id' => null,
            ],
            'next_month' => [
                'data' => null,
                'LeaveBalance' => null,
                'LeaveBalanceRowID' => null,
                'LeaveBalanceRow_leave_id' => null,
            ]
        );

        $balancesArray['current_month']['LeaveBalance'] = 0;
        $CurrentMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalance($postData['current_employee_id']);
        // print_r($CurrentMonthLeaveBalanceDataArray);
        // die();

        $balancesArray['current_month']['data'] = $CurrentMonthLeaveBalanceDataArray;
        foreach ($CurrentMonthLeaveBalanceDataArray as $i => $CurrentMonthLeaveBalanceData) {
            if ($CurrentMonthLeaveBalanceData['leave_code'] == $postData['type_of_leave_requested']) {
                $balancesArray['current_month']['LeaveBalance'] = $CurrentMonthLeaveBalanceData['balance'];
                $balancesArray['current_month']['LeaveBalanceRowID'] = $CurrentMonthLeaveBalanceData['id'] ?? null;
                $balancesArray['current_month']['LeaveBalanceRow_leave_id'] = $CurrentMonthLeaveBalanceData['leave_id'] ?? null;
            }
        }

        $balancesArray['next_month']['LeaveBalance'] = 0;
        $NextMonthLeaveBalanceDataArray = ProcessorHelper::getLeaveBalanceNextMonth($postData['current_employee_id']);
        // print_r($NextMonthLeaveBalanceDataArray);
        // die();
        $balancesArray['next_month']['data'] = $NextMonthLeaveBalanceDataArray;
        foreach ($NextMonthLeaveBalanceDataArray as $i => $NextMonthLeaveBalanceData) {
            if ($NextMonthLeaveBalanceData['leave_code'] == $postData['type_of_leave_requested']) {
                $balancesArray['next_month']['LeaveBalance'] = $NextMonthLeaveBalanceData['eligible_balance'];
                // $balancesArray['next_month']['LeaveBalanceRowID'] = $NextMonthLeaveBalanceData['id'];
                // $balancesArray['next_month']['LeaveBalanceRow_leave_id'] = $NextMonthLeaveBalanceData['leave_id'];
            }
        }
        return $balancesArray;
    }

    public function findDeductionDays($postData)
    {
        $number_of_days_to_deduct = 100;
        $Balances = $this->getBalances($postData);
        $CurrentMonthLeaveBalanceDataArray = $Balances['current_month']['data'];
        $CurrentMonthLeaveBalance = $Balances['current_month']['LeaveBalance'];
        $NextMonthLeaveBalanceDataArray = $Balances['next_month']['data'];
        $NextMonthLeaveBalance = $Balances['next_month']['LeaveBalance'];

        $from_date = $postData['from_date'];
        $to_date = $postData['to_date'];
        $from_month = date('m', strtotime($from_date));
        $to_month = date('m', strtotime($to_date));
        $from_year = date('Y', strtotime($from_date));
        $to_year = date('Y', strtotime($to_date));
        $current_month = date('m');
        $current_year = date('Y');

        if ($from_month == $current_month && $to_month == $current_month) {
            $number_of_days_current_month = $postData['number_of_days'];
            $number_of_days_next_month = 0;
            if ($postData['type_of_leave_requested'] != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type", $CurrentMonthLeaveBalanceDataArray)];
                die();
            }
            $number_of_days_to_deduct = $number_of_days_current_month;
        } elseif ($from_month !== $current_month && $to_month !== $current_month) {
            #check if both dates are from next year
            if ($from_year == $current_year && $to_year == $current_year) {
                if ($postData['type_of_leave_requested'] == 'EL') {
                    $number_of_days_current_month = 0;
                    $number_of_days_next_month = $postData['number_of_days'];
                    if ($postData['type_of_leave_requested'] != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Next Month", $NextMonthLeaveBalanceDataArray)];
                        die();
                    }
                    $number_of_days_to_deduct = $number_of_days_next_month;
                } else {
                    $number_of_days_current_month = 0;
                    $number_of_days_next_month = $postData['number_of_days'];
                    if ($postData['type_of_leave_requested'] != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Next Month", $NextMonthLeaveBalanceDataArray)];
                        die();
                    }
                    $number_of_days_to_deduct = 0;
                }
            } else {
                $number_of_days_current_month = 0;
                $number_of_days_next_month = $postData['number_of_days'];
                if ($postData['type_of_leave_requested'] != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                    return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Next Month", $CurrentMonthLeaveBalanceDataArray)];
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
                if ($postData['type_of_leave_requested'] == 'EL') {
                    if ($CurrentMonthLeaveBalance < ($postData['number_of_days'])) {
                        return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Current and Next Month", $CurrentMonthLeaveBalanceDataArray)];
                        die();
                    }
                    $number_of_days_to_deduct = $postData['number_of_days'];
                } else {
                    if ($postData['type_of_leave_requested'] != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                        return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Current Month", $CurrentMonthLeaveBalanceDataArray)];
                        die();
                    }
                    if ($postData['type_of_leave_requested'] != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                        return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Next Month", $NextMonthLeaveBalanceDataArray)];
                        die();
                    }
                    $number_of_days_to_deduct = $number_of_days_current_month;
                }
            } else {
                if ($postData['type_of_leave_requested'] != 'UL' && $CurrentMonthLeaveBalance < $number_of_days_current_month) {
                    return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Current Month", $CurrentMonthLeaveBalanceDataArray)];
                    die();
                }
                if ($postData['type_of_leave_requested'] != 'UL' && $NextMonthLeaveBalance < $number_of_days_next_month) {
                    return ['type' => 'error', 'data' => $this->createResponseData("error", "Number of days is higher than the balance of selected leave type in Next Month", $NextMonthLeaveBalanceDataArray)];
                    die();
                }
                $number_of_days_to_deduct = $number_of_days_current_month;
            }
        }
        return ['type' => 'success', 'data' => $number_of_days_to_deduct];
    }

    public function verifySickLeaveBalance($postData, $number_of_days_to_deduct)
    {
        if ($postData['type_of_leave'] == 'SICK LEAVE') {
            $LeaveRequestsModel = new LeaveRequestsModel();
            $currentYearSickLeave = $LeaveRequestsModel
                ->select('sum(leave_requests.number_of_days) as sick_leave_count')
                ->where('leave_requests.employee_id =', $postData['current_employee_id'])
                ->where('leave_requests.type_of_leave =', 'EL')
                ->where('leave_requests.sick_leave =', 'yes')
                ->where('leave_requests.from_date >=', date('Y-01-01'))
                ->where('leave_requests.to_date <=', date('Y-12-31'))
                ->whereIn('leave_requests.status', ['approved', 'pending'])
                ->first();
            $currentYearSickLeaveCount = $currentYearSickLeave['sick_leave_count'];
            $totalSickLeaves = $currentYearSickLeaveCount + $number_of_days_to_deduct;
            $maxAllowedSickLeaves = 4 - $currentYearSickLeaveCount;
            if ($currentYearSickLeave['sick_leave_count'] >= 4) {
                return $this->createResponseData("error", 'Maximum 4 Sick leave is allowed in a year, and you have already taken ' . $currentYearSickLeave["sick_leave_count"] . ' Sick Leaves this year');
                die();
            } elseif ($totalSickLeaves > 4) {
                return $this->createResponseData("error", 'Maximum 4 Sick leave is allowed in a year, and you have already taken ' . $currentYearSickLeave["sick_leave_count"] . ' Sick Leaves this year, So you can apply for maximum ' . $maxAllowedSickLeaves . ' Sick Leaves this year');
                die();
            }
        }
        return false;
    }



    public function prepareEmailContent($heading = null, $subheading = null, $keyValuePairs = null, $approvalButtons = null)
    {
        ob_start();
?>
        <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                <tbody>
                    <tr>
                        <td align="center" valign="center" style="text-align:center; padding: 40px">
                            <a href="<?= base_url('public') ?>" rel="noopener" target="_blank">
                                <img alt="Logo" src="<?= base_url('public') . "/assets/media/logos/logo-healthgenie.png" ?>" />
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="center">
                            <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                <div style="padding-bottom: 30px; font-size: 17px;">
                                    <strong><?= $heading ?></strong>
                                </div>
                                <div style="padding-bottom: 10px"><?= $subheading ?></div>
                                <?php
                                if (!empty($keyValuePairs)) {
                                    foreach ($keyValuePairs as $label => $text) {
                                ?><div style="padding-bottom: 10px"><span style="color:#aeaeae"><?= $label ?>:</span> <?= $text ?></div><?php
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                if (!empty($approvalButtons)) {
                                                                                                                                        ?>
                                    <div style="padding-bottom: 10px; text-align:center;">
                                        <?php
                                                                                                                                    foreach ($approvalButtons as $Button) {
                                        ?>
                                            <a href="<?= $Button['url'] ?>" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem; 
                                            <?php
                                                                                                                                        if ($Button['class'] == 'success') {
                                                                                                                                            echo 'background-color:#009EF7;';
                                                                                                                                        } elseif ($Button['class'] == 'danger') {
                                                                                                                                            echo 'background-color:#dc3545;';
                                                                                                                                        }
                                            ?>"
                                                target="_blank"><?= $Button['label'] ?></a>
                                        <?php
                                                                                                                                    }
                                        ?>
                                    </div>
                                <?php
                                                                                                                                }
                                ?>
                                <div style="padding-bottom: 10px">Kind regards,
                                    <br>HRM Team.
                    <tr>
                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                            <p>B-13, Okhla industrial area phase 2, Delhi 110020 India</p>
                            <p>Copyright ©
                                <a href="<?= base_url('public') ?>" rel="noopener" target="_blank">Healthgenie/Gstc</a>.
                            </p>
                        </td>
                    </tr></br>
        </div>
        </div>
        </td>
        </tr>
        </tbody>
        </table>
        </div>
<?php
        $emailContent = ob_get_clean();
        return $emailContent;
    }


    public function getAllLeaveRequests()
    {
        $current_user = $this->session->get('current_user');
        $leave_requests_sql = "select 
        lr.id as req_id, 
        lr.from_date as from_date, 
        lr.to_date as to_date, 
        lr.number_of_days as number_of_days, 
        lr.type_of_leave as type_of_leave, 
        lr.sick_leave as sick_leave, 
        lr.day_type as day_type, 
        lr.address_d_l as address_d_l, 
        lr.emergency_contact_d_l as emergency_contact_d_l, 
        lr.reason_of_leave as reason_of_leave, 
        lr.attachment as attachment, 
        lr.status as status, 
        lr.date_time as date_time, 
        trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name,
        lr.reviewed_date as reviewed_date,  
        trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name, 
        trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name, 
        lr.remarks as remarks 
        from leave_requests lr 
        left join employees e on e.id = lr.employee_id 
        left join departments d on d.id = e.department_id 
        left join employees e2 on e2.id = e.reporting_manager_id 
        left join employees e3 on e3.id = d.hod_employee_id 
        left join employees e4 on e4.id = lr.reviewed_by 
        where lr.employee_id = '" . $current_user['employee_id'] . "'";

        $CustomModel = new CustomModel();
        $leave_requests = $CustomModel->CustomQuery($leave_requests_sql)->getResultArray();
        foreach ($leave_requests as $index => $dataRow) {
            $from_date_formatted = !empty($dataRow['from_date']) ? date('d M Y', strtotime($dataRow['from_date'])) : '-';
            $from_date_ordering = !empty($dataRow['from_date']) ? strtotime($dataRow['from_date']) : '0';
            $leave_requests[$index]['from_date'] = array('formatted' => $from_date_formatted, 'ordering' => $from_date_ordering);

            $to_date_formatted = !empty($dataRow['to_date']) ? date('d M Y', strtotime($dataRow['to_date'])) : '-';
            $to_date_ordering = !empty($dataRow['to_date']) ? strtotime($dataRow['to_date']) : '0';
            $leave_requests[$index]['to_date'] = array('formatted' => $to_date_formatted, 'ordering' => $to_date_ordering);

            $reviewed_date_formatted = !empty($dataRow['reviewed_date']) ? date('d M Y', strtotime($dataRow['reviewed_date'])) : '-';
            $reviewed_date_ordering = !empty($dataRow['reviewed_date']) ? strtotime($dataRow['reviewed_date']) : '0';
            $leave_requests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);

            $date_time_formatted = !empty($dataRow['date_time']) ? date('d M Y', strtotime($dataRow['date_time'])) : '-';
            $date_time_ordering = !empty($dataRow['date_time']) ? strtotime($dataRow['date_time']) : '0';
            $leave_requests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            #begin::Salary disbursed or not
            $isCancellable_from_date = strtotime($dataRow['from_date']) < strtotime(first_date_of_last_month()) ? 'no' : 'yes';
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $this->session->get('current_user')['employee_id']);
            $PreFinalSalaryModel->where('year =', date('Y', strtotime($dataRow['from_date'])));
            $PreFinalSalaryModel->where('month =', date('m', strtotime($dataRow['from_date'])));
            $FinalSalary_from_date = $PreFinalSalaryModel->first();
            if (!empty($FinalSalary_from_date)) {
                $isCancellable_from_date = in_array($FinalSalary_from_date['status'], ['generated', 're-generated', 'unhold']) ? 'yes' : 'no';
            }
            if ($isCancellable_from_date == 'yes' && strtotime(date('Y-m-d')) > strtotime(date('Y-m-04')) && strtotime($dataRow['from_date']) < strtotime(date('Y-m-01'))) {
                $isCancellable_from_date = 'no';
            }

            $isCancellable_to_date = strtotime($dataRow['from_date']) < strtotime(first_date_of_last_month()) ? 'no' : 'yes';
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $PreFinalSalaryModel->select('pre_final_salary.*');
            $PreFinalSalaryModel->where('employee_id =', $this->session->get('current_user')['employee_id']);
            $PreFinalSalaryModel->where('year =', date('Y', strtotime($dataRow['to_date'])));
            $PreFinalSalaryModel->where('month =', date('m', strtotime($dataRow['to_date'])));
            $FinalSalary_to_date = $PreFinalSalaryModel->first();
            if (!empty($FinalSalary_to_date)) {
                $isCancellable_to_date = in_array($FinalSalary_to_date['status'], ['generated', 're-generated', 'unhold']) ? 'yes' : 'no';
            }
            if ($isCancellable_to_date == 'yes' && strtotime(date('Y-m-d')) > strtotime(date('Y-m-04')) && strtotime($dataRow['to_date']) < strtotime(date('Y-m-01'))) {
                $isCancellable_to_date = 'no';
            }

            $leave_requests[$index]['cancellable'] = ($isCancellable_from_date == 'yes' && $isCancellable_to_date == 'yes') ? 'yes' : 'no';

            $leave_requests[$index]['salary_status_from_date'] = $FinalSalary_from_date['status'] ?? false;
            $leave_requests[$index]['salary_status_to_date'] = $FinalSalary_to_date['status'] ?? false;
            #end::Salary disbursed or not

        }
        echo json_encode($leave_requests);
    }

    public function getSelfLeaveRequest()
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
            lr.day_type as day_type, 
            lr.reason_of_leave as reason_of_leave, 
            lr.status as status, 
            lr.date_time as date_time, 
            trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name,
            lr.reviewed_date as reviewed_date,  
            lr.remarks as remarks, 
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

            if ($leave_request['status'] == 'pending') {
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
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This leave request is not in Pending Status';
            }
        }
        #echo json_encode($response_array);
        return $this->response->setJSON($response_array);
    }

    public function cancelSelfLeaveRequest()
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
            $theLeaveRequest = $LeaveRequestsModel->find($leave_id);

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

            if ($LeaveRequest['status'] !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This leave request is not in Pending Status';
                return $this->response->setJSON($response_array);
                die();
            } else {

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
