<?php

namespace App\Controllers\Approval;

use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
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

        $current_user = $this->session->get('current_user');

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        #if( admin_module_accessible( $current_user['role'], 'leave', 'approval' ) ){
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        $data = [
            'page_title'     => 'Gate Pass Approval',
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
            $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

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
            $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $date_45_days_before . "')");
            $EmployeeModel->groupEnd();

            $EmployeeModel->orderby('c.company_short_name', 'ASC');
            $EmployeeModel->orderby('d.department_name', 'ASC');
            $EmployeeModel->orderby('employees.first_name', 'ASC');
            $Employees = $EmployeeModel->findAll();
            $data['Employees'] = $Employees;
        }

        $GatePassRequestsModel = new GatePassRequestsModel();
        $data['statuses'] = $GatePassRequestsModel->distinct()->select('status')->orderBy('status', 'ASC')->findAll();

        return view('Administrative/GatePassApproval', $data);
        #}else{
        #return redirect()->to(base_url('/unauthorised'));
        #}

    }

    public function getAllGatePassApprovalRequests()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $from_date      = isset($params['from_date']) ? $params['from_date'] : '';
        $status         = isset($params['status']) ? $params['status'] : '';
        $to_date        = isset($params['to_date']) ? $params['to_date'] : '';
        $reporting_to_me   = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $current_user = $this->session->get('current_user');

        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];

        $GatePassRequestsModel = new GatePassRequestsModel();

        $GatePassRequestsModel
            ->select('gate_pass_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select('d.department_name as department_name')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->join('employees as e1', 'e1.id = gate_pass_requests.employee_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = gate_pass_requests.reviewed_by', 'left');

        if ($reporting_to_me == 'yes') {
            $GatePassRequestsModel->groupStart();
            $GatePassRequestsModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $GatePassRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $GatePassRequestsModel->where('e1.reporting_manager_id !=', $current_user['employee_id']);
            $GatePassRequestsModel->groupStart();
            $GatePassRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $GatePassRequestsModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $GatePassRequestsModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $GatePassRequestsModel->groupStart();
            $GatePassRequestsModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $GatePassRequestsModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $GatePassRequestsModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $GatePassRequestsModel->groupEnd();
        }

        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $GatePassRequestsModel->whereIn('e1.company_id', $company_id);
        }
        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $GatePassRequestsModel->whereIn('e1.department_id', $department_id);
        }
        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $GatePassRequestsModel->whereIn('e1.id', $employee_id);
        }
        if (!empty($status) && !in_array('all_status', $status)) {
            $GatePassRequestsModel->whereIn('gate_pass_requests.status', $status);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $GatePassRequestsModel->groupStart();
            $GatePassRequestsModel->where("gate_pass_requests.gate_pass_date between '" . $from_date . "' and '" . $to_date . "'");
            $GatePassRequestsModel->groupEnd();
        }

        $GatePassRequestsModel->where('e1.id !=', $current_user['employee_id']);
        $GatePassRequestsModel->orderBy('gate_pass_requests.date_time', 'DESC');

        $GatePassRequests = $GatePassRequestsModel->findAll();

        /*echo '<pre>';
        print_r($GatePassRequestsModel->getLastQuery()->getQuery());
        echo '</pre>';
        die();*/

        foreach ($GatePassRequests as $index => $dataRow) {
            $GatePassRequests[$index]['actions'] = '';

            $gate_pass_date_formatted = !empty($dataRow['gate_pass_date']) ? date('d M Y', strtotime($dataRow['gate_pass_date'])) : '-';
            $gate_pass_date_ordering = !empty($dataRow['gate_pass_date']) ? strtotime($dataRow['gate_pass_date']) : '0';
            $GatePassRequests[$index]['gate_pass_date'] = array('formatted' => $gate_pass_date_formatted, 'ordering' => $gate_pass_date_ordering);

            $date_time_formatted = !empty($dataRow['date_time']) ? date('d M Y h:i A', strtotime($dataRow['date_time'])) : '-';
            $date_time_ordering = !empty($dataRow['date_time']) ? strtotime($dataRow['date_time']) : '0';
            $GatePassRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

            $reviewed_date_formatted = !empty($dataRow['reviewed_date']) ? date('d M Y', strtotime($dataRow['reviewed_date'])) : '-';
            $reviewed_date_ordering = !empty($dataRow['reviewed_date']) ? strtotime($dataRow['reviewed_date']) : '0';
            $GatePassRequests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);

            $GatePassRequests[$index]['gate_pass_hours'] = !empty($dataRow['gate_pass_hours']) ? date('h:i A', strtotime($dataRow['gate_pass_hours'])) : '-';
        }
        echo json_encode($GatePassRequests);
    }

    public function getGatePassRequest()
    {
        $response_array = array();
        $validation = $this->validate(
            [
                'gate_pass_id'  =>  [
                    'rules'         =>  'required|is_not_unique[gate_pass_requests.id]',
                    'errors'        =>  [
                        'required'  => 'Gate Pass ID is required',
                        'is_not_unique' => 'This Request does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('gate_pass_id');
        } else {
            $gate_pass_id   = $this->request->getVar('gate_pass_id');

            $GatePassRequestsModel = new GatePassRequestsModel();

            $GatePassRequest = $GatePassRequestsModel
                ->select('gate_pass_requests.*')
                ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
                ->select('d.department_name as department_name')
                ->select('c.company_short_name as company_short_name')
                ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
                ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
                ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
                ->join('employees as e1', 'e1.id = gate_pass_requests.employee_id', 'left')
                ->join('departments as d', 'd.id = e1.department_id', 'left')
                ->join('companies as c', 'c.id = e1.company_id', 'left')
                ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
                ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                ->join('employees as e4', 'e4.id = gate_pass_requests.reviewed_by', 'left')
                ->where('gate_pass_requests.id =', $gate_pass_id)
                ->first();

            /*echo '<pre>';
            print_r($GatePassRequest);
            echo '</pre>';
            die(); */

            $GatePassRequest['gate_pass_date'] = !empty($GatePassRequest['gate_pass_date']) ? date('d-M-Y', strtotime($GatePassRequest['gate_pass_date'])) : '';
            $GatePassRequest['date_time'] = !empty($GatePassRequest['date_time']) ? date('d-M-Y h:i A', strtotime($GatePassRequest['date_time'])) : '';
            $GatePassRequest['reviewed_date'] = !empty($GatePassRequest['reviewed_date']) ? date('d-M-Y', strtotime($GatePassRequest['reviewed_date'])) : '';
            $GatePassRequest['gate_pass_hours'] = !empty($GatePassRequest['gate_pass_hours']) ? date('h:i A', strtotime($GatePassRequest['gate_pass_hours'])) : '';

            if (empty($GatePassRequest)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Gate Pass Request Found';
                $response_array['response_data']['gate_pass_data'] = $GatePassRequest;
            }
        }
        #print_r($response_array);
        #echo json_encode($response_array);
        return $this->response->setJSON($response_array);
    }

    public function updateGatePassRequest()
    {
        $response_array = array();
        $rules = [
            'gate_pass_id'  =>  [
                'rules'         =>  'required|is_not_unique[gate_pass_requests.id]',
                'errors'        =>  [
                    'required'  => 'Gate Pass ID is required',
                    'is_not_unique' => 'This Request does not exist in our database'
                ]
            ],
            'status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Click on Approve or Reject Button',
                ]
            ]
        ];

        if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'rejected') {
            $rules['remarks'] =  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please specify why are you rejecting this request',
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
            $gate_pass_id   = $this->request->getPost('gate_pass_id');

            $GatePassRequestsModel = new GatePassRequestsModel();
            $current_status = $GatePassRequestsModel->select('status')->where('id', $gate_pass_id)->first()['status'];
            if ($current_status !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This request is not in pending status';
            } else {
                $current_user_employee_id = session()->get('current_user')['employee_id'];
                $current_date = date('Y-m-d');
                $data = [
                    'status'                => $this->request->getPost('status'),
                    'reviewed_by'           => $current_user_employee_id,
                    'reviewed_date'         => $current_date,
                    'remarks'               => $this->request->getPost('remarks'),
                ];
                $GatePassRequestsModel = new GatePassRequestsModel();
                $query = $GatePassRequestsModel->update($gate_pass_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Gate Pass ' . ucfirst($this->request->getPost("status"));
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
