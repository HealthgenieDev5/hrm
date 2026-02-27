<?php
namespace App\Models;
use CodeIgniter\Model;

class DeductionTrashModel extends Model{
	protected $table = 'deduction_minutes_trash';
	protected $allowedFields = 
	[
		'deduction_id', 
		'employee_id', 
		'minutes', 
		'attachment', 
		'date',
		'reviewed_by', 
		'reviewed_date', 
		'reviewer_remarks', 
		'deducted_by', 
		'initial_remarks', 
		'current_status',
		'deleted_by'
	];
}
?>


