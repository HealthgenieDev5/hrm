<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Controllers\Attendance\Processor;

class FinalAttendance extends BaseController
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



        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'            => 'Attendance Report',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'range_from'            => isset($_REQUEST['range_from']) ? $_REQUEST['range_from'] : date('F, Y', strtotime(first_date_of_last_month())),
            'range_to'              => isset($_REQUEST['range_to']) ? $_REQUEST['range_to'] : date('F, Y', strtotime(first_date_of_last_month())),
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
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $sql = "select
            e.id as id,
            e.internal_employee_id as internal_employee_id,
            trim(concat(e.first_name, ' ', e.last_name)) as employee_name,
            d.department_name as department_name,
            c.company_short_name as company_short_name
            from employees e
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id
            where e.id is not null " . $where_department . "
            and
            (
                e.date_of_leaving is null
                or
                e.date_of_leaving >= '" . $date_45_days_before . "'
            )
            and
            (
                e.reporting_manager_id = '" . $current_user['employee_id'] . "'
                or d.hod_employee_id = '" . $current_user['employee_id'] . "'
                " . $current_user['role'] . " in ('admin', 'superuser', 'hr')
            )
            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }


        return view('Salary/FinalAttendanceAll', $data);
    }

    public function generateAttendance($employee_id)
    {
        $from = date('Y-m-01');
        $to = date('Y-m-d');
        $from = isset($_GET['last_month']) && !empty($_GET['last_month']) && $_GET['last_month'] == 'yes' ? first_date_of_last_month() : date('Y-m-01');
        $to = isset($_GET['last_month']) && !empty($_GET['last_month']) && $_GET['last_month'] == 'yes' ? last_date_of_last_month() : date('Y-m-d');

        $EmployeeModel = new EmployeeModel();
        $Employees = $EmployeeModel
            ->select('employees.*')
            ->select('d.department_name as department_name')
            ->select('c.company_name as company_name')
            ->select('c.company_short_name as company_short_name')
            ->select('deg.designation_name as designation_name')
            ->select('s.shift_name as shift_name')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
            ->join('departments as d', 'd.id = employees.department_id', 'left')
            ->join('companies as c', 'c.id = employees.company_id', 'left')
            ->join('designations as deg', 'deg.id = employees.designation_id', 'left')
            ->join('shifts as s', 's.id = employees.shift_id', 'left')
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left')
            ->where('employees.id=', $employee_id)
            ->first();

        if (!empty($Employees)) {
            $employee_id = $Employees['id'];

            $joiningDate = $Employees['joining_date'];

            if( strtotime($joiningDate) > strtotime($from) ){
                $from = $joiningDate;
            }

            $PunchingData = Processor::getProcessedPunchingData($employee_id, $from, $to);

            foreach ($PunchingData as $rowIndex => $rowData) {
                $PunchingData[$rowIndex]['shift_start'] = (!empty($rowData['shift_start'])) ? date('H:i:s', strtotime($rowData['shift_start'])) : null;
                $PunchingData[$rowIndex]['shift_end'] = (!empty($rowData['shift_end'])) ? date('H:i:s', strtotime($rowData['shift_end'])) : null;
                $PunchingData[$rowIndex]['in_time_between_shift_with_od'] = (!empty($rowData['in_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['in_time_between_shift_with_od'])) : null;
                $PunchingData[$rowIndex]['out_time_between_shift_with_od'] = (!empty($rowData['out_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['out_time_between_shift_with_od'])) : null;
                $PunchingData[$rowIndex]['punch_in_time'] = (!empty($rowData['punch_in_time'])) ? date('H:i:s', strtotime($rowData['punch_in_time'])) : null;
                $PunchingData[$rowIndex]['punch_out_time'] = (!empty($rowData['punch_out_time'])) ? date('H:i:s', strtotime($rowData['punch_out_time'])) : null;
                $PunchingData[$rowIndex]['in_time_including_od'] = (!empty($rowData['in_time_including_od'])) ? date('H:i:s', strtotime($rowData['in_time_including_od'])) : null;
                $PunchingData[$rowIndex]['out_time_including_od'] = (!empty($rowData['out_time_including_od'])) ? date('H:i:s', strtotime($rowData['out_time_including_od'])) : null;
                $PunchingData[$rowIndex]['late_coming_grace'] = $rowData['grace'];
                $PunchingData[$rowIndex]['wave_off_minutes'] = $rowData['wave_off_minutes'];
                $PunchingData[$rowIndex]['wave_off_remarks'] = $rowData['wave_off_remarks'];
                $PunchingData[$rowIndex]['leave_request_type'] = (!empty($rowData['LeaveData'])) ? $rowData['LeaveData']['type_of_leave'] : '';
                $PunchingData[$rowIndex]['leave_request_amount'] = (!empty($rowData['LeaveData'])) ? ($rowData['LeaveData']['number_of_days'] > 1) ? 1 : $rowData['LeaveData']['number_of_days'] : '';
                $PunchingData[$rowIndex]['leave_request_status'] = (!empty($rowData['LeaveData'])) ? $rowData['LeaveData']['status'] : '';
            }
            return $PunchingData;
        } else {
            return null;
        }
    }


    public function attendanceSheet()
    {

        $company_id     = isset($_REQUEST['company']) ? $_REQUEST['company'] : [];
        $department_id  = isset($_REQUEST['department']) ? $_REQUEST['department'] : [];
        $employee_id    = isset($_REQUEST['employee']) ? $_REQUEST['employee'] : [];

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_short_name')
            ->join('companies', 'companies.id = employees.company_id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left');

        if (isset($company_id) && !empty($company_id) && !in_array('all_companies', $company_id) && !in_array('', $company_id)) {
            $EmployeeModel->whereIn('employees.company_id', $company_id);
        }
        if (isset($department_id) && !empty($department_id) && !in_array('all_departments', $department_id) && !in_array('', $department_id)) {
            $EmployeeModel->whereIn('employees.department_id', $department_id);
        }
        if (isset($employee_id) && !empty($employee_id) && !in_array('all_employees', $employee_id) && !in_array('', $employee_id)) {
            $EmployeeModel->whereIn('employees.id', $employee_id);
        }

        if (!in_array($current_user['role'], ['admin', 'superuser', 'hr'])) {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhere('employees.id =', $current_user['employee_id']);
            $EmployeeModel->groupEnd();
        }

        $Employees = $EmployeeModel->findAll();




        ######for filter######
        $data = [
            'page_title'            => 'Current Month Attendance',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(4),
            'month'                 => isset($_REQUEST['month']) ? $_REQUEST['month'] : date('Y-m', strtotime(first_date_of_month())),
            'Companies'             => $Companies,
        ];
        // dd($Employees);
        $data['ATTENDANCEData'] = $this->loadAttendanceSheet($Employees);
        // $data['ATTENDANCEData'] = [];

        $where_company = "";
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

        //$date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        // $sql = "select
        //     e.id as id,
        //     e.internal_employee_id as internal_employee_id,
        //     trim(concat(e.first_name, ' ', e.last_name)) as employee_name,
        //     d.department_name as department_name,
        //     c.company_short_name as company_short_name
        //     from employees e
        //     left join departments d on d.id = e.department_id
        //     left join companies c on c.id = e.company_id
        //     where e.id is not null ".$where_department."
        //     and
        //     (
        //         e.date_of_leaving is null
        //         or
        //         e.date_of_leaving >= '".$date_45_days_before."'
        //     )
        //     order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
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
        ######for filter######

        // return view('FinalAttendance/FinalAttendanceSheet', $data);
        return view('FinalAttendance/FinalAttendanceSheet', $data);
    }

    public function loadAttendanceSheet($Employees)
    {
        $range_from = date('Y-m-01');
        $range_to = date('Y-m-t');

        $MonthDates = date_range_between($range_from, $range_to);
        $FinalPaidDaysData = [];
        foreach ($Employees as $Employee) {
            $employeeData = [];
            $employeeData['employee_name'] = trim($Employee['first_name'] . ' ' . $Employee['last_name']);
            $employeeData['employee_code'] = $Employee['internal_employee_id'];
            $employeeData['company_short_name'] = $Employee['company_short_name'];

            $PreFinalPaidDays = $this->generateAttendance($Employee['id']);

            $PreFinalPaidDays_Data = array();
            if (!empty($PreFinalPaidDays)) {
                foreach ($PreFinalPaidDays as $PaidDayData) {
                    $PreFinalPaidDays_Data[$PaidDayData['date']] = $PaidDayData;
                }
            } else {
            }
            $employeeData['PreFinalPaidDays_Data'] = $PreFinalPaidDays_Data;
            if (!empty($PreFinalPaidDays)) {
                $employeeData['dates'] = array_column($PreFinalPaidDays, 'date');
            }

            $FinalPaidDaysData[] = $employeeData;
        }

        return $FinalPaidDaysData;
    }
}
