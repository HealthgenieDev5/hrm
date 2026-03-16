<?php
namespace App\Models;
use CodeIgniter\Model;

class WaveOffHalfDayWhoDidNotWorkForHalfDayRevisionModel extends Model{
	protected $table = 'waveoffhalfdaywhodidnotworkforhalfday_revision';
	protected $allowedFields = ['override_id', 'employee_id', 'date', 'remarks', 'added_by', 'date_time', 'revised_by'];
}
?>