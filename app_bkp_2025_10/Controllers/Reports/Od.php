<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;

class Od extends BaseController
{
    public $session;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function index()
    {

        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr', 'hod', 'tl', 'manager'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'OD Report',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'Companies'         =>  $Companies,
            'company_id_for_menu_url' =>  $current_company['company_id'],
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
            and 
            ( 
                e.date_of_leaving is null 
                or 
                e.date_of_leaving >= '" . $date_45_days_before . "'
            ) 
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

        $getStatusSql = "select distinct status from od_requests order by status ASC";
        $CustomModel = new CustomModel();
        $getStatusQuery = $CustomModel->CustomQuery($getStatusSql);
        if ($getStatusQuery) {
            $getStatus = $getStatusQuery->getResultArray();
            $data['statuses'] = $getStatus;
        }
        return view('Reports/OdReport', $data);
    }

    public function getOdReports()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $od_status      = isset($params['status']) ? $params['status'] : "";

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

        if (isset($od_status) && !empty($od_status) && !in_array('all_status', $od_status) && !in_array('', $od_status)) {
            $od_status_imploded = "'" . implode("', '", $od_status) . "'";
            $od_status_condition = " and od.status in (" . $od_status_imploded . ") ";
        } else {
            $od_status_condition = " ";
        }

        $from_date = $params['from_date'];
        $to_date = $params['to_date'];


        $CustomModel = new CustomModel();

        $CustomSql = "select 
        od.*, 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name, 
        trim( concat( e1.first_name, ' ', e1.last_name ) ) as reviewed_by_name, 
        trim( concat( e2.first_name, ' ', e2.last_name ) ) as assigned_by
        from od_requests od 
        left join employees e on e.id = od.employee_id
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        left join employees e1 on e1.id = od.reviewed_by
        left join employees e2 on e2.id = od.duty_assigner
        where   ( 
                    ( date(od.estimated_from_date_time) between '" . $from_date . "' and '" . $to_date . "') 
                    or ( date(od.estimated_to_date_time) between '" . $from_date . "' and '" . $to_date . "') 
                    or ( '" . $from_date . "' between date(od.estimated_from_date_time) and date(od.estimated_to_date_time) ) 
                    or ( '" . $to_date . "' between date(od.estimated_from_date_time) and date(od.estimated_to_date_time) ) 
                    or ( date(od.actual_from_date_time) between '" . $from_date . "' and '" . $to_date . "') 
                    or ( date(od.actual_to_date_time) between '" . $from_date . "' and '" . $to_date . "') 
                    or ( '" . $from_date . "' between date(od.actual_from_date_time) and date(od.actual_to_date_time) ) 
                    or ( '" . $to_date . "' between date(od.actual_from_date_time) and date(od.actual_to_date_time) ) 
                )
        " . $company_condition . $department_condition . $employee_condition . $od_status_condition . " order by c.company_short_name ASC, e.first_name ASC";

