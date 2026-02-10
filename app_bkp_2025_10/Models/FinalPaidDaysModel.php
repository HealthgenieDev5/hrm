<?php
namespace App\Models;
use CodeIgniter\Model;

class FinalPaidDaysModel extends Model{
	protected $table = 'final_paid_days ';
	protected $allowedFields = ['employee_id', 'date', 'day', 'shift_start', 'shift_end', 'in_time', 'out_time', 'late_coming_rule', 'late_coming_minutes', 'early_going_minutes', 'late_coming_plus_early_going_minutes', 'late_coming_plus_early_going_minus_od_minutes', 'work_hours', 'status', 'status_remarks', 'od_hours', 'paid', 'settlement_type', 'settlement', 'settlement_remarks', 'settled_by', 'final_paid'];

	
	
}
?>