<?php

namespace App\Models;

use CodeIgniter\Model;

class ShiftModel extends Model
{
	protected $table = 'shifts';
	protected $allowedFields = ['shift_code', 'shift_name', 'shift_type', 'weekoff', 'in_time', 'out_time'];
}
