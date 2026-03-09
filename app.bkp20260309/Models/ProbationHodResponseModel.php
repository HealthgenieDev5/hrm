<?php

namespace App\Models;

use CodeIgniter\Model;

class ProbationHodResponseModel extends Model
{
	protected $table = 'probation_hod_response';
	protected $allowedFields = [
		'employee_id',
		'hod_id',
		'response',
		'hr_manager_id',
		'hr_response',
		'hr_response_date',
		'date_time'
	];

	public function getPendingHrConfirmations($hrManagerId)
	{
		return $this->select('probation_hod_response.*,
			employees.first_name,
			employees.last_name,
			employees.joining_date,
			employees.probation,
			departments.department_name,
			designations.designation_name,
			hod.first_name as hod_first_name,
			hod.last_name as hod_last_name')
			->join('employees', 'employees.id = probation_hod_response.employee_id', 'left')
			->join('departments', 'departments.id = employees.department_id', 'left')
			->join('designations', 'designations.id = employees.designation_id', 'left')
			->join('employees as hod', 'hod.id = probation_hod_response.hod_id', 'left')
			->where('probation_hod_response.response', 'Confirmed')
			->whereIn('probation_hod_response.hr_response', ['pending', 'remind_later'])
			->where('probation_hod_response.hr_manager_id', $hrManagerId)
			->orderBy('probation_hod_response.date_time', 'ASC')
			->findAll();
	}


	public function setHrPending($recordId, $hrManagerId)
	{
		$data = [
			'hr_manager_id' => $hrManagerId,
			'hr_response' => 'pending'
		];
		return $this->update($recordId, $data);
	}

	public function hrUpdateStatus($recordId, $action)
	{
		$data = [
			'hr_response' => $action,
			'hr_response_date' => date('Y-m-d H:i:s')
		];
		return $this->update($recordId, $data);
	}


	// public function hrRemindLater($recordId)
	// {
	// 	$data = [
	// 		'hr_response' => 'remind_later',
	// 		'hr_response_date' => date('Y-m-d H:i:s')
	// 	];

	// 	return $this->update($recordId, $data);
	// }

	// public function hrConfirmed($recordId, $notes = null)
	// {
	// 	$data = [
	// 		'hr_response' => 'confirmed',
	// 		'hr_response_date' => date('Y-m-d H:i:s')
	// 	];

	// 	return $this->update($recordId, $data);
	// }

	public function hasUnconfirmedHrResponse($employeeId, $hodId)
	{
		$record = $this->where('employee_id', $employeeId)
			->where('hod_id', $hodId)
			->where('hr_response !=', 'confirmed')
			->where('hr_response IS NOT NULL')
			->first();

		return !empty($record);
	}
}
