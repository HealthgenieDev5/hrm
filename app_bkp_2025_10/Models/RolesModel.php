<?php
namespace App\Models;
use CodeIgniter\Model;

class RolesModel extends Model{
	protected $table = 'employee_roles';
	protected $allowedFields = ['role_name', 'capabilities'];
}
?>