<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\SalaryModel;
use App\Models\PreFinalSalaryModel;
use App\Models\MinWagesCategoryModel;
use \Dompdf\Options;
use App\Models\AppraisalsRevisionModel;
use App\Models\AppraisalsModel;



class AppraisalsController extends BaseController
{
    protected $appraisalsModel;
    protected $appraisalsRevisionModel;
    protected $session;

    public function __construct()
    {
        $this->session = session();

        helper(['url', 'form', 'Form_helper', 'global_helper', 'Config_defaults_helper']);
        $this->appraisalsModel = new AppraisalsModel();
        $this->appraisalsRevisionModel = new AppraisalsRevisionModel();
    }
    public function index()
    {
        $employee_id = session()->get('current_user')['employee_id'];
        if (!in_array($employee_id, ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }


        $CustomModel = new CustomModel();
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        // $filter = $this->request->getGet('filter');
        // $arrCompanyId = $this->request->getGet('company');
        // $arrDepartmentId = $this->request->getGet('department');
        // $arrEmployeeId = $this->request->getGet('employee');
        // $employeeModel = new EmployeeModel();
        // $employees = $employeeModel->getFilteredEmployees($arrCompanyId, $arrDepartmentId, $arrEmployeeId);

        $data = [
            'page_title'            => 'Employee Appraisals Management System',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            // 'employees'             => $employees,
            'employee_id'           => $employee_id,
            'Companies'             => $Companies,
        ];
        $data['appraisals'] = $this->appraisalsModel->findAll();
        // echo json value
        // echo json_encode($data);


        return view('Appraisals/Show', $data);
    }

    public function showTable()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $arrCompanyId     = isset($params['company']) ? $params['company'] : "";
        $arrDepartmentId  = isset($params['department']) ? $params['department'] : "";
        $arrEmployeeId    = isset($params['employee']) ? $params['employee'] : "";

        $current_employee_id = $this->session->get('current_user')['employee_id'];

        $arrResponseData  = $this->appraisalsModel->getFilteredAllAppraisalsData($arrCompanyId, $arrDepartmentId, $arrEmployeeId);

        // dd($arrResponseData);

        //$lastQuery = $this->appraisalsModel->getLastQuery()->getQuery();  
        // $data = [
        //     'appraisals' => $arrResponseData,
        // ];
        //  //echo $lastQuery = $this->appraisalsModel->getLastQuery()->getQuery();
        return json_encode($arrResponseData);
    }




    public function showTableByEmpId($employeeId)
    {
        return $this->response->setJSON($this->getAllAppraisalasByEmpId($employeeId));
    }

    private function getAllAppraisalasByEmpId($employeeId, $previousRecord = false)
    {
        if (empty($employeeId)) {
            return $this->response->setJSON("Employee_id is required");
        }
        $appraisals  = $this->appraisalsModel->getEmloyeeAppraisals($employeeId);
        $lastEntry = null;
        if (!empty($appraisals)) {
            $ids = [];
            foreach ($appraisals as $key => $appraisal) {
                $appraisal->total_appraisal = $appraisal->basic_salary +
                    $appraisal->house_rent_allowance +
                    $appraisal->conveyance +
                    $appraisal->medical_allowance +
                    $appraisal->special_allowance +
                    $appraisal->fuel_allowance +
                    $appraisal->vacation_allowance +
                    $appraisal->other_allowance +
                    $appraisal->bonus +
                    $appraisal->non_compete_loan_amount_per_month +
                    $appraisal->loyalty_incentive_amount_per_month +
                    $appraisal->pf_employer_contribution +
                    $appraisal->esi_employer_contribution +
                    $appraisal->lwf_employer_contribution +
                    $appraisal->other_benefits;

                $appraisals[$key] = $appraisal;
                $ids[] = $appraisal->id;
            }

            $max_id = max($ids);
            foreach ($appraisals as $key => $appraisal) {
                if ($appraisal->id == $max_id) {
                    $appraisals[$key]->is_editable = 1;
                } else {
                    $appraisals[$key]->is_editable = 0;
                }
            }

            foreach ($appraisals as $key => $appraisal) {
                if ($appraisal->id == $max_id) {
                    $lastEntry = $appraisal;
                }
            }
        }

        if ($previousRecord == false) {
            return $appraisals;
        } else {
            return $lastEntry;
        }
    }

