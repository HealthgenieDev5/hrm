<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveCreditHistoryModelNew extends Model{
	protected $table = 'leave_credit_history_new';
	protected $allowedFields = [
		'employee_id', 
		'leave_id', 
		'leave_code', 
		'leave_amount', 
		'type', 
		'remarks', 
		'credit_date', 
		'expiry_date', 
		'date_time'
	];
}
?>