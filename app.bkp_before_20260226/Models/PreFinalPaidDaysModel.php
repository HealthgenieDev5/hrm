<?php
namespace App\Models;
use CodeIgniter\Model;

class PreFinalPaidDaysModel extends Model{
	protected $table = 'pre_final_paid_days ';
	protected $allowedFields = [
		'employee_id', 
		'date', 
		'day', 
		'machine', 
		'shift_start', 
		'shift_end', 
		'punch_in_time', 
		'punch_out_time', 
		'in_time_between_shift_with_od', 
		'out_time_between_shift_with_od', 
		'in_time_including_od', 
		'out_time_including_od', 
		'late_coming_rule', 
		'late_coming_minutes', 
		'early_going_minutes', 
		'late_coming_plus_early_going_minutes', 
		'late_coming_plus_early_going_minutes_adjustable', 
		'late_coming_plus_early_going_minus_od_minutes', 
		'late_coming_grace', 
		'comp_off_minutes', 
		'wave_off_minutes', 
		'wave_off_remarks', 
		'deduction_minutes', 
		'deduction_remarks', 
		'wave_off_half_day_who_did_not_work_for_half_day', 
		'wave_off_half_day_who_did_not_work_for_half_day_remarks', 
		'ExtraWorkMinutes', 
		'LateSittingMinutes', 
		'OverTimeMinutes', 
		'work_hours', 
		'status', 
		'status_remarks', 
		'leave_request_type', 
		'leave_request_amount', 
		'leave_request_status', 
		'od_hours', 
		'paid', 
		'settlement_type', 
		'settlement', 
		'settlement_remarks', 
		'settled_by', 
		'final_paid'
	];	
}
?>