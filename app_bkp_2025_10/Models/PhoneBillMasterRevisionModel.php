<?php
namespace App\Models;
use CodeIgniter\Model;

class PhoneBillMasterRevisionModel extends Model{
	protected $table = 'phone_bill_master_revision';
	protected $allowedFields = ['phone_bill_record_id', 'employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated', 'date_time', 'revised_by'];
}
?>