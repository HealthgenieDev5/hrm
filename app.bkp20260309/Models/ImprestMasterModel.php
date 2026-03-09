<?php
namespace App\Models;
use CodeIgniter\Model;

class ImprestMasterModel extends Model{
	protected $table = 'imprest_master';
	protected $allowedFields = ['employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated'];
}
?>