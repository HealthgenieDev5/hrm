<?php

namespace App\Controllers\Requests;

use App\Models\UserLoanModel;
use App\Models\UserLoanEmiModel;
use App\Controllers\BaseController;

class Loan extends BaseController
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
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        // if( user_module_accessible( $current_user['role'], 'leave' ) ){
        $data = [
            'page_title'            => 'My Loans',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
        ];
        return view('User/UserLoan', $data);
        /*}else{
            return redirect()->to(base_url('/unauthorised'));
        }*/
    }

    public function getAllLoanRequests()
    {
        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $UserLoanModel = new UserLoanModel();
        $UserLoan = $UserLoanModel->where('employee_id =', $current_employee_id)->findAll();
        echo json_encode($UserLoan);
    }

    public function createLoanRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'loan_amount'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Loan Amount is required',
                    ]
                ],
                'emi_tenure'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'EMI Tenure is required',
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
                'loan_amount'       => $this->request->getPost('loan_amount'),
                'emi_tenure'        => $this->request->getPost('emi_tenure'),
                'reason'            => $this->request->getPost('reason'),
                'note'              => $this->request->getPost('note'),
                'employee_id'       => $this->session->get('current_user')['employee_id'],
            ];
            $UserLoanModel = new UserLoanModel();
            $query = $UserLoanModel->insert($data);
            if (!$query) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                #send Email
                $lastInsertID = $UserLoanModel->getInsertID();
                $UserLoanModel = new UserLoanModel();
                $LoanRequest = $UserLoanModel
                    ->select(' loan_requests.*')
                    ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                    ->select('e.internal_employee_id as internal_employee_id')
                    ->select('d.department_name as department_name')
                    ->select('e2.work_email as reporting_manager_email')
                    ->select('e3.work_email as hod_email')
                    ->join('employees as e', 'e.id = loan_requests.employee_id', 'left')
                    ->join('departments as d', 'd.id = e.department_id', 'left')
                    ->join('employees as e2', 'e2.id = e.reporting_manager_id', 'left')
                    ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                    ->where('loan_requests.id =', $lastInsertID)
                    ->first();

                $email = \Config\Services::email();
                $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                #$email->setReplyTo('payroll@healthgenie.in', 'HRM');
                $to_emails = array('developer3@healthgenie.in');
                if (!empty($LoanRequest['reporting_manager_email'])) {
                    $to_emails[] = $LoanRequest['reporting_manager_email'];
                }
                if (!empty($LoanRequest['hod_email'])) {
                    $to_emails[] = $LoanRequest['hod_email'];
                }
                $email->setTo($to_emails);
                $email->setSubject('New Loan Request');
                $email->setMessage('
                        You have recieved a loan request, 
                        <br>
                        The details are below. 
                        <br> 
                        <div>
                            <p>Employee Name: ' . $LoanRequest["employee_name"] . '</p>
                            <p>Employee Code: ' . $LoanRequest["internal_employee_id"] . '</p>
                            <p>Department: ' . $LoanRequest["department_name"] . '</p>
                            <p>Amount: ' . $LoanRequest["loan_amount"] . '</p>
                            <p>EMI Tenure: ' . $LoanRequest["emi_tenure"] . '</p>
                            <p>Reason: ' . $LoanRequest["reason"] . '</p>
                            <p>Note: ' . $LoanRequest["note"] . '</p>
                        </div>
                        <br> 
                        <a style="margin: 10px 20px" href="' . base_url("/backend/administrative/loan-approval?action=view&id=") . $lastInsertID . '">View Loan Request</a> 
                    ');
                if ($email->send()) {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Loan request created Successfully';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Loan request created Successfully, but email not send';
                }
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function getLoanEmi($loan_id)
    {
        $UserLoanEmiModel = new UserLoanEmiModel();
        $LoanEmi = $UserLoanEmiModel->where('loan_id =', $loan_id)->findAll();
        if (!empty($LoanEmi)) {
            foreach ($LoanEmi as $i => $emi) {
                $emi_date_temp = $emi['year'] . "-" . $emi['month'] . "-01";
                $LoanEmi[$i]['emi_month'] = date('M Y', strtotime($emi_date_temp));
            }
        }
        echo json_encode($LoanEmi);
    }
}
