<?php
namespace App\Models;
use CodeIgniter\Model;

class SpecialHolidayEmployeesModel extends Model{
	protected $table = 'special_holiday_employees';
	protected $allowedFields = [
		'employee_id', 
		'holiday_id',
	];

}
?>