<?php
namespace App\Models;
use CodeIgniter\Model;

class AdvanceSalaryRevisionModel extends Model{
	protected $table = 'advance_salary_requests_revision';
	protected $allowedFields = [
		'advance_salary_request_id', 
		'employee_id', 
		'amount', 
		'emi_tenure', 
		'reason', 
		'note', 
		'type', 
		'review_status', 
		'reviewed_date', 
		'reviewed_by', 
		'remarks', 
		'disbursed', 
		'disbursed_date', 
		'disbursed_by', 
		'disbursal_remarks', 
		'deduct_from_month', 
		'date_time', 
		'revised_by', 
	];
}
?>