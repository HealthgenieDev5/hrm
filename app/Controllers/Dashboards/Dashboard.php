<?php

namespace App\Controllers\Dashboards;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Models\OdRequestsModel;
use App\Models\CompOffCreditModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{

    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index()
    {

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        // $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'Dashboard - Today ' . date('d M', strtotime(current_date_of_month())),
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'Companies'         =>  $Companies,
            'company_id_for_filter' =>  '',
            // 'company_id_for_filter'=>  $current_company['company_id'],
        ];

        return view('Dashboard/AllDashboard', $data);
    }

    public function getPunchingReports()
    {
        $current_user = $this->session->get('current_user');
        $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];
        $company_id = $this->request->getPost('company_id');
        if ($company_id != 'all_companies' && $company_id != '') {
            $company_condition = " and e.company_id = '" . $company_id . "' ";
        } else {
            $company_condition = " ";
        }
        $current_employee_id = $current_user['employee_id'];
        $CustomModel = new CustomModel();

        $date_today = date('Y-m-d');
        $CustomSql = "select 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name 
        from employees e 
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        where ( e.id = '" . $current_employee_id . "' or e.reporting_manager_id = '" . $current_employee_id . "' or d.hod_employee_id = '" . $current_employee_id . "' or '" . $current_user['role'] . "' in ('admin', 'superuser', 'hr') ) " . $company_condition . " 
        and 
        ( 
            e.date_of_leaving is null 
            or 
            e.date_of_leaving >= '" . $date_today . "'
        )
        order by c.company_short_name ASC, e.first_name ASC";
        $all_employee_query = $CustomModel->CustomQuery($CustomSql);
        $punching_data = array();
        if ($all_employee_query) {
            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();

            foreach ($all_employee_data as $employee_data) {
                foreach ($get_punching_data as $punching_row) {
                    $temp_array = array();
                    if ($punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] !== '--:--') {
                        $temp_array['internal_employee_id'] = $punching_row['Empcode'];
                        $temp_array['employee_name']        = $employee_data['employee_name'];
                        $temp_array['machine']              = $punching_row['machine'];
                        $temp_array['in_time']              = $punching_row['INTime'];
                        $temp_array['out_time']             = $punching_row['OUTTime'];
                        $temp_array['department_name']      = $employee_data['department_name'];
                        $temp_array['company_short_name']   = $employee_data['company_short_name'];
                    }
                    if (!empty($temp_array)) {
                        $punching_data[] = $temp_array;
                    }
                }
            }
        }
        echo json_encode($punching_data);
    }

    public function getAbsentReports()
    {
        $current_user = $this->session->get('current_user');
        $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');

        if ($company_id != 'all_companies' && $company_id != '') {
            $company_condition = " and e.company_id = '" . $company_id . "' ";
        } else {
            $company_condition = " ";
        }

        $current_employee_id = $current_user['employee_id'];

        $CustomModel = new CustomModel();
        $date_today = date('Y-m-d');

        $CustomSql = "select 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name 
        from employees e 
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        where 
        e.id not in ( 
            select 
            leave_requests.employee_id 
            from 
            leave_requests 
            where 
            leave_requests.status in ('approved', 'pending') 
            and ('" . $date_today . "' between leave_requests.from_date and leave_requests.to_date)
        ) 
        and 
        ( 
            e.date_of_leaving is null 
            or 
            e.date_of_leaving >= '" . $date_today . "'
        )
        and 
        e.id not in ( 
            select 
            employee_id 
            from od_requests 
            where 
            od_requests.status in ('approved', 'pending') 
            and 
            ('" . $date_today . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))
        )         
        and 
        ( 
            e.id = '" . $current_employee_id . "' 
            or 
            e.reporting_manager_id = '" . $current_employee_id . "' 
            or 
            d.hod_employee_id = '" . $current_employee_id . "' 
            or 
            '" . $current_user['role'] . "' in ('admin', 'superuser', 'hr') 
        ) 
        " . $company_condition . " 
        order 
        by 
        c.company_short_name ASC, 
        e.first_name ASC";

        $all_employee_query = $CustomModel->CustomQuery($CustomSql);
        $punching_data = array();
        if ($all_employee_query) {
            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();

            foreach ($all_employee_data as $employee_data) {
                foreach ($get_punching_data as $punching_row) {
                    $temp_array = array();
                    if ($punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] == '--:--' && $punching_row['OUTTime'] == '--:--') {
                        $temp_array['internal_employee_id'] = $punching_row['Empcode'];
                        $temp_array['employee_name']        = $employee_data['employee_name'];
                        $temp_array['department_name']      = $employee_data['department_name'];
                        $temp_array['company_short_name']   = $employee_data['company_short_name'];
                        $temp_array['date_time']            = date('d M Y', strtotime($punching_row['DateString']));
                    }
                    if (!empty($temp_array)) {
                        $punching_data[] = $temp_array;
                    }
                }
            }
        }
        echo json_encode($punching_data);
    }

    public function getMissedPunchingReports()
    {
        $current_user = $this->session->get('current_user');
        $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');

        if ($company_id != 'all_companies' && $company_id != '') {
            $company_condition = " and e.company_id = '" . $company_id . "' ";
        } else {
            $company_condition = " ";
        }

        $current_employee_id = $current_user['employee_id'];

        $CustomModel = new CustomModel();
        $date_today = date('Y-m-d');

        $CustomSql = "select 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name 
        from employees e 
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        where 
        e.id not in ( 
            select 
            leave_requests.employee_id 
            from 
            leave_requests 
            where 
            leave_requests.status in ('approved', 'pending') 
            and ('" . $date_today . "' between leave_requests.from_date and leave_requests.to_date)
        ) 
        and 
        ( 
            e.date_of_leaving is null 
            or 
            e.date_of_leaving >= '" . $date_today . "'
        )
        and 
        e.id not in ( 
            select 
            employee_id 
            from od_requests 
            where 
            od_requests.status in ('approved', 'pending') 
            and 
            ('" . $date_today . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))
        )         
        and 
        ( 
            e.id = '" . $current_employee_id . "' 
            or 
            e.reporting_manager_id = '" . $current_employee_id . "' 
            or 
            d.hod_employee_id = '" . $current_employee_id . "' 
            or 
            '" . $current_user['role'] . "' in ('admin', 'superuser', 'hr') 
        ) 
        " . $company_condition . " 
        order 
        by 
        c.company_short_name ASC, 
        e.first_name ASC";

        $all_employee_query = $CustomModel->CustomQuery($CustomSql);
        $punching_data = array();
        if ($all_employee_query) {
            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();

            foreach ($all_employee_data as $employee_data) {
                foreach ($get_punching_data as $punching_row) {
                    $temp_array = array();
                    if (
                        $punching_row['Empcode'] == $employee_data['internal_employee_id'] &&
                        (
                            ($punching_row['INTime'] == '--:--' && $punching_row['OUTTime'] != '--:--') ||
                            ($punching_row['INTime'] != '--:--' && $punching_row['OUTTime'] == '--:--')
                        )
                    ) {
                        $temp_array['internal_employee_id'] = $punching_row['Empcode'];
                        $temp_array['employee_name']        = $employee_data['employee_name'];
                        $temp_array['department_name']      = $employee_data['department_name'];
                        $temp_array['company_short_name']   = $employee_data['company_short_name'];
                        $temp_array['date_time']            = date('d M Y', strtotime($punching_row['DateString']));
                    }
                    if (!empty($temp_array)) {
                        $punching_data[] = $temp_array;
                    }
                }
            }
        }
        echo json_encode($punching_data);
    }

    // public function getLateComingReports()
    // {
    //     $punching_data2 = array();
    //     $current_user = $this->session->get('current_user');
    //     $current_date_of_month = current_date_of_month();
    //     $current_day_of_month = strtolower(current_day_of_month());
    //     $company_id = $this->request->getVar('company_id');
    //     $current_employee_id = $current_user['employee_id'];
    //     $EmployeeModel = new EmployeeModel();
    //     $date_today = date('Y-m-d');
    //     $all_employee_data_query = $EmployeeModel
    //         ->select('employees.id as id')
    //         ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
    //         ->select('employees.internal_employee_id as internal_employee_id')
    //         ->select('d.department_name as department_name')
    //         ->select('c.company_short_name as company_short_name')
    //         ->select('s.shift_name as shift_name')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
    //         ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
    //         ->join('departments d', 'd.id = employees.department_id', 'left')
    //         ->join('companies c', 'c.id = employees.company_id', 'left')
    //         ->join('shifts s', 's.id = employees.shift_id', 'left')
    //         ->groupStart()
    //         ->where('employees.date_of_leaving is null')
    //         ->orWhere("employees.date_of_leaving >= ('" . $date_today . "')")
    //         ->groupEnd()
    //         ->where('(employees.id = "' . $current_employee_id . '" or employees.reporting_manager_id = "' . $current_employee_id . '" or d.hod_employee_id = "' . $current_employee_id . '" or "' . $current_user['role'] . '" in ("admin", "superuser", "hr"))');
    //     if ($company_id != 'all_companies' && $company_id != '') {
    //         $EmployeeModel->where('employees.company_id = ', $company_id);
    //     }

    //     $all_employee_data = $all_employee_data_query->findAll();

    //     $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];

    //     foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
    //         $day = date('l', strtotime($punching_data_row['DateString']));
    //         $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));
    //         $get_punching_data[$punching_data_index]['date_time'] = $date_time;
    //         $get_punching_data[$punching_data_index]['day'] = $day;
    //     }

    //     $punching_data = array();
    //     if (!empty($all_employee_data)) {
    //         foreach ($all_employee_data as $employee_data) {
    //             foreach ($get_punching_data as $punching_row) {
    //                 $temp_array = array();
    //                 if ($punching_row['Empcode'] == $employee_data['internal_employee_id'] && $punching_row['INTime'] !== '--:--') {

    //                     #make shift time array of current date
    //                     if (isset($employee_data[$punching_row['day']]) && !empty($employee_data[$punching_row['day']])) {
    //                         $shift = explode(',', $employee_data[$punching_row['day']]);
    //                     } else {
    //                         $shift = array('', '');
    //                     }
    //                     #make shift time array of current date
    //                     $shift_start = date_create($shift[0]);
    //                     $in_time = date_create($punching_row['INTime']);
    //                     $timediff = $shift_start->diff($in_time);
    //                     $late_minutes   = (int)$timediff->format('%r%i');
    //                     $late_hours     = (int)$timediff->format('%r%h');
    //                     if ($late_minutes > 0 || $late_hours > 0) {
    //                         $temp_array['internal_employee_id'] = $punching_row['Empcode'];
    //                         $temp_array['employee_name']        = $employee_data['employee_name'];
    //                         $temp_array['in_time']              = date('h:i a', strtotime($punching_row['INTime']));
    //                         $temp_array['shift_start']          = date('h:i a', strtotime($shift[0]));
    //                         $temp_array['late_minutes']         = $late_minutes + ($late_hours * 60);
    //                         $temp_array['department_name']      = $employee_data['department_name'];
    //                         $temp_array['company_short_name']   = $employee_data['company_short_name'];
    //                         $temp_array['date_time']            = date('d M Y', strtotime($punching_row['DateString']));
    //                         $temp_array['avg_7d']               = getLateMinutes($employee_data, date('Y-m-d', strtotime(current_date_of_month() . " -7 days")), current_date_of_month())['average'];
    //                         $temp_array['avg_15d']              = getLateMinutes($employee_data, date('Y-m-d', strtotime(current_date_of_month() . " -15 days")), current_date_of_month())['average'];
    //                         $temp_array['avg_mtd']              = getLateMinutes($employee_data, first_date_of_month(), current_date_of_month())['average'];
    //                     }
    //                 }
    //                 if (!empty($temp_array)) {
    //                     $punching_data[] = $temp_array;
    //                 }
    //             }
    //         }
    //         usort($punching_data, function ($a, $b) {
    //             return $a['late_minutes'] <=> $b['late_minutes'];
    //         });
    //         krsort($punching_data);
    //         foreach ($punching_data as $data) {
    //             $punching_data2[] = $data;
    //         }
    //     }

    //     echo json_encode($punching_data2);
    // }

    public function getLateComingReports()
    {
        $current_user = $this->session->get('current_user');
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getVar('company_id');
        $current_employee_id = $current_user['employee_id'];
        $date_today = date('Y-m-d');

        $CustomModel = new CustomModel();

        // Build permission filter
        $permission_filter = "(e.id = '{$current_employee_id}'
            OR e.reporting_manager_id = '{$current_employee_id}'
            OR d.hod_employee_id = '{$current_employee_id}'
            OR '{$current_user['role']}' IN ('admin', 'superuser', 'hr'))";

        // Build company filter
        $company_filter = '';
        if ($company_id != 'all_companies' && $company_id != '') {
            $company_filter = " AND e.company_id = '{$company_id}'";
        }

        // Optimized query using pre_final_paid_days table
        $sql = "
            SELECT
                pfd.employee_id,
                e.internal_employee_id,
                TRIM(CONCAT(e.first_name, ' ', e.last_name)) AS employee_name,
                d.department_name,
                c.company_short_name,
                pfd.date AS date_time,
                pfd.punch_in_time AS in_time,
                pfd.shift_start,
                pfd.late_coming_minutes AS late_minutes,

                -- 7-day average
                (SELECT AVG(late_coming_minutes)
                 FROM `pre_final_paid_days`
                 WHERE employee_id = pfd.employee_id
                 AND date BETWEEN DATE_SUB('{$current_date_of_month}', INTERVAL 7 DAY) AND '{$current_date_of_month}'
                 AND late_coming_minutes > 0
                ) AS avg_7d,

                -- 15-day average
                (SELECT AVG(late_coming_minutes)
                 FROM `pre_final_paid_days`
                 WHERE employee_id = pfd.employee_id
                 AND date BETWEEN DATE_SUB('{$current_date_of_month}', INTERVAL 15 DAY) AND '{$current_date_of_month}'
                 AND late_coming_minutes > 0
                ) AS avg_15d,

                -- Month-to-date average
                (SELECT AVG(late_coming_minutes)
                 FROM `pre_final_paid_days`
                 WHERE employee_id = pfd.employee_id
                 AND date BETWEEN DATE_FORMAT('{$current_date_of_month}', '%Y-%m-01') AND '{$current_date_of_month}'
                 AND late_coming_minutes > 0
                ) AS avg_mtd

            FROM `pre_final_paid_days` pfd
            LEFT JOIN employees e ON e.id = pfd.employee_id
            LEFT JOIN departments d ON d.id = e.department_id
            LEFT JOIN companies c ON c.id = e.company_id

            WHERE pfd.date = '{$current_date_of_month}'
            AND pfd.late_coming_minutes > 0
            AND pfd.punch_in_time IS NOT NULL
            AND pfd.punch_in_time != '--:--'
            AND (e.date_of_leaving IS NULL OR e.date_of_leaving >= '{$date_today}')
            AND {$permission_filter}
            {$company_filter}

            ORDER BY pfd.late_coming_minutes DESC
        ";

        $results = $CustomModel->CustomQuery($sql);

        $punching_data = array();
        if ($results) {
            $data = $results->getResultArray();

            foreach ($data as $row) {
                $punching_data[] = array(
                    'internal_employee_id' => $row['internal_employee_id'],
                    'employee_name'        => $row['employee_name'],
                    'in_time'              => !empty($row['in_time']) && $row['in_time'] != '--:--' ? date('h:i a', strtotime($row['in_time'])) : '--:--',
                    'shift_start'          => !empty($row['shift_start']) ? date('h:i a', strtotime($row['shift_start'])) : '--:--',
                    'late_minutes'         => (int)$row['late_minutes'],
                    'department_name'      => $row['department_name'] ?? '',
                    'company_short_name'   => $row['company_short_name'] ?? '',
                    'date_time'            => date('d M Y', strtotime($row['date_time'])),
                    'avg_7d'               => round($row['avg_7d'] ?? 0, 2),
                    'avg_15d'              => round($row['avg_15d'] ?? 0, 2),
                    'avg_mtd'              => round($row['avg_mtd'] ?? 0, 2)
                );
            }
        }

        echo json_encode($punching_data);
    }

    public function getOnLeaveTodayPending()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $current_user['employee_id'];
        $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');
        /*if( $company_id != 'all_companies' && $company_id != '' ){
            $company_condition = " and e.company_id = '".$company_id."' ";
        }else{
            $company_condition = " ";
        }*/

        $date_today = date('Y-m-d');

        $LeaveRequestsModel = new LeaveRequestsModel();
        $all_leave_requests_query = $LeaveRequestsModel
            ->select('leave_requests.*')
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
            ->select('e.internal_employee_id as internal_employee_id')
            ->select('d.department_name as department_name')
            ->select('c.company_short_name as company_short_name')
            ->join('employees e', 'e.id = leave_requests.employee_id', 'left')
            ->join('employees e2', 'e2.id = leave_requests.reviewed_by', 'left')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('companies c', 'c.id = e.company_id', 'left')
            ->where('leave_requests.status in ("pending")')
            ->where(
                '( 
                                                           (leave_requests.from_date between "' . $current_date_of_month . '" and "' . $current_date_of_month . '") 
                                                        or (leave_requests.to_date between "' . $current_date_of_month . '" and "' . $current_date_of_month . '") 
                                                        or ("' . $current_date_of_month . '" between leave_requests.from_date and leave_requests.to_date ) 
                                                        or ("' . $current_date_of_month . '" between leave_requests.from_date and leave_requests.to_date )
                                                        )'
            )
            ->groupStart()
            ->where('e.date_of_leaving is null')
            ->orWhere("e.date_of_leaving >= ('" . $date_today . "')")
            ->groupEnd()
            ->where('(e.id = "' . $current_employee_id . '" or e.reporting_manager_id = "' . $current_employee_id . '" or d.hod_employee_id = "' . $current_employee_id . '" or "' . $current_user['role'] . '" in ("admin", "superuser", "hr"))');
        if ($company_id != 'all_companies' && $company_id != '') {
            $LeaveRequestsModel->where('e.company_id = ', $company_id);
        }
        $all_leave_requests = $all_leave_requests_query->findAll();
        /*echo $LeaveRequestsModel->getLastQuery()->getQuery();
        die();*/

        foreach ($all_leave_requests as $index => $array) {
            if (isset($array['from_date']) && !empty($array['from_date']) && isset($array['to_date']) && !empty($array['to_date'])) {
                $from_date = date_create($array['from_date']);
                $to_date = date_create($array['to_date']);
                $interval = date_diff($from_date, $to_date);
                $all_leave_requests[$index]['number_of_days'] = (int)$interval->format('%d') + 1;

                $currentDate = date_create(date('Y-m-d'));
                $date_time = date_create($array['date_time']);
                $pendingInterval = date_diff($from_date, $to_date);
                $all_leave_requests[$index]['pending_days'] = (int)$pendingInterval->format('%d') + 1;
            }

            $from_date_formatted = !empty($array['from_date']) ? date('d M Y', strtotime($array['from_date'])) : '-';
            $from_date_ordering = !empty($array['from_date']) ? strtotime($array['from_date']) : '0';
            $all_leave_requests[$index]['from_date'] = array('formatted' => $from_date_formatted, 'ordering' => $from_date_ordering);

            $to_date_formatted = !empty($array['to_date']) ? date('d M Y', strtotime($array['to_date'])) : '-';
            $to_date_ordering = !empty($array['to_date']) ? strtotime($array['to_date']) : '0';
            $all_leave_requests[$index]['to_date'] = array('formatted' => $to_date_formatted, 'ordering' => $to_date_ordering);

            $reviewed_date_formatted = !empty($array['reviewed_date']) ? date('d M Y', strtotime($array['reviewed_date'])) : '-';
            $reviewed_date_ordering = !empty($array['reviewed_date']) ? strtotime($array['reviewed_date']) : '0';
            $all_leave_requests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);
        }


        /*echo '<pre>';
        print_r($all_leave_requests);
        die();*/

        /*foreach( $get_punching_data as $i => $punching_row ){
            if( $punching_row['Empcode'] == '588' && in_array(date('Y-m-d', strtotime($punching_row['DateString'])), ['2022-09-15'] ) ) {
                $get_punching_data[$i]['INTime'] ='--:--';
                $get_punching_data[$i]['OUTTime'] ='--:--';
            }
        }*/


        $present_employees_empcodes = array();
        foreach ($get_punching_data as $punching_row) {
            if ($punching_row['INTime'] !== '--:--') {
                $present_employees_empcodes[] = $punching_row['Empcode'];
            }
        }

        foreach ($all_leave_requests as $index => $leave_request) {
            if (in_array($leave_request['internal_employee_id'], $present_employees_empcodes)) {
                unset($all_leave_requests[$index]);
            }
        }
        echo json_encode($all_leave_requests);

        /*$punching_data = array();       
        if( !empty($all_leave_requests) ){
            foreach( $all_leave_requests as $leave_request ){
                foreach( $get_punching_data as $punching_row ){
                    $temp_array = array();
                    if( $punching_row['Empcode'] == $leave_request['internal_employee_id'] && $punching_row['INTime'] == '--:--'){
                        $temp_array['from_date']            = $leave_request['from_date'];
                        $temp_array['to_date']              = $leave_request['to_date'];
                        $temp_array['number_of_days']       = $leave_request['number_of_days'];
                        $temp_array['pending_days']         = $leave_request['pending_days'];
                        $temp_array['internal_employee_id'] = $punching_row['Empcode'];
                        $temp_array['employee_name']        = $leave_request['employee_name'];
                        $temp_array['department_name']      = $leave_request['department_name'];
                        $temp_array['company_short_name']   = $leave_request['company_short_name'];
                        $temp_array['status']               = $leave_request['status'];

                        $date_time_formatted = !empty($punching_row['DateString']) ? date( 'd M Y', strtotime($punching_row['DateString']) ) : '-' ;
                        $date_time_ordering = !empty($punching_row['DateString']) ? strtotime($punching_row['DateString']) : '0' ;
                        $temp_array['date_time'] = array('formatted'=>$date_time_formatted, 'ordering'=>$date_time_ordering);

                        $temp_array['address_d_l']          = $leave_request['address_d_l'];
                        $temp_array['emergency_contact_d_l'] = $leave_request['emergency_contact_d_l'];
                        $temp_array['reason_of_leave']      = $leave_request['reason_of_leave'];
                        $temp_array['attachment']           = $leave_request['attachment'];
                        $temp_array['reviewed_by_name']     = $leave_request['reviewed_by_name'];
                        $temp_array['reviewed_date']        = $leave_request['reviewed_date'];
                        $temp_array['remarks']              = $leave_request['remarks'];
                    }
                    if( !empty($temp_array) ){
                        $punching_data[] = $temp_array;
                    }
                }
            }
        }
        echo json_encode($punching_data);*/
    }

    public function getOnLeaveTodayApproved()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $current_user['employee_id'];
        $get_punching_data = json_decode(get_punching_data(), true)['InOutPunchData'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');
        if ($company_id != 'all_companies' && $company_id != '') {
            $company_condition = " and e.company_id = '" . $company_id . "' ";
        } else {
            $company_condition = " ";
        }

        $date_today = date('Y-m-d');

        $LeaveRequestsModel = new LeaveRequestsModel();
        $all_leave_requests_query = $LeaveRequestsModel
            ->select('leave_requests.*')
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
            ->select('e.internal_employee_id as internal_employee_id')
            ->select('d.department_name as department_name')
            ->select('c.company_short_name as company_short_name')
            ->join('employees e', 'e.id = leave_requests.employee_id', 'left')
            ->join('employees e2', 'e2.id = leave_requests.reviewed_by', 'left')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('companies c', 'c.id = e.company_id', 'left')
            ->where('leave_requests.status in ("approved")')
            ->groupStart()
            ->where('e.date_of_leaving is null')
            ->orWhere("e.date_of_leaving >= ('" . $date_today . "')")
            ->groupEnd()
            ->where('("' . $current_date_of_month . '" between leave_requests.from_date and leave_requests.to_date)')
            ->where('(e.id = "' . $current_employee_id . '" or e.reporting_manager_id = "' . $current_employee_id . '" or d.hod_employee_id = "' . $current_employee_id . '" or "' . $current_user['role'] . '" in ("admin", "superuser", "hr"))');
        if ($company_id != 'all_companies' && $company_id != '') {
            $LeaveRequestsModel->where('e.company_id = ', $company_id);
        }
        $all_leave_requests = $all_leave_requests_query->findAll();



        foreach ($all_leave_requests as $index => $array) {
            if (isset($array['from_date']) && !empty($array['from_date']) && isset($array['to_date']) && !empty($array['to_date'])) {
                $from_date = date_create($array['from_date']);
                $to_date = date_create($array['to_date']);
                $interval = date_diff($from_date, $to_date);
                $all_leave_requests[$index]['number_of_days'] = (int)$interval->format('%d') + 1;
            }

            $from_date_formatted = !empty($array['from_date']) ? date('d M Y', strtotime($array['from_date'])) : '-';
            $from_date_ordering = !empty($array['from_date']) ? strtotime($array['from_date']) : '0';
            $all_leave_requests[$index]['from_date'] = array('formatted' => $from_date_formatted, 'ordering' => $from_date_ordering);

            $to_date_formatted = !empty($array['to_date']) ? date('d M Y', strtotime($array['to_date'])) : '-';
            $to_date_ordering = !empty($array['to_date']) ? strtotime($array['to_date']) : '0';
            $all_leave_requests[$index]['to_date'] = array('formatted' => $to_date_formatted, 'ordering' => $to_date_ordering);

            $reviewed_date_formatted = !empty($array['reviewed_date']) ? date('d M Y', strtotime($array['reviewed_date'])) : '-';
            $reviewed_date_ordering = !empty($array['reviewed_date']) ? strtotime($array['reviewed_date']) : '0';
            $all_leave_requests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);
        }



        /*echo '<pre>';
        print_r($get_punching_data);
        die();*/


        $present_employees_empcodes = array();
        foreach ($get_punching_data as $punching_row) {
            if ($punching_row['INTime'] !== '--:--') {
                $present_employees_empcodes[] = $punching_row['Empcode'];
            }
        }

        foreach ($all_leave_requests as $index => $leave_request) {
            if (in_array($leave_request['internal_employee_id'], $present_employees_empcodes)) {
                unset($all_leave_requests[$index]);
            }
        }
        echo json_encode($all_leave_requests);

        /*$punching_data = array(); 
        if( !empty($all_leave_requests) ){
            foreach( $all_leave_requests as $leave_request ){
                foreach( $get_punching_data as $punching_row ){
                    $temp_array = array();
                    if( $punching_row['Empcode'] == $leave_request['internal_employee_id'] && $punching_row['INTime'] == '--:--' ){
                        $temp_array['from_date']            = $leave_request['from_date'];
                        $temp_array['to_date']              = $leave_request['to_date'];
                        $temp_array['number_of_days']       = $leave_request['number_of_days'];
                        $temp_array['internal_employee_id'] = $punching_row['Empcode'];
                        $temp_array['employee_name']        = $leave_request['employee_name'];
                        $temp_array['department_name']      = $leave_request['department_name'];
                        $temp_array['company_short_name']   = $leave_request['company_short_name'];
                        $temp_array['status']               = $leave_request['status'];

                        $date_time_formatted = !empty($punching_row['DateString']) ? date( 'd M Y', strtotime($punching_row['DateString']) ) : '-' ;
                        $date_time_ordering = !empty($punching_row['DateString']) ? strtotime($punching_row['DateString']) : '0' ;
                        $temp_array['date_time'] = array('formatted'=>$date_time_formatted, 'ordering'=>$date_time_ordering);

                        $temp_array['address_d_l']          = $leave_request['address_d_l'];
                        $temp_array['emergency_contact_d_l'] = $leave_request['emergency_contact_d_l'];
                        $temp_array['reason_of_leave']      = $leave_request['reason_of_leave'];
                        $temp_array['attachment']           = $leave_request['attachment'];
                        $temp_array['reviewed_by_name']     = $leave_request['reviewed_by_name'];
                        $temp_array['reviewed_date']        = $leave_request['reviewed_date'];
                        $temp_array['remarks']              = $leave_request['remarks'];
                    }
                    if( !empty($temp_array) ){
                        $punching_data[] = $temp_array;
                    }
                }
            }
        }
        echo json_encode($punching_data);*/
    }

    public function getOnOdTodayPending()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $current_user['employee_id'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');

        $date_today = date('Y-m-d');
        $OdRequestsModel =  new OdRequestsModel();
        $all_od_requests_query = $OdRequestsModel
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as assigned_by")
            ->select("e.internal_employee_id as internal_employee_id")
            ->select("d.department_name as department_name")
            ->select("c.company_short_name as company_short_name")
            ->select("od_requests.*")
            ->join("employees e", "e.id = od_requests.employee_id", "left")
            ->join("employees e2", "e2.id = od_requests.reviewed_by", "left")
            ->join("employees e3", "e3.id = od_requests.duty_assigner", "left")
            ->join("departments d", "d.id = e.department_id", "left")
            ->join("companies c", "c.id = e.company_id", "left")
            ->where("od_requests.status in ('pending')")
            ->groupStart()
            ->where('e.date_of_leaving is null')
            ->orWhere("e.date_of_leaving >= ('" . $date_today . "')")
            ->groupEnd()
            ->where("('" . $current_date_of_month . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))")
            ->where('(e.id = "' . $current_employee_id . '" or e.reporting_manager_id = "' . $current_employee_id . '" or d.hod_employee_id = "' . $current_employee_id . '" or "' . $current_user['role'] . '" in ("admin", "superuser", "hr"))');
        if ($company_id != 'all_companies' && $company_id != '') {
            $OdRequestsModel->where('e.company_id = ', $company_id);
        }

        $all_od_requests = $all_od_requests_query->findAll();
        /*echo '<pre>';
        print_r($OdRequestsModel->getlastQuery()->getQuery());
        echo '</pre>';
        die();*/

        if (!empty($all_od_requests)) {
            foreach ($all_od_requests as $index => $od_request) {
                $all_od_requests[$index]['estimated_from_time']  = date('h:i a', strtotime($od_request['estimated_from_date_time']));
                $all_od_requests[$index]['estimated_to_time']    = date('h:i a', strtotime($od_request['estimated_to_date_time']));

                if (isset($od_request['estimated_from_date_time']) && !empty($od_request['estimated_from_date_time']) && isset($od_request['date_time']) && !empty($od_request['date_time'])) {
                    if (strtotime($od_request['estimated_from_date_time']) >= strtotime($od_request['date_time'])) {
                        $all_od_requests[$index]['pre_post'] = 'Pre';
                    } else {
                        $all_od_requests[$index]['pre_post'] = 'Post';
                    }
                }

                if (isset($od_request['actual_from_date_time']) && !empty($od_request['actual_from_date_time']) && isset($od_request['actual_to_date_time']) && !empty($od_request['actual_to_date_time'])) {
                    $actual_from_date_time = date_create($od_request['actual_from_date_time']);
                    $actual_to_date_time = date_create($od_request['actual_to_date_time']);
                    $interval = date_diff($actual_from_date_time, $actual_to_date_time);
                } elseif (isset($od_request['estimated_from_date_time']) && !empty($od_request['estimated_from_date_time']) && isset($od_request['estimated_to_date_time']) && !empty($od_request['estimated_to_date_time'])) {
                    $estimated_from_date_time = date_create($od_request['estimated_from_date_time']);
                    $estimated_to_date_time = date_create($od_request['estimated_to_date_time']);
                    $interval = date_diff($estimated_from_date_time, $estimated_to_date_time);
                }
                $hours = 0;
                $hours += (int)$interval->format('%d') * 24;
                $hours += (int)$interval->format('%h');
                $minutes = 0;
                $minutes += (int)$interval->format('%i');
                $minutes += round((int)$interval->format('%s') / 60);
                $all_od_requests[$index]['interval'] = json_encode($interval);
                $all_od_requests[$index]['interval'] = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

                if ($od_request['duty_assigner'] == $od_request['employee_id']) {
                    $all_od_requests[$index]['assigned_by'] = 'Self';
                }

                $estimated_from_date_time_formatted = !empty($od_request['estimated_from_date_time']) ? date('d M Y h:i A', strtotime($od_request['estimated_from_date_time'])) : '-';
                $estimated_from_date_time_ordering = !empty($od_request['estimated_from_date_time']) ? strtotime($od_request['estimated_from_date_time']) : '0';
                $all_od_requests[$index]['estimated_from_date_time'] = array('formatted' => $estimated_from_date_time_formatted, 'ordering' => $estimated_from_date_time_ordering);

                $estimated_to_date_time_formatted = !empty($od_request['estimated_to_date_time']) ? date('d M Y h:i A', strtotime($od_request['estimated_to_date_time'])) : '-';
                $estimated_to_date_time_ordering = !empty($od_request['estimated_to_date_time']) ? strtotime($od_request['estimated_to_date_time']) : '0';
                $all_od_requests[$index]['estimated_to_date_time'] = array('formatted' => $estimated_to_date_time_formatted, 'ordering' => $estimated_to_date_time_ordering);

                $actual_from_date_time_formatted = !empty($od_request['actual_from_date_time']) ? date('d M Y h:i A', strtotime($od_request['actual_from_date_time'])) : '-';
                $actual_from_date_time_ordering = !empty($od_request['actual_from_date_time']) ? strtotime($od_request['actual_from_date_time']) : '0';
                $all_od_requests[$index]['actual_from_date_time'] = array('formatted' => $actual_from_date_time_formatted, 'ordering' => $actual_from_date_time_ordering);

                $actual_to_date_time_formatted = !empty($od_request['actual_to_date_time']) ? date('d M Y h:i A', strtotime($od_request['actual_to_date_time'])) : '-';
                $actual_to_date_time_ordering = !empty($od_request['actual_to_date_time']) ? strtotime($od_request['actual_to_date_time']) : '0';
                $all_od_requests[$index]['actual_to_date_time'] = array('formatted' => $actual_to_date_time_formatted, 'ordering' => $actual_to_date_time_ordering);

                $reviewed_date_time_formatted = !empty($od_request['reviewed_date_time']) ? date('d M Y h:i A', strtotime($od_request['reviewed_date_time'])) : '-';
                $reviewed_date_time_ordering = !empty($od_request['reviewed_date_time']) ? strtotime($od_request['reviewed_date_time']) : '0';
                $all_od_requests[$index]['reviewed_date_time'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($od_request['date_time']) ? date('d M Y h:i A', strtotime($od_request['date_time'])) : '-';
                $date_time_ordering = !empty($od_request['date_time']) ? strtotime($od_request['date_time']) : '0';
                $all_od_requests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $updated_date_time_formatted = !empty($od_request['updated_date_time']) ? date('d M Y h:i A', strtotime($od_request['updated_date_time'])) : '-';
                $updated_date_time_ordering = !empty($od_request['updated_date_time']) ? strtotime($od_request['updated_date_time']) : '0';
                $all_od_requests[$index]['updated_date_time'] = array('formatted' => $updated_date_time_formatted, 'ordering' => $updated_date_time_ordering);
            }
        }
        echo json_encode($all_od_requests);
    }

    public function getOnOdTodayApproved()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $current_user['employee_id'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');

        $date_today = date('Y-m-d');
        $OdRequestsModel =  new OdRequestsModel();
        $all_od_requests_query = $OdRequestsModel
            ->select("trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as assigned_by")
            ->select("e.internal_employee_id as internal_employee_id")
            ->select("d.department_name as department_name")
            ->select("c.company_short_name as company_short_name")
            ->select("od_requests.*")
            ->join("employees e", "e.id = od_requests.employee_id", "left")
            ->join("employees e2", "e2.id = od_requests.reviewed_by", "left")
            ->join("employees e3", "e3.id = od_requests.duty_assigner", "left")
            ->join("departments d", "d.id = e.department_id", "left")
            ->join("companies c", "c.id = e.company_id", "left")
            ->where("od_requests.status in ('approved')")
            ->groupStart()
            ->where('e.date_of_leaving is null')
            ->orWhere("e.date_of_leaving >= ('" . $date_today . "')")
            ->groupEnd()
            ->where("('" . $current_date_of_month . "' between date(od_requests.estimated_from_date_time) and date(od_requests.estimated_to_date_time))")
            ->where('(e.id = "' . $current_employee_id . '" or e.reporting_manager_id = "' . $current_employee_id . '" or d.hod_employee_id = "' . $current_employee_id . '" or "' . $current_user['role'] . '" in ("admin", "superuser", "hr"))');
        if ($company_id != 'all_companies' && $company_id != '') {
            $OdRequestsModel->where('e.company_id = ', $company_id);
        }

        $all_od_requests = $all_od_requests_query->findAll();
        /*echo '<pre>';
        print_r($OdRequestsModel->getlastQuery()->getQuery());
        echo '</pre>';
        die();*/

        if (!empty($all_od_requests)) {
            foreach ($all_od_requests as $index => $od_request) {
                $all_od_requests[$index]['estimated_from_time']  = date('h:i a', strtotime($od_request['estimated_from_date_time']));
                $all_od_requests[$index]['estimated_to_time']    = date('h:i a', strtotime($od_request['estimated_to_date_time']));

                if (isset($od_request['estimated_from_date_time']) && !empty($od_request['estimated_from_date_time']) && isset($od_request['date_time']) && !empty($od_request['date_time'])) {
                    if (strtotime($od_request['estimated_from_date_time']) >= strtotime($od_request['date_time'])) {
                        $all_od_requests[$index]['pre_post'] = 'Pre';
                    } else {
                        $all_od_requests[$index]['pre_post'] = 'Post';
                    }
                }

                if (isset($od_request['actual_from_date_time']) && !empty($od_request['actual_from_date_time']) && isset($od_request['actual_to_date_time']) && !empty($od_request['actual_to_date_time'])) {
                    $actual_from_date_time = date_create($od_request['actual_from_date_time']);
                    $actual_to_date_time = date_create($od_request['actual_to_date_time']);
                    $interval = date_diff($actual_from_date_time, $actual_to_date_time);
                } elseif (isset($od_request['estimated_from_date_time']) && !empty($od_request['estimated_from_date_time']) && isset($od_request['estimated_to_date_time']) && !empty($od_request['estimated_to_date_time'])) {
                    $estimated_from_date_time = date_create($od_request['estimated_from_date_time']);
                    $estimated_to_date_time = date_create($od_request['estimated_to_date_time']);
                    $interval = date_diff($estimated_from_date_time, $estimated_to_date_time);
                }
                $hours = 0;
                $hours += (int)$interval->format('%d') * 24;
                $hours += (int)$interval->format('%h');
                $minutes = 0;
                $minutes += (int)$interval->format('%i');
                $minutes += round((int)$interval->format('%s') / 60);
                $all_od_requests[$index]['interval'] = json_encode($interval);
                $all_od_requests[$index]['interval'] = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

                if ($od_request['duty_assigner'] == $od_request['employee_id']) {
                    $all_od_requests[$index]['assigned_by'] = 'Self';
                }

                $estimated_from_date_time_formatted = !empty($od_request['estimated_from_date_time']) ? date('d M Y h:i A', strtotime($od_request['estimated_from_date_time'])) : '-';
                $estimated_from_date_time_ordering = !empty($od_request['estimated_from_date_time']) ? strtotime($od_request['estimated_from_date_time']) : '0';
                $all_od_requests[$index]['estimated_from_date_time'] = array('formatted' => $estimated_from_date_time_formatted, 'ordering' => $estimated_from_date_time_ordering);

                $estimated_to_date_time_formatted = !empty($od_request['estimated_to_date_time']) ? date('d M Y h:i A', strtotime($od_request['estimated_to_date_time'])) : '-';
                $estimated_to_date_time_ordering = !empty($od_request['estimated_to_date_time']) ? strtotime($od_request['estimated_to_date_time']) : '0';
                $all_od_requests[$index]['estimated_to_date_time'] = array('formatted' => $estimated_to_date_time_formatted, 'ordering' => $estimated_to_date_time_ordering);

                $actual_from_date_time_formatted = !empty($od_request['actual_from_date_time']) ? date('d M Y h:i A', strtotime($od_request['actual_from_date_time'])) : '-';
                $actual_from_date_time_ordering = !empty($od_request['actual_from_date_time']) ? strtotime($od_request['actual_from_date_time']) : '0';
                $all_od_requests[$index]['actual_from_date_time'] = array('formatted' => $actual_from_date_time_formatted, 'ordering' => $actual_from_date_time_ordering);

                $actual_to_date_time_formatted = !empty($od_request['actual_to_date_time']) ? date('d M Y h:i A', strtotime($od_request['actual_to_date_time'])) : '-';
                $actual_to_date_time_ordering = !empty($od_request['actual_to_date_time']) ? strtotime($od_request['actual_to_date_time']) : '0';
                $all_od_requests[$index]['actual_to_date_time'] = array('formatted' => $actual_to_date_time_formatted, 'ordering' => $actual_to_date_time_ordering);

                $reviewed_date_time_formatted = !empty($od_request['reviewed_date_time']) ? date('d M Y h:i A', strtotime($od_request['reviewed_date_time'])) : '-';
                $reviewed_date_time_ordering = !empty($od_request['reviewed_date_time']) ? strtotime($od_request['reviewed_date_time']) : '0';
                $all_od_requests[$index]['reviewed_date_time'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($od_request['date_time']) ? date('d M Y h:i A', strtotime($od_request['date_time'])) : '-';
                $date_time_ordering = !empty($od_request['date_time']) ? strtotime($od_request['date_time']) : '0';
                $all_od_requests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $updated_date_time_formatted = !empty($od_request['updated_date_time']) ? date('d M Y h:i A', strtotime($od_request['updated_date_time'])) : '-';
                $updated_date_time_ordering = !empty($od_request['updated_date_time']) ? strtotime($od_request['updated_date_time']) : '0';
                $all_od_requests[$index]['updated_date_time'] = array('formatted' => $updated_date_time_formatted, 'ordering' => $updated_date_time_ordering);
            }
        }
        echo json_encode($all_od_requests);
    }

    public function getAllCompOffCreditRequests()
    {

        #this is database details
        $current_user = $this->session->get('current_user');
        $current_employee_id = $current_user['employee_id'];
        $current_date_of_month = current_date_of_month();
        $company_id = $this->request->getPost('company_id');

        $date_today = date('Y-m-d');

        $CompOffCreditModel = new CompOffCreditModel();
        $CompOffCreditModel
            ->select('comp_off_credit_requests.*')
            ->select("trim( concat( e1.first_name, ' ', e1.last_name ) ) as employee_name")
            ->select("trim( concat( e2.first_name, ' ', e2.last_name ) ) as reporting_manager_name")
            ->select('d.department_name as department_name')
            ->select("trim( concat( e3.first_name, ' ', e3.last_name ) ) as hod_name")
            ->select("trim( concat( e4.first_name, ' ', e4.last_name ) ) as reviewed_by_name")
            ->select("trim( concat( e5.first_name, ' ', e5.last_name ) ) as assigned_by_name")
            ->join('employees as e1', 'e1.id = comp_off_credit_requests.employee_id', 'left')
            ->join('employees as e2', 'e2.id = e1.reporting_manager_id', 'left')
            ->join('departments as d', 'd.id = e1.department_id', 'left')
            ->join('employees as e3', 'e3.id = d.hod_employee_id', 'left')
            ->join('employees as e4', 'e4.id = comp_off_credit_requests.reviewed_by', 'left')
            ->join('employees as e5', 'e5.id = comp_off_credit_requests.assigned_by', 'left')
            ->groupStart()
            ->where('e1.date_of_leaving is null')
            ->orWhere("e1.date_of_leaving >= ('" . $date_today . "')")
            ->groupEnd();
        /*if( $company_id != 'all_companies' && $company_id != '' ){
            $CompOffCreditModel->where('e1.company_id = ', $company_id);
        }*/
        $CompOffCreditModel->orderBy('comp_off_credit_requests.date_time', 'DESC');

        $CompOffCreditRequests = $CompOffCreditModel->findAll();

        if (!empty($CompOffCreditRequests)) {
            foreach ($CompOffCreditRequests as $index => $row) {

                $expiry_date_formatted = !empty($row['working_date']) ? date('d M Y', strtotime($row['working_date'] . ' + 90 days')) : '-';
                $expiry_date_ordering = !empty($row['working_date']) ? strtotime($row['working_date'] . ' + 90 days') : '0';
                $CompOffCreditRequests[$index]['expiry_date'] = array('formatted' => $expiry_date_formatted, 'ordering' => $expiry_date_ordering);

                $working_date_formatted = !empty($row['working_date']) ? date('d M Y', strtotime($row['working_date'])) : '-';
                $working_date_ordering = !empty($row['working_date']) ? strtotime($row['working_date']) : '0';
                $CompOffCreditRequests[$index]['working_date'] = array('formatted' => $working_date_formatted, 'ordering' => $working_date_ordering);

                $reviewed_date_time_formatted = !empty($row['reviewed_date_time']) ? date('d M Y h:i A', strtotime($row['reviewed_date_time'])) : '-';
                $reviewed_date_time_ordering = !empty($row['reviewed_date_time']) ? strtotime($row['reviewed_date_time']) : '0';
                $CompOffCreditRequests[$index]['reviewed_date'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                $date_time_formatted = !empty($row['date_time']) ? date('d M Y h:i A', strtotime($row['date_time'])) : '-';
                $date_time_ordering = !empty($row['date_time']) ? strtotime($row['date_time']) : '0';
                $CompOffCreditRequests[$index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                $CompOffCreditRequests[$index]['gate_pass_hours'] = !empty($row['gate_pass_hours']) ? date('h:i A', strtotime($row['gate_pass_hours'])) : '-';
            }
        }

        return $this->response->setJSON($CompOffCreditRequests);
    }
}
