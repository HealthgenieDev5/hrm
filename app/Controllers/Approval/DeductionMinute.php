<?php

namespace App\Controllers\Approval;

use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\DeductionModel;
use App\Models\DepartmentModel;
use App\Controllers\BaseController;
use App\Controllers\Attendance\Processor;

class DeductionMinute extends BaseController
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
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl', 'manager'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        $data = [
            'page_title'     => 'Deduction Approval',
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

            $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
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

        $DeductionModel = new DeductionModel();
        $data['statuses'] = $DeductionModel->distinct()->select('current_status')->orderBy('current_status', 'ASC')->findAll();

        return view('Administrative/DeductionApprovalRequests', $data);
    }

    public function getAllDeductionApprovalRequests()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $deduction_month = isset($params['deduction_month']) ? $params['deduction_month'] : '';
        $status         = isset($params['status']) ? $params['status'] : '';
        $reporting_to_me = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $current_user = $this->session->get('current_user');

        $superuser = ["admin", "superuser"];
        $current_user_role = $current_user['role'];

        $DeductionModel = new DeductionModel();

        $DeductionModel
            ->select('deduction_minutes .*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select('c.company_short_name as company_short_name')
            ->select('d.department_name as department_name')
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as department_hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as deducted_by_name")
            ->join('employees as e1', 'e1.id = deduction_minutes.employee_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = deduction_minutes.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = deduction_minutes.deducted_by', 'left')
            ->join('companies as c', 'c.id = e1.company_id', 'left');

        if ($reporting_to_me == 'yes') {
            $DeductionModel->groupStart();
            $DeductionModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $DeductionModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $DeductionModel->where('e1.reporting_manager_id !=', $current_user['employee_id']);
            $DeductionModel->groupStart();
            $DeductionModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $DeductionModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $DeductionModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $DeductionModel->groupStart();
            $DeductionModel->where('e1.reporting_manager_id =', $current_user['employee_id']);
            $DeductionModel->orWhere('d.hod_employee_id =', $current_user['employee_id']);
            $DeductionModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $DeductionModel->groupEnd();
        }

        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $DeductionModel->whereIn('e1.company_id', $company_id);
        }
        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $DeductionModel->whereIn('e1.department_id', $department_id);
        }
        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $DeductionModel->whereIn('e1.id', $employee_id);
        }
        if (!empty($status) && !in_array('all_status', $status)) {
            $DeductionModel->whereIn('deduction_minutes.current_status', $status);
        }
        if (!empty($deduction_month)) {
            $DeductionModel->groupStart();
            $DeductionModel->where("YEAR(deduction_minutes.date) =", date('Y', strtotime($deduction_month)));
            $DeductionModel->where("MONTH(deduction_minutes.date) =", date('m', strtotime($deduction_month)));
            $DeductionModel->groupEnd();
        }

        if ($current_user['employee_id'] !== '40') {
            $DeductionModel->where('e1.id !=', $current_user['employee_id']);
        }

        $DeductionModel->orderBy('deduction_minutes.date', 'DESC');
        $DeductionRequests = $DeductionModel->findAll();
        if (!empty($DeductionRequests)) {
            foreach ($DeductionRequests as $index => $dataRow) {
                $DeductionRequests[$index]['actions'] = '';
                $DeductionRequests[$index]['date'] = !empty($dataRow['date']) ? date('d M Y', strtotime($dataRow['date'])) : '-';
                $DeductionRequests[$index]['date_time'] = !empty($dataRow['date_time']) ? date('d M Y', strtotime($dataRow['date_time'])) : '-';
                $DeductionRequests[$index]['reviewed_date'] = !empty($dataRow['reviewed_date']) ? date('d M Y', strtotime($dataRow['reviewed_date'])) : '-';
                $DeductionRequests[$index]['attachment'] = !empty($dataRow['attachment']) ? base_url('public') . $dataRow['attachment'] : '';
            }
        }

        echo json_encode($DeductionRequests);
    }

    public function getDeductionApprovalRequest()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'deduction_request_id'  =>  [
                    'rules'         =>  'required|is_not_unique[deduction_minutes.id]',
                    'errors'        =>  [
                        'required'  => 'Deduction Request ID is required',
                        'is_not_unique' => 'This Request does not exist in our database'
                    ]
                ]
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = $this->validator->getError('deduction_request_id');
        } else {
            $deduction_request_id   = $this->request->getVar('deduction_request_id');

            $DeductionModel = new DeductionModel();

            $DeductionRequest = $DeductionModel
                ->select('deduction_minutes .*')
                ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
                ->select('c.company_short_name as company_short_name')
                ->select('d.department_name as department_name')
                ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
                ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as department_hod_name")
                ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
                ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as deducted_by_name")
                ->join('employees as e1', 'e1.id = deduction_minutes.employee_id', 'left')
                ->join('departments as d', 'd.id = e1.department_id', 'left')
                ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
                ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
                ->join('employees as e4', 'e4.id = deduction_minutes.reviewed_by', 'left')
                ->join('employees as e5', 'e5.id = deduction_minutes.deducted_by', 'left')
                ->join('companies as c', 'c.id = e1.company_id', 'left')
                ->where('deduction_minutes.id =', $deduction_request_id)
                ->first();

            if (empty($DeductionRequest)) {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
            } else {

                $DeductionRequest = $DeductionRequest;

                $attendance = Processor::getProcessedPunchingData($DeductionRequest['employee_id'], $DeductionRequest['date'], $DeductionRequest['date']);

                if (!empty($attendance)) {
                    $DeductionRequest['in_time'] = (isset($attendance[0]['punch_time_including_od'][0]) && !empty($attendance[0]['punch_time_including_od'][0])) ? date('h:i A', strtotime($attendance[0]['punch_time_including_od'][0])) : '';
                    $DeductionRequest['out_time'] = (isset($attendance[0]['punch_time_including_od'][1]) && !empty($attendance[0]['punch_time_including_od'][1])) ? date('h:i A', strtotime($attendance[0]['punch_time_including_od'][1])) : '';
                    $DeductionRequest['shift_start'] = (isset($attendance[0]['shift_start']) && !empty($attendance[0]['shift_start'])) ? date('h:i A', strtotime($attendance[0]['shift_start'])) : '';
                    $DeductionRequest['shift_end'] = (isset($attendance[0]['shift_end']) && !empty($attendance[0]['shift_end'])) ? date('h:i A', strtotime($attendance[0]['shift_end'])) : '';
                }


                $DeductionRequest['date'] = !empty($DeductionRequest['date']) ? date('d M Y', strtotime($DeductionRequest['date'])) : '-';
                $DeductionRequest['date_time'] = !empty($DeductionRequest['date_time']) ? date('d M Y', strtotime($DeductionRequest['date_time'])) : '-';
                $DeductionRequest['reviewed_date'] = !empty($DeductionRequest['reviewed_date']) ? date('d M Y', strtotime($DeductionRequest['reviewed_date'])) : '-';
                $DeductionRequest['attachment'] = !empty($DeductionRequest['attachment']) ? base_url('public') . $DeductionRequest['attachment'] : '';


                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Deduction Request Found';
                $response_array['response_data']['deduction_request_data'] = $DeductionRequest;
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function updateDeductionApprovalRequest()
    {

        $response_array = array();
        $rules = [
            'deduction_request_id'  =>  [
                'rules'         =>  'required|is_not_unique[deduction_minutes.id]',
                'errors'        =>  [
                    'required'  => 'Deduction Request ID is required',
                    'is_not_unique' => 'This Request does not exist in our database'
                ]
            ],
            'status'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Click on Approve or Reject Button',
                ]
            ],
            'remarks'  =>  [
                'rules'         =>  'required',
                'errors'        =>  [
                    'required'  => 'Please enter your remarks',
                ]
            ]
        ];

        $validation = $this->validate($rules);
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        } else {

            $deduction_request_id   = $this->request->getPost('deduction_request_id');
            $new_status   = $this->request->getPost('status');
            $remarks   = $this->request->getPost('remarks');
            $current_user_employee_id = $this->session->get('current_user')['employee_id'];
            $current_date = date('Y-m-d H:i:s');

            $DeductionModel = new DeductionModel();
            $current_status = $DeductionModel->select('current_status')->where('id', $deduction_request_id)->first()['current_status'];

            if ($current_status !== 'pending') {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'This request is not in pending status';
            } else {
                $data = [
                    'current_status'        => $new_status,
                    'reviewed_by'           => $current_user_employee_id,
                    'reviewed_date'         => $current_date,
                    'reviewer_remarks'      => $remarks,
                ];
                $DeductionModel = new DeductionModel();
                $query = $DeductionModel->update($deduction_request_id, $data);
                if (!$query) {
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Deduction Request ' . ucfirst($this->request->getPost("status"));
                }
            }
        }
        return $this->response->setJSON($response_array);
    }
}