    public function create($employee_id = null)
    {

        if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CustomModel = new CustomModel();
        $CustomSql = "select 
            e.id as id, 
            e.first_name as first_name, 
            e.last_name as last_name, 
            e.internal_employee_id as internal_employee_id, 
            d.department_name, 
            c.company_name 
            from employees e 
            left join departments d on d.id = e.department_id 
            left join companies c on c.id = e.company_id 
            order by e.first_name";
        $employees = $CustomModel->CustomQuery($CustomSql)->getResultArray();

        $data = [
            'page_title'            => 'Add Employee Appraisals',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'employees'             => $employees,
            'employee_id'           => $employee_id,
            // 'appraisals'            => $appraisals,
        ];

        $EmployeeModel = new EmployeeModel();

        $selectedEmployee = $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_name as company_name')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->select("trim(concat(hods.first_name, ' ', hods.last_name)) as hod_name")
            ->select("trim(concat(reporting_managers.first_name, ' ', reporting_managers.last_name)) as reporting_manager_name")
            ->select('minimum_wages_categories.minimum_wages_category_value as minimum_wages')
            // ->join('employee_salary', 'employee_salary.employee_id = employees.id', 'left')
            ->join('companies', 'companies.id = employees.company_id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->join('employees as hods', 'hods.id = departments.hod_employee_id', 'left')
            ->join('employees as reporting_managers', 'reporting_managers.id = employees.reporting_manager_id', 'left')
            ->join('minimum_wages_categories', 'minimum_wages_categories.id = employees.min_wages_category', 'left')
            ->where('employees.id = ', $employee_id)->first();

        if (!empty($selectedEmployee)) {
            $selectedEmployee['attachment'] = $selectedEmployee['attachment'] ? json_decode($selectedEmployee['attachment'], true) : null;
            $selectedEmployee['family_members'] = $selectedEmployee['family_members'] ? json_decode($selectedEmployee['family_members'], true) : null;
        }

        $data['selectedEmployee'] = $selectedEmployee;

        $appraisals = $this->getAllAppraisalasByEmpId($employee_id);
        $lastAppraisal = $this->getAllAppraisalasByEmpId($employee_id, true);

        $keysToSum = [
            'ctc',
            'gross_salary',
            'basic_salary',
            'house_rent_allowance',
            'conveyance',
            'medical_allowance',
            'special_allowance',
            'fuel_allowance',
            'vacation_allowance',
            'other_allowance'
        ];

        $salary_totals = array_fill_keys($keysToSum, 0);

        foreach ($appraisals as $row) {
            foreach ($keysToSum as $key) {
                if (isset($row->$key) && is_numeric($row->$key)) {
                    $salary_totals[$key] += $row->$key;
                }
            }
        }

        $data['salary_totals'] = $salary_totals;
        $data['lastAppraisal'] = $lastAppraisal;

        // dd($data);

        return view('Appraisals/Create', $data);
    }


    public function store()
    {
        if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $response_array = array();

        $rules = [];

        $rules['employee_id'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];

        if (!empty($this->request->getPost('non_compete_loan'))) {
            $rules['non_compete_loan_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['non_compete_loan_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('loyalty_incentive'))) {
            $rules['loyalty_incentive_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['loyalty_incentive_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('enable_other_benefit'))) {
            $rules['other_benefits'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['other_benefit_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }

        $rules['appraisal_remarks'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        $rules['appraisal_date'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            // print_r($errors);
            // die();
            $response_array['response_data']['validation'] = $errors;
        } else {

            $data = $this->prepareData($this->request->getPost());

            $id = $this->appraisalsModel->insert($data);
            if (!$id) {
                // $error = $this->appraisalsModel->errors();
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Failed to create appraisal';
            } else {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Appraisal added successfully';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function prepareData($data, $editingAppraisalId = null)
    {
        $data['employee_id'] = (int) $data['employee_id'];
        $data['basic_salary'] = (int) $data['basic_salary'];
        $data['house_rent_allowance'] = (int) $data['house_rent_allowance'];
        $data['conveyance'] = (int) $data['conveyance'];
        $data['medical_allowance'] = (int) $data['medical_allowance'];
        $data['special_allowance'] = (int) $data['special_allowance'];
        $data['fuel_allowance'] = (int) $data['fuel_allowance'];
        $data['vacation_allowance'] = (int) $data['vacation_allowance'];
        $data['other_allowance'] = (int) $data['other_allowance'];
        // $data['fuel_allowance'] = $this->request->getPost('fuel_allowance') ?? 0;
        // $data['vacation_allowance'] = $this->request->getPost('vacation_allowance') ?? 0;
        // $data['other_allowance'] = $this->request->getPost('other_allowance') ?? 0;

        $data['gross_salary'] = (int) $data['basic_salary'] +
            (int)$data['house_rent_allowance'] +
            (int)$data['conveyance'] +
            (int)$data['medical_allowance'] +
            (int)$data['special_allowance'] +
            (int)$data['fuel_allowance'] +
            (int)$data['vacation_allowance'] +
            (int)$data['other_allowance'];

        $previousAppraisals = $this->getAllAppraisalasByEmpId($data['employee_id']);

        // When editing, exclude the current appraisal from previous totals calculation
        if ($editingAppraisalId !== null) {
            $previousAppraisals = array_filter($previousAppraisals, function ($appraisal) use ($editingAppraisalId) {
                return $appraisal->id != $editingAppraisalId;
            });
        }

        $appraisalsCount =  count($previousAppraisals);

        $previousTotals = [
            'pf_employee_contribution' => 0,
            'pf_employer_contribution' => 0,
            'esi_employee_contribution' => 0,
            'esi_employer_contribution' => 0,
            'lwf_employee_contribution' => 0,
            'lwf_employer_contribution' => 0,
            'gross_salary' => 0,
            'bonus' => 0
        ];

        if (!empty($previousAppraisals)) {
            foreach ($previousAppraisals as $appraisal) {
                $previousTotals['pf_employee_contribution'] += $appraisal->pf_employee_contribution ?? 0;
                $previousTotals['pf_employer_contribution'] += $appraisal->pf_employer_contribution ?? 0;
                $previousTotals['esi_employee_contribution'] += $appraisal->esi_employee_contribution ?? 0;
                $previousTotals['esi_employer_contribution'] += $appraisal->esi_employer_contribution ?? 0;
                $previousTotals['lwf_employee_contribution'] += $appraisal->lwf_employee_contribution ?? 0;
                $previousTotals['lwf_employer_contribution'] += $appraisal->lwf_employer_contribution ?? 0;
                $previousTotals['gross_salary'] += $appraisal->gross_salary ?? 0;
                $previousTotals['bonus'] += $appraisal->bonus ?? 0;
            }
        }

        // bonus calcualtions
        $data['enable_bonus'] = $data['enable_bonus'] ?? 'no';
        if ($data['enable_bonus'] == 'yes') {
            $EmployeeModel = new EmployeeModel();
            $employee = $EmployeeModel->find($data['employee_id']);
            if (!empty($employee['min_wages_category'])) {
                $MinWagesCategoryModel = new MinWagesCategoryModel();
                $MinWage = $MinWagesCategoryModel->find($employee['min_wages_category']);
                $MinWageValue = $MinWage['minimum_wages_category_value'] ?? 0;
                $totalBonusNeeded = round(($MinWageValue * 8.33) / 100);

                if ($appraisalsCount >= 1) {
                    $data['bonus'] = max(0, $totalBonusNeeded - $previousTotals['bonus']);
                } else {
                    $data['bonus'] = $totalBonusNeeded;
                }
            }
        } else {
            $data['bonus'] = 0;
        }

        $data['pf'] = $data['pf'] ?? 'no';
        if ($data['pf'] === 'yes') {
            $total_gross_salary = $previousTotals['gross_salary'] + $data['gross_salary'];
            $pf_base_salary = ($total_gross_salary >= 15000) ? 15000 : $total_gross_salary;

            $total_pf_employee_needed = round(($pf_base_salary * 12) / 100, 2);
            $total_pf_employer_needed = round(($pf_base_salary * 13) / 100, 2);
            if ($appraisalsCount >= 1) {
                $data['pf_employee_contribution'] = max(0, $total_pf_employee_needed - $previousTotals['pf_employee_contribution']);
                $data['pf_employer_contribution'] = max(0, $total_pf_employer_needed - $previousTotals['pf_employer_contribution']);
            } else {
                $data['pf_employee_contribution'] = round((($data['gross_salary'] >= 15000) ? 15000 : $data['gross_salary']) * 12 / 100, 2);
                $data['pf_employer_contribution'] = round((($data['gross_salary'] >= 15000) ? 15000 : $data['gross_salary']) * 13 / 100, 2);
            }
        } else {
            $data['pf_employee_contribution'] = 0;
            $data['pf_employer_contribution'] = 0;
        }




        $data['esi'] = $data['esi'] ?? 'no';

        if ($appraisalsCount >= 1) {
            $total_gross_salary = $previousTotals['gross_salary'] + $data['gross_salary'];
            if ($data['esi'] === 'yes' && $total_gross_salary <= 21000) {

                $total_esi_employee_needed = round(($total_gross_salary * 0.75) / 100, 2);
                $total_esi_employer_needed = round(($total_gross_salary * 3.25) / 100, 2);

                $data['esi_employee_contribution'] = max(0, $total_esi_employee_needed - $previousTotals['esi_employee_contribution']);
                $data['esi_employer_contribution'] = max(0, $total_esi_employer_needed - $previousTotals['esi_employer_contribution']);
            } else {
                $data['esi_employee_contribution'] = 0;
                $data['esi_employer_contribution'] = 0;
            }
        } else {
            if ($data['esi'] === 'yes' && $data['gross_salary'] <= 21000) {
                $data['esi_employee_contribution'] = round(($data['gross_salary'] * 0.75) / 100, 2);
                $data['esi_employer_contribution'] = round(($data['gross_salary'] * 3.25) / 100, 2);
            } else {
                $data['esi_employee_contribution'] = 0;
                $data['esi_employer_contribution'] = 0;
            }
        }





        $data['lwf'] = $data['lwf'] ?? 'no';
        if ($data['lwf'] === 'yes') {
            $total_lwf_employee_needed = ((($total_gross_salary * 0.2) / 100 <= 31) ? round(($total_gross_salary * 0.2) / 100, 2) : 31);
            $total_lwf_employer_needed = round($total_lwf_employee_needed * 2, 2);
            if ($appraisalsCount >= 1) {
                $data['lwf_employee_contribution'] = max(0, $total_lwf_employee_needed - $previousTotals['lwf_employee_contribution']);
                $data['lwf_employer_contribution'] = max(0, $total_lwf_employer_needed - $previousTotals['lwf_employer_contribution']);
            } else {
                $data['lwf_employee_contribution'] = max(0, round(((($data['gross_salary'] * 0.2) / 100 <= 31) ? round(($data['gross_salary'] * 0.2) / 100, 2) : 31)));
                $data['lwf_employer_contribution'] = max(0, round(((($data['gross_salary'] * 0.2) / 100 <= 31) ? round(($data['gross_salary'] * 0.2) / 100, 2) : 31) * 2, 2));
            }
        } else {
            $data['lwf_employee_contribution'] = 0;
            $data['lwf_employer_contribution'] = 0;
        }

        $data['non_compete_loan'] = $data['non_compete_loan'] ?? 'no';
        $data['non_compete_loan_amount_per_month'] = $data['non_compete_loan'] == 'yes' ? $data['non_compete_loan_amount_per_month'] : 0;
        $data['non_compete_loan_from'] = $data['non_compete_loan'] == 'yes' ? $data['non_compete_loan_from'] : null;

        $data['loyalty_incentive'] = $data['loyalty_incentive'] ?? 'no';
        $data['loyalty_incentive_amount_per_month'] = $data['loyalty_incentive'] == 'yes' ? $data['loyalty_incentive_amount_per_month'] : 0;
        $data['loyalty_incentive_from'] = $data['loyalty_incentive'] == 'yes' ? $data['loyalty_incentive_from'] : null;

        $data['enable_other_benefit'] = $data['enable_other_benefit'] ?? 'no';
        $data['other_benefits'] = $data['enable_other_benefit'] == 'yes' ? $data['other_benefits'] : 0;
        $data['other_benefit_from'] = $data['enable_other_benefit'] == 'yes' ? $data['other_benefit_from'] : null;


        $data['ctc'] = $data['gross_salary'] +
            $data['bonus'] +
            $data['pf_employer_contribution'] +
            $data['esi_employer_contribution'] +
            $data['lwf_employer_contribution'] +
            $data['non_compete_loan_amount_per_month'] +
            $data['loyalty_incentive_amount_per_month'] +
            $data['other_benefits'];

        $data['created_by'] = session()->get('current_user')['employee_id'] ?? 40;
        // $data['appraisal_remarks'] = $data['appraisal_remarks'];
        // $data['appraisal_date'] = $data['appraisal_date'];
        return $data;
    }

    public function getAppraisalDetails($id)
    {
        $appraisal = $this->appraisalsModel->getAppraisalWithEmployee($id);
        if ($appraisal) {

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $appraisal,
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Appraisal not found.',
            ]);
        }
    }

    public function edit($id = null)
    {
        if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data['created_by'] = $this->session->get('current_user')['employee_id'];
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Appraisal ID is missing.',
            ]);
        }

        $appraisal = $this->appraisalsModel->find($id);
        if (!$appraisal) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Appraisal not found.',
            ]);
        }

        $data = [
            'page_title'            => 'Edit Employee Appraisals',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'appraisal'             => $appraisal,
        ];

        $EmployeeModel = new EmployeeModel();

        $selectedEmployee = $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_name as company_name')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->select("trim(concat(hods.first_name, ' ', hods.last_name)) as hod_name")
            ->select("trim(concat(reporting_managers.first_name, ' ', reporting_managers.last_name)) as reporting_manager_name")
            ->select('minimum_wages_categories.minimum_wages_category_value as minimum_wages')
            // ->join('employee_salary', 'employee_salary.employee_id = employees.id', 'left')
            ->join('companies', 'companies.id = employees.company_id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->join('employees as hods', 'hods.id = departments.hod_employee_id', 'left')
            ->join('employees as reporting_managers', 'reporting_managers.id = employees.reporting_manager_id', 'left')
            ->join('minimum_wages_categories', 'minimum_wages_categories.id = employees.min_wages_category', 'left')
            ->where('employees.id = ', $appraisal['employee_id'])->first();

        if (!empty($selectedEmployee)) {
            $selectedEmployee['attachment'] = $selectedEmployee['attachment'] ? json_decode($selectedEmployee['attachment'], true) : null;
            $selectedEmployee['family_members'] = $selectedEmployee['family_members'] ? json_decode($selectedEmployee['family_members'], true) : null;
        }

        $data['selectedEmployee'] = $selectedEmployee;

        $appraisals = $this->getAllAppraisalasByEmpId($appraisal['employee_id']);

        $keysToSum = [
            'ctc',
            'gross_salary',
            'basic_salary',
            'house_rent_allowance',
            'conveyance',
            'medical_allowance',
            'special_allowance',
            'fuel_allowance',
            'vacation_allowance',
            'other_allowance',
            'bonus',
            'non_compete_loan_amount_per_month',
            'loyalty_incentive_amount_per_month',
            'pf_employer_contribution',
            'esi_employer_contribution',
            'lwf_employer_contribution',
            'other_benefits'
        ];

        $salary_totals = array_fill_keys($keysToSum, 0);

        foreach ($appraisals as $row) {
            foreach ($keysToSum as $key) {
                if (isset($row->$key) && is_numeric($row->$key)) {
                    $salary_totals[$key] += $row->$key;
                }
            }
        }

        $data['salary_totals'] = $salary_totals;

        return view('Appraisals/Edit', $data);
    }

    public function update($id)
    {
        if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $response_array = array();

        $rules = [];

        // $rules['id'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        $rules['employee_id'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];

        if (!empty($this->request->getPost('non_compete_loan'))) {
            $rules['non_compete_loan_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['non_compete_loan_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('loyalty_incentive'))) {
            $rules['loyalty_incentive_amount_per_month'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['loyalty_incentive_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }
        if (!empty($this->request->getPost('enable_other_benefit'))) {
            $rules['other_benefits'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
            $rules['other_benefit_from'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        }

        $rules['appraisal_remarks'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];
        $rules['appraisal_date'] = ['rules' => 'required', 'errors' => ['required' => 'This field is required']];

        $validation = $this->validate($rules);

        if (!$validation) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'Sorry, looks like there are some errors,<br>Click ok to view errors';
            $errors = $this->validator->getErrors();
            // print_r($errors);
            // die();
            $response_array['response_data']['validation'] = $errors;
        } else {

            $data = $this->prepareData($this->request->getPost(), $id);

            $data['appraisal_id'] = $id;
            $data['action'] = 'insert';
            $revisionId = $this->appraisalsRevisionModel->insert($data);
            if ($revisionId) {
                $isUpdated = $this->appraisalsModel->update($id, $data);
                if (!$isUpdated) {
                    // $error = $this->appraisalsModel->errors();
                    $response_array['response_type'] = 'error';
                    $response_array['response_description'] = 'Failed to update appraisal';
                } else {
                    $response_array['response_type'] = 'success';
                    $response_array['response_description'] = 'Appraisal updated successfully';
                }
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'Failed to store revision';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function delete($id)
    {
        if (!in_array(session()->get('current_user')['employee_id'], ['40', '93'])) {
            return redirect()->to(base_url('/unauthorised'));
        }
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON([
                'title' => 'Error!',
                'status' => 'error',
                'message' => 'Appraisal ID is missing.',
            ]);
        }
        $appraisal = $this->appraisalsModel->find($id);
        $data = $appraisal;

        if (!$appraisal) {
            return $this->response->setJSON([
                'title' => 'Error!',
                'status' => 'error',
                'message' => 'Appraisal not found.',
            ]);
        }
        if ($this->appraisalsModel->delete($id)) {
            $data['appraisal_id'] = $appraisal['id'];
            $data['created_by'] = session()->get('current_user')['employee_id'];
            $data['action'] = 'delete';

            $id = $this->appraisalsRevisionModel->insert($data);

            return $this->response->setJSON([
                'title' => 'Success!',
                'status' => 'success',
                'message' => 'Appraisal deleted successfully.',
            ]);
        } else {
            return $this->response->setJSON([
                'title' => 'Error!',
                'status' => 'error',
                'message' => 'Failed to delete appraisal.',
            ]);
        }
    }

    public function downloadPDF($employee_id = null)
    {
        $appraisals = $this->appraisalsModel
            ->select('appraisals.*')
            ->select('employees.*')
            ->select('departments.department_name')
            ->select('designations.designation_name')
            ->select('companies.company_name, companies.logo_url')
            ->select('employee_salary.gratuity as gratuity')
            ->join('employees', 'employees.id = appraisals.employee_id')
            ->join('designations', 'designations.id = employees.designation_id')
            ->join('departments', 'departments.id = employees.department_id')
            ->join('companies', 'companies.id = employees.company_id')
            ->join('employee_salary', 'employee_salary.employee_id = appraisals.employee_id')
            ->where('appraisals.employee_id', $employee_id)
            ->orderBy('appraisals.id')
            ->groupBy('employees.id, appraisals.id')
            ->findAll();

        $arrAppraisalData = [];
        $appraisalCount = 0;
        $finalGrossSalary = 0;

        foreach ($appraisals as $key => $appraisal) {
            $appraisalCount++;
            $employeeId = $appraisal['employee_id'];

            if (!isset($arrAppraisalData[$employeeId])) {
                $attachment = json_decode($appraisal['attachment'], true);
                $pdc_enabled = isset($attachment['pdc_cheque']) && isset($attachment['pdc_cheque']['enable_pdc']) && $attachment['pdc_cheque']['enable_pdc'] == 'yes' ? true : false;
                $ensure_pdc_available = isset($attachment['pdc_cheque']['cheque_number_1']) && !empty($attachment['pdc_cheque']['cheque_number_1']) ? true : false;

                if (isset($appraisal['joining_date']) && !empty($appraisal['joining_date'])) {
                    $joining_date = date_create($appraisal['joining_date']);
                    $today = date_create(date('Y-m-d'));
                    $diff = date_diff($joining_date, $today);
                    $tenure_array = [];
                    if ($diff->y > 0) {
                        $tenure_array[] = $diff->y . ' Years';
                    }
                    if ($diff->m > 0) {
                        $tenure_array[] = $diff->m . ' Months';
                    }
                    $tenue = implode(' & ', $tenure_array);
                } else {
                    $tenue = 'N/A';
                }

                if (isset($appraisal['date_of_birth']) && !empty($appraisal['date_of_birth'])) {
                    $date_of_birth = date_create($appraisal['date_of_birth']);
                    $today = date_create(date('Y-m-d'));
                    $diff = date_diff($date_of_birth, $today);
                    if ($diff->y > 0) {
                        $age = $diff->y . ' Years';
                    } elseif ($diff->m > 0) {
                        $age = $diff->m . ' Months';
                    } elseif ($diff->d > 0) {
                        $age = $diff->d . ' Days';
                    } else {
                        $age = 'N/A';
                    }
                } else {
                    $age = 'N/A';
                }

                $tenureYears = null;
                if (is_numeric($tenue)) {
                    $tenureYears = (float)$tenue;
                } elseif (preg_match('/(\d+)\s*Months?/i', $tenue, $m)) {
                    $tenureYears = round($m[1] / 12, 2);
                } elseif (preg_match('/(\d+)\s*Years?/i', $tenue, $m)) {
                    $tenureYears = (float)$m[1];
                }

                $gratuityEligible = ($tenureYears !== null && $tenureYears >= 5) ? 'yes' : 'no';

                $arrAppraisalData[$employeeId] = [
                    'employee_name' => $appraisal['first_name'] . ' ' . $appraisal['last_name'],
                    'employee_id' => $appraisal['internal_employee_id'],
                    'notice_period' => $appraisal['notice_period'] . ' days',
                    'joining_date' => isset($appraisal['joining_date']) && !empty($appraisal['joining_date']) ? date('d M Y', strtotime($appraisal['joining_date'])) : 'N/A',
                    'designation' => $appraisal['designation_name'],
                    'department_name' => $appraisal['department_name'],
                    'company_name' => $appraisal['company_name'],
                    'logo_url' => $appraisal['logo_url'],
                    'enable_bonus' => $appraisal['enable_bonus'],
                    'probation' => $appraisal['probation'],
                    'date_of_birth' => isset($appraisal['date_of_birth']) && !empty($appraisal['date_of_birth']) ? date('d M Y', strtotime($appraisal['date_of_birth'])) : 'N/A',
                    'relevant_experience' => $appraisal['relevant_experience'] ? $appraisal['relevant_experience'] : 'N/A',
                    'age' => $age,
                    'tenure' => $tenue,
                    'gratuity' => $appraisal['gratuity'] ? 'yes' : 'no',
                    'pdc_for_np' => $pdc_enabled && $ensure_pdc_available ? 'yes' : 'no',
                    'gratuity_eligible' => $gratuityEligible,
                    'appraisals' => []
                ];

                // Initialize previous totals
                $previous_gross_salary[$employeeId] = 0;
                $previous_basic_salary[$employeeId] = 0;
                $previous_monthly_el[$employeeId] = 0;
                $previous_monthly_cl[$employeeId] = 0;
                $previous_house_rent_allowance[$employeeId] = 0;
                $previous_conveyance[$employeeId] = 0;
                $previous_medical_allowance[$employeeId] = 0;
                $previous_special_allowance[$employeeId] = 0;
                $previous_fuel_allowance[$employeeId] = 0;
                $previous_vacation_allowance[$employeeId] = 0;
                $previous_other_allowance[$employeeId] = 0;
                $previous_other_benefits[$employeeId] = 0;
                $previous_gratuity[$employeeId] = 0;
                $previous_bonus[$employeeId] = 0;
                $previous_loyalty_incentive[$employeeId] = 0;
                $previous_ctc_total[$employeeId] = 0;
                $previous_pf_employee_contribution[$employeeId] = 0;
                $previous_pf_employer_contribution[$employeeId] = 0;
                $previous_esi_employee_contribution[$employeeId] = 0;
                $previous_esi_employer_contribution[$employeeId] = 0;
                $previous_lwf_employee_contribution[$employeeId] = 0;
                $previous_lwf_employer_contribution[$employeeId] = 0;
                $previous_non_compete_loan[$employeeId] = 0;
                $previous_non_compete_loan_amount_per_month[$employeeId] = 0;
                $previous_loyalty_incentive_amount_per_month[$employeeId] = 0;
                $previous_in_hand_salary[$employeeId] = 0;
            }


            // Calculate CTC from table components only - convert all to float to avoid type errors
            $calculated_ctc = (float)$appraisal['gross_salary']
                + (float)$appraisal['pf_employer_contribution']
                + (float)$appraisal['esi_employer_contribution']
                + (float)$appraisal['lwf_employer_contribution']
                + (float)($appraisal['loyalty_incentive_amount_per_month'] ?? 0)
                + (float)($appraisal['non_compete_loan_amount_per_month'] ?? 0)
                //+ (float)$appraisal['bonus']
                + (float)$appraisal['other_benefits'];

            $arrAppraisalData[$employeeId]['appraisals'][$key] = [
                'ctc' => round($calculated_ctc), // Use calculated CTC instead of database CTC
                'ctc_total' => round($previous_ctc_total[$employeeId] += $calculated_ctc),
                'gross_salary' => round($appraisal['gross_salary']),
                'gross_salary_total' => round($previous_gross_salary[$employeeId] += $appraisal['gross_salary']),
                'basic_salary' => round($appraisal['basic_salary']),
                'monthly_el' => round($appraisal['basic_salary'] / 30 * 1.25),
                'monthly_el_total' => $previous_monthly_el[$employeeId] += round($appraisal['basic_salary'] / 30 * 1.25),
                'monthly_cl' => round($appraisal['gross_salary'] / 30 * 1),
                'monthly_cl_total' => $previous_monthly_cl[$employeeId] += round($appraisal['gross_salary'] / 30 * 1),
                'basic_salary_total' => $previous_basic_salary[$employeeId] += $appraisal['basic_salary'],
                'other_benefits' => round($appraisal['other_benefits']),
                'other_benefits_total' => round($previous_other_benefits[$employeeId] += $appraisal['other_benefits']),
                'house_rent_allowance' => round($appraisal['house_rent_allowance']),
                'house_rent_allowance_total' => round($previous_house_rent_allowance[$employeeId] += $appraisal['house_rent_allowance']),
                'conveyance' => round($appraisal['conveyance']),
                'conveyance_total' => round($previous_conveyance[$employeeId] += $appraisal['conveyance']),
                'medical_allowance' => round($appraisal['medical_allowance']),
                'medical_allowance_total' => round($previous_medical_allowance[$employeeId] += $appraisal['medical_allowance']),
                'special_allowance' => round($appraisal['special_allowance']),
                'special_allowance_total' => round($previous_special_allowance[$employeeId] += $appraisal['special_allowance']),
                'fuel_allowance' => round($appraisal['fuel_allowance']),
                'fuel_allowance_total' => round($previous_fuel_allowance[$employeeId] += $appraisal['fuel_allowance']),
                'vacation_allowance' => round($appraisal['vacation_allowance']),
                'vacation_allowance_total' => round($previous_vacation_allowance[$employeeId] += $appraisal['vacation_allowance']),
                'other_allowance' => round($appraisal['other_allowance']),
                'other_allowance_total' => round($previous_other_allowance[$employeeId] += $appraisal['other_allowance']),
                'gratuity' => round($appraisal['gratuity']),
                'gratuity_total' => round($previous_gratuity[$employeeId] += $appraisal['gratuity']),
                'enable_bonus' => $appraisal['enable_bonus'],
                'non_compete_loan_amount_per_month' => $appraisal['non_compete_loan_amount_per_month'] ?? 0,
                'bonus' => round($appraisal['bonus']),
                'bonus_total' => round($previous_bonus[$employeeId] += $appraisal['bonus']),
                'loyalty_incentive' => $appraisal['loyalty_incentive'],
                'loyalty_incentive_amount_per_month' => $appraisal['loyalty_incentive_amount_per_month'],
                'loyalty_incentive_total' => $previous_loyalty_incentive[$employeeId] += $appraisal['loyalty_incentive_amount_per_month'],
                'pf' => $appraisal['pf'],
                'pf_number' => $appraisal['pf_number'],
                'pf_employee_contribution' => round($appraisal['pf_employee_contribution']),
                'pf_employer_contribution' => round($appraisal['pf_employer_contribution']),
                'esi' => $appraisal['esi'],
                'esi_number' => $appraisal['esi_number'],
                'esi_employee_contribution' => round($appraisal['esi_employee_contribution']),
                'esi_employer_contribution' => round($appraisal['esi_employer_contribution']),
                'lwf' => $appraisal['lwf'],
                'lwf_employee_contribution' => round($appraisal['lwf_employee_contribution']),
                'lwf_employer_contribution' => round($appraisal['lwf_employer_contribution']),
                'lwf_deduction_on_every_n_month' => $appraisal['lwf_deduction_on_every_n_month'],
                'total_deductions' => $appraisal['pf_employee_contribution'] + $appraisal['esi_employee_contribution'] + $appraisal['lwf_employee_contribution'],
                'non_compete_loan' => $appraisal['non_compete_loan'],
                'non_compete_loan_amount_per_month' => round((float)($appraisal['non_compete_loan_amount_per_month'] ?? 0)),
                'non_compete_loan_amount_per_month_total' => $previous_non_compete_loan_amount_per_month[$employeeId] += (int)$appraisal['non_compete_loan_amount_per_month'] ?? 0,
                'appraisal_date' => isset($appraisal['appraisal_date']) && !empty($appraisal['appraisal_date']) ? date('d M Y', strtotime($appraisal['appraisal_date'])) : 'N/A',
                'appraisal_remarks' => $appraisal['appraisal_remarks'],
                'created_at' => isset($appraisal['created_at']) && !empty($appraisal['created_at']) ? date('d M Y', strtotime($appraisal['created_at'])) : 'N/A',
                'pf_employee_contribution_total' => round($previous_pf_employee_contribution[$employeeId] += $appraisal['pf_employee_contribution']),
                'pf_employer_contribution_total' => round($previous_pf_employer_contribution[$employeeId] += $appraisal['pf_employer_contribution']),
                'esi_employee_contribution_total' => round($previous_esi_employee_contribution[$employeeId] += $appraisal['esi_employee_contribution']),
                'esi_employer_contribution_total' => round($previous_esi_employer_contribution[$employeeId] += $appraisal['esi_employer_contribution']),
                'lwf_employee_contribution_total' => round($previous_lwf_employee_contribution[$employeeId] += $appraisal['lwf_employee_contribution']),
                'lwf_employer_contribution_total' => round($previous_lwf_employer_contribution[$employeeId] += $appraisal['lwf_employer_contribution']),
                'total_deductions_total' => round($appraisal['pf_employee_contribution'] + $appraisal['esi_employee_contribution'] + $appraisal['lwf_employee_contribution']),
                'in_hand_salary' => round($appraisal['gross_salary'] - ($appraisal['pf_employee_contribution'] + $appraisal['esi_employee_contribution'] + $appraisal['lwf_employee_contribution'])),
                'in_hand_salary_total' => round($previous_in_hand_salary[$employeeId] += $appraisal['gross_salary'] - ($appraisal['pf_employee_contribution'] + $appraisal['esi_employee_contribution'] + $appraisal['lwf_employee_contribution'])),
            ];
        }

        $data = [
            'employee_id' => $employee_id,
            'first_name' => $appraisal['first_name'],
            'last_name' => $appraisal['last_name'],
            'arrAppraisalData' => $arrAppraisalData,
        ];

        //return view('Appraisals/AppraisalsReportNew', $data);

        $options = new options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Appraisals/AppraisalsReportNew', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A3', 'landscape');
        $dompdf->render();
        $dompdf->stream("appraisal-report-" . strtolower($data['first_name'] . '-' . $data['last_name']) . "-.pdf");
    }
}
