<?php
namespace App\Models;
use CodeIgniter\Model;

class UserLoanRevisionModel extends Model{
	protected $table = 'loan_requests_revision';
	protected $allowedFields = [
		'loan_id', 
		'employee_id', 
		'loan_amount', 
		'emi_tenure', 
		'reason', 
		'note', 
		'review_status', 
		'reviewed_date', 
		'reviewed_by', 
		'remarks', 
		'disbursed', 
		'disbursed_date', 
		'disbursed_by', 
		'deduct_from_month', 
		'date_time', 
		'revised_by', 
	];
}
?>