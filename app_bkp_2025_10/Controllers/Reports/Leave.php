<?php

namespace App\Controllers\Reports;

use App\Models\CustomModel;
use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;

class Leave extends BaseController
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
            'page_title'        => 'Leave Report',
            'current_controller'    => $this->request->getUri()->getSegment(2),
            'current_method'        => $this->request->getUri()->getSegment(3),
            'Companies'             =>  $Companies,
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
            where e.id is not null " . $where_department . " 
            order by c.company_short_name ASC, d.department_name ASC, e.first_name ASC";
        $CustomModel = new CustomModel();
        $query = $CustomModel->CustomQuery($sql);
        if (!$query) {
            $data['employees_not_found'] = "There was an error fetching employees from database";
        } else {
            $data['Employees'] = $query->getResultArray();
        }

        $getStatusSql = "select distinct status from leave_requests order by status ASC";
        $CustomModel = new CustomModel();
        $getStatusQuery = $CustomModel->CustomQuery($getStatusSql);
        if ($getStatusQuery) {
            $getStatus = $getStatusQuery->getResultArray();
            $data['statuses'] = $getStatus;
        }
        return view('Reports/LeaveReport', $data);
    }

    public function getLeaveReports()
    {
        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);

        $company_id     = isset($params['company']) ? $params['company'] : "";
        $department_id  = isset($params['department']) ? $params['department'] : "";
        $employee_id    = isset($params['employee']) ? $params['employee'] : "";
        $leave_status   = isset($params['status']) ? $params['status'] : "";

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

        if (isset($leave_status) && !empty($leave_status) && !in_array('all_status', $leave_status) && !in_array('', $leave_status)) {
            $leave_status_imploded = "'" . implode("', '", $leave_status) . "'";
            $leave_status_condition = " and lr.status in (" . $leave_status_imploded . ") ";
        } else {
            $leave_status_condition = " ";
        }

        $from_date = $params['from_date'];
        $to_date = $params['to_date'];

        $date_45_days_before = date('Y-m-d', strtotime('-45 days'));

        $CustomModel = new CustomModel();
        $CustomSql = "select 
        trim( concat( e.first_name, ' ', e.last_name ) ) as employee_name, 
        e.internal_employee_id as internal_employee_id, 
        d.department_name as department_name, 
        c.company_short_name as company_short_name, 
        lr.*, 
        trim( concat( e1.first_name, ' ', e1.last_name ) ) as reviewed_by_name
        from leave_requests lr 
        left join employees e on e.id = lr.employee_id
        left join departments d on d.id = e.department_id 
        left join companies c on c.id = e.company_id 
        left join employees e1 on e1.id = lr.reviewed_by
        where 
        ( 
            (lr.from_date between '" . $from_date . "' and '" . $to_date . "') 
            or (lr.to_date between '" . $from_date . "' and '" . $to_date . "') 
            or ('" . $from_date . "' between lr.from_date and lr.to_date) 
            or ('" . $to_date . "' between lr.from_date and lr.to_date) 
        ) 
        and 
        ( 
            e.date_of_leaving is null 
            or 
            e.date_of_leaving >= '" . $date_45_days_before . "'
        ) 
        " . $company_condition . $department_condition . $employee_condition . $leave_status_condition . " order by c.company_short_name ASC, e.first_name ASC";

        /*echo '<pre>';
        print_r($CustomSql);
        die();*/

        $all_employee_query = $CustomModel->CustomQuery($CustomSql);
        $all_employee_data = array();
        if ($all_employee_query) {

            $all_employee_data = $CustomModel->CustomQuery($CustomSql)->getResultArray();
            if (!empty($all_employee_data)) {
                foreach ($all_employee_data as $row_index => $data_row) {
                    /*if( isset($data_row['from_date']) && !empty($data_row['from_date']) && isset($data_row['to_date']) && !empty($data_row['to_date'])){
                        $from_date = date_create($data_row['from_date']);
                        $to_date = date_create($data_row['to_date']);
                        $interval = date_diff($from_date, $to_date);
                        $all_employee_data[$row_index]['number_of_days'] = (int)$interval->format('%d')+1;
                    }*/

                    $from_date_formatted = (!empty($data_row['from_date']) && $data_row['from_date'] !== '0000-00-00') ? date('d M Y', strtotime($data_row['from_date'])) : '-';
                    $from_date_ordering = (!empty($data_row['from_date']) && $data_row['from_date'] !== '0000-00-00') ? strtotime($data_row['from_date']) : '0';
                    $all_employee_data[$row_index]['from_date'] = array('formatted' => $from_date_formatted, 'ordering' => $from_date_ordering);

                    $to_date_formatted = (!empty($data_row['to_date']) && $data_row['to_date'] !== '0000-00-00') ? date('d M Y', strtotime($data_row['to_date'])) : '-';
                    $to_date_ordering = (!empty($data_row['to_date']) && $data_row['to_date'] !== '0000-00-00') ? strtotime($data_row['to_date']) : '0';
                    $all_employee_data[$row_index]['to_date'] = array('formatted' => $to_date_formatted, 'ordering' => $to_date_ordering);

                    $reviewed_date_formatted = (!empty($data_row['reviewed_date']) && $data_row['reviewed_date'] !== '0000-00-00') ? date('d M Y', strtotime($data_row['reviewed_date'])) : '-';
                    $reviewed_date_ordering = (!empty($data_row['reviewed_date']) && $data_row['reviewed_date'] !== '0000-00-00') ? strtotime($data_row['reviewed_date']) : '0';
                    $all_employee_data[$row_index]['reviewed_date'] = array('formatted' => $reviewed_date_formatted, 'ordering' => $reviewed_date_ordering);

                    $date_time_formatted = (!empty($data_row['date_time']) && $data_row['date_time'] !== '0000-00-00 00:00:00') ? date('d M Y', strtotime($data_row['date_time'])) : '-';
                    $date_time_ordering = (!empty($data_row['date_time']) && $data_row['date_time'] !== '0000-00-00 00:00:00') ? strtotime($data_row['date_time']) : '0';
                    $all_employee_data[$row_index]['date_time'] = array('formatted' => $date_time_formatted, 'ordering' => $date_time_ordering);

                    $all_employee_data[$row_index]['reviewed_by_name'] = !empty($data_row['reviewed_by_name']) ? $data_row['reviewed_by_name'] : '-';
                }
            }
        }
        echo json_encode($all_employee_data);
    }
}
