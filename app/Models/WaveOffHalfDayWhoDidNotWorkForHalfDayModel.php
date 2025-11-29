<?php

namespace App\Models;

use CodeIgniter\Model;

class WaveOffHalfDayWhoDidNotWorkForHalfDayModel extends Model
{
	protected $table = 'waveoffhalfdaywhodidnotworkforhalfday';
	protected $allowedFields = ['employee_id', 'date', 'remarks', 'added_by'];
}
