<?php

namespace App\Controllers\Reports;

use \Dompdf\Options;
use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Controllers\Cron\FinalSalary as CronFinalSalary;
use App\Models\PreFinalSalaryModel;

class FinalSalary extends BaseController
{

    public $session;
    public $uri;
    private $intern_designation_id;
    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
        $this->intern_designation_id = 75;
    }

    public function index()
    {
        $selectedMonth = isset($_REQUEST['year_month']) ? date('Y-m-01', strtotime($_REQUEST['year_month'])) : date('Y-m-01', strtotime(first_date_of_last_month()));

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'            => 'Final Salary [Coming Soon]',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'year_month'            => isset($_REQUEST['year_month']) ? $_REQUEST['year_month'] : date('F Y', strtotime(first_date_of_last_month())),
            'Companies'             => $Companies,
        ];
        // print_r($data);
        // die();

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
        // $date_45_days_before = date('Y-m-d', strtotime('-45 days', strtotime($selectedMonth)));
        // $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $sql = "select 
            e.id as id, 
            e.internal_employee_id as internal_employee_id, 
            e.status as status, 
            trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
            d.department_name as department_name, 
            c.company_short_name as company_short_name 
            from employees e 
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id 
            where e.id is not null " . $where_department . " 
            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }

        // print_r($data);
        // die();

        return view('FinalSalary/FinalSalarySheet', $data);
    }

    public function internSalary()
    {
        $selectedMonth = isset($_REQUEST['year_month']) ? date('Y-m-01', strtotime($_REQUEST['year_month'])) : date('Y-m-01', strtotime(first_date_of_last_month()));
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'            => 'Final Salary [Coming Soon]',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'year_month'            => isset($_REQUEST['year_month']) ? $_REQUEST['year_month'] : date('F, Y', strtotime(first_date_of_last_month())),
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
        // $date_45_days_before = date('Y-m-d', strtotime('-45 days', strtotime($selectedMonth)));
        // $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $sql = "select 
            e.id as id, 
            e.internal_employee_id as internal_employee_id, 
            e.status as status, 
            trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
            d.department_name as department_name, 
            c.company_short_name as company_short_name 
            from employees e 
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id 
            where e.id is not null " . $where_department . " 
            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }

        // print_r($data);
        // die();

        // return view('FinalSalary/FinalSalarySheet', $data);
        return view('FinalSalary/FinalSalarySheetIntern', $data);
    }


    public function loadSalary()
    {
        $company_id     = $this->request->getVar('company');
        $department_id  = $this->request->getVar('department');
        $employee_id    = $this->request->getVar('employee');
        $status         = $this->request->getVar('status');
        $range_from     = $this->request->getVar('year_month');
        $range_to       = $this->request->getVar('year_month');

        echo json_encode($this->getAllSalary($company_id, $department_id, $employee_id, $status, $range_from, $range_to));
    }
    public function getAllSalary($company_id, $department_id, $employee_id, $status, $range_from, $range_to, $is_intern = false)
    {
        /*$company_id     = $this->request->getVar('company');
        $department_id  = $this->request->getVar('department');
        $employee_id    = $this->request->getVar('employee');
        $status         = $this->request->getVar('status');
        $range_from     = $this->request->getVar('year_month');
        $range_to       = $this->request->getVar('year_month');*/

        $PreFinalSalaryModel = new PreFinalSalaryModel();
        $PreFinalSalaryModel->select('pre_final_salary.*')->select('e.attachment as employee_attachment');
        if ($is_intern == true) {
            $PreFinalSalaryModel->where('e.designation_id=', $this->intern_designation_id);
        } else {
            $PreFinalSalaryModel->where('e.designation_id!=', $this->intern_designation_id);
        }
        $PreFinalSalaryModel->join('employees as e', 'e.id = pre_final_salary.employee_id', 'left');

        if ($range_from !== "" && $range_to !== "") {
            $monthfrom = date('m', strtotime($range_from));
            $monthto = date('m', strtotime($range_to));
            $yearfrom = date('Y', strtotime($range_from));
            $yearto = date('Y', strtotime($range_to));
            $year_diff = $yearto - $yearfrom;

            if ($year_diff > 1) {
                $PreFinalSalaryModel->groupStart();
                $PreFinalSalaryModel->groupStart();
                $PreFinalSalaryModel->where('pre_final_salary.month >=', $monthfrom);
                $PreFinalSalaryModel->where('pre_final_salary.year =', $yearfrom);
                $PreFinalSalaryModel->groupEnd();

                $PreFinalSalaryModel->orGroupStart();
                for ($i = $yearfrom + 1; $i < $yearto; $i++) {
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $i);
                }
                $PreFinalSalaryModel->groupEnd();

                $PreFinalSalaryModel->orGroupStart();
                $PreFinalSalaryModel->where('pre_final_salary.month <=', $monthto);
                $PreFinalSalaryModel->where('pre_final_salary.year =', $yearto);
                $PreFinalSalaryModel->groupEnd();
                $PreFinalSalaryModel->groupEnd();
            } else {
                if ($year_diff == 1) {
                    $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month >=', $monthfrom);
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearfrom);
                    $PreFinalSalaryModel->groupEnd();
                    $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month <=', '12');
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearfrom);
                    $PreFinalSalaryModel->groupEnd();
                    $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month >=', '1');
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearto);
                    $PreFinalSalaryModel->groupEnd();
                    $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month <=', $monthto);
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearto);
                    $PreFinalSalaryModel->groupEnd();
                    $PreFinalSalaryModel->groupEnd();
                } else {
                    // $PreFinalSalaryModel->orGroupStart();
                    $PreFinalSalaryModel->groupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month >=', $monthfrom);
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearfrom);
                    $PreFinalSalaryModel->groupEnd();
                    $PreFinalSalaryModel->groupStart();
                    $PreFinalSalaryModel->where('pre_final_salary.month <=', $monthto);
                    $PreFinalSalaryModel->where('pre_final_salary.year =', $yearto);
                    $PreFinalSalaryModel->groupEnd();
                    // $PreFinalSalaryModel->groupEnd();
                }
            }
        }

        if (!empty($company_id) && !in_array('all_companies', $company_id)) {
            $PreFinalSalaryModel->whereIn('e.company_id', $company_id);
        }

        if (!empty($department_id) && !in_array('all_departments', $department_id)) {
            $PreFinalSalaryModel->whereIn('e.department_id', $department_id);
        }

        if (!empty($employee_id) && !in_array('all_employees', $employee_id)) {
            $PreFinalSalaryModel->whereIn('pre_final_salary.employee_id', $employee_id);
        }

        if (!empty($status) && !in_array('all_statuses', $status)) {
            $PreFinalSalaryModel->whereIn('pre_final_salary.disbursed', $status);
        }

        // $PreFinalSalaryModel->where('e.status=', 'active');

        $FinalSalary = $PreFinalSalaryModel->findAll();

        // echo '<pre>';
        // print_r($PreFinalSalaryModel->getLastQuery()->getQuery());
        // echo '</pre>';
        // die();

        foreach ($FinalSalary as $index => $FinalSalaryRow) {
            foreach ($FinalSalaryRow as $FieldIndex => $FieldValue) {
                if (is_numeric($FieldValue) && floor($FieldValue) !== $FieldValue) {
                    $FinalSalaryRow[$FieldIndex] = round($FieldValue, 2);
                }
                if ($FieldIndex == 'month') {
                    $FinalSalaryRow[$FieldIndex] = date('F', strtotime(date('Y-' . $FieldValue . '-01')));
                }
                if ($FieldIndex == 'joining_date' && !empty($FieldValue)) {
                    $FinalSalaryRow[$FieldIndex] = date('d M, Y', strtotime($FieldValue));
                }
                if ($FieldIndex == 'salary_structure' && !empty($FieldValue)) {
                    $FinalSalaryRow[$FieldIndex] = json_decode($FieldValue);
                }
                if ($FieldIndex == 'employee_data' && !empty($FieldValue)) {
                    $FinalSalaryRow[$FieldIndex] = json_decode($FieldValue);
                }
                if ($FieldIndex == 'employee_data' && !empty($FieldValue)) {
                    $FieldValue = json_decode($FieldValue, true);
                    $FieldValue['joining_date'] = !empty($FieldValue['joining_date']) ? date('d M Y', strtotime($FieldValue['joining_date'])) : $FieldValue['joining_date'];
                    $FinalSalaryRow[$FieldIndex] = $FieldValue;
                }

                if ($FieldIndex == 'employee_attachment' && !empty($FieldValue)) {
                    $FinalSalaryRow[$FieldIndex] = json_decode($FieldValue, true);
                }
            }
            $FinalSalary[$index] = $FinalSalaryRow;
        }
        //echo json_encode($FinalSalary);
        return $FinalSalary;
    }

    public function loadInternSalary()
    {
        $company_id     = $this->request->getVar('company');
        $department_id  = $this->request->getVar('department');
        $employee_id    = $this->request->getVar('employee');
        $status         = $this->request->getVar('status');
        $range_from     = $this->request->getVar('year_month');
        $range_to       = $this->request->getVar('year_month');

        echo json_encode($this->getAllSalary($company_id, $department_id, $employee_id, $status, $range_from, $range_to, true));
    }

    /*public function disburseSalary(){
    	$response_array = array();
        $validation = $this->validate(
            [
                'id'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Select a data row first',
                    ]
                ],
            ]
        );
        if( !$validation ){
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            $response_array['response_data']['validation'] = $errors;
        }else{
            $id = $this->request->getPost('id');
            $data = [
            	'disbursed' => 'yes',
            	'disbursal_date' => date('Y-m-d'),
                'disbursed_by' => $current_user = $this->session->get('current_user')['employee_id'],
            ];
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $UpdateQuery = $PreFinalSalaryModel->update($id, $data);
            if( !$UpdateQuery ){
            	$response_array['response_type'] = 'error';
            	$response_array['response_description'] = 'Disbrsal Failed. Please contact administrator!';
            }else{
            	$response_array['response_type'] = 'success';
            	$response_array['response_description'] = 'Salary disbursed';
            }
        }
        return $this->response->setJSON($response_array);
    }*/

    public function doAction()
    {

        $response_array = array();
        $validation = $this->validate(
            [
                'ids'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Select a data row first',
                    ]
                ],
                'action'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Select an option from action dropdown',
                    ]
                ],
                'action_date'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please select a date',
                    ]
                ],
                'action_remarks'  =>  [
                    'rules'         =>  'required',
                    'errors'        =>  [
                        'required'  => 'Please enter remarks',
                    ]
                ],
            ]
        );
        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = implode(", ", $this->validator->getErrors());
            return $this->response->setJSON($response_array);
        } else {
            $ids = $this->request->getPost('ids');
            $action = $this->request->getPost('action');
            $action_date = $this->request->getPost('action_date');
            $action_remarks = $this->request->getPost('action_remarks');

            if (in_array($action, ['hold', 'unhold', 'finalized', 'disbursed'])) {
                foreach ($ids as $i => $id) {
                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $OldData = $PreFinalSalaryModel->find($id);
                    if ($OldData['status'] == 'hold' && $action != 'unhold') {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'only unhold action is permitted';
                        $response_array['failed_id'] = $id;
                        $response_array['failed_reason'] = 'salary on hold';
                        return $this->response->setJSON($response_array);
                    } elseif (in_array($OldData['status'], ['unhold', 'generated', 're-generated']) && !in_array($action, ['hold', 'finalized'])) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'only hold or finalized action is permitted';
                        $response_array['failed_id'] = $id;
                        return $this->response->setJSON($response_array);
                    } elseif ($OldData['status'] == 'finalized' && $action != 'disbursed') {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'only disbursed action is permitted';
                        $response_array['failed_id'] = $id;
                        return $this->response->setJSON($response_array);
                    } elseif ($OldData['status'] == 'disbursed') {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'Salary is already disbursed';
                        $response_array['failed_id'] = $id;
                        return $this->response->setJSON($response_array);
                    }

                    $remarks_timeline = !empty($OldData['remarks_timeline']) ? json_decode($OldData['remarks_timeline']) : [];
                    $remarks_timeline = [
                        [
                            'date' => date('Y-m-d H:i:s'),
                            'action' => $action,
                            'remarks' => $action_remarks,
                            'by' => trim($this->session->get('current_user')['name']),
                        ],
                        ...$remarks_timeline
                    ];

                    $final_salary['remarks_timeline'] = json_encode($remarks_timeline);
                    $final_salary['status'] = $action;
                    $final_salary['remarks'] = $action_remarks;
                    if ($action == 'disbursed') {
                        $final_salary['disbursed'] = 'yes';
                        $final_salary['disbursal_date'] = date('Y-m-d H:i:s');
                        $final_salary['disbursal_remarks'] = $action_remarks;
                        $final_salary['disbursed_by'] = $this->session->get('current_user')['employee_id'];
                    }

                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $PreFinalSalaryModel->saveRivision($OldData['id']);
                    $query = $PreFinalSalaryModel->update($OldData['id'], $final_salary);
                    if (!$query) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 'db Error::Failed to updated';
                        $response_array['failed_id'] = $id;
                        return $this->response->setJSON($response_array);
                    }
                }
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = $action . ' request was successful';
                return $this->response->setJSON($response_array);
            } elseif ($action == 'regenerate') {

                foreach ($ids as $i => $id) {
                    $PreFinalSalaryModel = new PreFinalSalaryModel();
                    $OldData = $PreFinalSalaryModel->find($id);

                    if (!in_array($OldData['status'], ['generated', 're-generated', 'unhold'])) {
                        $response_array['response_type'] = 'error';
                        $response_array['response_description'] = 're-generated action is not permitted';
                        $response_array['failed_id'] = $id;
                        return $this->response->setJSON($response_array);
                    }

                    $FinalSalaryCalculator = new CronFinalSalary();
                    $calculator_response = $FinalSalaryCalculator->calculateSalary($OldData['employee_id'], date('F Y', strtotime($OldData['year'] . "-" . $OldData['month'] . "-01")), $action_remarks, true);
                    $calculator_response = json_decode($calculator_response, true);
                    if ($calculator_response['response'] != 'success') {
                        $response_array['response_type'] = $calculator_response['response'];
                        $response_array['response_description'] = $calculator_response['description'];
                        return $this->response->setJSON($response_array);
                    }
                }

                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'All salary regenerated';
                return $this->response->setJSON($response_array);
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'No action performed';
                return $this->response->setJSON($response_array);
            }
        }
    }

    public function salarySlip($employee_id, $salary_year, $salary_month)
    {
        if (empty($employee_id)) {
            return;
        }
        if (empty($salary_year)) {
            return;
        }
        if (empty($salary_month)) {
            return;
        }
        $salary_month_year = $salary_year . '-' . $salary_month;

        $PreFinalSalaryModel = new PreFinalSalaryModel();
        $PreFinalSalaryModel->select('pre_final_salary.*');
        $PreFinalSalaryModel->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $PreFinalSalaryModel->join('employees', 'employees.id = pre_final_salary.employee_id', 'left');
        $PreFinalSalaryModel->where('pre_final_salary.employee_id =', $employee_id);
        $PreFinalSalaryModel->where("pre_final_salary.month =", date('m', strtotime($salary_month_year)));
        $PreFinalSalaryModel->where("pre_final_salary.year =", date('Y', strtotime($salary_month_year)));
        $FinalSalary = $PreFinalSalaryModel->first();
        /*echo '<pre>';
        $attachment = json_decode($FinalSalary['employee_data'])->attachment;
        print_r(json_decode($attachment)->bank_account->number);
        echo '</pre>';
        die();*/
        $data = [
            'page_title'            => 'Salary Slip [Coming Soon]',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'FinalSalary'           => $FinalSalary,
            'salary_year'           => $salary_year,
            'salary_month'          => $salary_month,
        ];

        // return view('FinalSalary/SalarySlip', $data);
        $options = new options();
        $options->set('isRemoteEnabled', true);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('FinalSalary/SalarySlip', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("salary-slip-" . str_replace(' ', '-', strtolower($FinalSalary['employee_name'])) . "-" . strtolower($FinalSalary['month']) . "-" . strtolower($FinalSalary['year']) . ".pdf", array("Attachment" => false));
        exit(0);
    }
}
