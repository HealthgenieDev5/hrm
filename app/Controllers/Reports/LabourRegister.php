<?php

namespace App\Controllers\Reports;

use App\Controllers\Attendance\Processor;
use App\Controllers\BaseController;
use App\Controllers\Cron\FinalSalary;
use App\Libraries\AttendanceProcessor;
use App\Models\CompanyModel;
use App\Models\CustomModel;
use App\Models\DepartmentModel;
use App\Models\EmployeeModel;
use App\Models\FinalPaidDaysModel;
use App\Models\LeaveBalanceModel;
use App\Models\PreFinalPaidDaysModel;
use App\Models\PreFinalPaidDaysTempModel;
use App\Models\PreFinalSalaryModel;
use App\Models\HolidayModel;
use App\Models\LeaveRequestsModel;
use App\Pipes\AttendanceProcessor\ProcessorHelper;
use \Dompdf\Options;

class LabourRegister extends BaseController
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

        if (! in_array($this->session->get('current_user')['role'], ['superuser', 'hr'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $company_request = $_REQUEST['company'] ?? ['all_companies'];
        $department_request = $_REQUEST['department'] ?? ['all_departments'];
        $employee_request = $_REQUEST['employee'] ?? ['all_employees'];
        $month_request = $_REQUEST['month'] ?? date('Y-m', strtotime(first_date_of_last_month()));
        $register_type_request = $_REQUEST['register_type'] ?? null;

        $CompanyModel = new CompanyModel();
        if (!in_array('all_companies', $company_request)) {
            $CompanyModel->whereIn('companies.id', $company_request);
        }
        $Companies = $CompanyModel->findAll();

        $DepartmentModel = new DepartmentModel();
        if (!in_array('all_companies', $company_request)) {
            $DepartmentModel->whereIn('company_id', $company_request);
        }
        if (!in_array('all_departments', $department_request)) {
            $DepartmentModel->whereIn('departments.id', $department_request);
        }
        $Departments = $DepartmentModel
            ->select('departments.*')
            ->select('companies.company_short_name as company_short_name')
            ->join('companies', 'companies.id=departments.company_id', 'left')
            ->findAll();

        $EmployeeModel = new EmployeeModel();
        if (!in_array('all_companies', $company_request)) {
            $EmployeeModel->whereIn('employees.company_id', $company_request);
        }
        if (!in_array('all_departments', $department_request)) {
            $EmployeeModel->whereIn('employees.department_id', $department_request);
        }

        $EmployeeModel
            ->select('employees.*')
            ->select("trim(concat(employees.first_name, ' ', employees.last_name)) as employee_name")
            ->select('departments.department_name as department_name')
            ->select('companies.company_short_name as company_short_name')
            ->select('companies.company_name as company_name')
            ->select('companies.address as company_address')
            ->select('companies.logo_url as company_logo_url')
            ->select('designations.designation_name as designation_name')
            ->join('departments', 'departments.id=employees.department_id', 'left')
            ->join('companies', 'companies.id=employees.company_id', 'left')
            ->join('designations', 'designations.id=employees.designation_id', 'left');
        // ->findAll();


        $EmployeesFilter = $EmployeeModel->findAll();

        if (!in_array('all_employees', $employee_request)) {
            $Employees = array_filter($EmployeesFilter, fn($e) => in_array($e['id'], $employee_request));
        } else {
            $Employees = $EmployeesFilter;
        }

        $data = [
            'page_title' => 'Labour Registers',
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(3),
        ];
        $data['register_types'] = [
            [
                'key' => 'attendance_register',
                'label' => 'Attendance Register'
            ],
            [
                'key' => 'wages_register',
                'label' => 'Wages Register'
            ],
            [
                'key' => 'wage_slip',
                'label' => 'Wages Slip'
            ],
            [
                'key' => 'overtime_register',
                'label' => 'Overtime Register'
            ],
            [
                'key' => 'muster_roll',
                'label' => 'Muster Roll'
            ],
            [
                'key' => 'leave_register',
                'label' => 'Leave Register'
            ]
        ];
        $filterData = [
            'month' => $month_request,
            'Companies' => $Companies,
            'Departments' => $Departments,
            'Employees' => $Employees,
            'EmployeesFilter' => $EmployeesFilter,
            'RegisterType' => $register_type_request,
        ];

        $data = array_merge($data, $filterData);

        if (!empty($register_type_request)) {
            $this->downloadRegister($filterData);
        }

        return view('Reports/LabourRegister', $data);
    }

    private function downloadRegister($filterData)
    {

        $register_type = $filterData['RegisterType'];
        $month = $filterData['month'];
        $employees = $filterData['Employees'];

        if (empty($register_type) || empty($month) || empty($employees)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing required parameters'
            ]);
        }

        switch ($register_type) {
            case 'attendance_register':
                $register_data = $this->getAttendanceRegisterData($employees, $month);
                break;
            case 'wages_register':
                $register_data = $this->getWagesRegisterData($employees, $month);
                break;
            case 'wage_slip':
                $register_data = $this->getWageSlipData($employees, $month);
                break;
            case 'overtime_register':
                $register_data = $this->getOvertimeRegisterData($employees, $month);
                break;
            case 'muster_roll':
                $register_data = $this->getMusterRollData($employees, $month);
                break;
            case 'leave_register':
                $register_data = $this->getLeaveRegisterData($employees, $month);
                break;
            default:
                $register_data = [];
        }

        // return $register_data;
    }


    private function getAttendanceRegisterData($employees, $month)
    {

        foreach ($employees as $index => $employee) {
            $PreFinalPaidDaysTempModel = new PreFinalPaidDaysTempModel();
            $PreFinalPaidDaysTempModel
                ->where('employee_id =', $employee['id'])
                ->where('date >=', date('Y-m-01', strtotime($month)))
                ->where('date <=', date('Y-m-t', strtotime($month)))
                ->orderBy('date', 'asc');
            $employees[$index]['records'] = $PreFinalPaidDaysTempModel->findAll();
            // $employees['records'] = $PreFinalPaidDaysTempModel->findAll();
        }
        $data['employees'] = $employees;
        $data['month'] = $month;
        $data['month_name'] = date('F, Y', strtotime($month));

        // return view('Pdf/AttendanceRegister', $data);

        $options = new options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/AttendanceRegister', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("AttendanceRegister-" . $data['month_name'] . ".pdf");
    }


    private function getWagesRegisterData($employees, $month)
    {
        // Group employees by company
        $grouped_by_company = [];

        foreach ($employees as $index => $employee) {
            $company_name = $employee['company_name'] ?? 'Unknown Company';

            // Fetch salary records for this employee
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $record = $PreFinalSalaryModel
                ->select('pre_final_salary.*')
                ->where('pre_final_salary.employee_id', $employee['id'])
                ->where('pre_final_salary.year', date('Y', strtotime($month)))
                ->where('pre_final_salary.month', date('m', strtotime($month)))
                ->first();


            if (empty($record)) {
                unset($employees[$index]);
                continue;
            }

            $employee_data = $record['employee_data'];
            $employee_data_decoded = json_decode($employee_data, true);
            $attachments = $employee_data_decoded['attachment'];
            $attachments_decoded = json_decode($attachments, true);
            $bank_account = $attachments_decoded['bank_account'];
            $bank_account_number = $bank_account['number'];
            $record['bank_account_number'] = $bank_account_number;

            // echo '<pre>';
            // print_r($bank_account);
            // die();


            $employee['record'] = $record;
            // Group by company name
            if (!isset($grouped_by_company[$company_name])) {
                $grouped_by_company[$company_name] = [
                    'company_name' => $company_name,
                    'company_short_name' => $employee['company_short_name'] ?? '',
                    'company_address' => $employee['company_address'] ?? '',
                    'company_logo_url' => $employee['company_logo_url'] ?? '',
                    'employees' => []
                ];
            }
            $grouped_by_company[$company_name]['employees'][] = $employee;
        }

        $data['companies'] = array_values($grouped_by_company);
        $data['month'] = date('Y-m', strtotime($month));
        $data['month_name'] = date('F, Y', strtotime($month));
        // Calculate disbursal date (7th-10th of next month, excluding Sunday, 2nd Saturday, and holidays)
        $nextMonth = date('Y-m', strtotime($month . '-01 +1 month'));

        // Get holidays for next month (excluding RH type)
        $HolidayModel = new HolidayModel();
        $holidays = $HolidayModel
            ->where('holiday_date >=', $nextMonth . '-07')
            ->where('holiday_date <=', $nextMonth . '-14')
            // ->where('holiday_type !=', 'RH')
            ->findAll();
        $holidayDates = array_column($holidays, 'holiday_date');

        $validDates = [];
        for ($day = 7; $day <= 14; $day++) {
            $dateStr = $nextMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('w', strtotime($dateStr)); // 0=Sunday, 6=Saturday
            $dayOfMonth = (int)date('j', strtotime($dateStr));

            // Skip Sunday
            if ($dayOfWeek == 0) continue;

            // Skip second Saturday (8-14 range and Saturday)
            if ($dayOfWeek == 6 && $dayOfMonth >= 8 && $dayOfMonth <= 14) continue;

            // Skip holidays (excluding RH)
            if (in_array($dateStr, $holidayDates)) continue;

            $validDates[] = $dateStr;
        }
        // Always pick the first valid date (guaranteed to have at least one in 7-14 range)
        $data['disbursal_date'] = date('d M, Y', strtotime($validDates[0]));

        // return view('Pdf/WagesRegister', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/WagesRegister', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream("WagesRegister-" . $data['month_name'] . ".pdf");
    }

    /**
     * Get Wage Slip Data
     */
    private function getWageSlipData($employees, $month)
    {
        // Group employees by company
        $grouped_by_company = [];

        foreach ($employees as $index => $employee_row) {
            $employee = $employee_row;
            $company_name = $employee['company_name'] ?? 'Unknown Company';
            $company_short_name = $employee['company_short_name'] ?? '';
            $company_address = $employee['company_address'] ?? '';

            // Fetch salary records for this employee
            $PreFinalSalaryModel = new PreFinalSalaryModel();
            $record = $PreFinalSalaryModel
                ->select('pre_final_salary.*')
                ->where('pre_final_salary.employee_id', $employee['id'])
                ->where('pre_final_salary.year', date('Y', strtotime($month)))
                ->where('pre_final_salary.month', date('m', strtotime($month)))
                ->first();


            if (empty($record)) {
                unset($employees[$index]);
                continue;
            }

            $employee_data = $record['employee_data'];
            $employee_data_decoded = json_decode($employee_data, true);
            $attachments = $employee_data_decoded['attachment'];
            $attachments_decoded = json_decode($attachments, true);
            $bank_account = $attachments_decoded['bank_account'];
            $bank_account_number = $bank_account['number'];
            $record['bank_account_number'] = $bank_account_number;

            // echo '<pre>';
            // print_r($bank_account);
            // die();


            $employee['record'] = $record;
            // Group by company name
            if (!isset($grouped_by_company[$company_name])) {
                $grouped_by_company[$company_name] = [
                    'company_name' => $company_name,
                    'company_short_name' => $company_short_name,
                    'company_address' => $company_address,
                    'employees' => []
                ];
            }
            $grouped_by_company[$company_name]['employees'][] = $employee;
        }

        $data['companies'] = array_values($grouped_by_company);
        $data['month'] = date('Y-m', strtotime($month));

        // Calculate disbursal date (7th-10th of next month, excluding Sunday, 2nd Saturday, and holidays)
        $nextMonth = date('Y-m', strtotime($month . '-01 +1 month'));

        // Get holidays for next month (excluding RH type)
        $HolidayModel = new HolidayModel();
        $holidays = $HolidayModel
            ->where('holiday_date >=', $nextMonth . '-07')
            ->where('holiday_date <=', $nextMonth . '-14')
            // ->where('holiday_type !=', 'RH')
            ->findAll();
        $holidayDates = array_column($holidays, 'holiday_date');

        $validDates = [];
        for ($day = 7; $day <= 14; $day++) {
            $dateStr = $nextMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('w', strtotime($dateStr)); // 0=Sunday, 6=Saturday
            $dayOfMonth = (int)date('j', strtotime($dateStr));

            // Skip Sunday
            if ($dayOfWeek == 0) continue;

            // Skip second Saturday (8-14 range and Saturday)
            if ($dayOfWeek == 6 && $dayOfMonth >= 8 && $dayOfMonth <= 14) continue;

            // Skip holidays (excluding RH)
            if (in_array($dateStr, $holidayDates)) continue;

            $validDates[] = $dateStr;
        }
        // Always pick the first valid date (guaranteed to have at least one in 7-14 range)
        $data['disbursal_date'] = date('d M, Y', strtotime($validDates[0]));

        $data['month_name'] = date('F, Y', strtotime($month));

        // return view('Pdf/WagesRegister', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/WageSlip', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("WageSlip-" . $data['month_name'] . ".pdf");
    }

    /**
     * Get Overtime Register Data
     */
    private function getOvertimeRegisterData($employees, $month)
    {

        foreach ($employees as $index => $employee) {
            $PreFinalPaidDaysTempModel = new PreFinalPaidDaysTempModel();
            $PreFinalPaidDaysTempModel
                ->where('employee_id =', $employee['id'])
                ->where('date >=', date('Y-m-01', strtotime($month)))
                ->where('date <=', date('Y-m-t', strtotime($month)))
                ->orderBy('date', 'asc');
            $records = $PreFinalPaidDaysTempModel->findAll();

            // Calculate shift_hours and set overtime fields to 0
            foreach ($records as $recordIndex => $record) {
                $shiftStart = $record['shift_start'] ?? null;
                $shiftEnd = $record['shift_end'] ?? null;
                $recordDate = $record['date'] ?? date('Y-m-d');

                // Build full datetime strings for shift calculation
                $shiftStartDateTime = null;
                $shiftEndDateTime = null;

                if (!empty($shiftStart) && !empty($shiftEnd) && $shiftStart !== '--:--' && $shiftEnd !== '--:--') {
                    $shiftStartDateTime = $recordDate . ' ' . $shiftStart;

                    // Check if overnight shift (end time earlier than start time)
                    if (strtotime($shiftEnd) <= strtotime($shiftStart)) {
                        // Add 1 day to shift end date for overnight shifts
                        $shiftEndDateTime = date('Y-m-d', strtotime($recordDate . ' +1 day')) . ' ' . $shiftEnd;
                    } else {
                        $shiftEndDateTime = $recordDate . ' ' . $shiftEnd;
                    }
                }

                $records[$recordIndex]['shift_hours'] = ProcessorHelper::get_time_difference(
                    $shiftStartDateTime,
                    $shiftEndDateTime,
                    'hours'
                );
                $records[$recordIndex]['overtime_hours'] = 0;
                $records[$recordIndex]['overtime_rate'] = 0;
                $records[$recordIndex]['overtime_earning'] = 0;
            }

            $employees[$index]['records'] = $records;
        }
        $data['employees'] = $employees;
        $data['month'] = $month;
        $data['month_name'] = date('F, Y', strtotime($month));

        // return view('Pdf/AttendanceRegister', $data);

        $options = new options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/OvertimeRegister', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("OvertimeRegister-" . $data['month_name'] . ".pdf");
    }

    /**
     * Get Muster Roll Data
     */
    private function getMusterRollData($employees, $month)
    {
        // Group employees by company
        $grouped_by_company = [];

        // Get all dates in the month
        $firstDay = date('Y-m-01', strtotime($month));
        $lastDay = date('Y-m-t', strtotime($month));
        $daysInMonth = (int)date('t', strtotime($month));

        foreach ($employees as $index => $employee) {
            $company_name = $employee['company_name'] ?? 'Unknown Company';

            // Fetch attendance records for this employee from PreFinalPaidDaysTempModel
            $PreFinalPaidDaysTempModel = new PreFinalPaidDaysTempModel();
            $attendanceRecords = $PreFinalPaidDaysTempModel
                ->where('employee_id', $employee['id'])
                ->where('date >=', $firstDay)
                ->where('date <=', $lastDay)
                ->orderBy('date', 'asc')
                ->findAll();

            if (empty($attendanceRecords)) {
                continue;
            }

            // Create attendance map indexed by date
            $attendanceByDate = [];
            $presentCount = 0;
            $weekOffCount = 0;
            $clCount = 0;
            $elCount = 0;
            $hlCount = 0;
            $absentCount = 0;
            $totalPaidDays = 0;

            foreach ($attendanceRecords as $record) {
                $attendanceByDate[$record['date']] = $record;
                $status = strtoupper($record['status'] ?? '');
                $finalPaid = floatval($record['paid'] ?? 0);

                // Count statuses using non-exclusive if blocks to handle combinations properly

                // Present/OD counting (full day)
                if (
                    $status === 'P' ||
                    $status === 'P on OD' ||
                    $status === 'P+OD' ||
                    $status === 'P+UL/2' ||
                    $status === 'OD'
                ) {
                    $presentCount += 1;
                }

                // Present/OD counting (half day) - H/D or OD/2 combinations
                if (
                    $status === 'H/D' ||
                    $status === 'H/D on OD' ||
                    $status === 'H/D on OD+UL/2' ||
                    $status === 'H/D+CL/2' ||
                    $status === 'H/D+COMP OFF/2' ||
                    $status === 'H/D+EL/2' ||
                    $status === 'H/D+UL/2' ||
                    $status === 'H/D+HL/2' ||
                    $status === 'OD/2' ||
                    $status === 'OD/2+CL/2' ||
                    $status === 'OD/2+HL/2' ||
                    $status === 'OD/2+COMP OFF/2' ||
                    $status === 'P+CL/2'
                ) {
                    $presentCount += 0.5;
                }

                // Week Off / Fixed Off counting
                if ($status === 'W/O' || $status === 'F/O') {
                    $weekOffCount += $finalPaid;
                }

                // Absent counting (full day) - includes A, S/W (Sandwich Leave), M/P (Missed Punch)
                if (
                    $status === 'A' ||
                    $status === 'S/W' ||   // Sandwich Leave counts as Absent
                    $status === 'M/P'      // Missed Punch counts as Absent
                ) {
                    $absentCount += 1;
                }

                // Absent (half day) - includes A+CL/2, and H/D without paid leave for other half
                if (
                    $status === 'A+CL/2' ||
                    $status === 'H/D' ||           // H/D alone means other half is absent
                    $status === 'H/D on OD' ||     // H/D on OD means other half is absent
                    $status === 'H/D on OD+UL/2' || // UL is unpaid, so other half counts as absent
                    $status === 'H/D+UL/2'         // UL is unpaid, so other half counts as absent
                ) {
                    $absentCount += 0.5;
                }

                // Unpaid Leave (not paid, just for tracking)
                // UL, UL/2, and combinations with UL are unpaid - no addition to paid counts

                // CL counting (full day)
                if ($status === 'CL') {
                    $clCount += 1;
                }

                // CL counting (half day)
                if (
                    $status === 'CL/2' ||
                    $status === 'A+CL/2' ||
                    $status === 'P+CL/2' ||
                    $status === 'H/D+CL/2' ||
                    $status === 'OD/2+CL/2'
                ) {
                    $clCount += 0.5;
                }

                // EL counting (full day)
                if ($status === 'EL') {
                    $elCount += 1;
                }

                // EL counting (half day)
                if (
                    $status === 'EL/2' ||
                    $status === 'H/D+EL/2'
                ) {
                    $elCount += 0.5;
                }

                // HL/Holiday counting (full day) - includes HL, SPL HL, RH, COMP OFF, NH, INC, ML
                if (
                    $status === 'HL' ||
                    $status === 'SPL HL' ||
                    $status === 'RH' ||
                    $status === 'COMP OFF' ||
                    $status === 'NH' ||
                    $status === 'INC' ||
                    $status === 'ML'  // Maternity Leave counts as H/L
                ) {
                    $hlCount += 1;
                }

                // HL/Holiday counting (half day)
                if (
                    $status === 'HL/2' ||
                    $status === 'H/D+HL/2' ||
                    $status === 'OD/2+HL/2' ||
                    $status === 'COMP OFF/2' ||
                    $status === 'H/D+COMP OFF/2' ||
                    $status === 'OD/2+COMP OFF/2'
                ) {
                    $hlCount += 0.5;
                }

                // Other statuses (M/P, ML, S/W) - tracked via final_paid

                $totalPaidDays += $finalPaid;
            }

            $employee['attendance'] = $attendanceByDate;
            $employee['summary'] = [
                'present' => $presentCount,
                'week_off' => $weekOffCount,
                'cl' => $clCount,
                'el' => $elCount,
                'hl' => $hlCount,
                'absent' => $absentCount,
                'total_paid_days' => $totalPaidDays
            ];

            // Group by company name
            if (!isset($grouped_by_company[$company_name])) {
                $grouped_by_company[$company_name] = [
                    'company_name' => $company_name,
                    'company_short_name' => $employee['company_short_name'] ?? '',
                    'company_address' => $employee['company_address'] ?? '',
                    'company_logo_url' => $employee['company_logo_url'] ?? '',
                    'employees' => []
                ];
            }
            $grouped_by_company[$company_name]['employees'][] = $employee;
        }

        $data['companies'] = array_values($grouped_by_company);
        $data['month'] = date('Y-m', strtotime($month));
        $data['days_in_month'] = $daysInMonth;
        $data['month_name'] = date('F, Y', strtotime($month));

        // return view('Pdf/MusterRoll', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->setIsPhpEnabled(true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/MusterRoll', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream("MusterRoll-" . $data['month_name'] . ".pdf");
    }

    /**
     * Get Leave Register Data
     */
    private function getLeaveRegisterData($employees, $month)
    {
        $range_from = date('Y-01-01', strtotime($month));
        $range_to = date('Y-12-31', strtotime($month));
        $year = date('Y', strtotime($month));

        $LeaveRequestsModel = new LeaveRequestsModel();
        $LeaveBalanceModel = new LeaveBalanceModel();

        foreach ($employees as $index => $employee) {
            // Get EL leave requests for this employee
            $employees[$index]['el_requests'] = $LeaveRequestsModel
                ->where('employee_id', $employee['id'])
                ->where('type_of_leave', 'EL')
                ->where('sick_leave', 'no')
                ->where('status', 'approved')
                ->groupStart()
                ->groupStart()
                ->where('from_date >=', $range_from)
                ->where('from_date <=', $range_to)
                ->groupEnd()
                ->orGroupStart()
                ->where('to_date >=', $range_from)
                ->where('to_date <=', $range_to)
                ->groupEnd()
                ->groupEnd()
                ->orderBy('from_date', 'ASC')
                ->findAll();

            // Get EL balance for this employee
            $balance = $LeaveBalanceModel
                ->where('employee_id', $employee['id'])
                ->where('leave_code', 'EL')
                ->where('year', $year)
                ->where('month', date('m', strtotime($month)))
                ->first();
            $employees[$index]['el_balance'] = $balance['balance'] ?? 0;

            // Get CL leave requests for this employee (including sick leave)
            $employees[$index]['cl_requests'] = $LeaveRequestsModel
                ->where('employee_id', $employee['id'])
                ->groupStart()
                ->where('type_of_leave', 'CL')
                ->orWhere('sick_leave', 'yes')
                ->groupEnd()
                ->where('status', 'approved')
                ->groupStart()
                ->groupStart()
                ->where('from_date >=', $range_from)
                ->where('from_date <=', $range_to)
                ->groupEnd()
                ->orGroupStart()
                ->where('to_date >=', $range_from)
                ->where('to_date <=', $range_to)
                ->groupEnd()
                ->groupEnd()
                ->orderBy('from_date', 'ASC')
                ->findAll();

            // $cl_balance = $LeaveBalanceModel
            //     ->where('employee_id', $employee['id'])
            //     ->where('leave_code', 'CL')
            //     ->where('year', $year)
            //     ->first();
            // $employees[$index]['cl_balance'] = $cl_balance['balance'] ?? 0;
        }

        $data['employees'] = $employees;
        $data['month'] = $month;
        $data['range_from'] = $range_from;
        $data['range_to'] = $range_to;
        $data['year'] = $year;
        $data['page_title'] = 'Register of Leave';

        // echo '<pre>';
        // print_r($data);
        // die();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $content = view('Pdf/LeaveRegister', $data);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("LeaveRegister-" . $data['year'] . ".pdf");
    }
}
