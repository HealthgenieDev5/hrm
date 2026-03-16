<?php
namespace App\Models;
use CodeIgniter\Model;

class ShiftOverrideRevisionModel extends Model{
	protected $table = 'shift_override_revision';
	protected $allowedFields = ['override_id', 'employee_id', 'shift_id', 'from_date', 'to_date', 'remarks', 'date_time', 'revised_by'];
}
?>