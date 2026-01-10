<?php
namespace App\Models;
use CodeIgniter\Model;

class WaveOffRevisionModel extends Model{
	protected $table = 'wave_off_minutes_revision';
	protected $allowedFields = ['override_id', 'employee_id', 'minutes', 'date', 'remarks', 'added_by', 'date_time', 'revised_by'];
}
?>


