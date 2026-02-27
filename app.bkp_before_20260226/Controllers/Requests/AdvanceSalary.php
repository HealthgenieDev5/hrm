<?php

namespace App\Controllers\Requests;

use App\Models\AdvanceSalaryModel;
use App\Controllers\BaseController;
use App\Models\AdvanceSalaryEmiModel;

class AdvanceSalary extends BaseController
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
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        // if( user_module_accessible( $current_user['role'], 'leave' ) ){
        $data = [
            'page_title'            => 'My Advance Salary Requests',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('User/AdvanceSalary', $data);
        /*}else{
            return redirect()->to(base_url('/unauthorised'));
        }*/
    }

    public function getAllAdvanceSalaryRequests()
    {
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $AdvanceSalaryModel = new AdvanceSalaryModel();
        $AdvanceSalary = $AdvanceSalaryModel->where('employee_id =', $current_employee_id)->findAll();
        echo json_encode($AdvanceSalary);

        // return $this->response->setJSON($UserLoan);
    }

    public function createAdvanceSalaryRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'amount'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Amount is required',
                    ]
                ],
                'reason'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Reason is required',
                    ]
                ],
                'note'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Note is required',
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
                'amount'            => $this->request->getPost('amount'),
                'reason'            => $this->request->getPost('reason'),
                'note'              => $this->request->getPost('note'),
                'employee_id'       => $this->session->get('current_user')['employee_id'],
            ];
            $AdvanceSalaryModel = new AdvanceSalaryModel();
            $query = $AdvanceSalaryModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #send Email
                $lastInsertID = $AdvanceSalaryModel->getInsertID();
                $AdvanceSalaryModel = new AdvanceSalaryModel();
                $AdvanceSalaryRequest = $AdvanceSalaryModel
                    ->select(' advance_salary_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->join('employees as e', 'e.id = advance_salary_requests.employee_id', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->where('advance_salary_requests.id =', $lastInsertID)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($AdvanceSalaryRequest['reporting_manager_email'])) {
                    $to_emails[] = $AdvanceSalaryRequest['reporting_manager_email'];
                }
                if (!empty($AdvanceSalaryRequest['hod_email'])) {
                    $to_emails[] = $AdvanceSalaryRequest['hod_email'];
                }
                $email->setTo($to_emails);
                $email->setSubject('New Advance Salary Request');
                $email->setMessage('
                        You have recieved an advance salary request, 
                        <br>
                        The details are below. 
                        <br> 
                        <div>
                            <p>Employee Name: ' . $AdvanceSalaryRequest["employee_name"] . '</p>
                            <p>Employee Code: ' . $AdvanceSalaryRequest["internal_employee_id"] . '</p>
                            <p>Department: ' . $AdvanceSalaryRequest["department_name"] . '</p>
                            <p>Amount: ' . $AdvanceSalaryRequest["amount"] . '</p>
                            <p>EMI Tenure: ' . $AdvanceSalaryRequest["emi_tenure"] . '</p>
                            <p>Reason: ' . $AdvanceSalaryRequest["reason"] . '</p>
                            <p>Note: ' . $AdvanceSalaryRequest["note"] . '</p>
                        </div>
                        <br> 
                        <a style="margin: 10px 20px" href="' . base_url("/backend/administrative/advance-salary-approval?action=view&id=") . $lastInsertID . '">View Advance Salary Request</a> 
                    ');
                if ($email->send()) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Advance salary request created Successfully';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Advance salary request created Successfully, but email not send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function getAdvanceSalaryEmi($advance_salary_request_id)
    {
        $AdvanceSalaryEmiModel = new AdvanceSalaryEmiModel();
        $AdvanceSalaryEmi = $AdvanceSalaryEmiModel->where('advance_salary_request_id =', $advance_salary_request_id)->findAll();
        if (!empty($AdvanceSalaryEmi)) {
            foreach ($AdvanceSalaryEmi as $i => $emi) {
                $emi_date_temp = $emi['year'] . "-" . $emi['month'] . "-01";
                $AdvanceSalaryEmi[$i]['emi_month'] = date('M Y', strtotime($emi_date_temp));
            }
        }
        echo json_encode($AdvanceSalaryEmi);
    }
}
