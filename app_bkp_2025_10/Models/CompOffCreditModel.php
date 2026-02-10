<?php
namespace App\Models;
use CodeIgniter\Model;

class CompOffCreditModel extends Model{
	protected $table = 'comp_off_credit_requests';
	protected $allowedFields = [
		'employee_id', 
		'working_date', 
		'assigned_by', 
		'reason', 
		'attachment', 
		'status', 
		'stage_1_reviewed_by', 
		'stage_1_reviewed_date', 
		'stage_1_remarks', 
		'reviewed_by', 
		'reviewed_date', 
		'remarks', 
		'exchange', 
		'minutes'
	];
}
?>