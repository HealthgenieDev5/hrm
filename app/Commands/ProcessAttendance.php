<?php

namespace App\Commands;

use App\Libraries\AttendanceProcessor;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessAttendance extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'attendance:process';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Processes attendance in batches using CLI';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'attendance:process [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--chunk' => 'Chunk size per batch (default: 25)',
        '--month' => 'Target month for processing (e.g. 2024-05)',
        '--employee' => 'Specific employee ID to process only that user',
    ];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        // Use named options manually from $params
        $options = [];

        foreach ($params as $param => $v) {
            if (strpos($param, '=') !== false) {
                [$key, $value] = explode('=', $param, 2);
                $options[$key] = $value;
            }
        }
        // dd($options);
        $first_date_of_current_month = date('Y-m-01');
        $first_date_of_last_month = date('Y-m-01', strtotime($first_date_of_current_month.'-1 days'));

        // print_r($first_date_of_last_month);
        // die();

        $chunkSize = $options['chunk'] ?? 25;
        $month = $options['month'] ?? date('Y-m', strtotime($first_date_of_last_month));
        $employeeIds = isset($options['employee']) ? explode(',', $options['employee']) : null;

        // dd([
        //     'chunkSize' => $chunkSize,
        //     'month' => $month,
        //     'employeeIds' => $employeeIds
        // ]);

        CLI::write('Starting employee processing', 'yellow');
        CLI::write("Chunk Size : {$chunkSize}", 'light_gray');
        CLI::write("Month      : {$month}", 'light_gray');
        CLI::write('Employee ID: '.($employeeId ?? 'All'), 'light_gray');

        $processor = new AttendanceProcessor;
        $processor->processAll((int) $chunkSize, $month, $employeeIds);
        sleep(1);

        CLI::write('Done processing.', 'green');
    }
}
