<?php
namespace App\Models;
use CodeIgniter\Model;

class MachineOverrideModel extends Model{
	protected $table = 'machine_override';
	protected $allowedFields = ['employee_id', 'machine', 'from_date', 'to_date', 'remarks'];
}
?>