<?php
namespace App\Models;
use CodeIgniter\Model;

class CompOffMinutesUtilizedModel extends Model{
	protected $table = 'comp_off_minutes_utilized';
	protected $allowedFields = [
		'employee_id', 
		'date', 
		'minutes', 
		'type', 
		'requested_by'
	];
}
?>