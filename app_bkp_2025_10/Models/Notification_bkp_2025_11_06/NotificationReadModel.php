<?php

namespace App\Models\Notification;

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
        'created_at',
    ];

    protected $useTimestamps = false;
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

        $now = date('Y-m-d H:i:s');
        $data = [
            'notification_id' => $notificationId,
            'employee_id' => $employeeId,
            'read_at' => $now,
            'created_at' => $now,
        ];

        return $this->insert($data);
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
