<?php
namespace App\Models;
use CodeIgniter\Model;

class WelcomeEmailModel extends Model{
	protected $table = 'welcome_email_history';
	protected $allowedFields = [
		'employee_id',
		'email_content',
		'sender_email',
		'to_emails',
		'bcc_emails',
		'reply_to_email',
		'sent_by'
	];
}
?>