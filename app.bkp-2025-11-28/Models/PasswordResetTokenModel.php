<?php
namespace App\Models;
use CodeIgniter\Model;

class PasswordResetTokenModel extends Model{
	protected $table = 'password_reset_token';
	protected $allowedFields = ['employee_id', 'token', 'used'];
}
?>