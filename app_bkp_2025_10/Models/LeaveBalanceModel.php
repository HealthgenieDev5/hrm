<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveBalanceModel extends Model{
	protected $table = 'leave_balance';
	protected $allowedFields = ['employee_id', 'leave_code', 'leave_id', 'balance', 'year', 'month'];
}
?>