<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\AttendanceApiClient;

/**
 * Test Attendance API Connection
 *
 * Usage:
 *   php spark test:api
 *   php spark test:api --employee=123 --shift=5 --date=2025-11-17
 */
class TestAttendanceApi extends BaseCommand
{
    protected $group       = 'API';
    protected $name        = 'test:api';
    protected $description = 'Test connection to Laravel Attendance API';

    protected $usage = 'test:api [options]';
    protected $arguments = [];
    protected $options = [
        '--employee' => 'Employee ID to test (optional)',
        '--shift'    => 'Shift ID to test (optional)',
        '--date'     => 'Date to test in Y-m-d format (optional)',
    ];

    public function run(array $params)
    {
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::write('  Testing Attendance API Connection', 'yellow');
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::newLine();

        // Display configuration
        $this->displayConfig();

        // Initialize API client
        try {
            $apiClient = new AttendanceApiClient();
            CLI::write('✓ AttendanceApiClient initialized successfully', 'green');
        } catch (\Exception $e) {
            CLI::error('✗ Failed to initialize API client: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        CLI::newLine();

        // Test 1: Health Check
        $this->testHealthCheck($apiClient);

        // Test 2: Authentication
        $this->testAuthentication($apiClient);

        // Test 3: Process Single Day (if parameters provided)
        $employeeId = CLI::getOption('employee');
        $shiftId = CLI::getOption('shift');
        $date = CLI::getOption('date');

        if ($employeeId && $shiftId && $date) {
            CLI::newLine();
            $this->testProcessSingleDay($apiClient, (int)$employeeId, (int)$shiftId, $date);
        } else {
            CLI::newLine();
            CLI::write('💡 To test processing, run:', 'cyan');
            CLI::write('   php spark test:api --employee=123 --shift=5 --date=2025-11-17', 'white');
        }

        CLI::newLine();
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');
        CLI::write('  Test Complete', 'yellow');
        CLI::write('═══════════════════════════════════════════════════════', 'yellow');

        return EXIT_SUCCESS;
    }

    /**
     * Display current configuration
     */
    private function displayConfig()
    {
        CLI::write('Configuration:', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');

        $config = [
            'USE_ATTENDANCE_API'                => getenv('USE_ATTENDANCE_API') ?: 'false',
            'ATTENDANCE_API_URL'                => getenv('ATTENDANCE_API_URL') ?: 'not set',
            'ATTENDANCE_API_KEY'                => $this->maskKey(getenv('ATTENDANCE_API_KEY')),
            'ATTENDANCE_API_SECRET'             => $this->maskKey(getenv('ATTENDANCE_API_SECRET')),
            'ATTENDANCE_API_TIMEOUT'            => getenv('ATTENDANCE_API_TIMEOUT') ?: '30',
            'ATTENDANCE_API_FALLBACK_TO_LOCAL'  => getenv('ATTENDANCE_API_FALLBACK_TO_LOCAL') ?: 'false',
        ];

        foreach ($config as $key => $value) {
            $color = ($key === 'USE_ATTENDANCE_API' && $value === 'true') ? 'green' : 'white';
            CLI::write("  {$key}: {$value}", $color);
        }

        CLI::newLine();
    }

    /**
     * Test health check endpoint
     */
    private function testHealthCheck($apiClient)
    {
        CLI::write('[1/3] Testing Health Check...', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');

        try {
            $isHealthy = $apiClient->checkHealth();

            if ($isHealthy) {
                CLI::write('✓ API is healthy and responding', 'green');

                // Get detailed status
                $status = $apiClient->getStatus();
                if (isset($status['status'])) {
                    CLI::write('  Status: ' . $status['status'], 'white');
                }
            } else {
                CLI::error('✗ API health check failed');
                CLI::write('  The API may be down or unreachable', 'red');
            }

        } catch (\Exception $e) {
            CLI::error('✗ Health check error: ' . $e->getMessage());
        }
    }

    /**
     * Test authentication
     */
    private function testAuthentication($apiClient)
    {
        CLI::write('[2/3] Testing Authentication...', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');

        try {
            // Use reflection to test authentication
            $reflection = new \ReflectionClass($apiClient);
            $method = $reflection->getMethod('authenticate');
            $method->setAccessible(true);

            $token = $method->invoke($apiClient);

            if (!empty($token)) {
                CLI::write('✓ Authentication successful', 'green');
                CLI::write('  Token: ' . substr($token, 0, 30) . '...', 'white');
            } else {
                CLI::error('✗ Authentication failed: No token received');
            }

        } catch (\Exception $e) {
            CLI::error('✗ Authentication error: ' . $e->getMessage());
            CLI::write('  Please check your API_KEY and API_SECRET in .env', 'yellow');
        }
    }

    /**
     * Test processing a single day
     */
    private function testProcessSingleDay($apiClient, int $employeeId, int $shiftId, string $date)
    {
        CLI::write('[3/3] Testing Process Single Day...', 'cyan');
        CLI::write('─────────────────────────────────────────────────────', 'dark_gray');
        CLI::write("  Employee ID: {$employeeId}", 'white');
        CLI::write("  Shift ID: {$shiftId}", 'white');
        CLI::write("  Date: {$date}", 'white');
        CLI::newLine();

        try {
            $result = $apiClient->processSingleDay($employeeId, $shiftId, $date);

            if ($result === null) {
                CLI::write('ℹ No punch data found for this employee on this date', 'yellow');
                CLI::write('  (This may indicate the employee was absent)', 'dark_gray');
            } else {
                CLI::write('✓ Attendance data received successfully', 'green');
                CLI::newLine();

                // Display key fields
                CLI::write('Response Data:', 'cyan');
                CLI::write('─────────────────────────────────────────────────────', 'dark_gray');

                $displayFields = [
                    'employee_id' => 'Employee ID',
                    'date' => 'Date',
                    'punch_in_time' => 'Punch In',
                    'punch_out_time' => 'Punch Out',
                    'work_hours_adjusted' => 'Work Hours (Adjusted)',
                    'work_minutes_adjusted' => 'Work Minutes (Adjusted)',
                    'is_present' => 'Is Present',
                    'is_absent' => 'Is Absent',
                    'late_coming_minutes' => 'Late Coming (min)',
                    'early_going_minutes' => 'Early Going (min)',
                    'reduction_applied' => 'Reduction Applied',
                    'reduction_percentage' => 'Reduction %',
                    'shift_type' => 'Shift Type',
                ];

                foreach ($displayFields as $field => $label) {
                    if (isset($result[$field])) {
                        $value = is_bool($result[$field])
                            ? ($result[$field] ? 'true' : 'false')
                            : $result[$field];
                        CLI::write("  {$label}: {$value}", 'white');
                    }
                }

                // Show all fields count
                CLI::newLine();
                CLI::write('Total fields received: ' . count($result), 'dark_gray');
            }

        } catch (\Exception $e) {
            CLI::error('✗ Processing error: ' . $e->getMessage());

            if (getenv('ATTENDANCE_API_FALLBACK_TO_LOCAL') === 'true') {
                CLI::write('  Fallback to local processing is enabled', 'yellow');
                CLI::write('  The system will use local calculation if API fails', 'yellow');
            }
        }
    }

    /**
     * Mask API key/secret for display
     */
    private function maskKey(?string $key): string
    {
        if (empty($key)) {
            return 'not set';
        }

        if (strlen($key) <= 8) {
            return str_repeat('*', strlen($key));
        }

        return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
    }
}
