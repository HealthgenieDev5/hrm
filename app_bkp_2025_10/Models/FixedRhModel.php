<?php

namespace App\Models;

use CodeIgniter\Model;

class FixedRhModel extends Model
{
	protected $table = 'fixed_rh ';
	protected $allowedFields = ['employee_id', 'rh_id', 'year'];
}
