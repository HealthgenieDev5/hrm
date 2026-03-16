<?php
namespace App\Models;
use CodeIgniter\Model;

class WaveOffModel extends Model{
	protected $table = 'wave_off_minutes';
	protected $allowedFields = ['employee_id', 'minutes', 'date', 'remarks', 'added_by'];
}
?>