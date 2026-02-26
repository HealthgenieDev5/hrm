<?php

namespace App\Controllers\Master;

use App\Models\CompanyModel;
use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Models\SpecialHolidayEmployeesModel;

class SpecialHoliday extends BaseController
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
        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();
        // echo '<pre>';
        // print_r($this->getAllEmployees('array'));
        // echo '</pre>';
        // die();

        $data = [
            'page_title'            => 'SpecialHoliday1111  ',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'special_holidays'      => $this->getSpecialHolidays(),
            'AllEmployees'          => $this->getAllEmployees('array'),
            'Companies'             => $Companies,
        ];

        return view('SpecialHoliday/SpecialHoliday', $data);
    }

    public function getSpecialHolidays()
    {
        $HolidayModel = new HolidayModel();
        $AllSpecialHolidays =
            $HolidayModel
            ->select('holidays.*')
            ->select('special_holiday_employees.employee_id as employee_id')
            ->join('special_holiday_employees as special_holiday_employees', 'special_holiday_employees.holiday_id = holidays.id', 'left')
            ->where('holidays.holiday_code =', 'SPL HL')
            ->findAll();

        if (!empty($AllSpecialHolidays)) {
            return $AllSpecialHolidays;
        } else {
            return null;
        }
    }

    public function getAllEmployees($returnType = 'json')
    {
        // print_r($_REQUEST);
        // die();
        $EmployeeModel = new EmployeeModel();

        $holiday_id  = $this->request->getVar('holiday_id');
        $HolidayModel = new HolidayModel();
        $holiday = $HolidayModel->find($holiday_id);
        $holiday_date = $holiday['holiday_date'];

        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.joining_date as joining_date')
            ->select('employees.date_of_leaving as date_of_leaving')
            ->select('employees.company_id as company_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('employees.machine as machine')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            // ->select("(select id from special_holiday_employees where special_holiday_employees.employee_id = employees.id and special_holiday_employees.holiday_id = '".$holiday_id."' limit 1) as special_holiday_employee_id")
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left');
        // ->join('special_holiday_employees as special_holiday_employees', 'special_holiday_employees.employee_id = employees.id', 'left');

        $company_id = $this->request->getVar('company_id');
        if (!empty($company_id) && $company_id !== 'all_companies') {
            $EmployeeModel->where('employees.company_id =', $company_id);
        }

        $machine = $this->request->getVar('machine');
        if (!empty($machine) && $machine !== 'all_machines') {
            $EmployeeModel->where('employees.machine =', $machine);
        }

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('{$holiday_date}')");
        $EmployeeModel->groupEnd();

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.joining_date is null');
        $EmployeeModel->orWhere("employees.joining_date <= ('{$holiday_date}')");
        $EmployeeModel->groupEnd();

        $EmployeeModel->orderBy('employees.first_name', 'ASC');

        $AllEmployees = $EmployeeModel->findAll();
        // print_r($AllEmployees);
        // die;

        $response_array = array();
        $response_array['response_type'] = 'success';
        $response_array['response_description'] = 'Employees Found';
        $response_array['response_data']['employees'] = $AllEmployees;
        return $returnType == 'array' ? $AllEmployees : $this->response->setJSON($response_array);
    }

    public function updateSpecialHoliday()
    {
        $response_array = array();

        $holiday_id = $this->request->getPost('holiday_id');
        $employee_id = implode(",", $this->request->getPost('employee_id'));

        $data = [
            'holiday_id' => $holiday_id,
            'employee_id' => $employee_id,
        ];
        $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
        $specialHolidayRecord = $SpecialHolidayEmployeesModel->select('special_holiday_employees.id as special_holiday_employees_record_row_id')->where('holiday_id=', $holiday_id)->first();
        if (!empty($specialHolidayRecord['special_holiday_employees_record_row_id'])) {
            #update
            $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
            $updateSpecialHolidayEmployeesQuery = $SpecialHolidayEmployeesModel->update($specialHolidayRecord['special_holiday_employees_record_row_id'], $data);
        } else {
            #insert
            $SpecialHolidayEmployeesModel = new SpecialHolidayEmployeesModel();
            $updateSpecialHolidayEmployeesQuery = $SpecialHolidayEmployeesModel->insert($data);
        }

        if ($updateSpecialHolidayEmployeesQuery) {
            $response_array['response_type'] = 'success';
            $response_array['response_description'] = 'Special holiday employee list updated';
        } else {
            $response_array['response_type'] = 'error';
            $response_array['response_description'] = 'DB Error: Unable to update special holiday employees list';
        }

        return $this->response->setJSON($response_array);
    }
}
