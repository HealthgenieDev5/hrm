<?php
namespace App\Models;
use CodeIgniter\Model;

class AttendanceOverrideModel extends Model{
	protected $table = 'attendance_override';
	protected $allowedFields = ['employee_id', 'attendance', 'attendance_date', 'remarks'];
}
?>