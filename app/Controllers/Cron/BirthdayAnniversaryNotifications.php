<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Models\Notification\EmployeeNotificationModel;
use App\Models\EmployeeModel;

class BirthdayAnniversaryNotifications extends BaseController
{
    protected $notificationModel;
    protected $employeeModel;

    public function __construct()
    {
        $this->notificationModel = new EmployeeNotificationModel();
        $this->employeeModel = new EmployeeModel();
    }

    /**
     * Create monthly birthday and anniversary notifications
     * This should run once per month (on 1st day of month)
     * Creates notifications for all days in the specified month
     *
     * @param string|null $yearMonth Year-month in YYYY-MM format (e.g., "2025-11"), or null for current month
     * @return array Statistics of notifications created
     */
    public function createMonthlyNotifications($yearMonth = null)
    {
        // Parse year-month parameter or use current month
        if ($yearMonth && preg_match('/^(\d{4})-(\d{2})$/', $yearMonth, $matches)) {
            $currentYear = $matches[1];
            $currentMonth = $matches[2];
        } else {
            $currentYear = date('Y');
            $currentMonth = date('m');
        }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$currentMonth, (int)$currentYear);

        $totalBirthdaysCreated = 0;
        $totalAnniversariesCreated = 0;
        $totalBirthdayEmployees = 0;
        $totalAnniversaryEmployees = 0;

        log_message('info', "Starting monthly birthday/anniversary notification creation for {$currentYear}-{$currentMonth}");

        // Loop through all days in the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%02d', $currentYear, $currentMonth, $day);
            $monthDay = sprintf('%s-%02d', $currentMonth, $day);

            // Get employees with birthdays on this day
            $birthdayEmployees = $this->getEmployeesWithBirthdayToday($monthDay);

            // Get employees with work anniversaries on this day
            $anniversaryEmployees = $this->getEmployeesWithAnniversaryToday($monthDay);

            // Create birthday notification for each employee
            foreach ($birthdayEmployees as $employee) {
                $created = $this->createBirthdayNotification([$employee], $date);
                $totalBirthdaysCreated += $created;
                if ($created > 0) {
                    $totalBirthdayEmployees++;
                }
            }

