<?php
namespace App\Models;

use CodeIgniter\Model;

class AddressScheduleModel extends Model
{
    protected $table = 'address_confirmation_schedule';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employee_id',
		'next_confirmation_date',
		'last_snoozed_date',
        'snooze_count',
		'is_popup_active'
    ];

    public function getEmployeesNeedingConfirmation()
    {
        return $this->where('next_confirmation_date <=', date('Y-m-d'))
                   ->where('is_popup_active', false)
                   ->findAll();
    }

    public function snoozeConfirmation($employeeId)
    {
        $this->where('employee_id', $employeeId)
             ->set([
                 'last_snoozed_date' => date('Y-m-d H:i:s'),
                 'snooze_count' => 'snooze_count + 1',
                 'is_popup_active' => false
             ], false)
             ->update();
    }
}