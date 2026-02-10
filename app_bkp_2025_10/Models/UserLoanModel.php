<?php
namespace App\Models;
use CodeIgniter\Model;

class UserLoanModel extends Model{
	protected $table = 'loan_requests';
	protected $allowedFields = [
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
		'disbursal_remarks', 
		'deduct_from_month', 
	];
}
?>