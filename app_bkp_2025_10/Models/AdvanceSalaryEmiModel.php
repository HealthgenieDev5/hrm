<?php
namespace App\Models;
use CodeIgniter\Model;

class AdvanceSalaryEmiModel extends Model{
	protected $table = 'advance_salary_emis';
	protected $allowedFields = [
		'advance_salary_request_id', 
		'year', 
		'month', 
		'principle_amount', 
		'emi', 
		'deducted', 
		'deduction_date', 
	];
}
?>