<?php
namespace App\Models;
use CodeIgniter\Model;

class HolidayModel extends Model{
	protected $table = 'holidays ';
	protected $allowedFields = ['holiday_name', 'holiday_code', 'holiday_type', 'employees', 'is_special_holiday', 'holiday_date'];
}
?>