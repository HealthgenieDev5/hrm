<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\HolidayModel;
use App\Models\EmployeeModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;

class Punching extends BaseController
{
    public $session;

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
        $current_department = $EmployeeModel->select('department_id')->where('id =', $current_user['employee_id'])->first();
        $current_employee_id = $current_user['employee_id'];

        $data = [
            'page_title'            => 'Punching Report',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'Companies'             => $Companies,
            /*'company_id_for_menu_url' =>  $current_company['company_id'],
            'department_id_for_menu_url' =>  $current_department['department_id'],
            'employee_id_for_menu_url' =>  $current_employee_id,*/
        ];

        $where_company = " ";

        if (!isset($_REQUEST) || empty($_REQUEST)) {
            $url = base_url("/backend/reports/punching-report") . "?company[]=" . $this->session->get('current_user')['company_id'] . "&department[]=" . $this->session->get('current_user')['department_id'] . "&employee[]=" . $this->session->get('current_user')['employee_id'];
            return redirect()->to($url);
        }

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
            trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
            d.department_name as department_name, 
            c.company_short_name as company_short_name 
            from employees e 
            left join departments d on d.id = e.department_id
            left join companies c on c.id = e.company_id 
            where e.id is not null " . $where_department . " order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }
        return view('Reports/PunchingReport', $data);
    }

    public function tempFunction($internal_employee_id, $from, $to)
    {
        $get_punching_data = json_decode(get_punching_data($internal_employee_id, $from, $to), true)['InOutPunchData'];
        return $get_punching_data;
    }

    public function getPunchingReports()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";

        if (isset($company_id) && !empty($company_id) && !in_array('all_companies', $company_id) && !in_array('', $company_id)) {
            $company_id_imploded = "'" . implode("', '", $company_id) . "'";
            $company_condition = " and e.company_id in (" . $company_id_imploded . ") ";
        } else {
            $company_condition = " ";
        }

        if (isset($department_id) && !empty($department_id) && !in_array('all_departments', $department_id) && !in_array('', $department_id)) {
            $department_id_imploded = "'" . implode("', '", $department_id) . "'";
            $department_condition = " and e.department_id in (" . $department_id_imploded . ") ";
        } else {
            $department_condition = " ";
        }
        if (isset($employee_id) && !empty($employee_id) && !in_array('all_employees', $employee_id) && !in_array('', $employee_id)) {
            $employee_id_imploded = "'" . implode("', '", $employee_id) . "'";
            $employee_condition = " and e.id in (" . $employee_id_imploded . ") ";
        } else {
            $employee_condition = " ";
        }

        $CustomModel = new CustomModel();
        $CustomSql = "select 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        e.id as employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name, 
        s.shift_name as shift_name, 
        s.shift_code as shift_code, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'monday' and shift_id = e.shift_id) as Monday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'tuesday' and shift_id = e.shift_id) as Tuesday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'wednesday' and shift_id = e.shift_id) as Wednesday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'thursday' and shift_id = e.shift_id) as Thursday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'friday' and shift_id = e.shift_id) as Friday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'saturday' and shift_id = e.shift_id) as Saturday, 
        (select concat(shift_start, ',',shift_end) from shift_per_day where day = 'sunday' and shift_id = e.shift_id) as Sunday
        from employees e 
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        left join shifts s on s.id = e.shift_id 
        where e.id is not null " . $company_condition . $department_condition . $employee_condition . " 
        order by c.company_short_name ASC, 
        e.first_name ASC";

        $all_employee_query = $CustomModel->CustomQuery($CustomSql);

        $punchingData = array();

        $from_date = $params['from_date'];
        $to_date = $params['to_date'];

        $get_punching_data = json_decode(get_punching_data('ALL', $from_date, $to_date), true)['InOutPunchData'];

        foreach ($get_punching_data as $punching_data_index => $punching_data_row) {
            $day = date('l', strtotime($punching_data_row['DateString']));
            // $date_time = date('Y-m-d', strtotime($punching_data_row['DateString']));

            $date_time_formatted = !empty($punching_data_row['DateString']) ? date('d M Y', strtotime(str_replace("/", "-", $punching_data_row['DateString']))) : '-';
            $date_time_ordering = !empty($punching_data_row['DateString']) ? strtotime(str_replace("/", "-", $punching_data_row['DateString'])) : '0';
            $get_punching_data[$punching_data_index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);
            $get_punching_data[$punching_data_index]['date_time_ordering'] = $date_time_ordering;

            $get_punching_data[$punching_data_index]['day'] = $day;
        }

        if ($all_employee_query) {
            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();
            foreach ($get_punching_data as $punching_data_index => $punching_data_row) {

                foreach ($all_employee_data as $employee_data_index => $employee_data_row) {

                    $temp_array = array();
                    if ($employee_data_row['internal_employee_id'] ==  $punching_data_row['Empcode']) {
                        $temp_array['internal_employee_id']    = $punching_data_row['Empcode'];
                        $temp_array['employee_name']           = $employee_data_row['employee_name'];
                        $temp_array['company_short_name']      = $employee_data_row['company_short_name'];
                        $temp_array['department_name']         = $employee_data_row['department_name'];
                        $temp_array['day']                     = $punching_data_row['day'];
                        $temp_array['date_time']               = $punching_data_row['date_time'];
                        $temp_array['DateString']              = $punching_data_row['DateString'];
                        $temp_array['date_time_ordering']      = $punching_data_row['date_time_ordering'];

                        $temp_array['shift_code']              = $employee_data_row['shift_code'];
                        // $shift = $employee_data_row[$punching_data_row['day']];

                        #add shift times to temp array 
                        if (isset($employee_data_row[$punching_data_row['day']]) && !empty($employee_data_row[$punching_data_row['day']])) {
                            $shift = explode(",", $employee_data_row[$punching_data_row['day']]);
                            $temp_array['shift_start']  = date('h:i A', strtotime($shift[0]));
                            $temp_array['shift_end']  = date('h:i A', strtotime($shift[1]));
                            $week_off = 'false';
                        } else {
                            $shift = array('', '');
                            $temp_array['shift_start']  = '';
                            $temp_array['shift_end']  = '';
                            $week_off = 'true';
                        }
                        $temp_array['shift'] = $shift;
                        $temp_array['is_weekOff'] = $week_off;
                        #add shift times to temp array

                        #add status in temp array
                        if ($punching_data_row['INTime'] !== '--:--') {
                            $temp_array['status'] = 'P';
                        } else {
                            #check for approved leave
                            $LeaveRequestsModel = new LeaveRequestsModel();
                            $CheckLeaveRequestRow = $LeaveRequestsModel
                                ->where('leave_requests.employee_id =', $employee_data_row['employee_id'])
                                ->where('leave_requests.status =', 'approved')
                                ->where(
                                    '( 
                                        (leave_requests.from_date between "' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '" and "' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '") 
                                        or (leave_requests.to_date between "' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '" and "' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '") 
                                        or ("' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '" between leave_requests.from_date and leave_requests.to_date ) 
                                        or ("' . date('Y-m-d', strtotime($punching_data_row['DateString'])) . '" between leave_requests.from_date and leave_requests.to_date )
                                    )'
                                )
                                ->first();

                            $HolidayModel = new HolidayModel();
                            $checkHoliday = $HolidayModel->where('holiday_date =', date('Y-m-d', strtotime($punching_data_row['DateString'])))->first();

                            $get_sandwitch_data = json_decode(get_punching_data($employee_data_row['internal_employee_id'], date('Y-m-d', strtotime("-1 day", strtotime($punching_data_row['date_time']))), date('Y-m-d', strtotime("+1 day", strtotime($punching_data_row['date_time'])))), true)['InOutPunchData'];

                            if (!empty($checkHoliday)) {
                                $temp_array['status'] = $checkHoliday['holiday_code'];
                            } elseif (!empty($CheckLeaveRequestRow)) {
                                $temp_array['status'] = '<strong>' . $CheckLeaveRequestRow['type_of_leave'] . '*</strong>';
                            } elseif ($get_sandwitch_data[0]['INTime'] == '--:--' && ($get_sandwitch_data[2]['INTime'] == '--:--' || !isset($get_sandwitch_data[2]['INTime']))) {
                                $temp_array['status'] = 'S/W';
                            } elseif ($week_off == 'true') {
                                $temp_array['status'] = 'W/O';
                            } else {
                                $temp_array['status'] = 'A';
                            }
                        }
                        #add status in temp array

                        #add in_time in temp array
                        if ($punching_data_row['INTime'] !== '--:--') {
                            $temp_array['in_time']             = date('h:i A', strtotime($punching_data_row['INTime']));
                        } else {
                            $temp_array['in_time']             = '';
                        }
                        #add in_time in temp array

                        #add late_minutes in temp array
                        if ($punching_data_row['INTime'] !== '--:--' && $shift[0] !== '') {
                            $shift_start    = date_create(date('H:i', strtotime($shift[0])));
                            $in_time        = date_create($punching_data_row['INTime']);
                            $latediff       = $shift_start->diff($in_time);
                            $late_minutes   = (int)$latediff->format('%r%i');
                            $late_hours     = (int)$latediff->format('%r%h');
                            $late_minutes   = $late_minutes + ($late_hours * 60);
                            if ($late_minutes > 0) {
                                $temp_array['late_minutes']        = $late_minutes;
                            } else {
                                $temp_array['late_minutes']        = '';
                            }
                        } else {
                            $temp_array['late_minutes']        = '';
                        }
                        #add late_minutes in temp array

                        #add out_time in temp array
                        if ($punching_data_row['OUTTime'] !== '--:--') {
                            $temp_array['out_time']             = date('h:i A', strtotime($punching_data_row['OUTTime']));
                        } else {
                            $temp_array['out_time']             = '';
                        }
                        #add out_time in temp array

                        #add early_going_minutes in temp array
                        if ($punching_data_row['OUTTime'] !== '--:--' && $shift[1] !== '') {
                            $shift_end              = date_create(date('H:i', strtotime($shift[1])));
                            $out_time               = date_create($punching_data_row['OUTTime']);
                            $earlyleave               = $out_time->diff($shift_end);
                            $early_going_minutes    = (int)$earlyleave->format('%r%i');
                            $early_going_hours      = (int)$earlyleave->format('%r%h');
                            $early_going_minutes    = $early_going_minutes + ($early_going_hours * 60);
                            if ($early_going_minutes > 0) {
                                $temp_array['early_going_minutes']        = $early_going_minutes;
                            } else {
                                $temp_array['early_going_minutes']        = '';
                            }
                        } else {
                            $temp_array['early_going_minutes'] = '';
                        }
                        #add early_going_minutes in temp array

                    }

                    if (!empty($temp_array)) {
                        $punchingData[] = $temp_array;
                    }
                }
            }
        }

        $punching_data_sorted = orderResultSet($punchingData, 'date_time_ordering', FALSE);

        echo json_encode($punching_data_sorted);
    }
}
