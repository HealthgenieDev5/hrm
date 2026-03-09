<?php

namespace App\Models\Notification;

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
