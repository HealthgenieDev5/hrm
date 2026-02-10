<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftPerDayModel extends Model{
	protected $table = 'shift_per_day';
	protected $allowedFields = ['shift_id', 'day', 'shift_start', 'shift_end', 'lunch_start', 'lunch_end', 'break_one_start', 'break_one_end', 'break_two_start', 'break_two_end', 'break_three_start', 'break_three_end'];
}
?>