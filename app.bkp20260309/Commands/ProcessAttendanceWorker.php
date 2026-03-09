<?php

namespace App\Commands;

use App\Libraries\AttendanceProcessor;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessAttendanceWorker extends BaseCommand
{
    protected $group = 'App';

    protected $name = 'attendance:worker';

    protected $description = 'Worker process for parallel attendance processing';

    protected $usage = 'attendance:worker [options]';

    protected $options = [
        '--month' => 'Target month for processing (e.g. 2024-05)',
        '--employee-ids' => 'Comma-separated employee IDs to process',
        '--worker-id' => 'Worker identifier for logging',
    ];

    public function run(array $params)
    {
        $options = $this->parseOptions($params);

        $workerId = $options['worker-id'] ?? 'unknown';
        $month = $options['month'] ?? null;
        $employeeIds = isset($options['employee-ids']) ? explode(',', $options['employee-ids']) : null;

        if (empty($employeeIds)) {
            CLI::error("Worker #{$workerId}: No employee IDs provided");

            return;
        }

        CLI::write("Worker #{$workerId}: Starting with ".count($employeeIds).' employees', 'green');

        $processor = new AttendanceProcessor;
        $processor->processAll(count($employeeIds), $month, $employeeIds);

        CLI::write("Worker #{$workerId}: Completed processing", 'green');
    }

    private function parseOptions(array $params): array
    {
        $options = [];
        foreach ($params as $param => $v) {
            if (strpos($param, '=') !== false) {
                [$key, $value] = explode('=', $param, 2);
                $options[$key] = $value;
            }
        }

        return $options;
    }
}
