<?php

namespace App\Controllers\Approval;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\UserLoanModel;
use App\Models\UserLoanEmiModel;
use App\Controllers\BaseController;
use App\Models\UserLoanRevisionModel;

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

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        if (!in_array($current_employee_id, [40, 1, 93, 293])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        // if( user_module_accessible( $current_user['role'], 'loan' ) ){
        $data = [
            'page_title'            => 'Loan Approval',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'Companies'             => $Companies,
        ];

        $where_company = " ";
        if (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && !in_array('all_companies', $_REQUEST['company'])) {
            $where_company .= " and d.company_id in ('" . implode("', '", $_REQUEST['company']) . "')";
        } else {
            $where_company .= " ";
        }
        $sql = "select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null " . $where_company . " order by c.company_short_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['departments_not_found'] = "There was an error fetching departments from database";
        } else {
            $data['Departments'] = $query->getResultArray();
        }

        $where_department = " ";
        if (isset($_REQUEST['company']) && !empty($_REQUEST['company']) && !in_array('all_companies', $_REQUEST['company'])) {
            $where_department .= " and e.company_id in ('" . implode("', '", $_REQUEST['company']) . "')";
        } else {
            $where_department .= " ";
        }

        if (isset($_REQUEST['department']) && !empty($_REQUEST['department']) && !in_array('all_departments', $_REQUEST['department'])) {
            $where_department .= " and e.department_id in ('" . implode("', '", $_REQUEST['department']) . "')";
        } else {
            $where_department .= " ";
        }
        $sql = "select 
            e.id as id, 
            e.internal_employee_id as internal_employee_id, 
            trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
            d.department_name as department_name, 
            c.company_short_name as company_short_name 
            from employees e 
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id 
            where e.id is not null " . $where_department . " order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }

        return view('Administrative/LoanApproval', $data);
        /*}else{
            return redirect()->to(base_url('/unauthorised'));
        }*/
    }

    public function getAllLoanApprovalRequests()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";

        $current_employee_id = $this->session->get('current_user')['employee_id'];
        $UserLoanModel = new UserLoanModel();
        $UserLoanModel->select('loan_requests.*')
            ->select('e.internal_employee_id as internal_employee_id')
            ->select('trim(concat(e.first_name, " ", e.last_name)) as employee_name')
            ->select('trim(concat(e2.first_name, " ", e2.last_name)) as reviewed_by_name')
            ->select('trim(concat(e3.first_name, " ", e3.last_name)) as disbursed_by_name')
            ->select('c.company_short_name as company_short_name')
            ->select('dep.department_name as department_name')
            ->join('employees as e', 'e.id = loan_requests.employee_id', 'left')
            ->join('employees as e2', 'e2.id = loan_requests.reviewed_by', 'left')
            ->join('employees as e3', 'e3.id = loan_requests.disbursed_by', 'left')
            ->join('companies as c', 'c.id = e.company_id', 'left')
            ->join('departments as dep', 'dep.id = e.department_id', 'left');

        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $UserLoanModel->whereIn('c.id', $company_id);
        }

        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $UserLoanModel->whereIn('dep.id', $department_id);
        }

        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $UserLoanModel->whereIn('loan_requests.employee_id', $employee_id);
        }

        $UserLoan = $UserLoanModel->findAll();
        echo json_encode($UserLoan);
    }

    public function getLoanApprovalRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'loan_id'  =>  [
                    'rules'         =>  'required|is_not_unique[loan_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Loan ID is required',
                        'is_not_unique' => 'This Loan is does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('loan_id');
        } else {
            $loan_id   = $this->request->getPost('loan_id');
            $UserLoanModel = new UserLoanModel();
            $UserLoanModel->select('loan_requests.*')
                ->select('e.internal_employee_id as internal_employee_id')
                ->select('trim(concat(e.first_name, " ", e.last_name)) as employee_name')
                ->select('trim(concat(e2.first_name, " ", e2.last_name)) as reviewed_by_name')
                ->select('c.company_short_name as company_short_name')
                ->select('dep.department_name as department_name')
                ->join('employees as e', 'e.id = loan_requests.employee_id', 'left')
                ->join('employees as e2', 'e2.id = loan_requests.reviewed_by', 'left')
                ->join('companies as c', 'c.id = e.company_id', 'left')
                ->join('departments as dep', 'dep.id = e.department_id', 'left')
                ->where('loan_requests.id', $loan_id);
            $loan_request = $UserLoanModel->first();
            $loan_request['date_time'] = !empty($loan_request['date_time']) ? date('d-M-Y h:i A', strtotime($loan_request['date_time'])) : '';
            $loan_request['reviewed_date'] = !empty($loan_request['reviewed_date']) ? date('d-M-Y', strtotime($loan_request['reviewed_date'])) : '';
            $loan_request['disbursed_date'] = !empty($loan_request['disbursed_date']) ? date('d-M-Y', strtotime($loan_request['disbursed_date'])) : '';
            $loan_request['review_status_html'] = $loan_request['review_status'];

            if (empty($loan_request)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Loan Found';
                $response_array['response_data']['loan_data'] = $loan_request;
            }
        }
        return $this->response->setJSON($response_array);
    }

    /*public function emi_calculator($p, $r, $t) { 
        $emi; 
        #one month interest 
        $r = $r / (12 * 100);
        #one month period 
        // $t = $t * 12;
        $emi = ($p * $r * pow(1 + $r, $t)) / (pow(1 + $r, $t) - 1);
        return ($emi); 
    } */

    public function approveLoanRequest()
    {
        $response_array = array();
        $rules = [
            'loan_id'  =>  [
                'rules'         =>  'required|is_not_unique[loan_requests.id]',
                'errors'        =>  [
                    'required'  => 'Loan ID is required',
                    'is_not_unique' => 'This Loan is does not exist in our database'
                ]
            ],
            'review_status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please select a status',
                ]
            ],
            'remarks'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Give some remarks',
                ]
            ],
        ];
        if ($this->request->getPost('review_status') == 'disbursed') {
            $rules['deduct_from_month'] = [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Select starting month of deduction',
                ]
            ];
        }
        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {

            $loan_id   = $this->request->getPost('loan_id');
            $UserLoanModel = new UserLoanModel();
            $oldData = $UserLoanModel->find($loan_id);
            $oldData['loan_id'] = $oldData['id'];
            unset($oldData['id']);
            $oldData['revised_by'] = $this->session->get('current_user')['employee_id'];
            $UserLoanRevisionModel = new UserLoanRevisionModel();
            $revisionQuery = $UserLoanRevisionModel->insert($oldData);
            if (!$revisionQuery) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Failed to save revision';
            } else {
                $data = [];

                if ($this->request->getPost('review_status') == 'disbursed') {
                    $data['disbursed']          = 'yes';
                    $data['disbursed_date']     = date('Y-m-d');
                    $data['disbursed_by']       = $this->session->get('current_user')['employee_id'];
                    $data['disbursal_remarks']  = $this->request->getPost('remarks');
                    $data['deduct_from_month']  = $this->request->getPost('deduct_from_month');

                    $deduct_from_month  = $this->request->getPost('deduct_from_month');
                    $emi_tenure     = $oldData['emi_tenure'];
                    $loan_amount    = $oldData['loan_amount'];
                    $emi            = round($loan_amount / $emi_tenure, 2);
                    $month = 0;
                    while ($month < $emi_tenure) {
                        $emi_data = [
                            'loan_id'           => $loan_id,
                            'year'              => date('Y', strtotime($deduct_from_month . ' + ' . $month . ' months')),
                            'month'             => date('m', strtotime($deduct_from_month . ' + ' . $month . ' months')),
                            'principle_amount'  => $loan_amount,
                            'emi'               => $emi,
                        ];
                        $UserLoanEmiModel = new UserLoanEmiModel();
                        $UserLoanEmiModel->insert($emi_data);
                        $month++;
                    }
                } else {
                    $data = [
                        'review_status' => $this->request->getPost('review_status'),
                        'remarks'       => $this->request->getPost('remarks'),
                        'reviewed_by'   => $this->session->get('current_user')['employee_id'],
                        'reviewed_date' => date('Y-m-d'),
                    ];
                }

                $UserLoanModel = new UserLoanModel();
                $query = $UserLoanModel->update($loan_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $UserLoanModel = new UserLoanModel();
                    $LoanRequest = $UserLoanModel
                        ->select(' loan_requests.*')
                        ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
                        ->select('e.internal_employee_id as internal_employee_id')
                        ->select('e.work_email as employee_email')
                        ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
                        ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as disbursed_by_name")
                        ->select('d.department_name as department_name')
                        ->join('employees as e', 'e.id = loan_requests.employee_id', 'left')
                        ->join('employees as e2', 'e2.id = loan_requests.reviewed_by', 'left')
                        ->join('employees as e3', 'e3.id = loan_requests.disbursed_by', 'left')
                        ->join('departments as d', 'd.id = e.department_id', 'left')
                        ->where('loan_requests.id =', $loan_id)
                        ->first();

                    $email = \Config\Services::email();
                    $email->setFrom('app.hrm@healthgenie.in', 'HRM');
                    $to_emails = array('developer3@healthgenie.in');
                    if (!empty($LoanRequest['employee_email'])) {
                        $to_emails[] = $LoanRequest['employee_email'];
                    }

                    $email->setTo($to_emails);
                    $email->setSubject('Loan ' . $this->request->getPost("review_status"));
                    $email->setMessage('
                            You have an update on your loan request, 
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
                                <p>Status: ' . $LoanRequest["review_status"] . '</p>
                                <p>Reviewed by: ' . $LoanRequest["reviewed_by_name"] . '</p>
                            </div>
                        ');
                    if ($email->send()) {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Loan request updated Successfully';
                    } else {
                        $response_array['response_type'] = 'success';
                        $response_array['response_description'] = 'Loan request updated Successfully, but email not send';
                    }
                }
            }
        }

        return $this->response->setJSON($response_array);
    }
}
