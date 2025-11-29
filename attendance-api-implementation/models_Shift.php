<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'shift_code',
        'shift_name',
        'shift_start',
        'shift_end',
        'shift_type',
        'reduction_percentage',
        'effective_from_date',
        'half_day_threshold_minutes',
        'absent_threshold_minutes',
        'is_active',
    ];

    protected $casts = [
        'shift_start' => 'datetime:H:i:s',
        'shift_end' => 'datetime:H:i:s',
        'reduction_percentage' => 'decimal:2',
        'effective_from_date' => 'date',
        'half_day_threshold_minutes' => 'integer',
        'absent_threshold_minutes' => 'integer',
        'is_active' => 'boolean',
    ];
}
