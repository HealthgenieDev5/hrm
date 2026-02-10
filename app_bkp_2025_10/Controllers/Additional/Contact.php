<?php

namespace App\Controllers\Additional;

use App\Models\EmployeeModel;
use App\Controllers\BaseController;

class Contact extends BaseController
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
        $data = [
            'page_title'            => 'Contact List',
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'all_contacts'          => $this->getAllContacts(),
        ];


        return view('Contacts/ContactList', $data);
    }

    public function getAllContacts()
    {
        $EmployeeModel = new EmployeeModel();
        $ContactList =
            $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.role as role')
            ->select('employees.attachment as attachment')
            ->select('employees.first_name as first_name')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('employees.desk_location as desk_location')
            ->select('employees.joining_date as joining_date')
            ->select('employees.status as status')
            ->select('employees.gender as gender')
            ->select('employees.date_of_birth as date_of_birth')
            ->select('employees.personal_mobile as personal_mobile')
            ->select('employees.work_email as work_email')
            ->select('employees.work_mobile as work_mobile')
            ->select('employees.work_phone_extension_number as work_phone_extension_number')
            ->select('employees.work_phone_cug_number as work_phone_cug_number')
            ->select('employees.desk_location as desk_location')
            ->select('trim( concat( employees.first_name, " ", employees.last_name ) ) as employee_name, ')
            ->select('ifnull(trim( concat( reporting_managers.first_name, " ", reporting_managers.last_name ) ), "") as reporting_manager_name, ')
            ->select('ifnull(trim( concat( hods.first_name, " ", hods.last_name ) ), "") as hod_name, ')
            ->select('companies.company_name as company_name')
            ->select('companies.company_short_name as company_short_name')
            ->select('departments.department_name as department_name')
            ->select('designations.designation_name as designation_name')
            ->select('shifts.shift_name as shift_name')
            ->select('raw_attendance.INTime as INTime')
            ->select('raw_attendance.OUTTime as OUTTime')
            ->join('raw_attendance as raw_attendance', 'raw_attendance.Empcode = employees.internal_employee_id and raw_attendance.DateString_2 = CURDATE()', 'left')
            ->join('employees as reporting_managers', 'reporting_managers.id = employees.reporting_manager_id', 'left')
            ->join('companies as companies', 'companies.id = employees.company_id', 'left')
            ->join('departments as departments', 'departments.id = employees.department_id', 'left')
            ->join('employees as hods', 'hods.id = departments.hod_employee_id', 'left')
            ->join('designations as designations', 'designations.id = employees.designation_id', 'left')
            ->join('shifts as shifts', 'shifts.id = employees.shift_id', 'left')
            ->where('employees.status =', 'active')
            ->findAll();

        if (!empty($ContactList)) {
            return $ContactList;
        } else {
            return null;
        }
    }
}
