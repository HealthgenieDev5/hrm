<?php
namespace App\Models;
use CodeIgniter\Model;

class ProbationHodResponseModel extends Model{
	protected $table = 'probation_hod_response';
	protected $allowedFields = [
		'employee_id', 
		'hod_id', 
		'response'
	];
}
?>