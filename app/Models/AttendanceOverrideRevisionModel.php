<?php
namespace App\Models;
use CodeIgniter\Model;

class AttendanceOverrideRevisionModel extends Model{
	protected $table = 'attendance_override_revision';
	protected $allowedFields = ['override_id', 'employee_id', 'attendance', 'attendance_date', 'remarks', 'date_time', 'revised_by'];
}
?>