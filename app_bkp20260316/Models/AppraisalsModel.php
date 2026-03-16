<?php

namespace App\Models;

use CodeIgniter\Model;

class AppraisalsModel extends Model
{
    protected $table = 'appraisals';
    protected $allowedFields = [
        'employee_id',
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
        'other_benefits',
        'gratuity',
        'enable_bonus',
        'bonus',
        'non_compete_loan',
        'non_compete_loan_from',
        'non_compete_loan_to',
        'non_compete_loan_amount_per_month',
        'non_compete_loan_remarks',
        'loyalty_incentive',
        'loyalty_incentive_from',
        'loyalty_incentive_to',
        'loyalty_incentive_amount_per_month',
        'loyalty_incentive_mature_after_month',
        'loyalty_incentive_pay_after_month',
        'loyalty_incentive_remarks',
        'pf',
        'pf_number',
        'pf_employee_contribution',
        'pf_employer_contribution',
        'esi',
        'esi_number',
        'esi_employee_contribution',
        'esi_employer_contribution',
        'lwf',
        'lwf_number',
        'lwf_employee_contribution',
        'lwf_employer_contribution',
        'lwf_deduction_on_every_n_month',
        'salary_details',
        'appraisal_remarks',
        'appraisal_date',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function getFilteredAllAppraisalsData($arrCompanyId = [], $arrDepartmentId = [], $arrEmployeeId = [])
    {
        $this->select('appraisals.*')
            ->select('employees.first_name')
            ->select('employees.last_name')
            ->select('employees.internal_employee_id')
            ->select('employees.designation_id')
            ->select('employees.department_id')
            ->select('employees.company_id')
            ->select('designations.designation_name')
            ->select('departments.department_name')
            ->select('companies.company_name')
            ->select('companies.company_short_name');
        $this->join('employees', 'employees.id = appraisals.employee_id', 'left');
        $this->join('designations', 'designations.id = employees.designation_id', 'left');
        $this->join('departments', 'departments.id = employees.department_id', 'left');
        $this->join('companies', 'companies.id = employees.company_id', 'left');
        $this->where('employees.status', 'Active');
        $this->orderBy('appraisals.appraisal_date', 'DESC');

        if (!empty($arrCompanyId) && !in_array('all_companies', $arrCompanyId)) {
            $this->whereIn('employees.company_id', $arrCompanyId);
        }
        if (!empty($arrDepartmentId && !in_array('all_departments', $arrDepartmentId))) {
            $this->whereIn('employees.department_id', $arrDepartmentId);
        }
        if (!empty($arrEmployeeId && !in_array('all_employees', $arrEmployeeId))) {
            $this->whereIn('employees.id', $arrEmployeeId);
        }

        // $this->where('appraisals.appraisal_remarks', 'Imported from employee_salary table');

        $data = $this->get()->getResultArray();

        // --- 🔎 Filtering logic (same as your JS `dataSrc`) ---
        if (empty($data)) {
            return [];
        }

        // 1. Group appraisals by employee_id
        $employees = [];
        foreach ($data as $appraisal) {
            $appraisal['total_appraisal'] = $appraisal['basic_salary'] +
                $appraisal['house_rent_allowance'] +
                $appraisal['conveyance'] +
                $appraisal['medical_allowance'] +
                $appraisal['special_allowance'] +
                $appraisal['fuel_allowance'] +
                $appraisal['vacation_allowance'] +
                $appraisal['other_allowance'] +
                $appraisal['bonus'] +
                $appraisal['non_compete_loan_amount_per_month'] +
                $appraisal['loyalty_incentive_amount_per_month'] +
                $appraisal['pf_employer_contribution'] +
                $appraisal['esi_employer_contribution'] +
                $appraisal['lwf_employer_contribution'] +
                $appraisal['other_benefits'];

            $employees[$appraisal['employee_id']][] = $appraisal;
        }

        $result = [];

        // 2. For each employee’s group
        foreach ($employees as $employeeId => $appraisals) {
            // sort by appraisal_date ASC
            usort($appraisals, function ($a, $b) {
                //return strtotime($a['id']) <=> strtotime($b['id']);
                return $a['id'] <=> $b['id'];
            });

            // 3. Drop the first record (earliest appraisal)
            $remaining = array_slice($appraisals, 1);

            // 4. Merge into flat list
            $result = array_merge($result, $remaining);
            // $result = array_merge($result, $appraisals);
        }

        // dd($result);

        return $result;
    }


    public function getFilteredEmployeesAppraisals($arrCompanyId = [], $arrDepartmentId = [], $arrEmployeeId = [])
    {
        $this->select('appraisals.*, employees.first_name, employees.last_name, employees.internal_employee_id, employees.designation_id, employees.department_id, employees.company_id');
        $this->join('employees', 'employees.id = appraisals.employee_id', 'left');
        $this->where('employees.status', 'Active');
        if (!empty($arrCompanyId) && !in_array('all_companies', $arrCompanyId)) {
            $this->whereIn('employees.company_id', $arrCompanyId);
        }
        if (!empty($arrDepartmentId && !in_array('all_departments', $arrDepartmentId))) {
            $this->whereIn('employees.department_id', $arrDepartmentId);
        }
        if (!empty($arrEmployeeId && !in_array('all_employees', $arrEmployeeId))) {
            $this->whereIn('employees.id', $arrEmployeeId);
        }
        //$this->db->getLastQuery();
        return $this->get()->getResult();
    }

    public function getEmloyeeAppraisals($employeeId)
    {
        $this->select('appraisals.*');
        $this->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name");
        $this->select('employees.internal_employee_id as employee_code');
        $this->join('employees', 'employees.id = appraisals.employee_id', 'left');
        $this->where('employees.status', 'Active');
        $this->where('employees.id', $employeeId);
        $this->orderBy('appraisals.id', 'DESC');
        return $this->get()->getResult();
    }

    public function getAppraisalWithEmployee($id)
    {
        return $this->select('appraisals.*, employees.first_name, employees.last_name, employees.internal_employee_id')
            ->join('employees', 'employees.id = appraisals.employee_id', 'left')
            ->where('appraisals.id', $id)
            ->first(); // Use first() to get a single result
    }
}
