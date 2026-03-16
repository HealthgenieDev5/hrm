<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveCreditHistoryModel extends Model{
	protected $table = 'leave_credit_history';
	protected $allowedFields = ['employee_id', 'leave_id', 'leave_amount', 'type', 'remarks', 'custom_remarks', 'date_time'];
}
?>