<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRelatedEmployeeIdToNotifications extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE employee_notifications ADD COLUMN related_employee_id INT NULL DEFAULT NULL AFTER target_employees');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE employee_notifications DROP COLUMN related_employee_id');
    }
}
