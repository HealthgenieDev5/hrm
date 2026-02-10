<?php
namespace App\Models;
use CodeIgniter\Model;


class ManualPunchModel extends Model
{
    protected $table = 'manual_punches';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id', 'punch_date', 'punch_in', 'punch_out', 'remarks', 'created_by'
    ];
}

