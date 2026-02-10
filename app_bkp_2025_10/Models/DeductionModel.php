<?php
namespace App\Models;
use CodeIgniter\Model;

class DeductionModel extends Model{
	protected $table = 'deduction_minutes';
	protected $allowedFields = [
		'employee_id', 
		'minutes', 
		'attachment', 
		'date',
		'reviewed_by', 
		'reviewed_date', 
		'reviewer_remarks', 
		'deducted_by', 
		'initial_remarks', 
		'current_status'
	];
}
?>