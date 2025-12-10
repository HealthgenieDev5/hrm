<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PreFinalSalaryModel;
use App\Models\PreFinalPaidDaysModel;

class AttendanceHistory extends BaseController
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
            'page_title' => isset($_REQUEST['month']) ? 'Attendance History ' . date('F Y', strtotime($_REQUEST['month'])) : 'Attendance History ' . date('F Y', strtotime(first_date_of_last_month())),
            'current_controller' => $this->request->getUri()->getSegment(2),
            'current_method' => $this->request->getUri()->getSegment(3),
            'month' => isset($_REQUEST['month']) ? date('F Y', strtotime($_REQUEST['month'])) : date('F Y', strtotime(first_date_of_last_month())),
            'employee_code' => $current_user['internal_employee_id']
        ];

        $PreFinalPaidDaysModel = new PreFinalPaidDaysModel();
        $PreFinalPaidDaysModel
            ->where('employee_id =', $this->session->get('current_user')['employee_id'])
            ->where('date >=', date('Y-m-01', strtotime($data['month'])))
            ->where('date <=', date('Y-m-t', strtotime($data['month'])))
            ->orderBy('date', 'ASC');
        $PreFinalPaidDays_Data = $PreFinalPaidDaysModel->findAll();
        $PreFinalPaidDays_Data_New = [];
        if (!empty($PreFinalPaidDays_Data)) {
            foreach ($PreFinalPaidDays_Data as $i => $v) {
                $PreFinalPaidDays_Data_New[$v['date']] = $v;
            }
        }

        $data['PreFinalPaidDays_Data'] = $PreFinalPaidDays_Data_New;

        // $SalarySlipDownloadable = strtotime($data['month']) < strtotime(first_date_of_last_month()) ? 'yes' : 'no';
        #begin::Salary disbursed or not
        $PreFinalSalaryModel = new PreFinalSalaryModel();
        $PreFinalSalaryModel->select('pre_final_salary.*');
        $PreFinalSalaryModel->where('employee_id =', $this->session->get('current_user')['employee_id']);
        $PreFinalSalaryModel->where('year =', date('Y', strtotime($data['month'])));
        $PreFinalSalaryModel->where('month =', date('m', strtotime($data['month'])));
        $FinalSalary = $PreFinalSalaryModel->first();
        if (!empty($FinalSalary) && $FinalSalary['status'] == 'disbursed') {
            $SalarySlipDownloadable = 'yes';
        } else {
            $SalarySlipDownloadable = 'no';
        }
        $data['salary_slip_downloadable'] = $SalarySlipDownloadable;


        return view('User/AttendanceHistory', $data);
    }
}
