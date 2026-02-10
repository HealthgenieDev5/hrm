<?php

namespace App\Commands;

use App\Controllers\Cron\FinalSalary;
use App\Models\EmployeeModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ProcessSalary extends BaseCommand
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
    protected $name = 'salary:process';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Processes Salary in batches using CLI';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'salary:process';

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
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        helper(['url', 'form', 'Form_helper', 'Config_defaults_helper']);
        $EmployeeModel = new EmployeeModel();
        $allEmployees = $EmployeeModel->where('id=', 587)->findAll();
        // $allEmployees = $EmployeeModel->findAll();

        $employees = array();

        if (!empty($allEmployees)) {
            foreach ($allEmployees as $e_data) {
                if ($e_data['status'] != 'active') {
                    if (!empty($e_data['date_of_leaving'])) {

                        if (date('Y-m', strtotime($e_data['date_of_leaving'])) == date('Y-m', strtotime(first_date_of_last_month()))) {
                            $employees[] = $e_data;
                        }
                    } else {
                        $employees[] = $e_data;
                    }
                } else {
                    $employees[] = $e_data;
                }
            }
        }

        $chunkSize   = $options['chunk'] ?? 25;
        $chunks = array_chunk($employees, $chunkSize);

        CLI::write("Starting salary processing", 'yellow');
        CLI::write("Chunk Size : {$chunkSize}", 'light_gray');

        foreach ($chunks as $i => $group) {
            CLI::write("Processing batch #" . ($i + 1), 'blue');
            foreach ($group as $employee_row) {
                $start = microtime(true);
                CLI::write("→ Employee ID: {$employee_row['id']} - Processing...", 'white');

                $FinalSalary = new FinalSalary();
                ob_start();
                $FinalSalary->calculateSalary($employee_row['id'], date('Y-m', strtotime(first_date_of_last_month())));

                $response = ob_get_clean();
                $response = json_decode($response, true);

                $id          = $employee_row['id'];
                $name        = trim($employee_row['first_name'] . ' ' . $employee_row['last_name']);
                $internalId  = $employee_row['internal_employee_id'];
                $status      = $employee_row['status'];
                $respCode    = $response['response'];
                $desc        = $response['description'];

                $errorNote = '';
                if ($respCode === 'failed' && $status === 'active') {
                    $errorNote = 'Check error on this line';
                }

                CLI::write("-------------------------------------------------------------");

                CLI::write("ID:            $id");
                CLI::write("Name:          $name");
                CLI::write("Internal ID:   $internalId");
                CLI::write("Status:        $status");
                CLI::write("Response:      $respCode");
                CLI::write("Description:   $desc");

                if ($errorNote) {
                    CLI::write("⚠️  Error:      $errorNote", 'red');
                }
                $end = microtime(true);
                $executionTime = $end - $start;
                CLI::write("→ Done in " . round($executionTime, 4) . " seconds", 'white');

                CLI::write("-------------------------------------------------------------\n");
            }
            CLI::write("Completed batch #" . ($i + 1), 'light_gray');
        }

        CLI::write("Done processing.", 'green');
    }
}
