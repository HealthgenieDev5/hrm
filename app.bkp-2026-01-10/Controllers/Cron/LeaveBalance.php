<?php

namespace App\Controllers\Cron;

use App\Models\LeaveModel;
use App\Models\LeaveBalanceModel;
use App\Models\FinalPaidDaysModel;
use App\Models\LeaveRequestsModel;
use App\Controllers\BaseController;
use App\Models\LeaveCreditHistoryModel;

class LeaveBalance extends BaseController
{
    public $session;
    public $uri;

    public function __construct()
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $this->session    = session();
    }

    public function returnLeaveAdjustment($employeeId, $date)
    {
        $FinalPaidDaysModel = new FinalPaidDaysModel();
        $oldFPD = $FinalPaidDaysModel
            ->where("employee_id = ", $employeeId)
            ->where("date = ", $date)
            ->first();

        if (!empty($oldFPD)) {

            $LeaveModel = new LeaveModel();
            $leaveRow = $LeaveModel->where("leave_code =", $oldFPD['status'])->first();
            if (!empty($leaveRow)) {
                $LeaveBalanceModel = new LeaveBalanceModel();
                $salaryMonthBalanceRow = $LeaveBalanceModel
                    ->where('leave_code =', $oldFPD['status'])
                    ->where('employee_id =', $employeeId)
                    ->where('year =', date('Y', strtotime($date)))
                    ->where('month =', date('m', strtotime($date)))
                    ->first();
                $salaryMonthBalance = $salaryMonthBalanceRow['balance'];
                $salaryMonthBalanceNew = $salaryMonthBalance + $oldFPD['paid'];
                $LeaveBalanceModel = new LeaveBalanceModel();
                $updateSalaryMonthBalanceQuery = $LeaveBalanceModel->update($salaryMonthBalanceRow['id'], ['balance' => $salaryMonthBalanceNew, 'update_date' => date('Y-m-d H:i:s')]);
                if ($updateSalaryMonthBalanceQuery) {
                    $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                    $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                        'employee_id'   => $employeeId,
                        'leave_id'      => $leaveRow['id'],
                        'leave_amount'  => $oldFPD['paid'],
                        'type'          => ($oldFPD['paid'] > 0) ? 'credit' : 'debit',
                        'remarks'       => $oldFPD['status'] . " Returned on " . $date,
                        'date_time'     => date('Y-m-d H:i:s', strtotime($date))
                    ]);
                    if ($leaveCreditHistoryQuery) {
                        if ($this->updateCurrentMonthBalance($employeeId, $oldFPD['status'], date('F Y', strtotime($date)))) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    public function updateLeaveBalance($employee_id, $leave_type, $settlement, $year_month, $settlement_remarks = '')
    {
        $LeaveModel = new LeaveModel();
        $leaveRow = $LeaveModel->where("leave_code =", $leave_type)->first();
        if (!empty($leaveRow)) {
            $LeaveBalanceModel = new LeaveBalanceModel();
            $salaryMonthBalanceRow = $LeaveBalanceModel
                ->where('leave_code=', $leave_type)
                ->where('employee_id=', $employee_id)
                ->where('year=', date('Y', (strtotime($year_month))))
                ->where('month=', date('m', (strtotime($year_month))))
                ->first();
            $salaryMonthBalance = $salaryMonthBalanceRow['balance'];
            $salaryMonthBalanceNew = $salaryMonthBalance - ($settlement);
            if ($salaryMonthBalanceNew >= 0) {
                $salaryMonthBalanceRowId = $salaryMonthBalanceRow['id'];
                $LeaveBalanceModel = new LeaveBalanceModel();
                $updateSalaryMonthBalanceQuery = $LeaveBalanceModel->update($salaryMonthBalanceRow['id'], ['balance' => $salaryMonthBalanceNew]);

                if ($updateSalaryMonthBalanceQuery) {
                    $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                    $leaveCreditHistoryQuery = $LeaveCreditHistoryModel->insert([
                        'employee_id'   => $employee_id,
                        'leave_id'      => $leaveRow['id'],
                        'leave_amount'  => abs($settlement),
                        'type'          => 'debit',
                        'remarks'       => !empty($settlement_remarks) ? $settlement_remarks : 'Settlement on paid days',
                    ]);
                    if ($leaveCreditHistoryQuery) {
                        $updateCurrentMonthBalanceQuery = $this->updateCurrentMonthBalance($employee_id, $leave_type, $year_month);
                        if ($updateCurrentMonthBalanceQuery) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
    }

    public function updateCurrentMonthBalance($employee_id, $leave_type, $year_month)
    {

        $salaryYear = date('Y', (strtotime($year_month)));
        $salaryMonth = date('m', (strtotime($year_month)));
        $year = date('Y', (strtotime($year_month . " +1 month")));
        $month = date('m', (strtotime($year_month . " +1 month")));

        $LeaveModel = new LeaveModel();
        $leaveRow = $LeaveModel->where("leave_code =", $leave_type)->first();
        if (!empty($leaveRow)) {
            $LeaveBalanceModel = new LeaveBalanceModel();
            $salaryMonthBalanceRow = $LeaveBalanceModel
                ->where('leave_code=', $leave_type)
                ->where('employee_id=', $employee_id)
                ->where('year=', date('Y', (strtotime($year_month))))
                ->where('month=', date('m', (strtotime($year_month))))
                ->first();


            if (!empty($salaryMonthBalanceRow)) {

                $salaryMonthBalanceNew = !empty($salaryMonthBalanceRow['balance']) ? $salaryMonthBalanceRow['balance'] : 0;

                if ($leaveRow['allocation'] == 'monthly') {
                    if ($salaryYear == $year) {
                        if ($leaveRow['carry_forward'] == 'yes') {
                            $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                            $system_generated_leave_credit_in_current_month = $LeaveCreditHistoryModel
                                ->where('leave_id =', $leaveRow['id'])
                                ->where('year(date_time) =', $year)
                                ->where('month(date_time) =', $month)
                                ->where('type =', 'credit')
                                ->where('remarks =', 'System generated')
                                ->first();

                            if (!empty($system_generated_leave_credit_in_current_month)) {
                                $system_generated_leave_credit_in_current_month_leave_amount = $system_generated_leave_credit_in_current_month['leave_amount'];
                            } else {
                                $system_generated_leave_credit_in_current_month_leave_amount = 0;
                            }
                            $currentMonthBalanceNew = $salaryMonthBalanceNew + $system_generated_leave_credit_in_current_month_leave_amount;
                        } else {
                            $currentMonthBalanceNew = $salaryMonthBalanceNew;
                        }
                    } else {
                        $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                        $system_generated_leave_credit_in_current_month = $LeaveCreditHistoryModel
                            ->where('leave_id =', $leaveRow['id'])
                            ->where('year(date_time) =', $year)
                            ->where('month(date_time) =', $month)
                            ->where('type =', 'credit')
                            ->where('remarks =', 'System generated')
                            ->first();

                        if (!empty($system_generated_leave_credit_in_current_month)) {
                            $system_generated_leave_credit_in_current_month_leave_amount = $system_generated_leave_credit_in_current_month['leave_amount'];
                        } else {
                            $system_generated_leave_credit_in_current_month_leave_amount = 0;
                        }
                        $currentMonthBalanceNew = $system_generated_leave_credit_in_current_month_leave_amount;
                    }

                    if ($leaveRow['carry_forward'] == 'yes' && $currentMonthBalanceNew > $leaveRow['carry_forward_threshold']) {
                        $currentMonthBalanceNew = $leaveRow['carry_forward_threshold'];
                    }
                    $LeaveBalanceModel = new LeaveBalanceModel();
                    $currentMonthBalanceRow = $LeaveBalanceModel
                        ->where('leave_code=', $leave_type)
                        ->where('employee_id=', $employee_id)
                        ->where('year=', $year)
                        ->where('month=', $month)
                        ->first();

                    if (!empty($currentMonthBalanceRow)) {
                        $LeaveBalanceModel = new LeaveBalanceModel();
                        $updateCurrentMonthBalanceQuery = $LeaveBalanceModel->update($currentMonthBalanceRow['id'], ['balance' => $currentMonthBalanceNew]);

                        if ($updateCurrentMonthBalanceQuery) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        $LeaveBalanceModel = new LeaveBalanceModel();
                        $leaveData = [
                            'employee_id' => $employee_id,
                            'leave_code' => $leaveRow['leave_code'],
                            'leave_id' => $leaveRow['id'],
                            'balance' => '0',
                            'year' => $year,
                            'month' => $month,
                        ];
                        $InsertLeaveBalanceQuery = $LeaveBalanceModel->insert($leaveData);
                        if ($InsertLeaveBalanceQuery) {
                            $this->updateCurrentMonthBalance($employee_id, $leaveRow['leave_code'], $year_month);
                        }
                    }
                } elseif ($leaveRow['allocation'] == 'annually') {
                    $salaryYear = date('Y', (strtotime($year_month)));
                    $salaryMonth = date('m', (strtotime($year_month)));
                    $year = date('Y', (strtotime($year_month . " +1 month")));
                    $month = date('m', (strtotime($year_month . " +1 month")));
                    $LeaveBalanceModel = new LeaveBalanceModel();
                    $currentMonthBalanceRow = $LeaveBalanceModel
                        ->where('leave_code=', $leave_type)
                        ->where('employee_id=', $employee_id)
                        ->where('year=', $year)
                        ->where('month=', $month)
                        ->first();
                    if (!empty($currentMonthBalanceRow)) {
                        if ($salaryYear == $year) {
                            $currentMonthBalanceNew = $salaryMonthBalanceNew;
                        } else {
                            $LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
                            $LeaveCreditHistoryModel
                                ->where('employee_id =', $employee_id)
                                ->where('leave_id =', $leaveRow['id'])
                                ->where('year(date_time) =', $year)
                                ->where('month(date_time) =', $month)
                                ->where('type =', 'credit')
                                ->where('remarks =', 'System generated');
                            $system_generated_leave_credit_in_current_month = $LeaveCreditHistoryModel->first();

                            if (!empty($system_generated_leave_credit_in_current_month)) {
                                $system_generated_leave_credit_in_current_month_leave_amount = $system_generated_leave_credit_in_current_month['leave_amount'];
                            } else {
                                $system_generated_leave_credit_in_current_month_leave_amount = 0;
                            }


                            /*echo 'employee_id'.$employee_id.', leave_type'.$leave_type.', year_month'.$year_month.'<br>';
                            echo 'salaryMonthBalanceNew'.$salaryMonthBalanceNew.', system_generated_leave_credit_in_current_month_leave_amount'.$system_generated_leave_credit_in_current_month_leave_amount.'<br>';*/
                            if ($leaveRow['carry_forward'] == 'yes') {
                                $currentMonthBalanceNew = $salaryMonthBalanceNew + $system_generated_leave_credit_in_current_month_leave_amount;
                            } else {
                                $currentMonthBalanceNew = $system_generated_leave_credit_in_current_month_leave_amount;
                            }
                        }
                        if ($leaveRow['carry_forward'] == 'yes' && $currentMonthBalanceNew > $leaveRow['carry_forward_threshold']) {
                            $currentMonthBalanceNew = $leaveRow['carry_forward_threshold'];
                        }

                        if ($month == '01') {
                            $number_of_days = 0;
                            $LeaveRequestsModel = new LeaveRequestsModel();
                            $LeaveRequestsModel
                                ->where("leave_requests.employee_id =", $employee_id)
                                ->where("leave_requests.type_of_leave =", $leaveRow['leave_code'])
                                ->whereIn("leave_requests.status", ['pending', 'approved'])
                                ->groupStart()
                                ->where("YEAR(leave_requests.from_date) = '" . date('Y') . "'")
                                ->orWhere("YEAR(leave_requests.to_date) = '" . date('Y') . "'")
                                ->groupEnd();
                            $LeaveRequests = $LeaveRequestsModel->findAll();
                            if (!empty($LeaveRequests)) {
                                foreach ($LeaveRequests as $LeaveRequest) {
                                    $from_date  = $LeaveRequest['from_date'];
                                    $to_date    = $LeaveRequest['to_date'];
                                    $from_month = date('m', strtotime($from_date));
                                    $to_month   = date('m', strtotime($to_date));
                                    $from_year  = date('Y', strtotime($from_date));
                                    $to_year    = date('Y', strtotime($to_date));
                                    $current_month = $month;
                                    $current_year = $year;


                                    if ($from_month == $current_month && $to_month == $current_month) {
                                        $number_of_days = $number_of_days + $LeaveRequest['number_of_days'];
                                    } elseif ($from_month !== $current_month && $to_month == $current_month) {
                                        $first_date_of_current_month = date('Y-m-01', strtotime($to_date));
                                        $first_date_of_current_month_created = date_create($first_date_of_current_month);
                                        $to_date_created = date_create($to_date);
                                        $diff = date_diff($first_date_of_current_month_created, $to_date_created);
                                        $number_of_days = $number_of_days + $diff->format("%R%a") + 1;
                                    }
                                }
                            }
                            $currentMonthBalanceNew = $currentMonthBalanceNew - $number_of_days;
                        }

                        $LeaveBalanceModel = new LeaveBalanceModel();
                        $updateCurrentMonthBalanceQuery = $LeaveBalanceModel->update($currentMonthBalanceRow['id'], ['balance' => $currentMonthBalanceNew]);



                        if ($updateCurrentMonthBalanceQuery) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                $LeaveBalanceModel = new LeaveBalanceModel();
                $leaveData = [
                    'employee_id' => $employee_id,
                    'leave_code' => $leaveRow['leave_code'],
                    'leave_id' => $leaveRow['id'],
                    'balance' => '0',
                    'year' => date('Y', strtotime($year_month)),
                    'month' => date('m', strtotime($year_month)),
                ];
                $InsertLeaveBalanceQuery = $LeaveBalanceModel->insert($leaveData);
                if ($InsertLeaveBalanceQuery) {
                    $this->updateCurrentMonthBalance($employee_id, $leaveRow['leave_code'], $year_month);
                }
            }
        }
    }
}
