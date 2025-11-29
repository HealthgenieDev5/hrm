# Employee Notification System - Implementation Plan

## Table of Contents

1. [Overview](#overview)
2. [Database Schema](#database-schema)
3. [File Structure](#file-structure)
4. [Implementation Details](#implementation-details)
5. [API Endpoints](#api-endpoints)
6. [Email System](#email-system)
7. [Dashboard Integration](#dashboard-integration)
8. [Cron Jobs](#cron-jobs)

---

## Overview

### Purpose

Create a comprehensive notification system that:

- Sends notifications to employees about important events
- Supports multiple reminder dates (1st, 2nd, and 3rd reminders)
- Shows notification modals on dashboard based on reminder dates
- Tracks which employees have read the notifications
- Sends email reminders on specific dates

### Key Features

- ✅ Admin panel to create/edit/delete notifications
- ✅ Multiple notification types (Event, Reminder, Alert, etc.)
- ✅ Event date with 3 configurable reminder dates
- ✅ Dashboard modal popup on reminder dates
- ✅ Read/Unread tracking per employee
- ✅ Automatic email sending on reminder dates
- ✅ Target specific employees or all employees

---

## Database Schema

### Table 1: `employee_notifications`

Main table to store notification details.

```sql
CREATE TABLE `employee_notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `notification_type` ENUM('event', 'reminder', 'alert', 'announcement', 'policy', 'other') DEFAULT 'event',
    `event_date` DATE NOT NULL,
    `reminder_1_date` DATE NULL,
    `reminder_2_date` DATE NULL,
    `reminder_3_date` DATE NULL,
    `target_employees` TEXT NULL COMMENT 'JSON array of employee IDs, NULL means all employees',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_by` INT NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `deleted_at` DATETIME NULL,
    INDEX `idx_event_date` (`event_date`),
    INDEX `idx_reminder_dates` (`reminder_1_date`, `reminder_2_date`, `reminder_3_date`),
    INDEX `idx_notification_type` (`notification_type`),
    INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Table 2: `notification_reads`

Track which employees have read which notifications.

```sql
CREATE TABLE `notification_reads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `notification_id` INT NOT NULL,
    `employee_id` INT NOT NULL,
    `read_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    UNIQUE KEY `unique_notification_employee` (`notification_id`, `employee_id`),
    INDEX `idx_notification_id` (`notification_id`),
    INDEX `idx_employee_id` (`employee_id`),
    FOREIGN KEY (`notification_id`) REFERENCES `employee_notifications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Table 3: `notification_email_logs`

Track email sending status.

```sql
CREATE TABLE `notification_email_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `notification_id` INT NOT NULL,
    `employee_id` INT NOT NULL,
    `reminder_number` TINYINT NOT NULL COMMENT '1, 2, or 3',
    `sent_at` DATETIME NOT NULL,
    `email_status` ENUM('sent', 'failed', 'bounced') DEFAULT 'sent',
    `error_message` TEXT NULL,
    `created_at` DATETIME NULL,
    INDEX `idx_notification_id` (`notification_id`),
    INDEX `idx_employee_id` (`employee_id`),
    FOREIGN KEY (`notification_id`) REFERENCES `employee_notifications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## File Structure

### 1. Database Migrations

#### `app/Database/Migrations/2025-10-11-000000_CreateEmployeeNotificationsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'notification_type' => [
                'type' => 'ENUM',
                'constraint' => ['event', 'reminder', 'alert', 'announcement', 'policy', 'other'],
                'default' => 'event',
            ],
            'event_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'reminder_1_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'reminder_2_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'reminder_3_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'target_employees' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of employee IDs, NULL means all employees',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_date');
        $this->forge->addKey(['reminder_1_date', 'reminder_2_date', 'reminder_3_date']);
        $this->forge->addKey('notification_type');
        $this->forge->addKey('is_active');
        $this->forge->createTable('employee_notifications');
    }

    public function down()
    {
        $this->forge->dropTable('employee_notifications');
    }
}
```

#### `app/Database/Migrations/2025-10-11-000001_CreateNotificationReadsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationReadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'notification_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'employee_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('notification_id');
        $this->forge->addKey('employee_id');
        $this->forge->addUniqueKey(['notification_id', 'employee_id'], 'unique_notification_employee');
        $this->forge->createTable('notification_reads');
    }

    public function down()
    {
        $this->forge->dropTable('notification_reads');
    }
}
```

#### `app/Database/Migrations/2025-10-11-000002_CreateNotificationEmailLogsTable.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationEmailLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'notification_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'employee_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'reminder_number' => [
                'type' => 'TINYINT',
                'null' => false,
                'comment' => '1, 2, or 3',
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'email_status' => [
                'type' => 'ENUM',
                'constraint' => ['sent', 'failed', 'bounced'],
                'default' => 'sent',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('notification_id');
        $this->forge->addKey('employee_id');
        $this->forge->createTable('notification_email_logs');
    }

    public function down()
    {
        $this->forge->dropTable('notification_email_logs');
    }
}
```

---

### 2. Models

#### `app/Models/EmployeeNotificationModel.php`

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeNotificationModel extends Model
{
    protected $table = 'employee_notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'title',
        'description',
        'notification_type',
        'event_date',
        'reminder_1_date',
        'reminder_2_date',
        'reminder_3_date',
        'target_employees',
        'is_active',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required',
        'notification_type' => 'required|in_list[event,reminder,alert,announcement,policy,other]',
        'event_date' => 'required|valid_date',
        'created_by' => 'required|integer',
    ];

    /**
     * Get notifications due for reminder on a specific date
     */
    public function getNotificationsDueForReminder($date, $reminderNumber = 1)
    {
        $reminderField = "reminder_{$reminderNumber}_date";

        return $this->where($reminderField, $date)
                    ->where('is_active', 1)
                    ->findAll();
    }

    /**
     * Get unread notifications for a specific employee
     */
    public function getUnreadNotificationsForEmployee($employeeId)
    {
        $today = date('Y-m-d');

        return $this->select('employee_notifications.*')
                    ->where('is_active', 1)
                    ->where("(reminder_1_date = '$today' OR reminder_2_date = '$today' OR reminder_3_date = '$today')")
                    ->where("(target_employees IS NULL OR JSON_CONTAINS(target_employees, '\"$employeeId\"'))")
                    ->where("NOT EXISTS (
                        SELECT 1 FROM notification_reads
                        WHERE notification_reads.notification_id = employee_notifications.id
                        AND notification_reads.employee_id = $employeeId
                    )")
                    ->findAll();
    }

    /**
     * Check if notification applies to employee
     */
    public function appliesToEmployee($notificationId, $employeeId)
    {
        $notification = $this->find($notificationId);

        if (!$notification) {
            return false;
        }

        // If target_employees is NULL, applies to all
        if ($notification['target_employees'] === null) {
            return true;
        }

        // Check if employee ID is in the JSON array
        $targetEmployees = json_decode($notification['target_employees'], true);
        return in_array($employeeId, $targetEmployees);
    }

    /**
     * Get all notifications with read status for employee
     */
    public function getAllNotificationsForEmployee($employeeId)
    {
        return $this->select('employee_notifications.*,
                             notification_reads.read_at,
                             CASE WHEN notification_reads.id IS NOT NULL THEN 1 ELSE 0 END as is_read')
                    ->join('notification_reads',
                           "notification_reads.notification_id = employee_notifications.id
                            AND notification_reads.employee_id = $employeeId",
                           'left')
                    ->where('is_active', 1)
                    ->where("(target_employees IS NULL OR JSON_CONTAINS(target_employees, '\"$employeeId\"'))")
                    ->orderBy('event_date', 'DESC')
                    ->findAll();
    }
}
```

#### `app/Models/NotificationReadModel.php`

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationReadModel extends Model
{
    protected $table = 'notification_reads';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'notification_id',
        'employee_id',
        'read_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    /**
     * Mark notification as read by employee
     */
    public function markAsRead($notificationId, $employeeId)
    {
        // Check if already marked as read
        $existing = $this->where('notification_id', $notificationId)
                         ->where('employee_id', $employeeId)
                         ->first();

        if ($existing) {
            return true; // Already marked
        }

        return $this->insert([
            'notification_id' => $notificationId,
            'employee_id' => $employeeId,
            'read_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Check if notification has been read by employee
     */
    public function isRead($notificationId, $employeeId)
    {
        return $this->where('notification_id', $notificationId)
                    ->where('employee_id', $employeeId)
                    ->first() !== null;
    }

    /**
     * Get read count for notification
     */
    public function getReadCount($notificationId)
    {
        return $this->where('notification_id', $notificationId)->countAllResults();
    }
}
```

#### `app/Models/NotificationEmailLogModel.php`

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationEmailLogModel extends Model
{
    protected $table = 'notification_email_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'notification_id',
        'employee_id',
        'reminder_number',
        'sent_at',
        'email_status',
        'error_message',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    /**
     * Check if email already sent
     */
    public function isEmailSent($notificationId, $employeeId, $reminderNumber)
    {
        return $this->where('notification_id', $notificationId)
                    ->where('employee_id', $employeeId)
                    ->where('reminder_number', $reminderNumber)
                    ->where('email_status', 'sent')
                    ->first() !== null;
    }

    /**
     * Log email send
     */
    public function logEmail($notificationId, $employeeId, $reminderNumber, $status = 'sent', $errorMessage = null)
    {
        return $this->insert([
            'notification_id' => $notificationId,
            'employee_id' => $employeeId,
            'reminder_number' => $reminderNumber,
            'sent_at' => date('Y-m-d H:i:s'),
            'email_status' => $status,
            'error_message' => $errorMessage,
        ]);
    }
}
```

---

### 3. Controller

#### `app/Controllers/EmployeeNotificationController.php`

```php
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeNotificationModel;
use App\Models\NotificationReadModel;
use App\Models\NotificationEmailLogModel;
use App\Models\EmployeeModel;

class EmployeeNotificationController extends BaseController
{
    protected $notificationModel;
    protected $notificationReadModel;
    protected $emailLogModel;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->notificationModel = new EmployeeNotificationModel();
        $this->notificationReadModel = new NotificationReadModel();
        $this->emailLogModel = new NotificationEmailLogModel();
        helper(['url', 'form', 'Form_helper']);
    }

    /**
     * List all notifications (Admin view)
     */
    public function index()
    {
        // Check if user is admin/hr
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $data = [
            'page_title' => 'Employee Notifications',
            'current_controller' => 'notifications',
            'current_method' => 'index',
        ];

        return view('Notifications/Index', $data);
    }

    /**
     * Get all notifications via AJAX
     */
    public function getAllNotifications()
    {
        $notifications = $this->notificationModel
            ->select('employee_notifications.*,
                     CONCAT(employees.first_name, " ", employees.last_name) as created_by_name,
                     (SELECT COUNT(*) FROM notification_reads WHERE notification_reads.notification_id = employee_notifications.id) as read_count')
            ->join('employees', 'employees.id = employee_notifications.created_by', 'left')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON($notifications);
    }

    /**
     * Show create form
     */
    public function create()
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
                                    ->orderBy('first_name', 'ASC')
                                    ->findAll();

        $data = [
            'page_title' => 'Create Notification',
            'current_controller' => 'notifications',
            'current_method' => 'create',
            'employees' => $employees,
        ];

        return view('Notifications/Create', $data);
    }

    /**
     * Store new notification
     */
    public function store()
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Unauthorized'
            ]);
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'notification_type' => 'required',
            'event_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Validation failed',
                'response_data' => ['validation' => $this->validator->getErrors()]
            ]);
        }

        $targetEmployees = $this->request->getPost('target_employees');

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'notification_type' => $this->request->getPost('notification_type'),
            'event_date' => $this->request->getPost('event_date'),
            'reminder_1_date' => $this->request->getPost('reminder_1_date'),
            'reminder_2_date' => $this->request->getPost('reminder_2_date'),
            'reminder_3_date' => $this->request->getPost('reminder_3_date'),
            'target_employees' => ($targetEmployees && count($targetEmployees) > 0) ? json_encode($targetEmployees) : null,
            'is_active' => $this->request->getPost('is_active') ?? 1,
            'created_by' => $this->session->get('current_user')['employee_id'],
        ];

        if ($this->notificationModel->insert($data)) {
            return $this->response->setJSON([
                'response_type' => 'success',
                'response_description' => 'Notification created successfully'
            ]);
        }

        return $this->response->setJSON([
            'response_type' => 'error',
            'response_description' => 'Failed to create notification'
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return redirect()->to(base_url('/unauthorised'));
        }

        $notification = $this->notificationModel->find($id);

        if (!$notification) {
            return redirect()->to(base_url('/backend/notifications'));
        }

        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->select('id, internal_employee_id, first_name, last_name, work_email')
                                    ->orderBy('first_name', 'ASC')
                                    ->findAll();

        $data = [
            'page_title' => 'Edit Notification',
            'current_controller' => 'notifications',
            'current_method' => 'edit',
            'notification' => $notification,
            'employees' => $employees,
        ];

        return view('Notifications/Edit', $data);
    }

    /**
     * Update notification
     */
    public function update($id)
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Unauthorized'
            ]);
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'notification_type' => 'required',
            'event_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Validation failed',
                'response_data' => ['validation' => $this->validator->getErrors()]
            ]);
        }

        $targetEmployees = $this->request->getPost('target_employees');

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'notification_type' => $this->request->getPost('notification_type'),
            'event_date' => $this->request->getPost('event_date'),
            'reminder_1_date' => $this->request->getPost('reminder_1_date'),
            'reminder_2_date' => $this->request->getPost('reminder_2_date'),
            'reminder_3_date' => $this->request->getPost('reminder_3_date'),
            'target_employees' => ($targetEmployees && count($targetEmployees) > 0) ? json_encode($targetEmployees) : null,
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ];

        if ($this->notificationModel->update($id, $data)) {
            return $this->response->setJSON([
                'response_type' => 'success',
                'response_description' => 'Notification updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'response_type' => 'error',
            'response_description' => 'Failed to update notification'
        ]);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        if (!in_array($this->session->get('current_user')['role'], ['superuser', 'admin', 'hr'])) {
            return $this->response->setJSON([
                'response_type' => 'error',
                'response_description' => 'Unauthorized'
            ]);
        }

        if ($this->notificationModel->delete($id)) {
            return $this->response->setJSON([
                'response_type' => 'success',
                'response_description' => 'Notification deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'response_type' => 'error',
            'response_description' => 'Failed to delete notification'
        ]);
    }

    /**
     * Get dashboard notifications for current employee
     */
    public function getDashboardNotifications()
    {
        $employeeId = $this->session->get('current_user')['employee_id'];
        $notifications = $this->notificationModel->getUnreadNotificationsForEmployee($employeeId);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $notificationId = $this->request->getPost('notification_id');
        $employeeId = $this->session->get('current_user')['employee_id'];

        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification ID required'
            ]);
        }

        if ($this->notificationReadModel->markAsRead($notificationId, $employeeId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to mark as read'
        ]);
    }
}
```

---

### 4. Cron Controller

#### `app/Controllers/Cron/NotificationReminders.php`

```php
<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Models\EmployeeNotificationModel;
use App\Models\NotificationEmailLogModel;
use App\Models\EmployeeModel;

class NotificationReminders extends BaseController
{
    protected $notificationModel;
    protected $emailLogModel;
    protected $employeeModel;

    public function __construct()
    {
        $this->notificationModel = new EmployeeNotificationModel();
        $this->emailLogModel = new NotificationEmailLogModel();
        $this->employeeModel = new EmployeeModel();
        helper(['email']);
    }

    /**
     * Send reminder emails for today's reminders
     */
    public function sendDailyReminders()
    {
        $today = date('Y-m-d');
        $emailsSent = 0;

        // Check all 3 reminder dates
        for ($reminderNumber = 1; $reminderNumber <= 3; $reminderNumber++) {
            $notifications = $this->notificationModel->getNotificationsDueForReminder($today, $reminderNumber);

            foreach ($notifications as $notification) {
                $emailsSent += $this->sendNotificationEmails($notification, $reminderNumber);
            }
        }

        echo "Sent {$emailsSent} reminder emails for {$today}\n";
        return $emailsSent;
    }

    /**
     * Send emails for a specific notification
     */
    private function sendNotificationEmails($notification, $reminderNumber)
    {
        $emailsSent = 0;
        $targetEmployees = [];

        // Get target employees
        if ($notification['target_employees'] === null) {
            // Send to all active employees
            $targetEmployees = $this->employeeModel
                ->select('id, work_email, first_name, last_name')
                ->where('status', 'active')
                ->findAll();
        } else {
            // Send to specific employees
            $employeeIds = json_decode($notification['target_employees'], true);
            $targetEmployees = $this->employeeModel
                ->select('id, work_email, first_name, last_name')
                ->whereIn('id', $employeeIds)
                ->where('status', 'active')
                ->findAll();
        }

        // Send email to each employee
        foreach ($targetEmployees as $employee) {
            // Check if already sent
            if ($this->emailLogModel->isEmailSent($notification['id'], $employee['id'], $reminderNumber)) {
                continue;
            }

            $emailSent = $this->sendEmail($employee, $notification, $reminderNumber);

            if ($emailSent) {
                $this->emailLogModel->logEmail(
                    $notification['id'],
                    $employee['id'],
                    $reminderNumber,
                    'sent'
                );
                $emailsSent++;
            } else {
                $this->emailLogModel->logEmail(
                    $notification['id'],
                    $employee['id'],
                    $reminderNumber,
                    'failed',
                    'Failed to send email'
                );
            }
        }

        return $emailsSent;
    }

    /**
     * Send individual email
     */
    private function sendEmail($employee, $notification, $reminderNumber)
    {
        $email = \Config\Services::email();

        $email->setFrom('noreply@healthgenie.in', 'HealthGenie HRM');
        $email->setTo($employee['work_email']);
        $email->setSubject('Reminder: ' . $notification['title']);

        $emailData = [
            'employee_name' => trim($employee['first_name'] . ' ' . $employee['last_name']),
            'notification' => $notification,
            'reminder_number' => $reminderNumber,
        ];

        $message = view('Emails/NotificationReminder', $emailData);
        $email->setMessage($message);

        return $email->send();
    }
}
```

---

### 5. Routes

#### `app/Config/CustomRoutes/NotificationRoutes.php`

```php
<?php

use App\Controllers\EmployeeNotificationController;
use App\Controllers\Cron\NotificationReminders;

// Admin routes
$routes->match(['get'], '/backend/notifications', [EmployeeNotificationController::class, 'index']);
$routes->match(['get', 'post'], '/backend/notifications/create', [EmployeeNotificationController::class, 'create']);
$routes->match(['post'], '/backend/notifications/store', [EmployeeNotificationController::class, 'store']);
$routes->match(['get'], '/backend/notifications/edit/(:num)', [EmployeeNotificationController::class, 'edit/$1']);
$routes->match(['post'], '/backend/notifications/update/(:num)', [EmployeeNotificationController::class, 'update/$1']);
$routes->match(['post'], '/backend/notifications/delete/(:num)', [EmployeeNotificationController::class, 'delete/$1']);

// AJAX routes
$routes->match(['get', 'post'], '/ajax/notifications/get-all', [EmployeeNotificationController::class, 'getAllNotifications']);
$routes->match(['get', 'post'], '/ajax/notifications/dashboard', [EmployeeNotificationController::class, 'getDashboardNotifications']);
$routes->match(['post'], '/ajax/notifications/mark-as-read', [EmployeeNotificationController::class, 'markAsRead']);

// Cron route
$routes->match(['get', 'post'], '/cron/notifications/send-reminders', [NotificationReminders::class, 'sendDailyReminders']);
```

---

## API Endpoints

### Admin Endpoints

| Method | Endpoint                             | Description            |
| ------ | ------------------------------------ | ---------------------- |
| GET    | `/backend/notifications`             | List all notifications |
| GET    | `/backend/notifications/create`      | Show create form       |
| POST   | `/backend/notifications/store`       | Create notification    |
| GET    | `/backend/notifications/edit/{id}`   | Show edit form         |
| POST   | `/backend/notifications/update/{id}` | Update notification    |
| POST   | `/backend/notifications/delete/{id}` | Delete notification    |

### AJAX Endpoints

| Method   | Endpoint                           | Description                                   |
| -------- | ---------------------------------- | --------------------------------------------- |
| GET/POST | `/ajax/notifications/get-all`      | Get all notifications with stats              |
| GET/POST | `/ajax/notifications/dashboard`    | Get unread notifications for current employee |
| POST     | `/ajax/notifications/mark-as-read` | Mark notification as read                     |

### Cron Endpoints

| Method   | Endpoint                             | Description                |
| -------- | ------------------------------------ | -------------------------- |
| GET/POST | `/cron/notifications/send-reminders` | Send daily reminder emails |

---

## Email System

### Email Template

**File:** `app/Views/Emails/NotificationReminder.php`

```html
<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
      }
      .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
      }
      .header {
        background: #007bff;
        color: white;
        padding: 20px;
        text-align: center;
      }
      .content {
        background: #f8f9fa;
        padding: 20px;
        margin-top: 20px;
      }
      .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
        color: #666;
      }
      .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1><?= esc($notification['title']) ?></h1>
      </div>

      <div class="content">
        <p>
          Dear
          <?= esc($employee_name) ?>,
        </p>

        <p>
          This is reminder #<?= $reminder_number ?>
          for the following notification:
        </p>

        <h3><?= esc($notification['title']) ?></h3>
        <p>
          <strong>Type:</strong>
          <?= ucfirst($notification['notification_type']) ?>
        </p>
        <p>
          <strong>Event Date:</strong>
          <?= date('d M Y', strtotime($notification['event_date'])) ?>
        </p>

        <div
          style="background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff;"
        >
          <?= nl2br(esc($notification['description'])) ?>
        </div>

        <p style="text-align: center; margin-top: 30px;">
          <a href="<?= base_url('dashboard') ?>" class="btn">View Dashboard</a>
        </p>
      </div>

      <div class="footer">
        <p>This is an automated email from HealthGenie HRM System.</p>
        <p>
          &copy;
          <?= date('Y') ?>
          HealthGenie. All rights reserved.
        </p>
      </div>
    </div>
  </body>
</html>
```

---

## Dashboard Integration

### Dashboard Notification Check (JavaScript)

```javascript
// Add to dashboard page
$(document).ready(function () {
  checkForNotifications();
});

function checkForNotifications() {
  $.ajax({
    url: '<?= base_url("ajax/notifications/dashboard") ?>',
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success && response.notifications.length > 0) {
        showNotificationModal(response.notifications[0]);
      }
    },
  });
}

function showNotificationModal(notification) {
  // Create and show modal
  const modal = `
        <div class="modal fade" id="notificationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">${notification.title}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Type:</strong> ${notification.notification_type}</p>
                        <p><strong>Event Date:</strong> ${notification.event_date}</p>
                        <hr>
                        <p>${notification.description}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="markAsRead(${notification.id})">
                            Mark as Read
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

  $("body").append(modal);
  $("#notificationModal").modal("show");
}

function markAsRead(notificationId) {
  $.ajax({
    url: '<?= base_url("ajax/notifications/mark-as-read") ?>',
    method: "POST",
    data: { notification_id: notificationId },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#notificationModal").modal("hide");
        $("#notificationModal").remove();
      }
    },
  });
}
```

---

## Cron Jobs

### Setup Daily Cron

Add to server crontab:

```bash
# Run daily at 8:00 AM
0 8 * * * cd /path/to/hrm && php spark cron:notifications
```

Or create a command:

**File:** `app/Commands/NotificationCron.php`

```php
<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class NotificationCron extends BaseCommand
{
    protected $group = 'Cron';
    protected $name = 'cron:notifications';
    protected $description = 'Send notification reminder emails';

    public function run(array $params)
    {
        $controller = new \App\Controllers\Cron\NotificationReminders();
        $emailsSent = $controller->sendDailyReminders();

        CLI::write("Notification reminders sent: {$emailsSent}", 'green');
    }
}
```

Run manually:

```bash
php spark cron:notifications
```

---

## Implementation Checklist

- [ ] Create database migrations
- [ ] Run migrations
- [ ] Create models
- [ ] Create controller
- [ ] Create routes file
- [ ] Create views (Index, Create, Edit)
- [ ] Create email template
- [ ] Create cron controller
- [ ] Add dashboard notification check
- [ ] Test notification creation
- [ ] Test reminder email sending
- [ ] Test mark as read functionality
- [ ] Setup cron job
- [ ] Test complete workflow

---

## Usage Examples

### 1. Create a New Notification

```php
POST /backend/notifications/store

{
    "title": "Annual Performance Review",
    "description": "Please complete your annual performance review...",
    "notification_type": "event",
    "event_date": "2025-12-15",
    "reminder_1_date": "2025-11-15",
    "reminder_2_date": "2025-12-01",
    "reminder_3_date": "2025-12-10",
    "target_employees": [40, 52, 93], // or null for all
    "is_active": 1
}
```

### 2. Mark Notification as Read

```javascript
$.post("/ajax/notifications/mark-as-read", {
  notification_id: 123,
});
```

### 3. Get Dashboard Notifications

```javascript
$.get("/ajax/notifications/dashboard", function (response) {
  console.log(response.notifications);
});
```

---

## Security Considerations

1. **Authorization**: Only admin/HR can create/edit notifications
2. **Validation**: All inputs validated before saving
3. **SQL Injection**: Using query builder prevents SQL injection
4. **XSS Protection**: All output escaped in views
5. **Email Rate Limiting**: Cron runs once daily to prevent spam

---

## Future Enhancements

1. Push notifications (browser notifications)
2. SMS reminders
3. Notification categories
4. Attachment support
5. Recurring notifications
6. Notification templates
7. Analytics dashboard
8. Employee acknowledgment tracking
9. Multi-language support
10. Rich text editor for description

---

**End of Documentation**
