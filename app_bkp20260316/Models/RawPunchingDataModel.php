<?php
namespace App\Models;
use CodeIgniter\Model;

class RawPunchingDataModel extends Model{
	protected $table = 'raw_attendance';
	protected $primaryKey = 'id';
	protected $allowedFields = ['Empcode', 'INTime', 'OUTTime', 'Remark', 'DateString', 'DateString_2', 'machine', 'default_machine', 'override_machine'];
}
?>