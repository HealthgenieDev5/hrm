<?php

namespace App\Models;

use CodeIgniter\Model;

class ResignationHodResponseModel extends Model
{
	protected $table = 'resignation_hod_response';
	protected $allowedFields = [
		'resignation_id',
		'employee_id',
		'hod_id',
		'hod_response',
		'hod_response_date',
		'hod_rejection_reason',
		'manager_id',
		'manager_viewed',
		'manager_viewed_date',
		'hr_id',
		'hr_viewed',
		'hr_viewed_date',
	];

	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';

	/**
	 * Get resignations requiring HOD acknowledgment
	 * Returns resignations where hod_response is pending or too_early (and eligible to show again)
	 */
	public function getPendingHodNotifications($hodId)
	{
		return $this->select('resignation_hod_response.*,
			resignations.resignation_date,
			resignations.last_working_date,
			resignations.resignation_reason,
			resignations.buyout_days,
			employees.first_name,
			employees.last_name,
			employees.internal_employee_id,
			departments.department_name,
			designations.designation_name,
			companies.company_name,
			manager.first_name as manager_first_name,
			manager.last_name as manager_last_name')
			->join('resignations', 'resignations.id = resignation_hod_response.resignation_id', 'left')
			->join('employees', 'employees.id = resignation_hod_response.employee_id', 'left')
			->join('departments', 'departments.id = employees.department_id', 'left')
			->join('designations', 'designations.id = employees.designation_id', 'left')
			->join('companies', 'companies.id = employees.company_id', 'left')
			->join('employees as manager', 'manager.id = resignation_hod_response.manager_id', 'left')
			->where('resignation_hod_response.hod_id', $hodId)
			->where('resignations.status', 'active')
			->groupStart()
			->where('resignation_hod_response.hod_response', 'pending')
			->orGroupStart()
			->where('resignation_hod_response.hod_response', 'too_early')
			->where('DATE(resignation_hod_response.hod_response_date) <', date('Y-m-d'))
			->groupEnd()
			->groupEnd()
			->whereNotIn('resignation_hod_response.hod_response', ['accept', 'rejected'])
			->orderBy('resignations.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Get resignations pending reporting manager review
	 * Returns records where manager_viewed is pending (set at resignation creation)
	 */
	public function getPendingManagerNotifications($managerId)
	{
		return $this->select('resignation_hod_response.*,
			resignations.resignation_date,
			resignations.last_working_date,
			resignations.resignation_reason,
			resignations.buyout_days,
			employees.first_name,
			employees.last_name,
			employees.internal_employee_id,
			departments.department_name,
			designations.designation_name,
			companies.company_name,
			hod.first_name as hod_first_name,
			hod.last_name as hod_last_name')
			->join('resignations', 'resignations.id = resignation_hod_response.resignation_id', 'left')
			->join('employees', 'employees.id = resignation_hod_response.employee_id', 'left')
			->join('departments', 'departments.id = employees.department_id', 'left')
			->join('designations', 'designations.id = employees.designation_id', 'left')
			->join('companies', 'companies.id = employees.company_id', 'left')
			->join('employees as hod', 'hod.id = resignation_hod_response.hod_id', 'left')
			->where('resignation_hod_response.manager_viewed', 'pending')
			->where('resignation_hod_response.manager_id', $managerId)
			->where('resignations.status', 'active')
			->orderBy('resignations.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Mark that manager has viewed the notification
	 */
	public function markManagerViewed($recordId)
	{
		$data = [
			'manager_viewed' => 'viewed',
			'manager_viewed_date' => date('Y-m-d H:i:s')
		];
		return $this->update($recordId, $data);
	}

	/**
	 * Set manager as pending (to be called after HOD responds)
	 */
	public function setManagerPending($recordId, $managerId)
	{
		$data = [
			'manager_id' => $managerId,
			'manager_viewed' => 'pending'
		];
		return $this->update($recordId, $data);
	}

	/**
	 * Check if there's an unviewed manager notification for this resignation
	 */
	public function hasUnviewedManagerNotification($resignationId, $hodId)
	{
		$record = $this->where('resignation_id', $resignationId)
			->where('hod_id', $hodId)
			->where('manager_viewed !=', 'viewed')
			->where('manager_viewed IS NOT NULL')
			->first();

		return !empty($record);
	}

	// ==================== HR NOTIFICATION METHODS ====================

	/**
	 * Get HOD responses pending HR review
	 * Returns records where HOD has responded and HR hasn't viewed yet
	 */
	public function getPendingHrNotifications($hrId)
	{
		return $this->select('resignation_hod_response.*,
			resignations.resignation_date,
			resignations.last_working_date,
			resignations.resignation_reason,
			resignations.buyout_days,
			employees.first_name,
			employees.last_name,
			employees.internal_employee_id,
			departments.department_name,
			designations.designation_name,
			companies.company_name,
			hod.first_name as hod_first_name,
			hod.last_name as hod_last_name')
			->join('resignations', 'resignations.id = resignation_hod_response.resignation_id', 'left')
			->join('employees', 'employees.id = resignation_hod_response.employee_id', 'left')
			->join('departments', 'departments.id = employees.department_id', 'left')
			->join('designations', 'designations.id = employees.designation_id', 'left')
			->join('companies', 'companies.id = employees.company_id', 'left')
			->join('employees as hod', 'hod.id = resignation_hod_response.hod_id', 'left')
			->where('resignation_hod_response.hr_id', $hrId)
			->where('resignation_hod_response.hr_viewed', 'pending')
			->whereIn('resignation_hod_response.hod_response', ['accept', 'rejected'])
			->orderBy('resignation_hod_response.hod_response_date', 'ASC')
			->findAll();
	}

	/**
	 * Mark that HR has viewed the notification
	 */
	public function markHrViewed($recordId)
	{
		return $this->update($recordId, [
			'hr_viewed' => 'viewed',
			'hr_viewed_date' => date('Y-m-d H:i:s')
		]);
	}

	/**
	 * Set HR as pending (to be called after HOD responds)
	 */
	public function setHrPending($recordId, $hrId)
	{
		return $this->update($recordId, [
			'hr_id' => $hrId,
			'hr_viewed' => 'pending'
		]);
	}
}
