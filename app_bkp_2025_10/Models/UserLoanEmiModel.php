<?php
namespace App\Models;
use CodeIgniter\Model;

class UserLoanEmiModel extends Model{
	protected $table = 'loan_emis';
	protected $allowedFields = [
		'loan_id', 
		'year', 
		'month', 
		'principle_amount', 
		'emi', 
		'deducted', 
		'deduction_date', 
	];
}
?>