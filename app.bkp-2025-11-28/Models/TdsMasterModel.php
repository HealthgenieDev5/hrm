<?php
namespace App\Models;
use CodeIgniter\Model;

class TdsMasterModel extends Model{
	protected $table = 'tds_master';
	protected $allowedFields = ['employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated'];
}
?>