        $all_employee_query = $CustomModel->CustomQuery($CustomSql);
        $all_employee_data = array();
        if ($all_employee_query) {

            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();
            if (!empty($all_employee_data)) {
                foreach ($all_employee_data as $row_index => $data_row) {
                    $all_employee_data[$row_index]['reviewed_by_name'] = !empty($data_row['reviewed_by_name']) ? $data_row['reviewed_by_name'] : '-';

                    if (isset($data_row['actual_from_date_time']) && !empty($data_row['actual_from_date_time']) && isset($data_row['actual_to_date_time']) && !empty($data_row['actual_to_date_time'])) {
                        $actual_from_date_time = date_create($data_row['actual_from_date_time']);
                        $actual_to_date_time = date_create($data_row['actual_to_date_time']);
                        $interval = date_diff($actual_from_date_time, $actual_to_date_time);
                    } elseif (isset($data_row['estimated_from_date_time']) && !empty($data_row['estimated_from_date_time']) && isset($data_row['estimated_to_date_time']) && !empty($data_row['estimated_to_date_time'])) {
                        $estimated_from_date_time = date_create($data_row['estimated_from_date_time']);
                        $estimated_to_date_time = date_create($data_row['estimated_to_date_time']);
                        $interval = date_diff($estimated_from_date_time, $estimated_to_date_time);
                    }
                    $hours = 0;
                    $hours += (int)$interval->format('%d') * 24;
                    $hours += (int)$interval->format('%h');
                    $minutes = 0;
                    $minutes += (int)$interval->format('%i');
                    $minutes += round((int)$interval->format('%s') / 60);
                    $all_employee_data[$row_index]['interval'] = json_encode($interval);
                    $all_employee_data[$row_index]['interval'] = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);

                    if (isset($data_row['estimated_from_date_time']) && !empty($data_row['estimated_from_date_time']) && isset($data_row['date_time']) && !empty($data_row['date_time'])) {
                        if (strtotime($data_row['estimated_from_date_time']) >= strtotime($data_row['date_time'])) {
                            $all_employee_data[$row_index]['pre_post'] = 'Pre';
                        } else {
                            $all_employee_data[$row_index]['pre_post'] = 'Post';
                        }
                    }

                    if ($data_row['duty_assigner'] == $data_row['employee_id']) {
                        $all_employee_data[$row_index]['assigned_by'] = 'Self';
                    }

                    $estimated_from_date_time_formatted = !empty($data_row['estimated_from_date_time']) ? date('d M Y h:i A', strtotime($data_row['estimated_from_date_time'])) : '-';
                    $estimated_from_date_time_ordering = !empty($data_row['estimated_from_date_time']) ? strtotime($data_row['estimated_from_date_time']) : '0';
                    $all_employee_data[$row_index]['estimated_from_date_time'] = array('formatted' => $estimated_from_date_time_formatted, 'ordering' => $estimated_from_date_time_ordering);

                    $estimated_to_date_time_formatted = !empty($data_row['estimated_to_date_time']) ? date('d M Y h:i A', strtotime($data_row['estimated_to_date_time'])) : '-';
                    $estimated_to_date_time_ordering = !empty($data_row['estimated_to_date_time']) ? strtotime($data_row['estimated_to_date_time']) : '0';
                    $all_employee_data[$row_index]['estimated_to_date_time'] = array('formatted' => $estimated_to_date_time_formatted, 'ordering' => $estimated_to_date_time_ordering);

                    $actual_from_date_time_formatted = !empty($data_row['actual_from_date_time']) ? date('d M Y h:i A', strtotime($data_row['actual_from_date_time'])) : '-';
                    $actual_from_date_time_ordering = !empty($data_row['actual_from_date_time']) ? strtotime($data_row['actual_from_date_time']) : '0';
                    $all_employee_data[$row_index]['actual_from_date_time'] = array('formatted' => $actual_from_date_time_formatted, 'ordering' => $actual_from_date_time_ordering);

                    $actual_to_date_time_formatted = !empty($data_row['actual_to_date_time']) ? date('d M Y h:i A', strtotime($data_row['actual_to_date_time'])) : '-';
                    $actual_to_date_time_ordering = !empty($data_row['actual_to_date_time']) ? strtotime($data_row['actual_to_date_time']) : '0';
                    $all_employee_data[$row_index]['actual_to_date_time'] = array('formatted' => $actual_to_date_time_formatted, 'ordering' => $actual_to_date_time_ordering);

                    $reviewed_date_time_formatted = !empty($data_row['reviewed_date_time']) ? date('d M Y', strtotime($data_row['reviewed_date_time'])) : '-';
                    $reviewed_date_time_ordering = !empty($data_row['reviewed_date_time']) ? strtotime($data_row['reviewed_date_time']) : '0';
                    $all_employee_data[$row_index]['reviewed_date_time'] = array('formatted' => $reviewed_date_time_formatted, 'ordering' => $reviewed_date_time_ordering);

                    $date_time_formatted = !empty($data_row['date_time']) ? date('d M Y', strtotime($data_row['date_time'])) : '-';
                    $date_time_ordering = !empty($data_row['date_time']) ? strtotime($data_row['date_time']) : '0';
                    $all_employee_data[$row_index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                    $updated_date_time_formatted = !empty($data_row['updated_date_time']) ? date('d M Y', strtotime($data_row['updated_date_time'])) : '-';
                    $updated_date_time_ordering = !empty($data_row['updated_date_time']) ? strtotime($data_row['updated_date_time']) : '0';
                    $all_employee_data[$row_index]['updated_date_time'] = array('formatted' => $updated_date_time_formatted, 'ordering' => $updated_date_time_ordering);
                }
            }
        }
        echo json_encode($all_employee_data);
    }
}
