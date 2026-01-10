<?php

namespace App\Models;

use CodeIgniter\Model;


class ManualPunchRevisionModel extends Model
{
    protected $table = 'manual_punches_revision';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id',
        'punch_date',
        'punch_in',
        'punch_out',
        'remarks',
        'created_by',
        'revised_by'
    ];
}
