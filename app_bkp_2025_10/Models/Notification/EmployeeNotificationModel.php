<?php

namespace App\Models\Notification;

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
                        AND DATE(notification_reads.read_at) = '$today'
                    )")
            ->orderBy('reminder_1_date', 'ASC')
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
            ->join(
                'notification_reads',
                "notification_reads.notification_id = employee_notifications.id
                            AND notification_reads.employee_id = $employeeId",
                'left'
            )
            ->where('is_active', 1)
            ->where("(target_employees IS NULL OR JSON_CONTAINS(target_employees, '\"$employeeId\"'))")
            ->orderBy('event_date', 'DESC')
            ->findAll();
    }

    /**
     * Get filtered notifications for admin panel
     */
    public function getFilteredNotifications($arrCompanyId = [], $arrDepartmentId = [], $arrEmployeeId = [])
    {
        $this->select('employee_notifications.*')
            ->select("CONCAT(employees.first_name, ' ', employees.last_name) as created_by_name")
            ->select('(SELECT COUNT(*) FROM notification_reads WHERE notification_reads.notification_id = employee_notifications.id) as read_count');
        $this->join('employees', 'employees.id = employee_notifications.created_by', 'left');
        $this->orderBy('created_at', 'DESC');

        if (!empty($arrCompanyId) && !in_array('all_companies', $arrCompanyId)) {
            $this->whereIn('employees.company_id', $arrCompanyId);
        }
        if (!empty($arrDepartmentId) && !in_array('all_departments', $arrDepartmentId)) {
            $this->whereIn('employees.department_id', $arrDepartmentId);
        }
        if (!empty($arrEmployeeId)) {
            $this->whereIn('employee_notifications.created_by', $arrEmployeeId);
        }

        return $this->findAll();
    }
}
