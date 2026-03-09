<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveBalanceOverrideHistoryModel extends Model{
	protected $table = 'leave_balance_overrides';
	protected $allowedFields = [
		'employee_id', 
		'leave_code', 
		'previous_balance', 
		'new_balance',
		'overriden_by', 
		'remarks'
	];
}
?>