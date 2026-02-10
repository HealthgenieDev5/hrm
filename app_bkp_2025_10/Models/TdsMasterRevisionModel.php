<?php
namespace App\Models;
use CodeIgniter\Model;

class TdsMasterRevisionModel extends Model{
	protected $table = 'tds_master_revision';
	protected $allowedFields = ['tds_record_id', 'employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated', 'date_time', 'revised_by'];
}
?>