<?php

namespace App\Commands;

use App\Controllers\Cron\BirthdayAnniversaryNotifications;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BirthdayAnniversaryCron extends BaseCommand
{
    protected $group = 'Cron';
    protected $name = 'cron:birthday-anniversary';
    protected $description = 'Create birthday and work anniversary notifications';
    protected $usage = 'cron:birthday-anniversary [options]';
    protected $options = [
        '--month' => 'Generate notifications for the entire month (optionally specify YYYY-MM format, e.g., --month=2025-11)',
    ];

    public function run(array $params)
    {
        $yearMonth = null;
        $isMonthlyMode = false;

        if (isset($params['month'])) {
            $isMonthlyMode = true;
            $yearMonth = $params['month'] ?: null;
        }

        foreach (array_keys($params) as $key) {
            if (strpos($key, 'month=') === 0) {
                $isMonthlyMode = true;
                $yearMonth = substr($key, strlen('month='));
                break;
            } elseif ($key === 'month') {
                $isMonthlyMode = true;
                break;
            }
        }

        if ($isMonthlyMode) {
            return $this->runMonthly($yearMonth);
        } else {
            return $this->runDaily();
        }
    }

    private function runDaily()
    {
        CLI::write('Starting Birthday & Anniversary notification creation (DAILY)...', 'yellow');
        CLI::newLine();

        try {
            $controller = new BirthdayAnniversaryNotifications();
            $result = $controller->createDailyNotifications();

            CLI::write('Process completed successfully!', 'green');
            CLI::write('Date: ' . $result['date'], 'white');
            CLI::newLine();

            if ($result['message'] ?? null === 'Already exists') {
                CLI::write('⚠ Notifications for today already exist', 'yellow');
                CLI::newLine();
                return EXIT_SUCCESS;
            }

            if ($result['birthdays_created'] > 0) {
                CLI::write('🎂 BIRTHDAYS:', 'cyan');
                CLI::write('   Employees celebrating: ' . $result['birthday_employees'], 'white');
                CLI::write('   Notifications created: ' . $result['birthdays_created'], 'green');
            } else {
                CLI::write('🎂 BIRTHDAYS: None today', 'white');
            }
            CLI::newLine();

            if ($result['anniversaries_created'] > 0) {
                CLI::write('🎉 WORK ANNIVERSARIES:', 'cyan');
                CLI::write('   Employees celebrating: ' . $result['anniversary_employees'], 'white');
                CLI::write('   Notifications created: ' . $result['anniversaries_created'], 'green');
            } else {
                CLI::write('🎉 WORK ANNIVERSARIES: None today', 'white');
            }
            CLI::newLine();

            $total = $result['birthdays_created'] + $result['anniversaries_created'];
            if ($total > 0) {
                CLI::write('✓ ' . $total . ' notification(s) created successfully', 'green');
                CLI::write('All employees will see these notifications on their dashboard', 'white');
            } else {
                CLI::write('No birthdays or anniversaries today', 'white');
            }
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            CLI::write('Stack trace:', 'red');
            CLI::write($e->getTraceAsString(), 'white');
            return EXIT_ERROR;
        }

        CLI::newLine();
        return EXIT_SUCCESS;
    }

    /**
     * Run monthly notification creation
     *
     * @param string|null $yearMonth Year-month in YYYY-MM format (e.g., "2025-11"), or null for current month
     */
    private function runMonthly($yearMonth = null)
    {
        $monthText = $yearMonth ? $yearMonth : 'current month';
        CLI::write('Starting Birthday & Anniversary notification creation (MONTHLY)...', 'yellow');
        CLI::write("This will create notifications for ALL days in {$monthText}", 'white');
        CLI::newLine();

        try {
            $controller = new BirthdayAnniversaryNotifications();
            $result = $controller->createMonthlyNotifications($yearMonth);
            CLI::write('Process completed successfully!', 'green');
            CLI::write('Month: ' . $result['month'], 'white');
            CLI::write('Days processed: ' . $result['days_processed'], 'white');
            CLI::newLine();

            // Birthday summary
            if ($result['birthdays_created'] > 0) {
                CLI::write('🎂 BIRTHDAYS:', 'cyan');
                CLI::write('   Total employees celebrating this month: ' . $result['total_birthday_employees'], 'white');
                CLI::write('   Notifications created: ' . $result['birthdays_created'], 'green');
            } else {
                CLI::write('🎂 BIRTHDAYS: None this month', 'white');
            }
            CLI::newLine();

            // Anniversary summary
            if ($result['anniversaries_created'] > 0) {
                CLI::write('🎉  ANNIVERSARIES:', 'cyan');
                CLI::write('   Total employees celebrating this month: ' . $result['total_anniversary_employees'], 'white');
                CLI::write('   Notifications created: ' . $result['anniversaries_created'], 'green');
            } else {
                CLI::write('🎉  ANNIVERSARIES: None this month', 'white');
            }
            CLI::newLine();

            // Overall summary
            $total = $result['birthdays_created'] + $result['anniversaries_created'];
            if ($total > 0) {
                CLI::write('✓ ' . $total . ' notification(s) created for the entire month', 'green');
                CLI::write('Employees will see these notifications on their respective dates', 'white');
            } else {
                CLI::write('No birthdays or anniversaries this month', 'white');
            }
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            CLI::write('Stack trace:', 'red');
            CLI::write($e->getTraceAsString(), 'white');
            return EXIT_ERROR;
        }

        CLI::newLine();
        return EXIT_SUCCESS;
    }
}
