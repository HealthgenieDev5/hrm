<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Controllers\BaseController;

class ReportAjax extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function getDepartmentByCompanyId()
    {
        $response_array = array();

        $company_ids = $this->request->getPost('company_id');

        if (!empty($company_ids) && !in_array('all_companies', $company_ids) && !in_array('', $company_ids)) {
            $company_ids_imploded = "'" . implode("', '", $company_ids) . "'";
            $where = " and d.company_id in (" . $company_ids_imploded . ") ";
        } else {
            $where = " ";
        }

        $sql = "select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null " . $where . " order by c.company_short_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
        } else {
            $departments = $query->getResultArray();
            if (!empty($departments)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Departments found';
                $response_array['response_data']['departments'] = $departments;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'No Department is associated with this company';
            }
        }

        return $this->response->setJSON($response_array);
    }

    public function getEmployeesByDepartmentId()
    {

        $response_array = array();
        $company_ids = $this->request->getPost('company_id');
        $department_ids = $this->request->getPost('department_id');

        //$date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $where = " ";
        if (!empty($company_ids) && !in_array('all_companies', $company_ids) && !in_array('', $company_ids)) {
            $company_ids_imploded = "'" . implode("', '", $company_ids) . "'";
            $where .= " and e.company_id in (" . $company_ids_imploded . ") ";
        } else {
            $where .= " ";
        }

        if (!empty($department_ids) && !in_array('all_departments', $department_ids) && !in_array('', $department_ids)) {
            $department_ids_imploded = "'" . implode("', '", $department_ids) . "'";
            $where .= " and e.department_id in (" . $department_ids_imploded . ") ";
        } else {
            $where .= " ";
        }

        // $sql = "select 
        // e.id as id, 
        // e.internal_employee_id as internal_employee_id, 
        // trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
        // d.department_name as department_name, 
        // c.company_short_name as company_short_name 
        // from employees e 
        // left join departments d on d.id = e.department_id 
        // left join companies c on c.id = e.company_id 
        // where 
        // e.id is not null ".$where." 
        // and 
        // ( 
        //     e.date_of_leaving is null 
        //     or 
        //     e.date_of_leaving >= '".$date_45_days_before."'
        // ) 
        // order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";

        // modified query to show all employees irrespective of leaving date -> requested by santu couse he wants to see all employees
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
        where 
        e.id is not null " . $where . " 
        order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";

        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'DB:Error <br> Please contact administrator.';
        } else {
            $employees = $query->getResultArray();
            if (!empty($employees)) {
                $response_array['response_type'] = 'success';
                $response_array['response_description'] = 'Employees found';
                $response_array['response_data']['employees'] = $employees;
                $response_array['response_data']['sql'] = $sql;
            } else {
                $response_array['response_type'] = 'error';
                $response_array['response_description'] = 'No Employee is associated with this department';
            }
        }
        return $this->response->setJSON($response_array);
    }
}
