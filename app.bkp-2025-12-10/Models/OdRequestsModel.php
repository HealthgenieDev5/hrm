<?php

namespace App\Models;

use CodeIgniter\Model;

class OdRequestsModel extends Model
{
	protected $table = 'od_requests';
	protected $allowedFields = ['employee_id', 'estimated_from_date_time', 'estimated_to_date_time', 'actual_from_date_time', 'actual_to_date_time', 'international', 'duty_location', 'duty_assigner', 'reason', 'status', 'reviewed_by', 'reviewed_date_time', 'remarks', 'updated_date_time'];
}
