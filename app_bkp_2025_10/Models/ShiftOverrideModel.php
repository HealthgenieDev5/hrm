<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftOverrideModel extends Model{
	protected $table = 'shift_override';
	protected $allowedFields = ['employee_id', 'shift_id', 'from_date', 'to_date', 'remarks'];
}
?>