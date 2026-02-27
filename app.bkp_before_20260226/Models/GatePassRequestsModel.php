<?php
namespace App\Models;
use CodeIgniter\Model;

class GatePassRequestsModel extends Model{
	protected $table = 'gate_pass_requests';
	protected $allowedFields = ['employee_id', 'gate_pass_type', 'gate_pass_date', 'gate_pass_hours', 'reason', 'status', 'reviewed_by', 'reviewed_date', 'remarks'];
}
?>