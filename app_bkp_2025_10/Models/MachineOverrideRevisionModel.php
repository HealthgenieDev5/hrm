<?php
namespace App\Models;
use CodeIgniter\Model;

class MachineOverrideRevisionModel extends Model{
	protected $table = 'machine_override_revision';
	protected $allowedFields = ['override_id', 'employee_id', 'machine', 'from_date', 'to_date', 'remarks', 'date_time', 'revised_by'];
}
?>