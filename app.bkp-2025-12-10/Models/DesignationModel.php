<?php
namespace App\Models;
use CodeIgniter\Model;

class DesignationModel extends Model{
	protected $table = 'designations';
	protected $allowedFields = ['designation_name'];
}
?>