            // Create anniversary notification for each employee
            foreach ($anniversaryEmployees as $employee) {
                $created = $this->createAnniversaryNotification([$employee], $date, $currentYear);
                $totalAnniversariesCreated += $created;
                if ($created > 0) {
                    $totalAnniversaryEmployees++;
                }
            }
        }

        $message = "Monthly notifications created: {$totalBirthdaysCreated} birthday notifications ({$totalBirthdayEmployees} employees), {$totalAnniversariesCreated} anniversary notifications ({$totalAnniversaryEmployees} employees)";
        log_message('info', $message);
        echo $message . "\n";

        return [
            'month' => $currentYear . '-' . $currentMonth,
            'days_processed' => $daysInMonth,
            'birthdays_created' => $totalBirthdaysCreated,
            'anniversaries_created' => $totalAnniversariesCreated,
            'total_birthday_employees' => $totalBirthdayEmployees,
            'total_anniversary_employees' => $totalAnniversaryEmployees
        ];
    }

    /**
     * Create daily birthday and anniversary notifications
     * This should run once per day at midnight
     *
     * @return array Statistics of notifications created
     */
    public function createDailyNotifications()
    {
        $today = date('Y-m-d');
        $todayMonthDay = date('m-d'); // Format: MM-DD (e.g., 10-25)

        $birthdaysCreated = 0;
        $anniversariesCreated = 0;

        log_message('info', "Starting birthday/anniversary notification creation for {$today}");

        // Get employees with birthdays today
        $birthdayEmployees = $this->getEmployeesWithBirthdayToday($todayMonthDay);

        // Get employees with work anniversaries today
        $anniversaryEmployees = $this->getEmployeesWithAnniversaryToday($todayMonthDay);

        // Create birthday notification for each employee
        foreach ($birthdayEmployees as $employee) {
            $birthdaysCreated += $this->createBirthdayNotification([$employee], $today);
        }

        // Create anniversary notification for each employee
        foreach ($anniversaryEmployees as $employee) {
            $anniversariesCreated += $this->createAnniversaryNotification([$employee], $today);
        }

        $message = "Created {$birthdaysCreated} birthday notification(s) and {$anniversariesCreated} anniversary notification(s) for {$today}";
        log_message('info', $message);
        echo $message . "\n";

        return [
            'date' => $today,
            'birthdays_created' => $birthdaysCreated,
            'anniversaries_created' => $anniversariesCreated,
            'birthday_employees' => count($birthdayEmployees),
            'anniversary_employees' => count($anniversaryEmployees)
        ];
    }

    /**
     * Check if notification already exists for specific employee and date
     *
     * @param string $date Date to check (Y-m-d format)
     * @param string $employeeName Employee full name
     * @param string $type 'Birthday' or 'Wedding Anniversary'
     * @return bool
     */
    private function notificationAlreadyExists($date, $employeeName, $type)
    {
        $existing = $this->notificationModel
            ->where('event_date', $date)
            ->where('notification_type', 'event')
            ->like('title', $type)
            ->like('title', $employeeName)
            ->countAllResults();

        return $existing > 0;
    }

    /**
     * Get employees with birthdays today
     *
     * @param string $monthDay Month and day in MM-DD format
     * @return array
     */
    private function getEmployeesWithBirthdayToday($monthDay)
    {
        $employees = $this->employeeModel
            ->select('employees.id, employees.first_name, employees.last_name, employees.date_of_birth, employees.internal_employee_id, designations.designation_name as designation_name')
            ->join('designations', 'designations.id = employees.designation_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.date_of_birth IS NOT NULL')
            ->findAll();

        $birthdayEmployees = [];

        foreach ($employees as $employee) {
            if (!empty($employee['date_of_birth'])) {
                $birthMonthDay = date('m-d', strtotime($employee['date_of_birth']));
                if ($birthMonthDay === $monthDay) {
                    $birthdayEmployees[] = $employee;
                }
            }
        }

        return $birthdayEmployees;
    }

    /**
     * Get employees with wedding anniversaries today
     *
     * @param string $monthDay Month and day in MM-DD format
     * @return array
     */
    private function getEmployeesWithAnniversaryToday($monthDay)
    {
        $employees = $this->employeeModel
            ->select('employees.id, employees.first_name, employees.last_name, employees.date_of_anniversary, employees.internal_employee_id, designations.designation_name as designation_name')
            ->join('designations', 'designations.id = employees.designation_id', 'left')
            ->where('employees.status', 'active')
            ->where('employees.date_of_anniversary IS NOT NULL')
            ->findAll();

        $anniversaryEmployees = [];

        foreach ($employees as $employee) {
            if (!empty($employee['date_of_anniversary'])) {
                $annivMonthDay = date('m-d', strtotime($employee['date_of_anniversary']));
                if ($annivMonthDay === $monthDay) {
                    $anniversaryEmployees[] = $employee;
                }
            }
        }

        return $anniversaryEmployees;
    }

    /**
     * Create birthday notification
     *
     * @param array $employees Employees with birthdays (should be single employee)
     * @param string $today Today's date
     * @return int Number of notifications created (0 or 2)
     */
    private function createBirthdayNotification($employees, $today)
    {
        if (empty($employees)) {
            return 0;
        }

        $employee = $employees[0];
        $employeeId = $employee['id'];
        $name = trim($employee['first_name'] . ' ' . $employee['last_name']);
        $empCode = !empty($employee['internal_employee_id']) ? $employee['internal_employee_id'] : 'N/A';
        $designation = !empty($employee['designation_name']) ? $employee['designation_name'] : 'N/A';

        // Check if notification already exists
        if ($this->notificationAlreadyExists($today, $name, 'Birthday')) {
            return 0;
        }

        $notificationsCreated = 0;

        // 1. Create notification for the birthday person
        $titleForEmployee = "Happy Birthday!";
        $descriptionForEmployee = $this->buildBirthdayDescriptionForEmployee($name);

        $dataForEmployee = [
            'title' => $titleForEmployee,
            'description' => $descriptionForEmployee,
            'notification_type' => 'event',
            'event_date' => $today,
            'reminder_1_date' => $today,
            'reminder_2_date' => null,
            'reminder_3_date' => null,
            'target_employees' => json_encode([$employeeId]), // Only for the birthday person
            'related_employee_id' => $employeeId,
            'is_active' => 1,
            'created_by' => 385, // System user
        ];

        try {
            $this->notificationModel->insert($dataForEmployee);
            $notificationsCreated++;
        } catch (\Exception $e) {
            log_message('error', "Failed to create birthday notification for employee {$name}: " . $e->getMessage());
        }

        // 2. Create notification for all other employees (excluding birthday person)
        // Get all active employee IDs except the birthday person
        $allEmployeeIds = $this->employeeModel
            ->select('id')
            ->where('status', 'active')
            ->where('id !=', $employeeId)
            ->findColumn('id');

        // Only create notification if there are other employees
        if (!empty($allEmployeeIds)) {
            $title = "Birthday: {$name} ({$empCode} - {$designation})";
            $description = $this->buildBirthdayDescription($name);

            $data = [
                'title' => $title,
                'description' => $description,
                'notification_type' => 'event',
                'event_date' => $today,
                'reminder_1_date' => $today, // Show today
                'reminder_2_date' => null,
                'reminder_3_date' => null,
                'target_employees' => json_encode($allEmployeeIds), // All employees except birthday person
                'related_employee_id' => $employeeId,
                'is_active' => 1,
                'created_by' => 385, // System user
            ];

            try {
                $this->notificationModel->insert($data);
                $notificationsCreated++;
            } catch (\Exception $e) {
                log_message('error', "Failed to create birthday notification for others about {$name}: " . $e->getMessage());
            }
        }

        return $notificationsCreated;
    }

    /**
     * Create wedding anniversary notification
     *
     * @param array $employees Employees with wedding anniversaries
     * @param string $today Today's date
     * @param string|null $yearForCalculation Year to use for anniversary calculation (defaults to current year)
     * @return int Number of notifications created (0 or 2)
     */
    private function createAnniversaryNotification($employees, $today, $yearForCalculation = null)
    {
        if (empty($employees)) {
            return 0;
        }

        $employee = $employees[0];
        $employeeId = $employee['id'];
        $anniversaryDate = $employee['date_of_anniversary'];
        $calculationYear = $yearForCalculation ?? date('Y');
        $years = (int)$calculationYear - date('Y', strtotime($anniversaryDate));
        $name = trim($employee['first_name'] . ' ' . $employee['last_name']);
        $empCode = !empty($employee['internal_employee_id']) ? $employee['internal_employee_id'] : 'N/A';
        $designation = !empty($employee['designation_name']) ? $employee['designation_name'] : 'N/A';

        // Check if notification already exists
        if ($this->notificationAlreadyExists($today, $name, 'Wedding Anniversary')) {
            return 0;
        }

        $notificationsCreated = 0;

        // 1. Create notification for the anniversary person
        $titleForEmployee = "Happy Wedding Anniversary!";
        $descriptionForEmployee = $this->buildAnniversaryDescriptionForEmployee($name, $years);

        $dataForEmployee = [
            'title' => $titleForEmployee,
            'description' => $descriptionForEmployee,
            'notification_type' => 'event',
            'event_date' => $today,
            'reminder_1_date' => $today,
            'reminder_2_date' => null,
            'reminder_3_date' => null,
            'target_employees' => json_encode([$employeeId]), // Only for the anniversary person
            'related_employee_id' => $employeeId,
            'is_active' => 1,
            'created_by' => 385, // System user
        ];

        try {
            $this->notificationModel->insert($dataForEmployee);
            $notificationsCreated++;
        } catch (\Exception $e) {
            log_message('error', "Failed to create anniversary notification for employee {$name}: " . $e->getMessage());
        }

        // 2. Create notification for all other employees (excluding anniversary person)
        // Get all active employee IDs except the anniversary person
        $allEmployeeIds = $this->employeeModel
            ->select('id')
            ->where('status', 'active')
            ->where('id !=', $employeeId)
            ->findColumn('id');

        // Only create notification if there are other employees
        if (!empty($allEmployeeIds)) {
            $yearsSuffix = $years > 0 ? " ({$years} years)" : "";
            $title = "Wedding Anniversary: {$name} ({$empCode} - {$designation}){$yearsSuffix}";
            $description = $this->buildAnniversaryDescription($name, $years);

            $data = [
                'title' => $title,
                'description' => $description,
                'notification_type' => 'event',
                'event_date' => $today,
                'reminder_1_date' => $today, // Show today
                'reminder_2_date' => null,
                'reminder_3_date' => null,
                'target_employees' => json_encode($allEmployeeIds), // All employees except anniversary person
                'related_employee_id' => $employeeId,
                'is_active' => 1,
                'created_by' => 385, // System user
            ];

            try {
                $this->notificationModel->insert($data);
                $notificationsCreated++;
            } catch (\Exception $e) {
                log_message('error', "Failed to create anniversary notification for others about {$name}: " . $e->getMessage());
            }
        }

        return $notificationsCreated;
    }

    /**
     * Build birthday description text for the birthday employee
     *
     * @param string $name Employee name
     * @return string
     */
    private function buildBirthdayDescriptionForEmployee($name)
    {
        return "Dear {$name},\n\nWishing you a very Happy Birthday!\n\nMay this special day bring you joy, happiness, and all the wonderful things you deserve. Here's to another year of success, good health, and memorable moments!\n\nBest wishes from the entire team!";
    }

    /**
     * Build birthday description text for other employees
     *
     * @param string $name Employee name
     * @return string
     */
    private function buildBirthdayDescription($name)
    {
        //  return "Birthday Celebration!\n\nToday we celebrate {$name}'s birthday!\n\nLet's extend our warm wishes for a wonderful year ahead filled with happiness, success, and good health.";
        return "Today is {$name}'s special day!\n\nTake a moment to wish them a Happy Birthday and continued success.";
    }

    /**
     * Build anniversary description text for the anniversary employee
     *
     * @param string $name Employee name
     * @param int $years Years of marriage
     * @return string
     */
    private function buildAnniversaryDescriptionForEmployee($name, $years)
    {
        if ($years > 0) {
            $yearText = $years === 1 ? "1 year" : "{$years} years";
            return "Dear {$name},\n\nWishing you a very Happy {$yearText} Wedding Anniversary!\n\nMay this special milestone be filled with love, joy, and cherished memories. Here's to many more years of happiness together!\n\nWarm wishes from the entire team!";
        } else {
            return "Dear {$name},\n\nWishing you a very Happy Wedding Anniversary!\n\nMay this special day be filled with love, joy, and cherished memories. Here's to many more years of happiness together!\n\nWarm wishes from the entire team!";
        }
    }

    /**
     * Build anniversary description text for other employees
     *
     * @param string $name Employee name
     * @param int $years Years of marriage
     * @return string
     */
    private function buildAnniversaryDescription($name, $years)
    {
        if ($years > 0) {
            $yearText = $years === 1 ? "1 year" : "{$years} years";
            return "Wedding Anniversary Celebration!\n\nToday we celebrate {$name}'s {$yearText} wedding anniversary!\n\nCongratulations on {$yearText} of marriage! May your bond continue to grow stronger with love, joy, and beautiful memories together.";
        } else {
            return "Wedding Anniversary Celebration!\n\nToday we celebrate {$name}'s wedding anniversary!\n\nWishing you both a wonderful celebration filled with love and happiness!";
        }
    }

    /**
     * Manual trigger for testing daily notifications
     * Usage: /cron/birthday-anniversary/test
     */
    public function createTestNotifications()
    {
        // Check if user is authorized (admin/hr only)
        if (!in_array(session()->get('current_user')['role'] ?? '', ['superuser', 'hr'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $result = $this->createDailyNotifications();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test birthday/anniversary notifications created (daily)',
            'data' => $result
        ]);
    }

    /**
     * Manual trigger for testing monthly notifications
     * Usage: /cron/birthday-anniversary/test-monthly
     */
    public function createTestMonthlyNotifications()
    {
        // Check if user is authorized (admin/hr only)
        if (!in_array(session()->get('current_user')['role'] ?? '', ['superuser', 'hr'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $result = $this->createMonthlyNotifications();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test birthday/anniversary notifications created for entire month',
            'data' => $result
        ]);
    }
}
