<?php

namespace App\Controllers\Requests;

use App\Controllers\BaseController;
use App\Models\GatePassRequestsModel;

class GatePass extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }
    public function index()
    {
        $data = [
            'page_title'            => 'My Gate Pass Requests',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            // 'employees'             => $employees,
        ];
        return view('User/GatePass', $data);
    }

    public function createGatePassRequest()
    {
        $response_array = array();

        $validation = $this->validate(
            [
                'gate_pass_type'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select type of your request',
                    ]
                ],
                'gate_pass_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date correctly',
                    ]
                ],
                'gate_pass_hours'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select how many minutes/hours',
                    ]
                ],
                'reason'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please specify the reason of this request',
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
                'employee_id'       => $this->session->get('current_user')['employee_id'],
                'gate_pass_type'    => $this->request->getPost('gate_pass_type'),
                'gate_pass_date'    => $this->request->getPost('gate_pass_date'),
                'gate_pass_hours'   => $this->request->getPost('gate_pass_hours'),
                'reason'            => $this->request->getPost('reason'),
            ];

            $GatePassRequestsModel = new GatePassRequestsModel();
            $query = $GatePassRequestsModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #send Email
                $lastInsertID = $GatePassRequestsModel->getInsertID();
                $GatePassRequestsModel = new GatePassRequestsModel();
                $GatePassRequest = $GatePassRequestsModel
                    ->select('gate_pass_requests.*')
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
                    ->select('e1.internal_employee_id as internal_employee_id')
                    ->select('e1.work_email as employee_work_email')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->join('employees as e1', 'e1.id = gate_pass_requests.employee_id', 'left')
                    ->join('departments as d', 'd.id = e1.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->where('gate_pass_requests.id =', $lastInsertID)
                    ->first();



                /*$email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if( !empty($GatePassRequest['reporting_manager_email']) ){
                    $to_emails[] = $GatePassRequest['reporting_manager_email'];
                }
                if( !empty($GatePassRequest['hod_email']) ){
                    $to_emails[] = $GatePassRequest['hod_email'];
                }

                $to_emails_new = [];
                foreach( $to_emails as $i => $e ){
                    if( $e == $GatePassRequest['employee_work_email'] ){
                        unset( $to_emails[$i] );
                    }
                }

                $to_emails[] = 'developer3@healthgenie.in';*/

                /*if( !empty( $to_emails ) ){
                
                    $email->setTo($to_emails);
                    $email->setSubject('New Gate Pass Request');
                    $email->setMessage('
                            You have recieved a Gate Pass request, 
                            <br>
                            The details are below. 
                            <br> 
                            <div>
                                <p>Employee Name: '.$GatePassRequest["employee_name"].'</p>
                                <p>Employee Code: '.$GatePassRequest["internal_employee_id"].'</p>
                                <p>Gate Pass Type: '.$GatePassRequest["gate_pass_type"].'</p>
                                <p>Department: '.$GatePassRequest["department_name"].'</p>
                                <p>Date: '.$GatePassRequest["gate_pass_date"].'</p>
                                <p>Minutes/Hours: '.$GatePassRequest["gate_pass_hours"].'</p>
                                <p>Reason: '.$GatePassRequest["reason"].'</p>
                            </div>
                            <br> 
                            <a style="margin: 10px 20px" href="'.base_url("/backend/administrative/gate-pass-approval?action=approve&id=").$lastInsertID.'">Approve</a> 
                            <a style="margin: 10px 20px" href="'.base_url("/backend/administrative/gate-pass-approval?action=reject&id=").$lastInsertID.'">Reject</a>
                        ');
                    if( $email->send() ){
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Request Submitted Successfully';
                    }else{
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Request Submitted Successfully, but email not send';
                    }
                }else{
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Request Submitted Successfully, but email was not send since there was no email address to send';
                }*/

                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Request Submitted Successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function getAllGatePassRequests()
    {
        #this is database details
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $GatePassRequestsModel = new GatePassRequestsModel();
        $GatePassRequests = $GatePassRequestsModel
            ->select('gate_pass_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select('d.department_name as department_name')
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->join('employees as e1', 'e1.id = gate_pass_requests.employee_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = gate_pass_requests.reviewed_by', 'left')
            ->where('gate_pass_requests.employee_id =', $current_employee_id)
            ->orderBy('gate_pass_requests.date_time', 'DESC')
            ->findAll();

        if (!empty($GatePassRequests)) {
            foreach ($GatePassRequests as $index => $row) {

                $gate_pass_date_formatted = !empty($row['gate_pass_date']) ? date('d M Y', strtotime($row['gate_pass_date'])) : '-';
                $gate_pass_date_ordering = !empty($row['gate_pass_date']) ? strtotime($row['gate_pass_date']) : '0';
                $GatePassRequests[$index]['gate_pass_date'] = array('formatted' => $gate_pass_date_formatted, 'ordering' => $gate_pass_date_ordering);

                $reviewed_date_time_formatted = !empty($row['reviewed_date_time']) ? date('d M Y h:i A', strtotime($row['reviewed_date_time'])) : '-';
                $reviewed_date_time_ordering = !empty($row['reviewed_date_time']) ? strtotime($row['reviewed_date_time']) : '0';
                $GatePassRequests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($row['date_time']) ? date('d M Y h:i A', strtotime($row['date_time'])) : '-';
                $date_time_ordering = !empty($row['date_time']) ? strtotime($row['date_time']) : '0';
                $GatePassRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $GatePassRequests[$index]['gate_pass_hours'] = !empty($row['gate_pass_hours']) ? date('h:i A', strtotime($row['gate_pass_hours'])) : '-';
            }
        }

        echo json_encode($GatePassRequests);
    }

    public function getGatePassRequestToday()
    {
        #this is database details
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $date = date('Y-m-d');
        $GatePassRequestsModel = new GatePassRequestsModel();
        $GatePassRequests = $GatePassRequestsModel
            ->select('gate_pass_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select('d.department_name as department_name')
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->join('employees as e1', 'e1.id = gate_pass_requests.employee_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = gate_pass_requests.reviewed_by', 'left')
            ->where('gate_pass_requests.employee_id =', $current_employee_id)
            ->where('gate_pass_requests.gate_pass_date =', $date)
            ->orderBy('gate_pass_requests.date_time', 'DESC')
            ->findAll();

        echo !empty($GatePassRequests) ? 'Available' : 'Not Available';
    }
}
