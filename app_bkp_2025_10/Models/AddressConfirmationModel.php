<?php
namespace App\Models;

use CodeIgniter\Model;

class AddressConfirmationModel extends Model
{
    protected $table = 'employee_address_confirmations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id',
		'address_text',
		'document_path',
		'document_type',
        'status',
		'hr_reviewed_by',
		'hr_review_date',
		'hr_comments'
    ];
    protected $useTimestamps = false;

    public function getPendingConfirmations()
    {
        return $this->select('employee_address_confirmations.*, employees.name, employees.employee_code')
                   ->join('employees', 'employees.id = employee_address_confirmations.employee_id')
                   ->where('status', 'pending')
                   ->findAll();
    }

    public function getEmployeeLatestConfirmation($employeeId)
    {
        return $this->where('employee_id', $employeeId)
                   ->where('is_active', true)
                   ->orderBy('submission_date', 'DESC')
                   ->first();
    }
}