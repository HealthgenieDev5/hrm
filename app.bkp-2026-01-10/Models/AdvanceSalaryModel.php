<?php
namespace App\Models;
use CodeIgniter\Model;

class AdvanceSalaryModel extends Model{
	protected $table = 'advance_salary_requests';
	protected $allowedFields = [
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
	];
}
?>