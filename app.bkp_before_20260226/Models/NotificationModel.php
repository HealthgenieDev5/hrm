<?php
namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model{
	protected $table = 'notifications';
	protected $allowedFields = [
        'target_employee_id', 
        'notification_title', 
        'notification_message', 
        'status', 
    ];
}
?>