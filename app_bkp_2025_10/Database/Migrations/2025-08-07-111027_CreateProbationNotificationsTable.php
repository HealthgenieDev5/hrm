<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProbationNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'auto_increment' => true],
            'employee_id'           => ['type' => 'INT', 'null' => false],
            'probation_period'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'probation_status'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'probation_confirmed_on' => ['type' => 'DATE', 'null' => false],
            'email_sent_at'         => ['type' => 'DATETIME', 'null' => false],
            'acknowledged_at'       => ['type' => 'DATETIME', 'null' => true],
            'email_status'          => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'sent'],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        //  $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('probation_notifications');
    }

    public function down()
    {
        $this->forge->dropTable('probation_notifications');
    }
}
