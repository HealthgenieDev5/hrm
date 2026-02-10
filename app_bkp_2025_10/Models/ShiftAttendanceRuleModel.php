<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftAttendanceRuleModel extends Model{
	protected $table = 'shift_attendance_rule';
	protected $allowedFields = ['shift_id', 'consider_early_arrival', 'consider_early_arrival_max_hours', 'consider_late_departure', 'consider_late_departure_max_hours', 'late_coming_rule', 'attendance_rule'];
}
?>