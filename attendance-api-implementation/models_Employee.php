<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'name',
        'emp_code',
        'joining_date',
        'exit_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'exit_date' => 'date',
    ];
}
