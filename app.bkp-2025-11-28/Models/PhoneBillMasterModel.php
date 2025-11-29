<?php
namespace App\Models;
use CodeIgniter\Model;

class PhoneBillMasterModel extends Model{
	protected $table = 'phone_bill_master';
	protected $allowedFields = ['employee_id', 'deduction_amount', 'year', 'month', 'status', 'last_updated'];
}
?>