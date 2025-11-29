<?php

namespace App\Controllers\Requests;

use App\Models\CustomModel;
use App\Models\EmployeeModel;
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
        $EmployeeModel = new EmployeeModel();
        $employees = $EmployeeModel
            ->select('id')
            ->select('first_name')
            ->select('last_name')
            ->orderBy('first_name ASC')
            ->findAll();
        /*echo '<pre>';
        print_r($employees);
        die();*/

        $data = [
            'page_title'            => 'My ODs',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'employees'             => $employees,
        ];
        return view('User/Od', $data);
    }


    public function createOdRequest()
    {
        $response_array = array();


        $validation = $this->validate(
            [
                'estimated_from_date_time'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Estimated From is required',
                    ]
                ],
                'estimated_to_date_time'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Estimated To is required',
                    ]
                ],
                'duty_location'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Duty Location is required',
                    ]
                ],
                'international'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Select Yes if this OD includes internation trip, otherwise select NO',
                    ]
                ],
                'duty_assigner'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Duty Assigner is required',
                    ]
                ],
                'reason'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Reason of OD is required',
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
            $data = [
                'employee_id'               => $this->session->get('current_user')['employee_id'],
                'estimated_from_date_time'  => $this->request->getPost('estimated_from_date_time'),
                'estimated_to_date_time'    => $this->request->getPost('estimated_to_date_time'),
                'international'             => $this->request->getPost('international'),
                'duty_location'             => $this->request->getPost('duty_location'),
                'duty_assigner'             => $this->request->getPost('duty_assigner'),
                'reason'                    => $this->request->getPost('reason'),
            ];
            $OdRequestsModel = new OdRequestsModel();
            $query = $OdRequestsModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #send Email
                $lastInsertID = $OdRequestsModel->getInsertID();
                $OdRequestsModel = new OdRequestsModel();
                $ODRequest = $OdRequestsModel
                    ->select('od_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as assigned_by_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('e.work_email as employee_work_email')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->join('employees as e', 'e.id = od_requests.employee_id', 'left')
                    ->join('employees as e1', 'e1.id = od_requests.duty_assigner', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->where('od_requests.id =', $lastInsertID)
                    ->first();

                /*echo '<pre>';
                print_r($ODRequest);
                echo '</pre>';
                die();*/

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($ODRequest['reporting_manager_email'])) {
                    $to_emails[] = $ODRequest['reporting_manager_email'];
                }
                if (!empty($ODRequest['hod_email'])) {
                    $to_emails[] = $ODRequest['hod_email'];
                }

                $to_emails_new = [];
                foreach ($to_emails as $i => $e) {
                    if ($e == $ODRequest['employee_work_email']) {
                        unset($to_emails[$i]);
                    }
                }

                if (!empty($to_emails)) {


                    $email->setTo($to_emails);
                    $email->setSubject('New OD Request');
                    // $email->setMessage('
                    //         You have recieved an OD request, 
                    //         <br>
                    //         The details are below. 
                    //         <br> 
                    //         <div>
                    //             <p>Employee Name: '.$ODRequest["employee_name"].'</p>
                    //             <p>Employee Code: '.$ODRequest["internal_employee_id"].'</p>
                    //             <p>Department: '.$ODRequest["department_name"].'</p>
                    //             <p>Estimated From: '.$ODRequest["estimated_from_date_time"].'</p>
                    //             <p>Estimated To: '.$ODRequest["estimated_to_date_time"].'</p>
                    //             <p>Duty Location: '.$ODRequest["duty_location"].'</p>
                    //             <p>Internationl trip included: '.$ODRequest["international"].'</p>
                    //             <p>Assigned By: '.$ODRequest["assigned_by_name"].'</p>
                    //             <p>Reason: '.$ODRequest["reason"].'</p>
                    //         </div>
                    //         <br> 
                    //         <a style="margin: 10px 20px" href="'.base_url("/backend/administrative/odapproval?action=approve&id=").$lastInsertID.'">Approve</a> 
                    //         <a style="margin: 10px 20px" href="'.base_url("/backend/administrative/odapproval?action=reject&id=").$lastInsertID.'">Reject</a>
                    //     ');

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
                                                        <strong>You have recieved an OD request</strong>
                                                    </div>
                                                    <div style="padding-bottom: 10px">Details of the leave request are mentioned below.</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Name:</span> ' . $ODRequest["employee_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Employee Code:</span> ' . $ODRequest["internal_employee_id"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Department:</span> ' . $ODRequest["department_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated From:</span> ' . $ODRequest["estimated_from_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Estimated To:</span> ' . $ODRequest["estimated_to_date_time"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Duty Location:</span> ' . $ODRequest["duty_location"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Internationl trip included:</span> ' . $ODRequest["international"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Assigned By:</span> ' . $ODRequest["assigned_by_name"] . '</div>
                                                    <div style="padding-bottom: 10px"><span style="color:#aeaeae">Reason:</span> ' . $ODRequest["reason"] . '</div>

                                                    <div style="padding-bottom: 10px; text-align:center;">

                                                        <a href="' . base_url("/backend/administrative/odapproval?action=approve&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009EF7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Approve</a>

                                                        <a href="' . base_url("/backend/administrative/odapproval?action=reject&id=") . $lastInsertID . '" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#dc3545;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;margin:1rem" target="_blank">Reject</a>
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
                        $response_array['response_description'] = 'Request Submitted Successfully';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Request Submitted Successfully, but email not send';
                    }
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Request Submitted Successfully, but email was not send since there was no email address to send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }


    public function getAllOdRequests()
    {
        #this is database details
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $CustomModel = new CustomModel();
        /*$sql = "select 
        o.id as id, 
        o.estimated_from_date_time as estimated_from_date_time, 
        o.estimated_to_date_time as estimated_to_date_time, 
        o.actual_from_date_time as actual_from_date_time, 
        o.actual_to_date_time as actual_to_date_time, 
        o.duty_location as duty_location, 
        trim(concat(e.first_name, ' ', e.last_name)) as assigned_by,
        o.reason as reason, 
        o.status as status,
        trim(concat(e2.first_name, ' ', e2.last_name)) as reviewed_by,
        o.reviewed_date_time as reviewed_date_time,
        o.remarks as remarks,
        o.updated_date_time as updated_date_time,
        o.date_time as date_time
        from od_requests o 
        left join employees e on e.id = o.duty_assigner 
        left join employees e2 on e2.id = o.reviewed_by 
        where o.employee_id = '".$current_employee_id."'
        ";
        $ODRequestData = $CustomModel->CustomQuery($sql)->getResultArray();*/
        $OdRequestsModel = new OdRequestsModel();
        $ODRequestData = $OdRequestsModel
            ->select('od_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select('d.department_name as department_name')
            ->select('c.company_short_name as company_short_name')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by_name")
            ->join('employees as e1', 'e1.id = od_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('companies as c', 'c.id = e1.company_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = od_requests.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = od_requests.duty_assigner', 'left')
            ->where('od_requests.employee_id =', $current_employee_id)
            ->findAll();


        /*echo '<pre>';
        print_r($ODRequestData);
        die();*/
        if (!empty($ODRequestData)) {
            foreach ($ODRequestData as $index => $row) {

                if (isset($row['actual_from_date_time']) && !empty($row['actual_from_date_time']) && isset($row['actual_to_date_time']) && !empty($row['actual_to_date_time'])) {
                    $actual_from_date_time = date_create($row['actual_from_date_time']);
                    $actual_to_date_time = date_create($row['actual_to_date_time']);
                    $interval = date_diff($actual_from_date_time, $actual_to_date_time);
                } elseif (isset($row['estimated_from_date_time']) && !empty($row['estimated_from_date_time']) && isset($row['estimated_to_date_time']) && !empty($row['estimated_to_date_time'])) {
                    $estimated_from_date_time = date_create($row['estimated_from_date_time']);
                    $estimated_to_date_time = date_create($row['estimated_to_date_time']);
                    $interval = date_diff($estimated_from_date_time, $estimated_to_date_time);
                }

                $hours = 0;
                $hours += (int)$interval->format('%d') * 24;
                $hours += (int)$interval->format('%h');
                $minutes = 0;
                $minutes += (int)$interval->format('%i');
                $minutes += round((int)$interval->format('%s') / 60);
                $ODRequestData[$index]['interval'] = json_encode($interval);
                $ODRequestData[$index]['interval'] = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

                if ($row['duty_assigner'] == $row['employee_id']) {
                    $ODRequestData[$index]['assigned_by'] = 'Self';
                }

                if (isset($row['estimated_from_date_time']) && !empty($row['estimated_from_date_time']) && isset($row['date_time']) && !empty($row['date_time'])) {
                    if (strtotime($row['estimated_from_date_time']) >= strtotime($row['date_time'])) {
                        $ODRequestData[$index]['pre_post'] = 'Pre';
                    } else {
                        $ODRequestData[$index]['pre_post'] = 'Post';
                    }
                }

                if ((isset($row['date_time']) && !empty($row['date_time'])) && (!isset($row['reviewed_date_time']) && empty($row['reviewed_date_time']))) {
                    $RequestedOn = date_create($row['date_time']);
                    $CheckingOn = date_create(date('Y-m-d'));
                    $PendingDays = date_diff($RequestedOn, $CheckingOn);
                    $ODRequestData[$index]['pending_days'] = (int)$PendingDays->format('%d') + 1;
                } else {
                    $ODRequestData[$index]['pending_days'] = '';
                }

                $estimated_from_date_time_formatted = !empty($row['estimated_from_date_time']) ? date('d M Y h:i A', strtotime($row['estimated_from_date_time'])) : '-';
                $estimated_from_date_time_ordering = !empty($row['estimated_from_date_time']) ? strtotime($row['estimated_from_date_time']) : '0';
                $ODRequestData[$index]['estimated_from_date_time'] = array('formatted' => $estimated_from_date_time_formatted, 'ordering' => $estimated_from_date_time_ordering);

                $estimated_to_date_time_formatted = !empty($row['estimated_to_date_time']) ? date('d M Y h:i A', strtotime($row['estimated_to_date_time'])) : '-';
                $estimated_to_date_time_ordering = !empty($row['estimated_to_date_time']) ? strtotime($row['estimated_to_date_time']) : '0';
                $ODRequestData[$index]['estimated_to_date_time'] = array('formatted' => $estimated_to_date_time_formatted, 'ordering' => $estimated_to_date_time_ordering);

                $actual_from_date_time_formatted = !empty($row['actual_from_date_time']) ? date('d M Y h:i A', strtotime($row['actual_from_date_time'])) : '-';
                $actual_from_date_time_ordering = !empty($row['actual_from_date_time']) ? strtotime($row['actual_from_date_time']) : '0';
                $ODRequestData[$index]['actual_from_date_time'] = array('formatted' => $actual_from_date_time_formatted, 'ordering' => $actual_from_date_time_ordering);

                $actual_to_date_time_formatted = !empty($row['actual_to_date_time']) ? date('d M Y h:i A', strtotime($row['actual_to_date_time'])) : '-';
                $actual_to_date_time_ordering = !empty($row['actual_to_date_time']) ? strtotime($row['actual_to_date_time']) : '0';
                $ODRequestData[$index]['actual_to_date_time'] = array('formatted' => $actual_to_date_time_formatted, 'ordering' => $actual_to_date_time_ordering);

                $reviewed_date_time_formatted = !empty($row['reviewed_date_time']) ? date('d M Y h:i A', strtotime($row['reviewed_date_time'])) : '-';
                $reviewed_date_time_ordering = !empty($row['reviewed_date_time']) ? strtotime($row['reviewed_date_time']) : '0';
                $ODRequestData[$index]['reviewed_date_time'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($row['date_time']) ? date('d M Y h:i A', strtotime($row['date_time'])) : '-';
                $date_time_ordering = !empty($row['date_time']) ? strtotime($row['date_time']) : '0';
                $ODRequestData[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $updated_date_time_formatted = !empty($row['updated_date_time']) ? date('d M Y h:i A', strtotime($row['updated_date_time'])) : '-';
                $updated_date_time_ordering = !empty($row['updated_date_time']) ? strtotime($row['updated_date_time']) : '0';
                $ODRequestData[$index]['updated_date_time'] = array('formatted' => $updated_date_time_formatted, 'ordering' => $updated_date_time_ordering);
            }
        }
        /*echo '<pre>';
        print_r($ODRequestData);
        echo '</pre>';
        die();*/
        echo json_encode($ODRequestData);
    }
}
