<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveModel extends Model{
	protected $table = 'leaves';
	protected $allowedFields = ['leave_code', 'leave_name', 'encash', 'allocation', 'limit', 'carry_forward', 'carry_forward_threshold', 'only_after_1_year_complete', 'pro_rated'];
}
?>