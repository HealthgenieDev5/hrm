<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Controllers\Reports\FinalSalary;

class LoyaltyIncentive extends BaseController
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

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'Loyalty Incentive Report',
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

        return view('LoyaltyIncentive/LoyaltyIncentive', $data);
    }

    public function getAll()
    {
        $loyalty_incentive_data = null;
        $company_id     = $this->request->getVar('company');
        $department_id  = $this->request->getVar('department');
        $employee_id    = $this->request->getVar('employee');
        $range_from     = $this->request->getVar('year_month');
        $range_to       = !empty($this->request->getVar('year_month_to')) ? $this->request->getVar('year_month_to') : $this->request->getVar('year_month');

        if (strtotime($range_from) > strtotime($range_to)) {
            $temp_from = $range_from;
            $temp_to = $range_to;
            $range_from = $temp_to;
            $range_to = $temp_from;
        }
        $status = ['all_statuses'];
        $FinalSalary_controller = new FinalSalary();
        $loyaltyIncentive_data = [];
        $salary_data = $FinalSalary_controller->getAllSalary($company_id, $department_id, $employee_id, $status, $range_from, $range_to);
        if (!empty($salary_data)) {
            foreach ($salary_data as $row) {
                if (isset($row['salary_structure']) && !empty($row['salary_structure'])) {
                    $salary_structure = json_decode(json_encode($row['salary_structure']), true);
                    $loyalty_incentive_enabled = (isset($salary_structure['loyalty_incentive']) && $salary_structure['loyalty_incentive'] == 'yes') ? true : false;
                    $loyalty_incentive_from = (isset($salary_structure['loyalty_incentive_from']) && !empty($salary_structure['loyalty_incentive_from'])) ? $salary_structure['loyalty_incentive_from'] : "";
                    $loyalty_incentive_to = (isset($salary_structure['loyalty_incentive_to']) && !empty($salary_structure['loyalty_incentive_to'])) ? $salary_structure['loyalty_incentive_to'] : "";
                    $loyalty_incentive_amount = (isset($salary_structure['loyalty_incentive_amount_per_month']) && !empty($salary_structure['loyalty_incentive_amount_per_month'])) ? $salary_structure['loyalty_incentive_amount_per_month'] : 0;

                    $loyalty_incentive_mature_after_month = (isset($salary_structure['loyalty_incentive_mature_after_month']) && !empty($salary_structure['loyalty_incentive_mature_after_month'])) ? $salary_structure['loyalty_incentive_mature_after_month'] . " month" : "";
                    $loyalty_incentive_pay_after_month = (isset($salary_structure['loyalty_incentive_pay_after_month']) && !empty($salary_structure['loyalty_incentive_pay_after_month'])) ? $salary_structure['loyalty_incentive_pay_after_month'] . " month" : "";

                    $salary_month = $row['year'] . '-' . $row['month'] . '01';
                    if (
                        $row['loyalty_incentive'] > 0 ||
                        (
                            ($loyalty_incentive_enabled == true && $loyalty_incentive_amount > 0 && strtotime($salary_month) >= strtotime($loyalty_incentive_from) && empty($loyalty_incentive_to))
                            ||
                            ($loyalty_incentive_enabled == true && $loyalty_incentive_amount > 0 && strtotime($salary_month) >= strtotime($loyalty_incentive_from) && strtotime($salary_month) <= strtotime($loyalty_incentive_to))
                        )
                    ) {

                        $row_data = [];
                        $row_data['employee_id'] = $row['employee_id'];
                        $employee_data = isset($row['employee_data']) ? $row['employee_data'] : null;
                        if (!empty($employee_data)) {
                            $row_data['internal_employee_id'] = $employee_data['internal_employee_id'];
                            $row_data['employee_name'] = trim($employee_data['first_name'] . " " . $employee_data['last_name']);
                            $row_data['department_name'] = $employee_data['department_name'];
                            $row_data['company_short_name'] = $employee_data['company_short_name'];
                        } else {
                            $the_employee_id = $row_data['employee_id'];
                            $EmployeeModel = new EmployeeModel();
                            $EmployeeModel
                                ->select('employees.*')
                                ->select('companies.company_short_name as company_short_name')
                                ->select('departments.department_name as department_name')
                                ->join('companies', 'companies.id = employees.company_id', 'left')
                                ->join('departments', 'departments.id = employees.department_id', 'left')
                                ->where('employees.id =' . $the_employee_id);
                            $employee_data = $EmployeeModel->first();
                            if (!empty($employee_data)) {
                                $row_data['internal_employee_id'] = $employee_data['internal_employee_id'];
                                $row_data['employee_name'] = trim($employee_data['first_name'] . " " . $employee_data['last_name']);
                                $row_data['department_name'] = $employee_data['department_name'];
                                $row_data['company_short_name'] = $employee_data['company_short_name'];
                            }
                        }

                        $row_data['month'] = trim($row['month'] . " " . $row['year']);


                        $row_data['loyalty_incentive_from'] = !empty($loyalty_incentive_from) ? date('d M, Y', strtotime($loyalty_incentive_from)) : "";
                        $row_data['loyalty_incentive_to'] = !empty($loyalty_incentive_to) ? date('d M, Y', strtotime($loyalty_incentive_to)) : "";
                        $row_data['loyalty_incentive'] = $loyalty_incentive_amount;
                        $row_data['loyalty_incentive_mature_after_month'] = $loyalty_incentive_mature_after_month;
                        $row_data['loyalty_incentive_pay_after_month'] = $loyalty_incentive_pay_after_month;

                        $row_data['working_days'] = $row['final_paid_days'];

                        $final_paid_days = $row['final_paid_days'];
                        /*if( $final_paid_days < 12 ){
                            $eligibility = "0%";
                        }elseif( $final_paid_days >= 12 && $final_paid_days < 18 ){
                            $eligibility = "50%";
                        }else{
                            $eligibility = "100%";
                        }
                        $row_data['eligibility'] = $eligibility;*/

                        $row_data['payable'] = $row['loyalty_incentive'];

                        $attachment = !empty($row['employee_attachment']) ? $row['employee_attachment'] : "";
                        $bank_account = isset($attachment['bank_account']) && !empty($attachment['bank_account']) ? $attachment['bank_account'] : "";
                        $bank_account_number = isset($bank_account['number']) && !empty($bank_account['number']) ? $bank_account['number'] : "";
                        $row_data['bank_account'] = $bank_account_number;


                        $loyalty_incentive_data[] = $row_data;
                    }
                }
            }
        }

        echo json_encode($loyalty_incentive_data);
    }
}
