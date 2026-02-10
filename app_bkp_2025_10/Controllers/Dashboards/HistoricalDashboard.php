<?php

namespace App\Controllers\Dashboards;

use App\Models\CompanyModel;
use App\Models\EmployeeModel;
use App\Controllers\BaseController;
use App\Controllers\Attendance\Processor;
use App\Models\RawPunchingDataModel;

class HistoricalDashboard extends BaseController
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
        $current_employee_id = $this->session->get('current_user')['employee_id'];

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'Dashboard - Historical',
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'Companies'         =>  $Companies,
            'company_id_for_filter' =>  $current_company['company_id'],
        ];
        return view('Dashboards/HistoricalDashboard', $data);
    }

    public function getMissPunchDashboard()
    {
        $current_user = $this->session->get('current_user');
        $current_employee_id = $this->session->get('current_user')['employee_id'];

        $CompanyModel = new CompanyModel();
        $Companies = $CompanyModel->findAll();

        $EmployeeModel = new EmployeeModel();
        $current_user = $this->session->get('current_user');

        $current_company = $EmployeeModel->select('company_id')->where('id =', $current_user['employee_id'])->first();

        $data = [
            'page_title'        => 'Dashboard - Miss Punch',
            'current_controller'    => $this->request->getUri()->getSegment(1),
            'Companies'         =>  $Companies,
            'company_id_for_filter' =>  $current_company['company_id'],
        ];
        return view('Dashboards/MissPunchDashboard', $data);
    }

    public function getLateEarlyLateGoingReport()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $reporting_to_me   = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $EmployeeData = [];
        $current_user = $this->session->get('current_user');
        $current_user_employee_id   = $current_user['employee_id'];
        $current_user_role          = $current_user['role'];

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.id as employee_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
            ->join('departments', 'departments.id = employees.department_id', 'left');

        if ($reporting_to_me == 'yes') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $EmployeeModel->where('employees.reporting_manager_id !=', $current_user['employee_id']);
            $EmployeeModel->groupStart();
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        }
        $EmployeeModel->orWhere('employees.id =', $current_user['employee_id']);

        $AllEmployees = $EmployeeModel->findAll();

        if (!empty($AllEmployees)) {
            foreach ($AllEmployees as $employee_row) {
                $DataRow = [];
                $DataRow['employee_name'] = $employee_row['employee_name'];
                $DataRow['internal_employee_id'] = $employee_row['internal_employee_id'];

                $data_30_days = Processor::getProcessedDashboardData($employee_row['employee_id'], date('Y-m-d', strtotime('-30 days')), date('Y-m-d'));

                $first_date_of_month = date('Y-m-01');
                $last_7_days_date = date('Y-m-d', strtotime(' -6 days'));
                $last_15_days_date = date('Y-m-d', strtotime(' -14 days'));

                $late_in_mtd_total = 0;
                $late_in_mtd_avg = 0;
                $late_in_7d_total = 0;
                $late_in_7d_avg = 0;
                $late_in_15d_total = 0;
                $late_in_15d_avg = 0;

                $early_out_mtd_total = 0;
                $early_out_mtd_avg = 0;
                $early_out_7d_total = 0;
                $early_out_7d_avg = 0;
                $early_out_15d_total = 0;
                $early_out_15d_avg = 0;

                $late_out_mtd_total = 0;
                $late_out_mtd_avg = 0;
                $late_out_7d_total = 0;
                $late_out_7d_avg = 0;
                $late_out_15d_total = 0;
                $late_out_15d_avg = 0;

                if (!empty($data_30_days)) {

                    foreach ($data_30_days as $data_30_days_row) {
                        if (strtotime($data_30_days_row['date']) >= strtotime($first_date_of_month)) {
                            $late_in_mtd_total += $data_30_days_row['late_coming_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_7_days_date)) {
                            $late_in_7d_total += $data_30_days_row['late_coming_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_15_days_date)) {
                            $late_in_15d_total += $data_30_days_row['late_coming_minutes'];
                        }
                    }
                    foreach ($data_30_days as $data_30_days_row) {
                        if (strtotime($data_30_days_row['date']) >= strtotime($first_date_of_month)) {
                            $early_out_mtd_total += $data_30_days_row['early_going_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_7_days_date)) {
                            $early_out_7d_total += $data_30_days_row['early_going_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_15_days_date)) {
                            $early_out_15d_total += $data_30_days_row['early_going_minutes'];
                        }
                    }
                    foreach ($data_30_days as $data_30_days_row) {
                        if (strtotime($data_30_days_row['date']) >= strtotime($first_date_of_month)) {
                            $late_out_mtd_total += $data_30_days_row['extra_working_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_7_days_date)) {
                            $late_out_7d_total += $data_30_days_row['extra_working_minutes'];
                        }
                        if (strtotime($data_30_days_row['date']) >= strtotime($last_15_days_date)) {
                            $late_out_15d_total += $data_30_days_row['extra_working_minutes'];
                        }
                    }
                }

                $late_in_mtd_avg = round($late_in_mtd_total / date('d'));
                $late_in_7d_avg = round($late_in_7d_total / 7);
                $late_in_15d_avg = round($late_in_15d_total / 15);

                $early_out_mtd_avg = round($early_out_mtd_total / date('d'));
                $early_out_7d_avg = round($early_out_7d_total / 7);
                $early_out_15d_avg = round($early_out_15d_total / 15);

                $late_out_mtd_avg = round($late_out_mtd_total / date('d'));
                $late_out_7d_avg = round($late_out_7d_total / 7);
                $late_out_15d_avg = round($late_out_15d_total / 15);

                $DataRow['late_in_mtd_total']  = $late_in_mtd_total;
                $DataRow['late_in_mtd_avg']    = $late_in_mtd_avg;
                $DataRow['late_in_7d_total']   = $late_in_7d_total;
                $DataRow['late_in_7d_avg']     = $late_in_7d_avg;
                $DataRow['late_in_15d_total']  = $late_in_15d_total;
                $DataRow['late_in_15d_avg']    = $late_in_15d_avg;

                $DataRow['early_out_mtd_total']  = $early_out_mtd_total;
                $DataRow['early_out_mtd_avg']    = $early_out_mtd_avg;
                $DataRow['early_out_7d_total']   = $early_out_7d_total;
                $DataRow['early_out_7d_avg']     = $early_out_7d_avg;
                $DataRow['early_out_15d_total']  = $early_out_15d_total;
                $DataRow['early_out_15d_avg']    = $early_out_15d_avg;

                $DataRow['late_out_mtd_total']  = $late_out_mtd_total;
                $DataRow['late_out_mtd_avg']    = $late_out_mtd_avg;
                $DataRow['late_out_7d_total']   = $late_out_7d_total;
                $DataRow['late_out_7d_avg']     = $late_out_7d_avg;
                $DataRow['late_out_15d_total']  = $late_out_15d_total;
                $DataRow['late_out_15d_avg']    = $late_out_15d_avg;

                $EmployeeData[] = $DataRow;
            }
        }

        return $this->response->setJSON($EmployeeData);
    }

    

    public function getAbsentReport()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $reporting_to_me   = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $EmployeeData = [];
        $current_user = $this->session->get('current_user');
        $current_user_employee_id   = $current_user['employee_id'];
        $current_user_role          = $current_user['role'];

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel
            ->select('employees.id as employee_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
            ->join('departments', 'departments.id = employees.department_id', 'left');

        if ($reporting_to_me == 'yes') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $EmployeeModel->where('employees.reporting_manager_id !=', $current_user['employee_id']);
            $EmployeeModel->groupStart();
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        }
        $EmployeeModel->orWhere('employees.id =', $current_user['employee_id']);

        #$EmployeeModel->where('employees.id =', 40);

        $AllEmployees = $EmployeeModel->findAll();

        $first_date_of_month = date('Y-m-01');
        $current_date_of_month = date('Y-m-d');
        $first_date_of_last_month = date('Y-m-01', strtotime($first_date_of_month . ' -1 days'));
        $last_date_of_last_month = date('Y-m-t', strtotime($first_date_of_last_month));
        $first_date_of_last_3month = date('Y-m-d', strtotime($first_date_of_last_month . ' -2 months'));
        $first_date_of_last_6month = date('Y-m-d', strtotime($first_date_of_last_month . ' -5 months'));

        if (!empty($AllEmployees)) {
            foreach ($AllEmployees as $employee_row) {
                $DataRow = [];
                $DataRow['employee_name'] = $employee_row['employee_name'];
                $DataRow['internal_employee_id'] = $employee_row['internal_employee_id'];

                $data_all_days = Processor::getProcessedDashboardData($employee_row['employee_id'], $first_date_of_last_6month, $current_date_of_month);


                if (!empty($data_all_days)) {
                    foreach ($data_all_days as $i => $data_all_day_row) {
                        if (
                            $data_all_day_row['is_sandwitch'] == 'yes'
                        ) {
                            $data_all_days[$i]['absent'] = 'yes';
                        } elseif (
                            $data_all_day_row['is_weekoff'] == 'no'
                            && $data_all_day_row['is_holiday'] == 'no'
                            && $data_all_day_row['is_fixed_off'] == 'no'
                            && $data_all_day_row['is_onLeave'] == 'no'
                            && $data_all_day_row['is_onOD'] == 'no'
                            && $data_all_day_row['is_missed_punch'] == 'no'
                            && $data_all_day_row['is_present'] == 'no'
                            && $data_all_day_row['date'] !=  $first_date_of_month
                        ) {
                            $data_all_days[$i]['absent'] = 'yes';
                        } else {
                            $data_all_days[$i]['absent'] = 'no';
                        }
                    }
                }

                $absent_mtd = 0;
                $absent_last_month = 0;
                $absent_last_3month = 0;
                $absent_last_6month = 0;

                if (!empty($data_all_days)) {

                    foreach ($data_all_days as $data_all_days_row) {
                        if (strtotime($data_all_days_row['date']) >= strtotime($first_date_of_month) && strtotime($data_all_days_row['date']) <= strtotime($current_date_of_month)) {
                            if ($data_all_days_row['absent'] == 'yes') {
                                $absent_mtd += 1;
                            }
                        }
                        if (strtotime($data_all_days_row['date']) >= strtotime($first_date_of_last_month) && strtotime($data_all_days_row['date']) <= strtotime($last_date_of_last_month)) {
                            if ($data_all_days_row['absent'] == 'yes') {
                                $absent_last_month += 1;
                            }
                        }
                        if (strtotime($data_all_days_row['date']) >= strtotime($first_date_of_last_3month) && strtotime($data_all_days_row['date']) <= strtotime($last_date_of_last_month)) {
                            if ($data_all_days_row['absent'] == 'yes') {
                                $absent_last_3month += 1;
                            }
                        }
                        if (strtotime($data_all_days_row['date']) >= strtotime($first_date_of_last_6month) && strtotime($data_all_days_row['date']) <= strtotime($last_date_of_last_month)) {
                            if ($data_all_days_row['absent'] == 'yes') {
                                $absent_last_6month += 1;
                            }
                        }
                    }
                }

                $DataRow['absent_mtd']  = $absent_mtd;
                $DataRow['absent_last_month']  = $absent_last_month;
                $DataRow['absent_last_3month']  = $absent_last_3month;
                $DataRow['absent_last_6month']  = $absent_last_6month;

                $EmployeeData[] = $DataRow;
            }
        }

        return $this->response->setJSON($EmployeeData);
    }


    public function getGGNData()
    {
        $employee_code = 'ALL';
        $dateFrom = '2022-12-20';
        $dateTo = '2022-12-21';
        $get_punching_data_ggn = get_punching_data_ggn($employee_code, $dateFrom, $dateTo);
        echo '<pre>';
        print_r($get_punching_data_ggn);
        // print_r('dgfhdgf dfgh dgfh dgfh dfgh');
        echo '</pre>';
    }

    public function getMissingPunchingReport()
    {

        $filter = $this->request->getPost('filter');
        parse_str($filter, $params);
        $reporting_to_me   = isset($params['reporting_to_me']) ? $params['reporting_to_me'] : 'no rule';

        $EmployeeData = [];
        $current_user = $this->session->get('current_user');

        $current_user_employee_id   = $current_user['employee_id'];
        $current_user_role          = $current_user['role'];

        $first_date_of_month = date('Y-m-01');
        $current_date_of_month = date('Y-m-d');
        $last_7_days_date = date('Y-m-d', strtotime(' -6 days'));
        $last_15_days_date = date('Y-m-d', strtotime(' -14 days'));



        $EmployeeModel = new EmployeeModel();

        $EmployeeModel
            ->select('employees.id as id')
            ->select('employees.id as employee_id')
            ->select('employees.internal_employee_id as internal_employee_id')
            ->select('trim(concat(employees.first_name, " ", employees.last_name)) as employee_name')
            ->select('s.id as shift_id')
            ->select('s.shift_name as shift_name')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "monday" and shift_id = employees.shift_id) as Monday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "tuesday" and shift_id = employees.shift_id) as Tuesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "wednesday" and shift_id = employees.shift_id) as Wednesday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "thursday" and shift_id = employees.shift_id) as Thursday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "friday" and shift_id = employees.shift_id) as Friday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "saturday" and shift_id = employees.shift_id) as Saturday')
            ->select('(select concat(shift_start, ",",shift_end) from shift_per_day where day = "sunday" and shift_id = employees.shift_id) as Sunday')
            ->join('shifts as s', 's.id = employees.shift_id', 'left')
            ->join('shift_per_day as spd', 'spd.id = s.id', 'left')
            ->join('departments', 'departments.id = employees.department_id', 'left');

        if ($reporting_to_me == 'yes') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no') {
            $EmployeeModel->where('employees.reporting_manager_id !=', $current_user['employee_id']);
            $EmployeeModel->groupStart();
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        } elseif ($reporting_to_me == 'no rule') {
            $EmployeeModel->groupStart();
            $EmployeeModel->where('employees.reporting_manager_id =', $current_user['employee_id']);
            $EmployeeModel->orWhere('departments.hod_employee_id =', $current_user['employee_id']);
            $EmployeeModel->orWhereIn("'" . $current_user_role . "'", ['admin', 'superuser', 'hr']);
            $EmployeeModel->groupEnd();
        }


        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('" . $first_date_of_month . "')");
        $EmployeeModel->groupEnd();

        // $EmployeeModel->orWhere('employees.id =', $current_user['employee_id']);

        // $EmployeeModel->where('employees.id =', 40);

        // $EmployeeModel->limit(10);

        $AllEmployees = $EmployeeModel->get()->getResultArray();

        // $AllEmployees = $EmployeeModel->findAll();

        // dd($AllEmployees);

        if (!empty($AllEmployees)) {
            $empCodes = array_column($AllEmployees, 'internal_employee_id');
            
            $RawPunchingDataModel = new RawPunchingDataModel();
            $RawPunchingDataModel->whereIn('Empcode', $empCodes);            
            $RawPunchingDataModel->where('(DateString_2 between "' . $first_date_of_month . '" and "' . $current_date_of_month . '")');
            $RawPunchingDataModel->orderBy('DateString_2', 'DESC');
            $RawPunchingDataAll = $RawPunchingDataModel->findAll();


            foreach ($AllEmployees as $employee_row) {
                $DataRow = [];
                $DataRow['employee_name'] = $employee_row['employee_name'];
                $DataRow['internal_employee_id'] = $employee_row['internal_employee_id'];

                $internal_employee_id = $employee_row['internal_employee_id'];
                

                // $data_all_days = Processor::getProcessedDashboardDataNew($employee_row, $first_date_of_month, $current_date_of_month);
                $RawPunchingData = array_filter($RawPunchingDataAll, function($row) use ($internal_employee_id) {
                    return $row['Empcode'] == $internal_employee_id;
                });

                $data_all_days = Processor::getProcessedDashboardDataNew($employee_row, $RawPunchingData);

                // dd($data_all_days);


                // $data_all_days = $RawPunchingData;
                $missed_punch_mtd = 0;
                $missed_punch_last_7day = 0;
                $missed_punch_last_15day = 0;
                
                if (!empty($data_all_days)) {

                    $missedMtd = array_filter($data_all_days, function($row) {
                        return isset($row['is_missed_punch']) && $row['is_missed_punch'] === 'yes';
                    });
                    
                    // $missed_punch_mtd = isset($missedMtd) && !empty($missedMtd) ? count($missedMtd) : $missed_punch_mtd;
                    
                    $missedMtd = array_values(array_filter($data_all_days, function($row) use ($last_7_days_date, $current_date_of_month) {
                        return isset($row['is_missed_punch'], $row['date'])
                            && $row['is_missed_punch'] === 'yes'
                            && $row['date'] < $current_date_of_month;
                    }));
                    $missed_punch_mtd = isset($missedMtd) && !empty($missedMtd) ? count($missedMtd) : $missed_punch_mtd;

                    $missedLast7 = array_values(array_filter($data_all_days, function($row) use ($last_7_days_date, $current_date_of_month) {
                        return isset($row['is_missed_punch'], $row['date'])
                            && $row['is_missed_punch'] === 'yes'
                            && $row['date'] >= $last_7_days_date
                            && $row['date'] < $current_date_of_month;
                    }));
                    $missed_punch_last_7day = isset($missedLast7) && !empty($missedLast7) ? count($missedLast7) : $missed_punch_last_7day;

                    $missedLast15 = array_values(array_filter($data_all_days, function($row) use ($last_15_days_date, $current_date_of_month) {
                        return isset($row['is_missed_punch'], $row['date'])
                            && $row['is_missed_punch'] === 'yes'
                            && $row['date'] >= $last_15_days_date
                            && $row['date'] < $current_date_of_month;
                    }));

                    $missed_punch_last_15day = isset($missedLast15) && !empty($missedLast15) ? count($missedLast15) : $missed_punch_last_15day;
                }

                $DataRow['missed_punch_mtd']  = $missed_punch_mtd;
                $DataRow['missed_punch_last_7day']  = $missed_punch_last_7day;
                $DataRow['missed_punch_last_15day']  = $missed_punch_last_15day;

                $EmployeeData[] = $DataRow;
            }
        }

        return $this->response->setJSON($EmployeeData);
    }
}
