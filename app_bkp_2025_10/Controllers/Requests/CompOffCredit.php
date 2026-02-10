<?php

namespace App\Controllers\Requests;

use App\Models\CompOffCreditModel;
use App\Controllers\BaseController;

class CompOffCredit extends BaseController
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
        $data = [
            'page_title'            => 'My Comp Off Requests',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            // 'employees'             => $employees,
        ];
        return view('User/CompOffCreditRequests', $data);
    }

    public function CreateCOMPOFFCreditRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'comp_off_credit_request_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date correctly',
                    ]
                ],
                'comp_off_credit_request_duty_assigner'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select who assigned your duty',
                    ]
                ],
                'comp_off_credit_request_reason'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please specify the reason of this duty',
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
                'working_date'      => $this->request->getPost('comp_off_credit_request_date'),
                'assigned_by'       => $this->request->getPost('comp_off_credit_request_duty_assigner'),
                'reason'            => $this->request->getPost('comp_off_credit_request_reason'),
            ];



            $CompOffCreditModel = new CompOffCreditModel();
            $check_existing = $CompOffCreditModel->where('working_date =', $data['working_date'])->whereIn('status', ['stage_1', 'pending', 'approved'])->where('employee_id =', $data['employee_id'])->first();
            if (!empty($check_existing)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Duplicate Request';
                $this->validator->setError('comp_off_credit_request_date', 'Duplicate request, Please select another date');
                $errors = $this->validator->getErrors();
                $response_array['response_data']['validation'] = $errors;
                return $this->response->setJSON($response_array);
                die();
            }

            #Begin:: Attachment
            $attachment = $this->request->getFile('compoff_attachment');
            if ($attachment->isValid() && ! $attachment->hasMoved()) {
                $upload_folder = WRITEPATH . 'uploads/' . date('Y') . '/' . date('m');
                $uploaded = $attachment->move($upload_folder);
                if ($uploaded) {
                    $data['attachment'] = str_replace(WRITEPATH, "/", $upload_folder . '/' . $attachment->getName());
                }
            }
            #End:: Attachment


            $CompOffCreditModel = new CompOffCreditModel();
            $query = $CompOffCreditModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #send Email
                $lastInsertID = $CompOffCreditModel->getInsertID();
                $CompOffCreditModel = new CompOffCreditModel();
                $CompOffCreditRequest = $CompOffCreditModel
                    ->select('comp_off_credit_requests.*')
                    ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
                    ->select('e1.internal_employee_id as internal_employee_id')
                    ->select('e1.work_email as employee_work_email')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as assigned_by_name")
                    ->join('employees as e1', 'e1.id = comp_off_credit_requests.employee_id', 'left')
                    ->join('departments as d', 'd.id = e1.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->join('employees as e4', 'e4.id = comp_off_credit_requests.assigned_by', 'left')
                    ->where('comp_off_credit_requests.id =', $lastInsertID)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                /*if( !empty($CompOffCreditRequest['reporting_manager_email']) ){
                    $to_emails[] = $CompOffCreditRequest['reporting_manager_email'];
                }
                if( !empty($CompOffCreditRequest['hod_email']) ){
                    $to_emails[] = $CompOffCreditRequest['hod_email'];
                }*/

                $to_emails_new = [];
                foreach ($to_emails as $i => $e) {
                    if ($e == $CompOffCreditRequest['employee_work_email']) {
                        unset($to_emails[$i]);
                    }
                }

                $to_emails[] = 'developer3@healthgenie.in';

                if (!empty($to_emails)) {


                    $email->setTo($to_emails);
                    $email->setSubject('New Comp Off Credit Request');
                    $email->setMessage('
                            You have recieved a Comp Off Credit request, 
                            <br>
                            The details are below. 
                            <br> 
                            <div>
                                <p>Employee Name: ' . $CompOffCreditRequest["employee_name"] . '</p>
                                <p>Employee Code: ' . $CompOffCreditRequest["internal_employee_id"] . '</p>
                                <p>Date of Working: ' . $CompOffCreditRequest["working_date"] . '</p>
                                <p>Department: ' . $CompOffCreditRequest["department_name"] . '</p>
                                <p>Reason: ' . $CompOffCreditRequest["reason"] . '</p>
                            </div>
                            <br> 
                            <a style="margin: 10px 20px" href="' . base_url("/backend/administrative/comp-off-credit-approval?action=approve&id=") . $lastInsertID . '">Approve</a> 
                            <a style="margin: 10px 20px" href="' . base_url("/backend/administrative/comp-off-credit-approval?action=reject&id=") . $lastInsertID . '">Reject</a>
                        ');
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

    public function getAllCompOffCreditRequests()
    {

        #this is database details
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditRequests = $CompOffCreditModel
            ->select('comp_off_credit_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select('d.department_name as department_name')
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by_name")
            ->join('employees as e1', 'e1.id = comp_off_credit_requests.employee_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = comp_off_credit_requests.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = comp_off_credit_requests.assigned_by', 'left')
            ->where('comp_off_credit_requests.employee_id =', $current_employee_id)
            ->orderBy('comp_off_credit_requests.date_time', 'DESC')
            ->findAll();

        if (!empty($CompOffCreditRequests)) {
            foreach ($CompOffCreditRequests as $index => $row) {

                $working_date_formatted = !empty($row['working_date']) ? date('d M Y', strtotime($row['working_date'])) : '';
                $working_date_ordering = !empty($row['working_date']) ? strtotime($row['working_date']) : '0';
                $working_day = !empty($row['working_date']) ? date('l', strtotime($row['working_date'])) : '-';
                $CompOffCreditRequests[$index]['working_date'] = array('formatted' => $working_date_formatted, 'ordering' => $working_date_ordering, 'day' => $working_day);
                $CompOffCreditRequests[$index]['working_day'] = $working_day;

                $expiry_date_formatted = !empty($row['working_date']) ? date('d M Y', strtotime($row['working_date'] . ' + 90 days')) : '';
                $expiry_date_ordering = !empty($row['working_date']) ? strtotime($row['working_date'] . ' + 90 days') : '0';
                $CompOffCreditRequests[$index]['expiry_date'] = array('formatted' => $expiry_date_formatted, 'ordering' => $expiry_date_ordering);

                $stage_1_reviewed_date_formatted = !empty($row['stage_1_reviewed_date']) ? date('d M Y h:i A', strtotime($row['stage_1_reviewed_date'])) : '';
                $stage_1_reviewed_date_ordering = !empty($row['stage_1_reviewed_date']) ? strtotime($row['stage_1_reviewed_date']) : '0';
                $CompOffCreditRequests[$index]['stage_1_reviewed_date'] = array('formatted' => $stage_1_reviewed_date_formatted, 'ordering' => $stage_1_reviewed_date_ordering);

                $reviewed_date_time_formatted = !empty($row['reviewed_date_time']) ? date('d M Y h:i A', strtotime($row['reviewed_date_time'])) : '';
                $reviewed_date_time_ordering = !empty($row['reviewed_date_time']) ? strtotime($row['reviewed_date_time']) : '0';
                $CompOffCreditRequests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($row['date_time']) ? date('d M Y h:i A', strtotime($row['date_time'])) : '';
                $date_time_ordering = !empty($row['date_time']) ? strtotime($row['date_time']) : '0';
                $CompOffCreditRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $CompOffCreditRequests[$index]['gate_pass_hours'] = !empty($row['gate_pass_hours']) ? date('h:i A', strtotime($row['gate_pass_hours'])) : '';
            }
        }

        return $this->response->setJSON($CompOffCreditRequests);
    }
}
