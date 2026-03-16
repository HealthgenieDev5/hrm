<?php

namespace App\Models;

use CodeIgniter\Model;

class ResignationHodResponseModel extends Model
{
	protected $table = 'resignation_response';
	protected $allowedFields = [
		'resignation_id',
		'employee_id',
		'role',
		'response',
		'response_date',
		'remarks',
	];

	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';

	// ==================== PRIVATE HELPERS ====================

	/**
	 * Base SELECT + JOINs shared by all notification queries.
	 * Gets the resigning employee's info via resignations.employee_id.
	 */
	private function baseBuilder(): static
	{
		$this->select('resignation_response.*,
				r.resignation_date,
				r.last_working_date,
				r.resignation_reason,
				r.buyout_days,
				e.first_name,
				e.last_name,
				e.internal_employee_id,
				e.attachment,
				departments.department_name,
				designations.designation_name,
				companies.company_name')
			->join('resignations r', 'r.id = resignation_response.resignation_id', 'left')
			->join('employees e', 'e.id = r.employee_id', 'left')
			->join('departments', 'departments.id = e.department_id', 'left')
			->join('designations', 'designations.id = e.designation_id', 'left')
			->join('companies', 'companies.id = e.company_id', 'left');

		return $this;
	}

	/**
	 * Adds HOD peer response columns.
	 * Used by manager, HR, and HR Manager queries.
	 */
	private function withHodPeer(): static
	{
		$this->select('hod_row.id as hod_row_id,
				hod_row.employee_id as hod_employee_id,
				hod_row.response as hod_response,
				hod_row.response_date as hod_response_date,
				hod_row.remarks as hod_rejection_reason,
				hod_emp.first_name as hod_first_name,
				hod_emp.last_name as hod_last_name')
			->join('resignation_response as hod_row', "hod_row.resignation_id = resignation_response.resignation_id AND hod_row.role = 'hod'", 'left')
			->join('employees hod_emp', 'hod_emp.id = hod_row.employee_id', 'left');

		return $this;
	}

	/**
	 * Adds Manager peer response columns.
	 * Used by HR and HR Manager queries.
	 */
	private function withManagerPeer(): static
	{
		$this->select('manager_row.id as manager_row_id,
				manager_row.employee_id as manager_employee_id,
				manager_row.response as manager_response,
				manager_row.response_date as manager_response_date,
				manager_row.remarks as manager_remarks,
				mgr_emp.first_name as manager_first_name,
				mgr_emp.last_name as manager_last_name')
			->join('resignation_response as manager_row', "manager_row.resignation_id = resignation_response.resignation_id AND manager_row.role = 'manager'", 'left')
			->join('employees mgr_emp', 'mgr_emp.id = manager_row.employee_id', 'left');

		return $this;
	}

	/**
	 * Adds WHERE for pending OR (too_early AND response_date < today).
	 */
	private function withPendingCondition(): static
	{
		$this->groupStart()
			->where('resignation_response.response', 'pending')
			->orGroupStart()
			->where('resignation_response.response', 'too_early')
			->where('DATE(resignation_response.response_date) <', date('Y-m-d'))
			->groupEnd()
			->groupEnd();

		return $this;
	}

	// ==================== PUBLIC METHODS ====================

	/**
	 * Get resignations requiring HOD acknowledgment.
	 */
	public function getPendingHodNotifications(int $hodId): array
	{
		$this->baseBuilder();
		$this->withPendingCondition();

		return $this
			->where('resignation_response.employee_id', $hodId)
			->where('resignation_response.role', 'hod')
			->where('r.status', 'active')
			->orderBy('r.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Get resignations pending reporting manager review.
	 */
	public function getPendingManagerNotifications(int $managerId): array
	{
		$this->baseBuilder();
		$this->withHodPeer();

		return $this
			->where('resignation_response.employee_id', $managerId)
			->where('resignation_response.role', 'manager')
			->where('resignation_response.response', 'pending')
			->where('r.status', 'active')
			->orderBy('r.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Get resignations pending HR review.
	 * Returns HOD + Manager peer responses for the HR modal.
	 */
	public function getPendingHrNotifications(int $hrId): array
	{
		$this->baseBuilder();
		$this->withHodPeer();
		$this->withManagerPeer();

		return $this
			->where('resignation_response.employee_id', $hrId)
			->where('resignation_response.role', 'hr')
			->where('resignation_response.response', 'pending')
			->where('r.status', 'active')
			->orderBy('r.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Get resignations pending HR Manager review.
	 * Returns HOD + Manager peer responses.
	 */
	public function getPendingHrManagerNotifications(int $hrManagerId): array
	{
		$this->baseBuilder();
		$this->withHodPeer();
		$this->withManagerPeer();

		return $this
			->where('resignation_response.employee_id', $hrManagerId)
			->where('resignation_response.role', 'hr_manager')
			->where('resignation_response.response', 'pending')
			->where('r.status', 'active')
			->orderBy('r.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Get HR-decision rows for a reporting manager.
	 * These are inserted when HR marks a resignation as retained/retention_failed.
	 * Returns r.status aliased as hr_decision so the controller can display the outcome.
	 */
	public function getPendingHrDecisionNotifications(int $managerId): array
	{
		$this->baseBuilder();
		$this->select('r.status as hr_decision');

		return $this
			->where('resignation_response.employee_id', $managerId)
			->where('resignation_response.role', 'manager')
			->where('resignation_response.response', 'pending')
			->whereIn('r.status', ['retained', 'retention_failed'])
			->orderBy('r.resignation_date', 'ASC')
			->findAll();
	}

	/**
	 * Mark a response row with the given response value and optional remarks.
	 */
	public function markResponse(int $recordId, string $response, ?string $remarks = null): bool
	{
		$data = [
			'response'      => $response,
			'response_date' => date('Y-m-d H:i:s'),
		];

		if ($remarks !== null) {
			$data['remarks'] = $remarks;
		}

		return $this->update($recordId, $data);
	}

	/**
	 * Get a single row by resignation ID and role.
	 */
	public function getByResignationAndRole(int $resignationId, string $role): ?array
	{
		return $this->where('resignation_id', $resignationId)
			->where('role', $role)
			->first();
	}

	/**
	 * Ensure an HR row exists for this resignation and set it to pending.
	 * Called after HOD/Manager have responded.
	 */
	public function setHrPending(int $resignationId, int $hrId): bool
	{
		$existing = $this->where('resignation_id', $resignationId)
			->where('role', 'hr')
			->first();

		if ($existing) {
			return $this->update($existing['id'], [
				'employee_id' => $hrId,
				'response'    => 'pending',
			]);
		}

		return (bool) $this->insert([
			'resignation_id' => $resignationId,
			'employee_id'    => $hrId,
			'role'           => 'hr',
			'response'       => 'pending',
		]);
	}
}
