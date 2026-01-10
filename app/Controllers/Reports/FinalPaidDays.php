<?php

namespace App\Controllers\Reports;

use App\Controllers\Attendance\Processor;
use App\Controllers\BaseController;
use App\Controllers\Cron\FinalSalary;
use App\Libraries\AttendanceProcessor;
use App\Models\CompanyModel;
use App\Models\CustomModel;
use App\Models\EmployeeModel;
use App\Models\FinalPaidDaysModel;
use App\Models\LeaveBalanceModel;
use App\Models\PreFinalPaidDaysModel;
use App\Models\PreFinalSalaryModel;

class FinalPaidDays extends BaseController
{
    public $session;

    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session = session();
    }

    public function index()
    {

        if (! in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel;
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel;
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title' => 'Final Paid Days',
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(3),
            'range_from' => isset($_REQUEST['range_from']) ? $_REQUEST['range_from'] : date('F, Y', strtotime(first_date_of_last_month())),
            'range_to' => isset($_REQUEST['range_to']) ? $_REQUEST['range_to'] : date('F, Y', strtotime(first_date_of_last_month())),
            'Companies' => $Companies,
        ];

        $where_company = ' ';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_company .= " and d.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_company .= ' ';
        }
        $sql = 'select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null '.$where_company.' order by c.company_short_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['departments_not_found'] = 'There was an error fetching departments from database';
        } else {
            $data['Departments'] = $query->getResultArray();
        }

        $where_department = ' ';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_department .= " and e.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_department .= ' ';
        }

        if (isset($_REQUEST['department']) && ! empty($_REQUEST['department']) && ! in_array('all_departments', $_REQUEST['department'])) {
            $where_department .= " and e.department_id in ('".implode("', '", $_REQUEST['department'])."')";
        } else {
            $where_department .= ' ';
        }
        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

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
            where e.id is not null ".$where_department.'

            and
            (
                e.date_of_leaving is null
            )

            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['employees_not_found'] = 'There was an error fetching employees from database';
        } else {
            $data['Employees'] = $query->getResultArray();
        }

        dd($data);

        return view('Salary/FinalPaidDaysAll', $data);
    }

    public function loadFinalPaidDays($params = false, $return = false)
    {
        if (! $return) {
            $filter = $this->request->getPost('filter');
            parse_str($filter, $params);
        }

        $company_id = isset($params['company']) ? $params['company'] : '';
        $department_id = isset($params['department']) ? $params['department'] : '';
        $employee_id = isset($params['employee']) ? $params['employee'] : '';
        $status = isset($params['status']) ? $params['status'] : '';
        $range_from = isset($params['range_from']) ? $params['range_from'] : '';
        $range_to = isset($params['range_to']) ? $params['range_to'] : '';

        $FinalPaidDaysModel = new FinalPaidDaysModel;
        $FinalPaidDaysModel
            ->select('final_paid_days.employee_id as employee_id')
            ->select('e.internal_employee_id as internal_employee_id')
            ->select('trim(concat(e.first_name, " ", e.last_name)) as employee_name')
            ->select('sum(final_paid_days.final_paid) as final_paid_days')
            ->select('count(final_paid_days.id) as month_days')
            ->select("date_format(final_paid_days.date, '%m') as month")
            ->select('year(final_paid_days.date) as year')
            ->join('employees as e', 'e.id = final_paid_days.employee_id', 'left')
            ->join('companies as c', 'c.id = e.company_id', 'left')
            ->join('departments as dep', 'dep.id = e.department_id', 'left');

        if (! empty($range_from) && ! empty($range_to)) {
            $date_from = date('Y-m-01', strtotime($range_from));
            $date_to = date('Y-m-t', strtotime($range_to));
            $FinalPaidDaysModel->where("( final_paid_days.date between '".$date_from."' and '".$date_to."' )");
        }

        if (! empty($company_id) && ! in_array('all_companies', $company_id)) {
            $FinalPaidDaysModel->whereIn('c.id', $company_id);
        }

        if (! empty($department_id) && ! in_array('all_departments', $department_id)) {
            $FinalPaidDaysModel->whereIn('dep.id', $department_id);
        }

        if (! empty($employee_id) && ! in_array('all_employees', $employee_id)) {
            $FinalPaidDaysModel->whereIn('final_paid_days.employee_id', $employee_id);
        }

        if (! empty($status) && ! in_array('all_statuses', $status)) {
            $FinalPaidDaysModel->whereIn('final_paid_days.disbursed', $status);
        }

        $FinalPaidDaysModel->groupBy('final_paid_days.employee_id');
        $FinalPaidDaysModel->groupBy('year(final_paid_days.date)');
        $FinalPaidDaysModel->groupBy("date_format(final_paid_days.date, '%m')");
        $FinalPaidDaysModel->orderBy('final_paid_days.date', 'DESC');

        $FinalPaidDays = $FinalPaidDaysModel->findAll();

        foreach ($FinalPaidDays as $index => $FinalPaidDaysRow) {
            foreach ($FinalPaidDaysRow as $FieldIndex => $FieldValue) {
                if (is_numeric($FieldValue) && floor($FieldValue) !== $FieldValue) {
                    $FinalPaidDaysRow[$FieldIndex] = round($FieldValue, 2);
                }
                if ($FieldIndex == 'month') {
                    $FinalPaidDaysRow[$FieldIndex] = date('F', strtotime(date('Y-'.$FieldValue.'-01')));
                }
            }
            $FinalPaidDays[$index] = $FinalPaidDaysRow;
        }

        // if ($return) {
        //     return $FinalPaidDays;
        // }
        echo json_encode($FinalPaidDays);
    }

    public function single($employee_id, $get_salary_range)
    {
        if (! empty($employee_id)) {
            $current_user = $this->session->get('current_user');
            $CustomModel = new CustomModel;
            $CustomSql = 'select
            e.id as id,
            e.internal_employee_id as internal_employee_id,
            e.first_name as first_name,
            e.last_name as last_name,
            e.shift_id as shift_id,
            d.department_name,
            c.company_name
            from employees e
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id
            order by e.first_name
            ';
            $employees = $CustomModel->CustomQuery($CustomSql)->getResultArray();

            $data = [
                'page_title' => 'Final Paid Days',
                'current_controller' => $this->request->getUri()->getSegment(2),
                'current_method' => $this->request->getUri()->getSegment(3),
                'employees' => $employees,
                'employee_id' => $employee_id,
                'salary_range' => ! empty($get_salary_range) ? $get_salary_range : date('F, Y', strtotime(first_date_of_last_month())),
            ];

            $settlement_array = ['None' => ['None' => '0']];
            $settlement_balance_array = ['None' => 'infinite'];
            $LeaveBalanceModel = new LeaveBalanceModel;
            $LeaveBalance = $LeaveBalanceModel
                ->where('employee_id =', $employee_id)
                ->where('year =', date('Y', strtotime($data['salary_range'])))
                ->where('month =', date('m', strtotime($data['salary_range'])))
                ->where('leave_code !=', 'EL')
                ->findAll();
            foreach ($LeaveBalance as $balance) {
                if ($balance['balance'] > 0) {
                    if (strtoupper($balance['leave_code']) == 'CL') {
                        $settlement_array[$balance['leave_code']] = ['Full day' => '+1', 'Half day' => '+0.5'];
                    } else {
                        $settlement_array[$balance['leave_code']] = ['Full day' => '+1'];
                    }
                }
                $settlement_balance_array[$balance['leave_code']] = $balance['balance'];
            }
            $settlement_array['OD'] = ['Full day' => '+1', 'Half day' => '+0.5'];
            $settlement_balance_array['OD'] = 'infinite';
            $settlement_array['OT'] = ['Full day' => '+1', 'Half day' => '+0.5'];
            $settlement_balance_array['OT'] = 'infinite';
            $settlement_array['Wave Off'] = ['Full day' => '+1', 'Half day' => '+0.5'];
            $settlement_balance_array['Wave Off'] = 'infinite';
            $settlement_array['Deduction'] = ['Full day' => '-1', 'Half day' => '-0.5'];
            $settlement_balance_array['Deduction'] = 'infinite';
            $data['settlement_array'] = $settlement_array;
            $data['settlement_balance_array'] = $settlement_balance_array;

            $from = date('Y-m-01', strtotime($data['salary_range']));
            $to = date('Y-m-t', strtotime($data['salary_range']));

            $FinalPaidDaysModel = new FinalPaidDaysModel;
            $paid_days_data = $FinalPaidDaysModel
                ->select('final_paid_days.*')
                ->select('e.internal_employee_id as internal_employee_id')
                ->select("trim(concat(e2.first_name, ' ', e2.last_name)) as settled_by_name")
                ->join('employees e', 'e.id = final_paid_days.employee_id', 'left')
                ->join('employees e2', 'e2.id = final_paid_days.settled_by', 'left')
                ->where('final_paid_days.employee_id =', $employee_id)
                ->where("(final_paid_days.date between '".$from."' and '".$to."')")
                ->findAll();
            foreach ($paid_days_data as $index => $salary_row) {
                $salary_row['date'] = date('d M Y', strtotime($salary_row['date']));
                $salary_row['shift']['shift_start'] = ! empty($salary_row['shift_start']) ? date('h:i A', strtotime($salary_row['shift_start'])) : '-';
                $salary_row['shift']['shift_end'] = ! empty($salary_row['shift_end']) ? date('h:i A', strtotime($salary_row['shift_end'])) : '-';

                $salary_row['late_coming_rule'] = ! empty($salary_row['late_coming_rule']) ? json_decode($salary_row['late_coming_rule'], true) : '-';

                $salary_row['in_time'] = ! empty($salary_row['in_time']) ? date('h:i A', strtotime($salary_row['in_time'])) : '-';
                $salary_row['out_time'] = ! empty($salary_row['out_time']) ? date('h:i A', strtotime($salary_row['out_time'])) : '-';
                $salary_row['work_hours'] = ! empty($salary_row['work_hours']) ? date('H:i', strtotime($salary_row['work_hours'])) : '-';
                $salary_row['od_hours'] = ! empty($salary_row['od_hours']) ? date('H:i', strtotime($salary_row['od_hours'])) : '-';
                $salary_row['settlement'] = ! empty($salary_row['settlement']) ? $salary_row['settlement'] : '-';
                $salary_row['settlement_remarks'] = ! empty($salary_row['settlement_remarks']) ? $salary_row['settlement_remarks'] : '-';
                $salary_row['settled_by_name'] = ! empty($salary_row['settled_by_name']) ? $salary_row['settled_by_name'] : '-';

                if (! empty($salary_row['settlement_type'])) {
                    $salary_row['status'] = $salary_row['settlement_type'].'<br><small class="text-danger">Orginally <strong>'.$salary_row['status'].'</strong></small>';
                    $salary_row['status_remarks'] = $salary_row['settlement_remarks'];
                }

                $salary_row['final_paid'] = ! empty($salary_row['final_paid']) ? $salary_row['final_paid'] : '0';
                $salary_row['selection'] = '';

                $paid_days_data[$index] = $salary_row;
            }

            $data['punching_data_encoded'] = json_encode($paid_days_data);

            return view('Salary/FinalPaidDays', $data);
        } else {
            $data = [
                'page_title' => 'Missing parameter in the url',
                'message' => 'Missing parameter in the url',
            ];

            return view('show-error', $data);
        }
    }

    public function generateFinalPaidDays__not_being_used($company = null, $department = null, $employee = null, $month = null, $page_url = null)
    {

        if (! empty($month)) {
            $from = date('Y-m-01', strtotime($month));
            $to = date('Y-m-t', strtotime($month));
        } elseif (isset($_GET['current_month']) && ! empty($_GET['current_month']) && $_GET['current_month'] == 'yes') {
            $from = first_date_of_month();
            $to = current_date_of_month();
        } else {
            $from = first_date_of_last_month();
            $to = last_date_of_last_month();
        }

        $EmployeeModel = new EmployeeModel;
        $EmployeeModel
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
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left');

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('".$from."')");
        $EmployeeModel->groupEnd();

        // $EmployeeModel->whereIn('employees.company_id', [1, 3, 5]);
        $EmployeeModel->whereIn('employees.company_id', [2]);
        // $EmployeeModel->whereIn('employees.company_id', [4]);

        if (! empty($employee)) {
            $EmployeeModel->whereIn('employees.id', $employee);
        }

        // $EmployeeModel->limit(25);
        // $EmployeeModel->limit(25, 50); // offset, limit
        // $EmployeeModel->limit(50, 75); // offset, limit
        // $EmployeeModel->limit(50, 150); // offset, limit
        // $EmployeeModel->limit(50, 200); // offset, limit
        // $EmployeeModel->limit(50, 250); // offset, limit
        // $EmployeeModel->limit(50, 300); // offset, limit
        // $EmployeeModel->limit(50, 350); // offset, limit

        $allEmployees = $EmployeeModel->findAll();
        // dd($allEmployees);
        // $allEmployees = $EmployeeModel->get()->getResultArray();

        // print_r($EmployeeModel->getLastQuery()->getQuery());

        // die;

        $chunks = ! empty($allEmployees) ? array_chunk($allEmployees, 25) : null;

        foreach ($chunks as $batchIndex => $employeeGroup) {

            // if ($batchIndex >= 2) break; // Stop after 100 employees for testing

            if (! empty($employeeGroup)) {
                foreach ($employeeGroup as $employee_row) {

                    $PreFinalSalaryModel = new PreFinalSalaryModel;
                    $PreFinalSalaryModel->select('pre_final_salary.*');
                    $PreFinalSalaryModel->where('pre_final_salary.employee_id =', $employee_row['id']);
                    $PreFinalSalaryModel->where('pre_final_salary.month =', date('m', strtotime($from)));
                    $PreFinalSalaryModel->where('pre_final_salary.year =', date('Y', strtotime($from)));
                    $FinalSalary = $PreFinalSalaryModel->first();

                    if (! empty($FinalSalary) && ! in_array($FinalSalary['status'], ['generated', 're-generated', 'unhold'])) {
                        $message = 'Salary not updatable of '.trim($employee_row['first_name'].' '.$employee_row['last_name']).' ('.$employee_row['internal_employee_id'].')';
                        if (! empty($employee)) {
                            return ['regenerated' => 'no', 'message' => $message];
                        }
                    }

                    // Begin::Pulling Attendance from E time office for this employee

                    // excluded for feb 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '765', '745', '813']) ){
                    // excluded for mar 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '765', '745', '813', '719', '822', '685', '848']) ){
                    // excluded for apr 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '765', '745', '719', '809', '856', '857', '822', 'HGGN05', '812', '685']) ){
                    // excluded for may 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '719', '685', '809', '765', '745', '856', '857', 'HN862', 'HN863', '812', 'HN864']) ){
                    // excluded for jun 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '719', '685', '809', '765', '745', '856', '857']) ){
                    // excluded for july 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '280', '719', '685', '809', '765', '745', '856', '857', '879']) ){
                    // excluded for august 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['101', '102', '685', '280', '719', '879', '809', '765', '745', '856', '857', '848']) ){
                    // excluded for september 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['685', '280', '719', '809', '745', '856', '857', '848']) ){
                    // excluded for october 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['280', '809', '745', '856', '857', '848', '812', '822']) ){
                    // exxcluded for November 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['809', '745', '856', '857', '848']) ){
                    // exxcluded for December 2024
                    // if( !in_array($employee_row['internal_employee_id'], ['809', '745', '856', '857', '848']) ){
                    // excluded for jan 2025
                    // if( !in_array($employee_row['internal_employee_id'], ['809','745','856','857','848']) ){
                    // excluded for fab 2025
                    // if( !in_array($employee_row['internal_employee_id'], ['809','745','856','857','848', 'HN1011']) ){
                    // excluded for March 2025
                    if (! in_array($employee_row['internal_employee_id'], ['809', '745', '856', '857', 'HN1011'])) {
                        save_raw_punching_data($employee_row['internal_employee_id'], $from, $to);
                    }
                    // End::Pulling Attendance from E time office for this employee

                    $employee_id = $employee_row['id'];
                    $shift_id = $employee_row['shift_id'];

                    $PunchingData = Processor::getProcessedPunchingData($employee_id, $from, $to);

                    // if( in_array($employee_row['internal_employee_id'], ['848']) ){
                    //     echo '<pre>';
                    //     print_r($PunchingData);
                    //     echo '</pre>';
                    //     die();
                    // }
                    foreach ($PunchingData as $rowIndex => $rowData) {
                        $PunchingData[$rowIndex]['shift_start'] = (! empty($rowData['shift_start'])) ? date('H:i:s', strtotime($rowData['shift_start'])) : null;
                        $PunchingData[$rowIndex]['shift_end'] = (! empty($rowData['shift_end'])) ? date('H:i:s', strtotime($rowData['shift_end'])) : null;
                        $PunchingData[$rowIndex]['in_time_between_shift_with_od'] = (! empty($rowData['in_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['in_time_between_shift_with_od'])) : null;
                        $PunchingData[$rowIndex]['out_time_between_shift_with_od'] = (! empty($rowData['out_time_between_shift_with_od'])) ? date('H:i:s', strtotime($rowData['out_time_between_shift_with_od'])) : null;
                        $PunchingData[$rowIndex]['punch_in_time'] = (! empty($rowData['punch_in_time'])) ? date('H:i:s', strtotime($rowData['punch_in_time'])) : null;
                        $PunchingData[$rowIndex]['punch_out_time'] = (! empty($rowData['punch_out_time'])) ? date('H:i:s', strtotime($rowData['punch_out_time'])) : null;
                        $PunchingData[$rowIndex]['in_time_including_od'] = (! empty($rowData['in_time_including_od'])) ? date('H:i:s', strtotime($rowData['in_time_including_od'])) : null;
                        $PunchingData[$rowIndex]['out_time_including_od'] = (! empty($rowData['out_time_including_od'])) ? date('H:i:s', strtotime($rowData['out_time_including_od'])) : null;
                        $PunchingData[$rowIndex]['late_coming_grace'] = $rowData['grace'];
                        $PunchingData[$rowIndex]['wave_off_minutes'] = $rowData['wave_off_minutes'];
                        $PunchingData[$rowIndex]['wave_off_remarks'] = $rowData['wave_off_remarks'];
                        $PunchingData[$rowIndex]['wave_off_half_day_who_did_not_work_for_half_day'] = $rowData['wave_off_half_day_who_did_not_work_for_half_day'];
                        $PunchingData[$rowIndex]['wave_off_half_day_who_did_not_work_for_half_day_remarks'] = $rowData['wave_off_half_day_who_did_not_work_for_half_day_remarks'];
                        $PunchingData[$rowIndex]['leave_request_type'] = (! empty($rowData['LeaveData'])) ? $rowData['LeaveData']['type_of_leave'] : '';
                        $PunchingData[$rowIndex]['leave_request_amount'] = (! empty($rowData['LeaveData'])) ? ($rowData['LeaveData']['number_of_days'] > 1) ? 1 : $rowData['LeaveData']['number_of_days'] : '';
                        $PunchingData[$rowIndex]['leave_request_status'] = (! empty($rowData['LeaveData'])) ? $rowData['LeaveData']['status'] : '';
                        $PunchingData[$rowIndex]['late_coming_rule'] = (! empty($rowData['late_coming_rule'])) ? json_encode($rowData['late_coming_rule']) : null;
                    }
                    foreach ($PunchingData as $salary_row) {
                        $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;

                        $PreFinalPaidDaysModel
                            ->where('employee_id =', $salary_row['employee_id'])
                            ->where('date =', $salary_row['date']);

                        $get_existing = $PreFinalPaidDaysModel->first();

                        if (! empty($get_existing)) {
                            $id = $get_existing['id'];
                            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;
                            $query = $PreFinalPaidDaysModel->update($id, $salary_row);
                            if (! $query) {
                                echo trim($employee_row['first_name'].' '.$employee_row['last_name']).' ('.$employee_row['internal_employee_id'].') :::: '.$salary_row['date'].' => failed update <br>';
                                echo $PreFinalPaidDaysModel->getLastQuery()->getQuery();
                                exit();
                            } else {
                                echo trim($employee_row['first_name'].' '.$employee_row['last_name']).' ('.$employee_row['internal_employee_id'].') :::: '.$salary_row['date'].' => success update <br>';
                            }
                        } else {
                            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;
                            $query = $PreFinalPaidDaysModel->insert($salary_row);
                            if (! $query) {
                                echo trim($employee_row['first_name'].' '.$employee_row['last_name']).' ('.$employee_row['internal_employee_id'].') :::: '.$salary_row['date'].' => failed insert <br>';
                                echo $PreFinalPaidDaysModel->getLastQuery()->getQuery();
                                exit();
                            } else {
                                echo trim($employee_row['first_name'].' '.$employee_row['last_name']).' ('.$employee_row['internal_employee_id'].') :::: '.$salary_row['date'].' => success insert <br>';
                            }
                        }
                    }
                }
            }

            // Optionally log progress
            echo "Processed batch $batchIndex <br>";

            // Optional: pause briefly to ease server load
            usleep(500000); // 0.5 seconds

        }

        if (! empty($employee)) {
            return ['regenerated' => 'yes', 'message' => 'Final paid days and Salary regenerated'];
        }
    }

    public function generateFinalPaidDays($employee = null, $month = null)
    {
        $month = $month ?? date('Y-m', strtotime(first_day_of_last_month()));
        $processor = new AttendanceProcessor;

        if (! empty($employee) && $employee != 'all' && $employee != 'All') {
            $processor->processAll(25, $month, $employee);
        } else {
            // $processor->processAll(25, $month);
            echo 'For optimization purpose We have disabled Attendance Processing for ALL. Please run cli commands to regenerate attenadnce in bulk';
            exit();
        }

        if (! empty($employee)) {
            return ['regenerated' => 'yes', 'message' => 'Final paid days and Salary regenerated'];
        }
    }

    public function reGenerateFinalPaidDays()
    {

        $return_data = $this->generateFinalPaidDays($_REQUEST['employee'], $_REQUEST['month']);
        if ($return_data['regenerated'] == 'yes') {
            $FinalSalary = new FinalSalary;
            foreach ($_REQUEST['employee'] as $e) {
                $FinalSalary->calculateSalary($e, date('Y-m', strtotime($_REQUEST['month'])));
                // echo '<pre>';
                // print_r($e);
                // echo '</pre>';
            }
        }

        if (isset($_REQUEST['page_url'])) {
            $return_url = $_REQUEST['page_url'];
        } else {
            $return_url = base_url('/backend/reports/final-paid-days/final-paid-days-sheet?regenerated=').$return_data['regenerated'];
            if (! empty($_REQUEST['company'])) {
                foreach ($_REQUEST['company'] as $c) {
                    $return_url .= '&company[]='.$c;
                }
            }
            if (! empty($_REQUEST['department'])) {
                foreach ($_REQUEST['department'] as $d) {
                    $return_url .= '&department[]='.$d;
                }
            }
            if (! empty($_REQUEST['employee'])) {
                foreach ($_REQUEST['employee'] as $e) {
                    $return_url .= '&employee[]='.$e;
                }
            }
            if (! empty($_REQUEST['month'])) {
                $return_url .= '&month='.$_REQUEST['month'];
            }
        }

        // echo $return_url;

        $this->session->setFlashdata('regenerated', $return_data['regenerated']);
        $this->session->setFlashdata('regenerated_message', $return_data['message']);

        header('location: '.$return_url);
        exit(); // Added this line on 2024-12-02 because the redirect was not working.
    }

    public function finalPaidDaysSheet()
    {

        if (
            session()->get('current_user')['role'] == 'hr'
            || session()->get('current_user')['employee_id'] == '20'
            || session()->get('current_user')['employee_id'] == '40'
            || session()->get('current_user')['employee_id'] == '165'
        ) {
            $can_load_other_employee_attendance = true;
        } else {
            $can_load_other_employee_attendance = false;
        }

        $CompanyModel = new CompanyModel;
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel;
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $EmployeeModel = new EmployeeModel;

        $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_short_name')
            ->join('companies', 'companies.id = employees.company_id', 'left');

        $company_id = isset($_REQUEST['company']) ? $_REQUEST['company'] : '';
        $department_id = isset($_REQUEST['department']) ? $_REQUEST['department'] : '';

        if ($can_load_other_employee_attendance == true) {
            // $_REQUEST['employee'] = [44, 94, 221, 254, 269, 293, 295, 330, 341, 443, 165, 460, 461, 467, 470, 473, 476, 477, 480, 499, 510, 509, 515, 518, 519, 527, 531, 539, 543, 545, 546, 548, 550, 560, 566, 569, 575, 581, 252, 164, 152, 337, 353, 365, 381, 382, 384, 386, 396, 401, 403, 407, 432, 413, 402, 412, 394, 411, 426, 415, 429, 398];
            $employee_id = isset($_REQUEST['employee']) ? $_REQUEST['employee'] : '';
        } else {
            $employee_id = [session()->get('current_user')['employee_id']];
        }

        if (isset($company_id) && ! empty($company_id) && ! in_array('all_companies', $company_id) && ! in_array('', $company_id)) {
            $EmployeeModel->whereIn('employees.company_id', $company_id);
        }
        if (isset($department_id) && ! empty($department_id) && ! in_array('all_departments', $department_id) && ! in_array('', $department_id)) {
            $EmployeeModel->whereIn('employees.department_id', $department_id);
        }

        if (isset($employee_id) && ! empty($employee_id) && ! in_array('all_employees', $employee_id) && ! in_array('', $employee_id)) {
            $EmployeeModel->whereIn('employees.id', $employee_id);
        }

        if ($can_load_other_employee_attendance == true) {
            $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('Y-m', strtotime(first_date_of_last_month()));
            $lastDate = date('Y-m-t', strtotime($month));
            $firstDate = date('Y-m-01', strtotime($month));
        } else {
            $month = date('Y-m');
            $lastDate = date('Y-m-d', strtotime($month));
            $firstDate = date('Y-m-01', strtotime($month));
        }

        // $date_45_days_before = date('Y-m-d', strtotime($lastDate . '-45 days'));
        // removed on Santu's request to get all employee
        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('".$firstDate."')");
        $EmployeeModel->groupEnd();

        // $EmployeeModel->where('employees.internal_employee_id=', 'HN114');

        /*$limit  = 100;
        $offset = 0; // or 100, 200, etc., depending on the request/page you want
        $offset = (int) ($this->request->getVar('offset') ?? 0);
        $EmployeeModel->limit($limit, $offset);
        $Employees = $EmployeeModel->find();*/

        $Employees = $EmployeeModel->findAll();

        // print_r($EmployeeModel->getLastQuery()->getQuery());
        // die();

        // #####for filter######
        $data = [
            'page_title' => 'Final Paid Days - '.date('F Y', strtotime($month)),
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(4),
            'month' => date('Y-m', strtotime($month)),
            'Companies' => $Companies,
            'can_load_other_employee_attendance' => $can_load_other_employee_attendance,
        ];

        $data['HRSHEETData'] = $this->loadHrSheet($Employees, $data['month']);
        if ($month == date('Y-m')) {
            $today = date('Y-m-d');
            $data['HRSHEETData'] = array_map(
                static function ($employeeData) use ($today) {

                    $employeeData['PreFinalPaidDays_Data'] = array_filter(
                        $employeeData['PreFinalPaidDays_Data'] ?? [],
                        static fn ($date) => $date <= $today,
                        ARRAY_FILTER_USE_KEY
                    );

                    $employeeData['dates'] = array_values(array_filter(
                        $employeeData['dates'] ?? [],
                        static fn ($date) => $date <= $today
                    ));

                    return $employeeData;
                },
                $data['HRSHEETData']
            );
        }
        $where_company = '';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_company .= " and d.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_company .= ' ';
        }

        $sql = 'select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null '.$where_company.' order by c.company_short_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['departments_not_found'] = 'There was an error fetching departments from database';
        } else {
            $data['Departments'] = $query->getResultArray();
        }

        $where_department = ' ';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_department .= " and e.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_department .= ' ';
        }

        if (isset($_REQUEST['department']) && ! empty($_REQUEST['department']) && ! in_array('all_departments', $_REQUEST['department'])) {
            $where_department .= " and e.department_id in ('".implode("', '", $_REQUEST['department'])."')";
        } else {
            $where_department .= ' ';
        }
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
            where e.id is not null ".$where_department;

        // and
        // (
        //     e.date_of_leaving is null
        //     or
        //     e.date_of_leaving >= '".#$date_45_days_before."'
        // )
        $sql .= ' order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['employees_not_found'] = 'There was an error fetching employees from database';
        } else {
            $data['Employees'] = $query->getResultArray();
        }
        // #####for filter######

        return view('FinalPaidDays/FinalPaidDaysSheet', $data);
    }

    public function loadHrSheet($Employees, $month)
    {
        $range_from = date('Y-m-01', strtotime($month));
        $range_to = date('Y-m-t', strtotime($month));
        $MonthDates = date_range_between(date('Y-m-01', strtotime($month)), date('Y-m-t', strtotime($month)));
        $FinalPaidDaysData = [];
        foreach ($Employees as $Employee) {
            $employeeData = [];
            $employeeData['employee_name'] = trim($Employee['first_name'].' '.$Employee['last_name']);
            $employeeData['employee_code'] = $Employee['internal_employee_id'];
            $employeeData['company_short_name'] = $Employee['company_short_name'];
            $employee_joining_date = $Employee['joining_date'] ?? null;

            if (! empty($employee_joining_date)) {
                if ($employee_joining_date > $range_from) {
                    $range_from = $employee_joining_date;
                }
            }

            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;
            $PreFinalPaidDaysModel->select('pre_final_paid_days.*');
            $PreFinalPaidDaysModel->select('trim(concat(settler.first_name, " ", settler.last_name)) as settled_by_name');
            $PreFinalPaidDaysModel->join('employees as settler', 'settler.id = pre_final_paid_days.settled_by', 'left');

            // if (
            //     session()->get('current_user')['role'] == 'hr'
            //     || session()->get('current_user')['employee_id'] == '20'
            //     // || session()->get('current_user')['employee_id'] == '40'
            //     || session()->get('current_user')['employee_id'] == '165'
            // ) {
            $PreFinalPaidDaysModel->where('pre_final_paid_days.employee_id =', $Employee['id']);
            // } else {
            //     $PreFinalPaidDaysModel->where('pre_final_paid_days.employee_id =', session()->get('current_user')['employee_id']);
            // }

            $PreFinalPaidDaysModel->where("(pre_final_paid_days.date between '".$range_from."' and '".$range_to."')");
            $PreFinalPaidDays = $PreFinalPaidDaysModel->orderBy('pre_final_paid_days.date', 'ASC');
            $PreFinalPaidDays = $PreFinalPaidDaysModel->findAll();
            $PreFinalPaidDays_Data = [];
            foreach ($PreFinalPaidDays as $PaidDayData) {
                // $employeeData[ $PaidDayData['date'] ] = $PaidDayData;
                $PreFinalPaidDays_Data[$PaidDayData['date']] = $PaidDayData;
            }
            $employeeData['PreFinalPaidDays_Data'] = $PreFinalPaidDays_Data;
            $employeeData['dates'] = array_column($PreFinalPaidDays, 'date');
            $FinalPaidDaysData[] = $employeeData;
        }
        /*echo '<pre>';
        print_r($FinalPaidDaysData);
        echo '</pre>';
        die();*/

        return $FinalPaidDaysData;
        // return $this->response->setJSON($FinalPaidDaysData);
    }

    public function finalPaidDaysSheetNew()
    {
        $CompanyModel = new CompanyModel;
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel;
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $EmployeeModel = new EmployeeModel;
        $EmployeeModel
            ->select('employees.*')
            ->select('companies.company_short_name')
            ->join('companies', 'companies.id = employees.company_id', 'left');

        $company_id = isset($_REQUEST['company']) ? $_REQUEST['company'] : '';
        $department_id = isset($_REQUEST['department']) ? $_REQUEST['department'] : '';
        $employee_id = isset($_REQUEST['employee']) ? $_REQUEST['employee'] : '';
        if (isset($company_id) && ! empty($company_id) && ! in_array('all_companies', $company_id) && ! in_array('', $company_id)) {
            $EmployeeModel->whereIn('employees.company_id', $company_id);
        }
        if (isset($department_id) && ! empty($department_id) && ! in_array('all_departments', $department_id) && ! in_array('', $department_id)) {
            $EmployeeModel->whereIn('employees.department_id', $department_id);
        }
        if (isset($employee_id) && ! empty($employee_id) && ! in_array('all_employees', $employee_id) && ! in_array('', $employee_id)) {
            $EmployeeModel->whereIn('employees.id', $employee_id);
        }

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('".$date_45_days_before."')");
        $EmployeeModel->groupEnd();

        $Employees = $EmployeeModel->findAll();

        // #####for filter######
        $data = [
            'page_title' => 'Final Paid Days New',
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(4),
            'month' => isset($_REQUEST['month']) ? $_REQUEST['month'] : date('Y-m', strtotime(first_date_of_last_month())),
            'Companies' => $Companies,
        ];

        $data['HRSHEETData'] = $this->loadHrSheetNew($Employees, $data['month']);

        // echo '<pre>';
        // print_r($data['HRSHEETData']->getBody());
        // echo '</pre>';
        // /die();

        $where_company = '';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_company .= " and d.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_company .= ' ';
        }

        $sql = 'select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null '.$where_company.' order by c.company_short_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['departments_not_found'] = 'There was an error fetching departments from database';
        } else {
            $data['Departments'] = $query->getResultArray();
        }

        $where_department = ' ';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_department .= " and e.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_department .= ' ';
        }

        if (isset($_REQUEST['department']) && ! empty($_REQUEST['department']) && ! in_array('all_departments', $_REQUEST['department'])) {
            $where_department .= " and e.department_id in ('".implode("', '", $_REQUEST['department'])."')";
        } else {
            $where_department .= ' ';
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
            where e.id is not null ".$where_department."
            and
            (
                e.date_of_leaving is null
                or
                e.date_of_leaving >= '".$date_45_days_before."'
            )
            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['employees_not_found'] = 'There was an error fetching employees from database';
        } else {
            $data['Employees'] = $query->getResultArray();
        }
        // #####for filter######

        return view('FinalPaidDays/finalPaidDaysSheetNew', $data);
    }

    public function loadHrSheetNew($Employees, $month)
    {
        $range_from = date('Y-m-01', strtotime($month));
        $range_to = date('Y-m-t', strtotime($month));

        $MonthDates = date_range_between(date('Y-m-01', strtotime($month)), date('Y-m-t', strtotime($month)));
        // print_r($MonthDates);
        // die();
        $FinalPaidDaysData = [];
        foreach ($Employees as $Employee) {
            $employeeData = [];
            $employeeData['employee_name'] = trim($Employee['first_name'].' '.$Employee['last_name']);
            $employeeData['employee_code'] = $Employee['internal_employee_id'];
            $employeeData['company_short_name'] = $Employee['company_short_name'];
            $employee_joining_date = $Employee['joining_date'] ?? null;
            if (! empty($employee_joining_date)) {
                if ($employee_joining_date > $range_from) {
                    $range_from = $employee_joining_date;
                }
            }

            $PreFinalPaidDaysModel = new PreFinalPaidDaysModel;
            $PreFinalPaidDays = $PreFinalPaidDaysModel
                ->select('pre_final_paid_days.*')
                ->select('trim(concat(settler.first_name, " ", settler.last_name)) as settled_by_name')
                ->join('employees as settler', 'settler.id = pre_final_paid_days.settled_by', 'left')
                ->where('pre_final_paid_days.employee_id =', $Employee['id'])
                ->where("(pre_final_paid_days.date between '".$range_from."' and '".$range_to."')")
                ->orderBy('pre_final_paid_days.date', 'ASC')
                ->findAll();
            $PreFinalPaidDays_Data = [];

            $finalData = [];
            $data_names = [
                'shift_start',
                'shift_end',
                'machine',
                'punch_in_time',
                'punch_out_time',
                'in_time_between_shift_with_od',
                'out_time_between_shift_with_od',
                'in_time_including_od',
                'out_time_including_od',
                'late_coming_minutes',
                'early_going_minutes',
                'late_coming_grace',
                'comp_off_minutes',
                'wave_off_minutes',
                'wave_off_remarks',
                'deduction_minutes',
                'deduction_remarks',
                'wave_off_half_day_who_did_not_work_for_half_day',
                'wave_off_half_day_who_did_not_work_for_half_day_remarks',
                'ExtraWorkMinutes',
                'LateSittingMinutes',
                'OverTimeMinutes',
                'status',
                'status_remarks',
                'leave_request_type',
                'leave_request_amount',
                'leave_request_status',
                'paid',
                'settlement_type',
                'settlement',
                'settlement_remarks',
                'settled_by_name',
            ];
            foreach ($data_names as $data_name) {
                $row = [];
                if ($data_name == 'shift_start') {
                    $row['employee_name'] = $employeeData['employee_name'];
                    $row['employee_code'] = $employeeData['employee_code'];
                    $row['company_short_name'] = $employeeData['company_short_name'];
                } elseif ($data_name != 'shift_start') {
                    $row['employee_name'] = $employeeData['employee_name'];
                    $row['employee_code'] = $employeeData['employee_code'];
                    $row['company_short_name'] = $employeeData['company_short_name'];
                }

                $row['column_name'] = $data_name;

                if (! empty($MonthDates)) {
                    foreach ($MonthDates as $date) {
                        $row[$date] = $this->getValueForDate($PreFinalPaidDays, $date, $data_name);
                    }
                }

                if ($data_name == 'shift_start') {
                    $row['total_late_minutes'] = $this->getTotalLateMinutes($PreFinalPaidDays);
                    $row['total_late_minutes_allowed'] = $this->getTotalLateMinutesAllowed($PreFinalPaidDays);
                    $row['total_present_days'] = $this->getTotalPresent($PreFinalPaidDays);
                } elseif ($data_name != 'shift_start') {
                    $row['total_late_minutes'] = '';
                    $row['total_late_minutes_allowed'] = '';
                    $row['total_present_days'] = '';
                }

                $finalData[] = $row;
            }

            // print_r($finalData);
            // die();

            // $employeeData[ 'PreFinalPaidDays_Data' ] = $PreFinalPaidDays_Data;
            // $employeeData[ 'dates' ] = array_column($PreFinalPaidDays, 'date');
            $FinalPaidDaysData[] = $employeeData;
        }

        // return $FinalPaidDaysData;
        return $this->response->setJSON($FinalPaidDaysData);
    }

    protected function getValueForDate(array $dataArray, string $desiredDate, string $fieldName)
    {
        // Filter the array down to records where 'date' equals $desiredDate
        $filtered = array_filter($dataArray, function ($data) use ($desiredDate) {
            return isset($data['date']) && $data['date'] === $desiredDate;
        });

        // If we have at least one matching row, return the requested field from the first match
        if (! empty($filtered)) {
            // reset() gives us the first element in the filtered array
            $firstMatch = reset($filtered);

            // Return the requested field if it exists
            return $firstMatch[$fieldName] ?? null;
        }

        // No match found, return null
        return null;
    }

    protected function getTotalLateMinutes($PreFinalPaidDays)
    {
        $INC_columns_array = array_filter($PreFinalPaidDays, function ($row) {
            return $row['status'] === 'INC';
        });
        $INC_columns = ! empty($INC_columns_array) ? array_column($INC_columns_array, 'late_coming_plus_early_going_minutes_adjustable') : null;
        $total_inc_minutes = ! empty($INC_columns) ? array_sum($INC_columns) : 0;

        $late_coming_minutes = array_sum(array_column($PreFinalPaidDays, 'late_coming_minutes'));
        $early_going_minutes = array_sum(array_column($PreFinalPaidDays, 'early_going_minutes'));
        $deduction_minutes = array_sum(array_column($PreFinalPaidDays, 'deduction_minutes'));
        ob_start();
        ?>
        <span class="d-block w-100 border-bottom">
            <?php echo $late_coming_minutes + $early_going_minutes + $deduction_minutes + $total_inc_minutes; ?>
        </span>
        <small>
            <?php echo $late_coming_minutes.'+'.$early_going_minutes.'+'.$deduction_minutes; ?>
            <?php echo ($total_inc_minutes > 0) ? '+'.$total_inc_minutes." <small style='font-size: 0.75rem'>(INC)</small>" : ''; ?>
        </small>
    <?php
                return ob_get_clean();
    }

    protected function getTotalLateMinutesAllowed($PreFinalPaidDays)
    {
        ob_start();
        ?>
        <span class="d-block w-100 border-bottom">
            <?php
                echo array_sum(array_column($PreFinalPaidDays, 'late_coming_grace'))
                    + array_sum(array_column($PreFinalPaidDays, 'LateSittingMinutes'))
                    + array_sum(array_column($PreFinalPaidDays, 'OverTimeMinutes'))
                    + array_sum(array_column($PreFinalPaidDays, 'comp_off_minutes'))
                    + array_sum(array_column($PreFinalPaidDays, 'wave_off_minutes'));
        ?>
        </span>
        <small>
            (
            <?php
        echo array_sum(array_column($PreFinalPaidDays, 'late_coming_grace'))
            .'+'.array_sum(array_column($PreFinalPaidDays, 'LateSittingMinutes'))
            .'+'.array_sum(array_column($PreFinalPaidDays, 'OverTimeMinutes'))
            .'+'.array_sum(array_column($PreFinalPaidDays, 'comp_off_minutes'))
            .'+'.array_sum(array_column($PreFinalPaidDays, 'wave_off_minutes'));
        ?>
            )
        </small>
<?php
        return ob_get_clean();
    }

    protected function getTotalPresent($PreFinalPaidDays)
    {
        ob_start();
        $status_column = array_column($PreFinalPaidDays, 'status');
        $All_Present = array_filter($status_column, function ($d) {
            return $d == 'P';
        });
        echo count($All_Present);

        return ob_get_clean();
    }

    public function attendanceSummary()
    {

        if (! in_array($this->session->get('current_user')['role'], ['superuser', 'hr', 'hod', 'manager', 'tl'])) {
            return view('User/Unauthorised');
        }

        $CompanyModel = new CompanyModel;
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel;
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        // #####for filter######
        $data = [
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(4),
            'from' => isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m', strtotime(first_date_of_last_month())),
            'to' => isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m', strtotime(first_date_of_last_month())),
            'Companies' => $Companies,
        ];

        // dd($current_user, $data);

        $data['page_title'] = 'Attendance Summary '.date('M Y', strtotime($data['from'])).' - '.date('M Y', strtotime($data['to']));

        $where_company = '';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_company .= " and d.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_company .= ' ';
        }

        $sql = 'select d.*, c.company_short_name from departments d left join companies c on c.id = d.company_id where d.company_id is not null '.$where_company.' order by c.company_short_name ASC';
        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['departments_not_found'] = 'There was an error fetching departments from database';
        } else {
            $data['Departments'] = $query->getResultArray();
        }

        $where_department = ' ';
        if (isset($_REQUEST['company']) && ! empty($_REQUEST['company']) && ! in_array('all_companies', $_REQUEST['company'])) {
            $where_department .= " and e.company_id in ('".implode("', '", $_REQUEST['company'])."')";
        } else {
            $where_department .= ' ';
        }

        if (isset($_REQUEST['department']) && ! empty($_REQUEST['department']) && ! in_array('all_departments', $_REQUEST['department'])) {
            $where_department .= " and e.department_id in ('".implode("', '", $_REQUEST['department'])."')";
        } else {
            $where_department .= ' ';
        }
        // $date_45_days_before = date('Y-m-d', strtotime('-45 days'));
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
        where e.id is not null ".$where_department.'
        order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC';

        $CustomModel = new CustomModel;
        $query = $CustomModel->CustomQuery($sql);
        if (! $query) {
            $data['employees_not_found'] = 'There was an error fetching employees from database';
        } else {
            $Employees = $query->getResultArray();
            $data['Employees'] = $Employees;
            $employee_ids = (isset($_REQUEST['employee']) && ! empty($_REQUEST['employee']) && ! in_array('all_employees', $_REQUEST['employee'])) ? $_REQUEST['employee'] : array_column($data['Employees'], 'id');

            $attendanceSummary = $this->getAttendanceSummary($employee_ids, $data['from'], $data['to']);

            // echo '<pre>';
            // print_r($attendanceSummary);
            // echo '</pre>';
            // die();

            $data['attendanceSummary'] = $attendanceSummary;
        }
        // #####for filter######

        return view('Reports/AttendanceSummary', $data);
    }

    public function getAttendanceSummary($employee_ids = null, $from = null, $to = null)
    {
        $current_user = $this->session->get('current_user');
        if (isset($employee_ids) && ! empty($employee_ids) && isset($from) && ! empty($from) && isset($to) && ! empty($to)) {
            $range_from = date('Y-m-01', strtotime($from));
            $range_to = date('Y-m-t', strtotime($to));

            $EmployeeModel = new EmployeeModel;

            $PreFinalPaidDays = $EmployeeModel
                // Employee id
                ->select('employees.id as employee_id')
                // Employee name
                ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
                // Employee code
                ->select('employees.internal_employee_id as internal_employee_id')
                // Company short name
                ->select('companies.company_short_name as company_short_name')
                // Department name
                ->select('departments.department_name as department_name')
                // Total Late coming minutes
                ->select('SUM(pre_final_paid_days.late_coming_minutes) as late_coming_minutes')
                // Total early going minutes
                ->select('SUM(pre_final_paid_days.early_going_minutes) as early_going_minutes')
                // Total deduction minutes from module
                ->select('SUM(pre_final_paid_days.deduction_minutes) as deduction_minutes')

                // Total Grace minutes
                ->select('SUM(pre_final_paid_days.late_coming_grace) as late_coming_grace')
                // Total comp off minutes utilized
                ->select('SUM(pre_final_paid_days.comp_off_minutes) as comp_off_minutes')
                // Total Wave off minutes given
                ->select('SUM(pre_final_paid_days.wave_off_minutes) as wave_off_minutes')

                // Total INC Minutes when INC
                ->select('SUM(CASE WHEN pre_final_paid_days.status = "INC" THEN pre_final_paid_days.late_coming_plus_early_going_minutes_adjustable ELSE 0 END) as total_INC_minutes')
                // Extra work minutes
                ->select('SUM(pre_final_paid_days.ExtraWorkMinutes) as ExtraWorkMinutes')
                // TOtal Late sitting minutes given
                ->select('SUM(pre_final_paid_days.LateSittingMinutes) as LateSittingMinutes')

                // Total Minutes which were adjusted
                ->select("SUM(CASE
                    WHEN pre_final_paid_days.status IN ('P', 'OD', 'H/D+CL/2', 'H/D+COMP OFF/2', 'OD/2+CL/2', 'H/D+HL/2', 'OD/2+HL/2', 'H/D+EL/2')
                    THEN pre_final_paid_days.late_coming_plus_early_going_minutes_adjustable
                    ELSE 0
                END) as adjusted_minutes")

                // Present days
                ->select("SUM(CASE
                    WHEN pre_final_paid_days.status IN ('P', 'H/D', 'H/D+CL/2', 'H/D+UL/2', 'H/D+COMP OFF/2', 'H/D+HL/2', 'H/D+EL/2')
                    THEN CASE
                            WHEN pre_final_paid_days.status = 'P' THEN 1
                            ELSE 0.5
                         END
                    ELSE 0
                END) as present_days")
                // OD days
                ->select("SUM(CASE
                    WHEN pre_final_paid_days.status IN ('OD', 'P on OD', 'P+OD', 'H/D on OD', 'H/D on OD+UL/2', 'OD/2', 'OD/2+CL/2', 'OD/2+UL/2', 'OD/2+COMP OFF/2', 'OD/2+HL/2', 'OD/2+EL/2')
                    THEN CASE
                            WHEN pre_final_paid_days.status IN ('OD', 'P on OD', 'P+OD') THEN 1
                            ELSE 0.5
                         END
                    ELSE 0
                END) as od_days")
                // Total CL Count
                ->select('SUM(CASE WHEN pre_final_paid_days.leave_request_type = "CL" THEN pre_final_paid_days.leave_request_amount ELSE 0 END) as total_cl')
                // Total EL Count
                ->select('SUM(CASE WHEN pre_final_paid_days.leave_request_type = "EL" THEN pre_final_paid_days.leave_request_amount ELSE 0 END) as total_el_plus_sick_leave')
                // CompOff Leave Count
                ->select('SUM(CASE WHEN pre_final_paid_days.leave_request_type = "COMP OFF" THEN pre_final_paid_days.leave_request_amount ELSE 0 END) as total_compoff')
                // WeekOffs
                ->select('SUM(CASE WHEN pre_final_paid_days.status = "W/O" THEN 1 ELSE 0 END) as total_weekoff')
                // Fixed Off
                ->select('SUM(CASE WHEN pre_final_paid_days.status = "F/O" THEN 1 ELSE 0 END) as total_fixedoff')
                // Holidays
                ->select("SUM(CASE
                    WHEN pre_final_paid_days.status IN ('NH', 'HL', 'H/D+HL/2', 'OD/2+HL/2', 'HL/2', 'SPL HL')
                    THEN CASE
                            WHEN pre_final_paid_days.status IN ('NH', 'HL', 'SPL HL') THEN 1
                            ELSE 0.5
                         END
                    ELSE 0
                END) as total_holidays")
                // RH
                ->select('SUM(CASE WHEN pre_final_paid_days.status = "RH" THEN 1 ELSE 0 END) as total_rh')
                // INC Days
                ->select('SUM(CASE WHEN pre_final_paid_days.status = "INC" THEN 1 ELSE 0 END) as total_INC')
                // Absent days
                ->select("SUM(CASE
                    WHEN pre_final_paid_days.status IN ('A', 'S/W', 'M/P', 'UL', 'H/D', 'H/D on OD', 'H/D on OD+UL/2', 'CL/2', 'A+CL/2', 'H/D+UL/2', 'OD/2', 'HL/2', 'COMP OFF/2', 'UL/2')
                    THEN CASE
                            WHEN pre_final_paid_days.status IN ('A', 'S/W', 'M/P', 'UL', 'A+CL/2', 'OD/2', 'UL/2') THEN 1
                            ELSE 0.5
                         END
                    ELSE 0
                END) as total_absent_days")
                // Year
                ->select('year(pre_final_paid_days.date) as year')
                // Month for showing number of days
                ->select('month(pre_final_paid_days.date) as month')
                // MonthName
                // ->select('MONTHNAME(pre_final_paid_days.date) as month_name')
                // Paid Days in month
                ->select('SUM(pre_final_paid_days.paid) as adjusted_paid_days')

                ->join('pre_final_paid_days', 'employees.id = pre_final_paid_days.employee_id', 'left')
                ->join('departments', 'departments.id = employees.department_id', 'left')
                ->join('companies', 'companies.id = employees.company_id', 'left')
                ->whereIn('pre_final_paid_days.employee_id', $employee_ids)
                ->where('pre_final_paid_days.date >=', $range_from)
                ->where('pre_final_paid_days.date <=', $range_to)
                ->groupBy('employees.id')
                ->groupBy('year(pre_final_paid_days.date)')
                ->groupBy('month(pre_final_paid_days.date)')
                ->orderBy('employees.id', 'ASC')
                ->findAll();

            if (! empty($PreFinalPaidDays)) {
                return $PreFinalPaidDays;
            }

            return false;
        }

        return false;
    }
}
