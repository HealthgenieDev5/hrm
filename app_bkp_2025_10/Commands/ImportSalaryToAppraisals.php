<?php

namespace App\Commands;

use App\Controllers\AppraisalsController;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\SalaryModel;
use App\Models\AppraisalsModel;

class ImportSalaryToAppraisals extends BaseCommand
{
    protected $group = 'Import';
    protected $name = 'import:salary-to-appraisals';
    protected $description = 'Import all salary data to appraisals table with same calculation logic as create appraisal';
    protected $usage = 'import:salary-to-appraisals [arguments] [options]';
    protected $arguments = [];
    protected $options = [
        '--code'  => 'Specific employee Codes to process only that user'
    ];


    // public function run(array $params)
    // {
    //     // Use named options manually from $params
    //     $options = [];

    //     foreach ($params as $param => $v) {
    //         if (strpos($param, '=') !== false) {
    //             [$key, $value] = explode('=', $param, 2);
    //             $options[$key] = $value;
    //         }
    //     }

    //     $employeeCodes = isset($options['code']) ? explode(',', $options['code']) : null;



    //     $salaryModel = new SalaryModel();
    //     $appraisalsModel = new AppraisalsModel();
    //     CLI::write('Starting salary data import to appraisals with calculation logic...', 'green');

    //     if ($employeeCodes) {
    //         $salaryRecords = $salaryModel
    //             ->select('employee_salary.*')
    //             ->select('employees.internal_employee_id as employee_code')
    //             ->whereIn('employees.internal_employee_id', $employeeCodes)
    //             ->join('employees', 'employees.id=employee_salary.employee_id', 'Left')

    //             ->findAll();



    //         CLI::write("Processing salary data for employee Codes: {$options['code']}", 'blue');
    //     } else {
    //         $salaryRecords = $salaryModel->findAll();
    //         CLI::write("Processing all salary records: " . count($salaryRecords) . " records found", 'blue');
    //     }

    //     if (empty($salaryRecords)) {
    //         dd($salaryModel->getLastQuery()->getQuery());
    //         CLI::write('No salary records found!', 'red');
    //         return;
    //     }

    //     $successCount = 0;
    //     $errorCount = 0;

    //     foreach ($salaryRecords as $salary) {
    //         try {
    //             CLI::write("Processing Employee Code: {$salary['employee_code']}", 'yellow');

    //             // Check if appraisal already exists
    //             $existingAppraisal = $appraisalsModel->where('employee_id', $salary['employee_id'])->first();
    //             if ($existingAppraisal) {
    //                 CLI::write("Appraisal already exists for Employee Code: {$salary['employee_code']}, skipping...", 'yellow');
    //                 continue;
    //             }

    //             // Use the same logic as AppraisalsController::prepareData
    //             // $data = $this->prepareAppraisalDataFromSalary($salary);
    //             $allowedKeys  = [
    //                 'employee_id',
    //                 'basic_salary',
    //                 'house_rent_allowance',
    //                 'conveyance',
    //                 'medical_allowance',
    //                 'special_allowance',
    //                 'fuel_allowance',
    //                 'vacation_allowance',
    //                 'other_allowance',
    //                 'enable_bonus',
    //                 'pf',
    //                 'esi',
    //                 'lwf',
    //                 'non_compete_loan',
    //                 'non_compete_loan_amount_per_month',
    //                 'non_compete_loan_from',
    //                 'loyalty_incentive',
    //                 'loyalty_incentive_amount_per_month',
    //                 'loyalty_incentive_from'
    //             ];
    //             $filteredData = array_intersect_key($salary, array_flip($allowedKeys));
    //             // dd($filteredData);

    //             $AppraisalsController = new AppraisalsController();
    //             $preparedData = $AppraisalsController->prepareData($filteredData);

    //             $preparedData['appraisal_remarks'] = 'Imported from employee_salary table';
    //             $preparedData['appraisal_date'] = date('Y-m-d');
    //             //print_r($preparedData);
    //             // Insert appraisal
    //             $appraisalId = $appraisalsModel->insert($preparedData);
    //             if (!$appraisalId) {
    //                 CLI::write("Failed to create appraisal for Employee Code: {$salary['employee_code']}", 'red');
    //                 print_r($appraisalsModel->errors()); // CI4 validation errors
    //                 print_r($preparedData);              // the data you tried to insert
    //                 $errorCount++;
    //                 continue;
    //             }

    //             CLI::write("Successfully created appraisal for Employee Code: {$salary['employee_code']} (ID: {$appraisalId})", 'green');
    //             $successCount++;
    //         } catch (\Exception $e) {
    //             CLI::write("Error processing Employee Code {$salary['employee_code']}: " . $e->getMessage(), 'red');
    //             $errorCount++;
    //         }
    //     }

