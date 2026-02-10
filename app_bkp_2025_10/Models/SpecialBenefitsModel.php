<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecialBenefitsModel extends Model
{
	protected $table = 'employees';
	protected $allowedFields = [
		'second_saturday_fixed_off',
		'late_sitting_allowed',
		'late_sitting_formula',
		'late_sitting_formula_effective_from',
		'over_time_allowed',
	];
}
