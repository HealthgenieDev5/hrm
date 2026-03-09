<?php

namespace App\Controllers\Cron;

use App\Models\LeaveModel;
use App\Models\EmployeeModel;
use App\Models\LeaveBalanceModel;
use App\Controllers\BaseController;
use App\Models\LeaveCreditHistoryModel;

class UpdateCreditandBalance extends BaseController
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
		$EmployeeModel = new EmployeeModel();
		// $employees = $EmployeeModel->where('status =', 'active')->where('id = ', 132)->find();
		// $employees = $EmployeeModel->where('status =', 'active')->where('id = ', 48)->find();
		// $employees = $EmployeeModel->where('status =', 'active')->where('id = ', 40)->orderBy('id', 'ASC')->find();
		$employees = $EmployeeModel->where('status =', 'active')->where('designation_id !=', '75')->orderBy('id', 'ASC')->find();
		// $employees = $EmployeeModel->where('id =', '95')->orderBy('id', 'ASC')->find();

		foreach ($employees as $employee) {
			$date = '2026-03-02';
			$LeaveModel = new LeaveModel();
			$leaves = $LeaveModel->findAll();
			$this->insertIntoCredit($employee, $date, $leaves);
		}
	}

	public function insertIntoCredit($employee, $date, $leaves)
	{
		$LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
		foreach ($leaves as $index => $leaveRow) {
			if ($leaveRow['allocation'] == 'monthly') {
				if ($leaveRow['only_after_18_days_complete'] == 'yes') {
					if (!empty($employee['joining_date'])) {
						$date1 = strtotime($date);
						$date2 = strtotime($employee['joining_date']);
						$datediff = $date1 - $date2;
						$days_from_employment = round($datediff / (60 * 60 * 24));

						if ($days_from_employment >= '18') {
							$leaveData = [
								'employee_id' 	=> $employee['id'],
								'leave_id' 		=> $leaveRow['id'],
								'leave_amount' 	=> $leaveRow['limit'],
								'type' 			=> 'credit',
								'remarks' 		=> 'System generated',
								'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date)),
							];
							$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
						} else {
							$leaveData = [
								'employee_id' 	=> $employee['id'],
								'leave_id' 		=> $leaveRow['id'],
								'leave_amount' 	=> '0',
								'type' 			=> 'credit',
								'remarks' 		=> 'System generated',
								'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
							];
							$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
						}
					} else {
						$leaveData = [
							'employee_id' 	=> $employee['id'],
							'leave_id' 		=> $leaveRow['id'],
							'leave_amount' 	=> '0',
							'type' 			=> 'credit',
							'remarks' 		=> 'System generated',
							'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
						];
						$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
					}
				} else {
					$LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
					$leaveData = [
						'employee_id' 	=> $employee['id'],
						'leave_id' 		=> $leaveRow['id'],
						'leave_amount' 	=> $leaveRow['limit'],
						'type' 			=> 'credit',
						'remarks' 		=> 'System generated',
						'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
					];
					$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
				}
			} elseif ($leaveRow['allocation'] == 'annually') {
				if (date('m', strtotime($date)) == '01') {
					if ($leaveRow['only_after_1_year_complete'] == 'yes') {
						if (!empty($employee['joining_date'])) {
							$date1 = date_create(date('Y-m-01', strtotime($date)));
							$date2 = date_create($employee['joining_date']);
							$diff = date_diff($date1, $date2);
							$yearInterval = $diff->format("%y");
							$monthInterval = $diff->format("%m");
							if ($yearInterval >= '1') {
								$leaveData = [
									'employee_id' 	=> $employee['id'],
									'leave_id' 		=> $leaveRow['id'],
									'leave_amount' 	=> $leaveRow['limit'],
									'type' 			=> 'credit',
									'remarks' 		=> 'System generated',
									'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
								];
								$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
							} else {
								$leaveData = [
									'employee_id' 	=> $employee['id'],
									'leave_id' 		=> $leaveRow['id'],
									'leave_amount' 	=> '0',
									'type' 			=> 'credit',
									'remarks' 		=> 'System generated',
									'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
								];
								$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
							}
						} else {
							$leaveData = [
								'employee_id' 	=> $employee['id'],
								'leave_id' 		=> $leaveRow['id'],
								'leave_amount' 	=> '0',
								'type' 			=> 'credit',
								'remarks' 		=> 'System generated',
								'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
							];
							$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
						}
					} else {
						$leaveData = [
							'employee_id' 	=> $employee['id'],
							'leave_id' 		=> $leaveRow['id'],
							'leave_amount' 	=> $leaveRow['limit'],
							'type' 			=> 'credit',
							'remarks' 		=> 'System generated',
							'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
						];
						$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
					}
				} else {
					if ($leaveRow['only_after_1_year_complete'] == 'yes') {
						$date1 = date_create(date('Y-m-01', strtotime($date)));
						$date2 = date_create($employee['joining_date']);
						$diff = date_diff($date1, $date2);
						$yearInterval = $diff->format("%y");
						$monthInterval = $diff->format("%m");
						if ($yearInterval == '1' && $monthInterval == '0') {
							$date3 = date_create(date('Y-m-01', strtotime($date)));
							$date4 = date_create(date('Y-12-31', strtotime($date)));
							$diffProRate = date_diff($date3, $date4);
							$ProRateMonths = $diffProRate->format("%m");
							$leave_amount = round(($leaveRow['limit'] / 12) * $ProRateMonths);
							$leaveData = [
								'employee_id' 	=> $employee['id'],
								'leave_id' 		=> $leaveRow['id'],
								'leave_amount' 	=> $leave_amount,
								'type' 			=> 'credit',
								'remarks' 		=> 'System generated',
								'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
							];
							$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
						} else {
							$LeaveCreditHistoryModel = new LeaveCreditHistoryModel();
							$leaveData = [
								'employee_id' 	=> $employee['id'],
								'leave_id' 		=> $leaveRow['id'],
								'leave_amount' 	=> '0',
								'type' 			=> 'credit',
								'remarks' 		=> 'System generated',
								'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
							];
							$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
						}
					} else {
						$leaveData = [
							'employee_id' 	=> $employee['id'],
							'leave_id' 		=> $leaveRow['id'],
							'leave_amount' 	=> '0',
							'type' 			=> 'credit',
							'remarks' 		=> 'System generated',
							'date_time'	 	=> date('Y-m-01 H:i:s', strtotime($date))
						];
						$insertLeaveCreditHistoryModelQuery = $LeaveCreditHistoryModel->insert($leaveData);
					}
				}
			}

			$LeaveBalanceModel = new LeaveBalanceModel();
			$leaveData = [
				'employee_id' => $employee['id'],
				'leave_code' => $leaveRow['leave_code'],
				'leave_id' => $leaveRow['id'],
				'balance' => '0',
				'year' => date('Y', strtotime($date)),
				'month' => date('m', strtotime($date)),
			];
			$InsertLeaveBalanceQuery = $LeaveBalanceModel->insert($leaveData);
			if ($InsertLeaveBalanceQuery) {
				$LeaveBalance = new LeaveBalance();
				$LeaveBalance->updateCurrentMonthBalance($employee['id'], $leaveRow['leave_code'], date('F Y', strtotime($date . " -1 month")));
			}
		}
	}
}
