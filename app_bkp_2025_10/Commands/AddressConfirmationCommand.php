<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Models\AddressScheduleModel;

class AddressConfirmationCommand extends BaseCommand
{
    protected $group = 'Address';
    protected $name = 'address:check-due';

    public function run(array $params)
    {
        $scheduleModel = new AddressScheduleModel();

        // Activate popups for employees with due confirmations
        $dueEmployees = $scheduleModel->getEmployeesNeedingConfirmation();

        foreach ($dueEmployees as $employee) {
            $scheduleModel->update($employee['id'], ['is_popup_active' => true]);
        }

        // Reactivate snoozed popups after 1 day
        $scheduleModel->where('last_snoozed_date <=', date('Y-m-d H:i:s', strtotime('-1 day')))
            ->where('is_popup_active', false)
            ->set(['is_popup_active' => true])
            ->update();

        $this->write('Address confirmation check completed');
    }
}
