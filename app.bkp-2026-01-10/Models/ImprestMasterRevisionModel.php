<?php
namespace App\Models;
use CodeIgniter\Model;

class ImprestMasterRevisionModel extends Model{
	protected $table = 'imprest_master_revision';
	protected $allowedFields = ['imprest_record_id', 'employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated', 'date_time', 'revised_by'];
}
?>