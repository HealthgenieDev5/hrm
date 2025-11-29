<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawAttendance extends Model
{
    use HasFactory;

    protected $table = 'raw_attendance';
    public $timestamps = true;

    protected $fillable = [
        'Empcode',
        'INTime',
        'OUTTime',
        'DateString',
        'DateString_2',
        'Remark',
        'machine',
        'default_machine',
        'override_machine',
    ];

    protected $casts = [
        'INTime' => 'datetime:H:i:s',
        'OUTTime' => 'datetime:H:i:s',
        'DateString_2' => 'date',
    ];

    /**
     * Get punching data for employee on specific date
     */
    public static function getPunchingData(string $employeeCode, string $date): ?self
    {
        return self::where('Empcode', $employeeCode)
            ->where('DateString_2', $date)
            ->first();
    }
}