    //     CLI::write("\n" . str_repeat('=', 50), 'blue');
    //     CLI::write("Import Summary:", 'blue');
    //     CLI::write("Successfully imported: {$successCount} records", 'green');
    //     CLI::write("Errors encountered: {$errorCount} records", 'red');
    //     CLI::write("Total processed: " . ($successCount + $errorCount) . " records", 'blue');
    //     CLI::write(str_repeat('=', 50), 'blue');
    // }


    public function run(array $params)
    {
        // Parse CLI option properly (don’t hand-roll)
        $codeOpt = CLI::getOption('code'); // supports --code=E001,E002
        $employeeCodes = $codeOpt ? explode(',', $codeOpt) : null;

        $salaryModel      = new SalaryModel();
        $appraisalsModel  = new AppraisalsModel();

        CLI::write('Starting salary data import to appraisals with calculation logic...', 'green');

        // Build one query that always joins employees & selects employee_code
        $query = $salaryModel->asArray()
            ->select('employee_salary.*')
            ->select('employees.internal_employee_id as employee_code')
            ->join('employees', 'employees.id = employee_salary.employee_id', 'left');

        if ($employeeCodes) {
            $query->whereIn('employees.internal_employee_id', $employeeCodes);
            CLI::write("Processing salary data for employee Codes: {$codeOpt}", 'blue');
        }

        $salaryRecords = $query->findAll();

        CLI::write("Processing " . count($salaryRecords) . " salary record(s)", 'blue');

        if (empty($salaryRecords)) {
            // Optional: debug last query
            // dd($salaryModel->getLastQuery()->getQuery());
            CLI::write('No salary records found!', 'red');
            return;
        }

        $successCount = 0;
        $errorCount   = 0;

        foreach ($salaryRecords as $salary) {
            try {
                // Safe label even if alias is missing for any reason
                $empLabel = $salary['employee_code'] ?? ('EID:' . $salary['employee_id']);
                CLI::write("Processing Employee: {$empLabel}", 'yellow');

                // Skip if appraisal already exists
                $existingAppraisal = $appraisalsModel
                    ->where('employee_id', $salary['employee_id'])
                    ->first();

                if ($existingAppraisal) {
                    CLI::write("Appraisal already exists for {$empLabel}, skipping...", 'yellow');
                    continue;
                }

                // Filter allowed keys
                $allowedKeys = [
                    'employee_id',
                    'basic_salary',
                    'house_rent_allowance',
                    'conveyance',
                    'medical_allowance',
                    'special_allowance',
                    'fuel_allowance',
                    'vacation_allowance',
                    'other_allowance',
                    'enable_bonus',
                    'pf',
                    'esi',
                    'lwf',
                    'non_compete_loan',
                    'non_compete_loan_amount_per_month',
                    'non_compete_loan_from',
                    'loyalty_incentive',
                    'loyalty_incentive_amount_per_month',
                    'loyalty_incentive_from',
                ];
                $filteredData = array_intersect_key($salary, array_flip($allowedKeys));

                // Prepare data using controller logic
                $AppraisalsController = new AppraisalsController();
                $preparedData = $AppraisalsController->prepareData($filteredData);

                $preparedData['appraisal_remarks'] = 'Imported from employee_salary table';
                $preparedData['appraisal_date']    = date('Y-m-d');

                $appraisalId = $appraisalsModel->insert($preparedData);
                if (!$appraisalId) {
                    CLI::write("Failed to create appraisal for {$empLabel}", 'red');
                    print_r($appraisalsModel->errors());
                    print_r($preparedData);
                    $errorCount++;
                    continue;
                }

                CLI::write("Created appraisal for {$empLabel} (ID: {$appraisalId})", 'green');
                $successCount++;
            } catch (\Throwable $e) {
                $empLabel = $salary['employee_code'] ?? ('EID:' . ($salary['employee_id'] ?? 'unknown'));
                CLI::write("Error processing {$empLabel}: " . $e->getMessage(), 'red');
                $errorCount++;
            }
        }

        CLI::write("\n" . str_repeat('=', 50), 'blue');
        CLI::write("Import Summary:", 'blue');
        CLI::write("Successfully imported: {$successCount} records", 'green');
        CLI::write("Errors encountered: {$errorCount} records", 'red');
        CLI::write("Total processed: " . ($successCount + $errorCount) . " records", 'blue');
        CLI::write(str_repeat('=', 50), 'blue');
    }
}
