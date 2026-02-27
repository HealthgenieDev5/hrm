<?php

namespace App\Services;

use App\Models\ResignationModel;
use App\Models\EmployeeModel;

class ResignationAutoCompleteService
{
    /**
     * Automatically complete overdue resignations
     * This runs silently in the background when dashboard is accessed
     *
     * @return array Statistics about what was completed
     */
    public static function autoCompleteOverdue()
    {
        $ResignationModel = new ResignationModel();
        $EmployeeModel = new EmployeeModel();
        $db = db_connect();

        // Find all active resignations that have passed their last working day
        $builder = $db->table('resignations r');
        $builder->select("
            r.id as resignation_id,
            r.employee_id,
            r.resignation_date,
            r.buyout_days,
            e.notice_period,
            DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY) as calculated_last_working_day
        ");
        $builder->join('employees e', 'e.id = r.employee_id', 'left');
        $builder->where('r.status', 'active');
        $builder->where('DATE_ADD(r.resignation_date, INTERVAL (e.notice_period - COALESCE(r.buyout_days, 0)) DAY) <', date('Y-m-d'));

        $overdueResignations = $builder->get()->getResultArray();

        if (empty($overdueResignations)) {
            return [
                'success' => true,
                'completed' => 0,
                'failed' => 0,
                'message' => 'No overdue resignations'
            ];
        }

        $completed = 0;
        $failed = 0;

        foreach ($overdueResignations as $resignation) {
            try {
                $db->transStart();

                // Update resignation status
                $ResignationModel->update($resignation['resignation_id'], ['status' => 'completed']);

                // Update employee date_of_leaving
                $EmployeeModel->update($resignation['employee_id'], [
                    'date_of_leaving' => $resignation['calculated_last_working_day']
                ]);

                // Complete transaction
                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                $completed++;

            } catch (\Exception $e) {
                log_message('error', 'Failed to auto-complete resignation ID ' . $resignation['resignation_id'] . ': ' . $e->getMessage());
                $failed++;
            }
        }

        return [
            'success' => true,
            'completed' => $completed,
            'failed' => $failed,
            'message' => "Auto-completed {$completed} resignation(s)" . ($failed > 0 ? ", {$failed} failed" : '')
        ];
    }
}
