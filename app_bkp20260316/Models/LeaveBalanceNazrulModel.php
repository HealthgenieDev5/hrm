<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveBalanceNazrulModel extends Model{
	protected $table = 'leave_balance_nazrul';
	protected $allowedFields = ['employee_id', 'leave_code', 'leave_id', 'balance', 'year', 'month'];
}
?>