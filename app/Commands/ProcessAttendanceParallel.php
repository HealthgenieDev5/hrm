<?php

namespace App\Commands;

use App\Models\EmployeeModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessAttendanceParallel extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'attendance:parallel';
    protected $description = 'Spawns multiple workers to process attendance in parallel';
    protected $usage = 'attendance:parallel [options]';

    protected $options = [
        '--workers' => 'Number of parallel workers (default: 10)',
        '--month' => 'Target month for processing (e.g. 2024-05)',
        '--employee' => 'Specific employee IDs (comma-separated) or "all"',
    ];

    public function run(array $params)
    {
        // Load required helpers
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $options = $this->parseOptions($params);

        $numWorkers = (int)($options['workers'] ?? 10);
        $month = $options['month'] ?? date('Y-m', strtotime(date('Y-m-01') . ' -1 month'));
        $employeeFilter = isset($options['employee']) ? explode(',', $options['employee']) : null;

        CLI::write("=== Parallel Attendance Processing ===", 'yellow');
        CLI::write("Workers: {$numWorkers}", 'light_gray');
        CLI::write("Month: {$month}", 'light_gray');
        CLI::write("Starting at: " . date('Y-m-d H:i:s'), 'light_gray');
        CLI::newLine();

        // Get employee list
        $employees = $this->getEmployeeList($month, $employeeFilter);
        $totalEmployees = count($employees);

        if ($totalEmployees === 0) {
            CLI::error("No employees found to process");
            return;
        }

        CLI::write("Total employees to process: {$totalEmployees}", 'cyan');

        // Pull fresh attendance data once for all employees
        CLI::write("\nPulling fresh attendance from eTimeOffice...", 'blue');
        $from = date('Y-m-01', strtotime($month));
        $to = date('Y-m-t', strtotime($month));
        save_raw_punching_data('ALL', $from, $to);
        CLI::write("Done pulling attendance data\n", 'blue');

        // Split employees into batches for workers
        $batches = $this->splitIntoBatches($employees, $numWorkers);

        CLI::write("Spawning {$numWorkers} workers...\n", 'yellow');

        // Spawn workers
        $processes = [];
        foreach ($batches as $workerId => $batch) {
            if (empty($batch)) continue;

            $employeeIds = implode(',', array_column($batch, 'id'));
            $logFile = WRITEPATH . "logs/worker_{$workerId}_{$month}.log";

            $command = $this->buildWorkerCommand($workerId, $month, $employeeIds, $logFile);

            CLI::write("Worker #{$workerId}: Processing " . count($batch) . " employees", 'green');

            // Spawn process in background
            $process = $this->spawnWorker($command);
            $processes[$workerId] = [
                'process' => $process,
                'logFile' => $logFile,
                'count' => count($batch)
            ];
        }

        CLI::newLine();
        CLI::write("All workers spawned. Waiting for completion...", 'yellow');
        CLI::write("You can monitor progress in: " . WRITEPATH . "logs/worker_*.log", 'light_gray');
        CLI::newLine();

        // Wait for all processes to complete
        $this->waitForWorkers($processes);

        CLI::newLine();
        CLI::write("=== Processing Complete ===", 'green');
        CLI::write("Finished at: " . date('Y-m-d H:i:s'), 'light_gray');
    }

    private function getEmployeeList(string $month, ?array $employeeFilter): array
    {
        $from = date('Y-m-01', strtotime($month));

        $EmployeeModel = new EmployeeModel();
        $EmployeeModel->select('employees.id, employees.first_name, employees.last_name, employees.internal_employee_id');

        $EmployeeModel->groupStart();
        $EmployeeModel->where('employees.date_of_leaving is null');
        $EmployeeModel->orWhere("employees.date_of_leaving >= ('{$from}')");
        $EmployeeModel->groupEnd();

        if (!empty($employeeFilter) && !in_array('all', array_map('strtolower', $employeeFilter))) {
            $EmployeeModel->whereIn('employees.id', $employeeFilter);
        }

        return $EmployeeModel->findAll();
    }

    private function splitIntoBatches(array $employees, int $numWorkers): array
    {
        $batches = array_fill(0, $numWorkers, []);
        $totalEmployees = count($employees);
        $batchSize = ceil($totalEmployees / $numWorkers);

        foreach ($employees as $index => $employee) {
            $workerIndex = floor($index / $batchSize);
            $batches[$workerIndex][] = $employee;
        }

        return array_filter($batches); // Remove empty batches
    }

    private function buildWorkerCommand(int $workerId, string $month, string $employeeIds, string $logFile): string
    {
        $phpBinary = PHP_BINARY;
        $sparkPath = FCPATH . '../spark';

        // Windows-compatible command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return "start /B cmd /C \"{$phpBinary} {$sparkPath} attendance:worker --worker-id={$workerId} --month={$month} --employee-ids={$employeeIds} > {$logFile} 2>&1\"";
        } else {
            // Linux/Mac
            return "{$phpBinary} {$sparkPath} attendance:worker --worker-id={$workerId} --month={$month} --employee-ids={$employeeIds} > {$logFile} 2>&1 &";
        }
    }

    private function spawnWorker(string $command)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            pclose(popen($command, 'r'));
            return true;
        } else {
            // Linux/Mac
            exec($command);
            return true;
        }
    }

    private function waitForWorkers(array $processes): void
    {
        $completed = 0;
        $total = count($processes);

        while ($completed < $total) {
            sleep(2);

            $currentCompleted = 0;
            foreach ($processes as $workerId => $info) {
                if (file_exists($info['logFile'])) {
                    $content = file_get_contents($info['logFile']);
                    if (strpos($content, 'Completed processing') !== false) {
                        $currentCompleted++;
                    }
                }
            }

            if ($currentCompleted > $completed) {
                $completed = $currentCompleted;
                CLI::write("Progress: {$completed}/{$total} workers completed", 'cyan');
            }
        }
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
