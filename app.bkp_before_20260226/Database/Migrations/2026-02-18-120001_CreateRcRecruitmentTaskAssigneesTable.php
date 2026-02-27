<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcRecruitmentTaskAssigneesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'task_id'     => ['type' => 'INT', 'null' => false],
            'assigned_to' => ['type' => 'INT', 'null' => false],
            'status'      => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed'],
                'default'    => 'pending',
            ],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rc_recruitment_task_assignees');
    }

    public function down()
    {
        $this->forge->dropTable('rc_recruitment_task_assignees');
    }
}
