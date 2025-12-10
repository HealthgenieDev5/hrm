<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\RawPunchingDataModel;
use App\Models\EmployeeModel;

/**
 * Fetch Raw Attendance from API and Save to Database
 *
 * This command fetches raw punching data from the Laravel Attendance API
 * and saves it to the raw_attendance table in HRM database.
 *
 * Usage:
 *   php spark fetch:raw-attendance
 *   php spark fetch:raw-attendance --employee=HG001
 *   php spark fetch:raw-attendance --from=2025-11-01 --to=2025-11-30
 *   php spark fetch:raw-attendance --month=2025-11
 */
class FetchRawAttendance extends BaseCommand
{
    protected $group       = 'Attendance';
    protected $name        = 'fetch:raw-attendance';
    protected $description = 'Fetch raw attendance data from API and save to database';

    protected $usage = 'fetch:raw-attendance [options]';
    protected $arguments = [];
    protected $options = [
        'employee' => 'Employee code (e.g., HG001). Default: ALL',
        'from'     => 'Start date (Y-m-d format). Default: first day of current month',
        'to'       => 'End date (Y-m-d format). Default: today',
        'month'    => 'Month (Y-m format, e.g., 2025-11). Overrides --from and --to',
    ];

    public function run(array $params)
    {
        // Load helper
        helper('config_defaults');

        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::write('  Fetching Raw Attendance from API', 'yellow');
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::newLine();

        // Check if API is enabled
        $apiEnabled = filter_var(getenv('USE_ATTENDANCE_API'), FILTER_VALIDATE_BOOLEAN);
        if (!$apiEnabled) {
            CLI::error('Attendance API is not enabled in .env');
            CLI::write('Set USE_ATTENDANCE_API=true to enable', 'yellow');
            return EXIT_ERROR;
        }

        // Parse parameters
        $employeeCode = CLI::getOption('employee') ?? 'ALL';
        $month = CLI::getOption('month');

        if ($month) {
            // Month specified, use first and last day of that month
            $fromDate = date('Y-m-01', strtotime($month . '-01'));
            $toDate = date('Y-m-t', strtotime($month . '-01'));
        } else {
            $fromDate = CLI::getOption('from') ?? first_date_of_month();
            $toDate = CLI::getOption('to') ?? current_date_of_month();
        }



        CLI::write('Parameters:', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');
        CLI::write("  Employee: {$employeeCode}", 'white');
        CLI::write("  From Date: {$fromDate}", 'white');
        CLI::write("  To Date: {$toDate}", 'white');
        CLI::newLine();

        // Fetch data from API
        CLI::write('Fetching data from API...', 'cyan');

        try {
            $rawDataJson = get_raw_punching_data($employeeCode, $fromDate, $toDate);


            $data = json_decode($rawDataJson, true);
            $punchingData = $data['InOutPunchData'] ?? [];

            if (empty($punchingData)) {
                CLI::write('✗ No data received from API', 'red');
                CLI::write('  Check if API is running and has data for this period', 'yellow');
                return EXIT_ERROR;
            }

            CLI::write('✓ Received ' . count($punchingData) . ' records from API', 'green');
            CLI::newLine();
        } catch (\Exception $e) {
            CLI::error('✗ Failed to fetch from API: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        // Save to database
        CLI::write('Saving to database...', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');

        $RawPunchingDataModel = new RawPunchingDataModel();
        $savedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        foreach ($punchingData as $dataRow) {
            try {
                $empCode = $dataRow['Empcode'];
                $dateString = $dataRow['DateString_2'];

                // Remove API-specific fields that shouldn't be saved to our database
                unset($dataRow['id']);
                unset($dataRow['created_at']);
                unset($dataRow['updated_at']);

                // Convert empty strings to default values for NOT NULL fields
                if (empty($dataRow['OUTTime'])) {
                    $dataRow['OUTTime'] = '--:--';
                }
                if (empty($dataRow['INTime'])) {
                    $dataRow['INTime'] = '--:--';
                }

                // Check if record already exists
                $existing = $RawPunchingDataModel
                    ->where('Empcode', $empCode)
                    ->where('DateString_2', $dateString)
                    ->first();

                if (!empty($existing)) {
                    // Update existing record
                    $dataRow['id'] = $existing['id'];
                    $saved = $RawPunchingDataModel->save($dataRow);

                    if ($saved) {
                        $updatedCount++;
                        CLI::write("  Updated: {$empCode} - {$dateString}", 'dark_gray');
                    } else {
                        $errorCount++;
                        CLI::write("  Error updating: {$empCode} - {$dateString}", 'red');
                    }
                } else {
                    // Insert new record
                    $saved = $RawPunchingDataModel->save($dataRow);

                    if ($saved) {
                        $savedCount++;
                        CLI::write("  Saved: {$empCode} - {$dateString} | In: {$dataRow['INTime']} Out: {$dataRow['OUTTime']}", 'green');
                    } else {
                        $errorCount++;
                        CLI::write("  Error saving: {$empCode} - {$dateString}", 'red');
                    }
                }
            } catch (\Exception $e) {
                $errorCount++;
                CLI::write("  Error: {$empCode} - {$dateString} | " . $e->getMessage(), 'red');
            }
        }

        CLI::newLine();
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::write('  Summary', 'yellow');
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::write("  Total Records: " . count($punchingData), 'white');
        CLI::write("  New Records Saved: {$savedCount}", 'green');
        CLI::write("  Records Updated: {$updatedCount}", 'cyan');
        CLI::write("  Errors: {$errorCount}", $errorCount > 0 ? 'red' : 'white');
        CLI::newLine();

        if ($errorCount > 0) {
            return EXIT_ERROR;
        }

        return EXIT_SUCCESS;
    }
}
