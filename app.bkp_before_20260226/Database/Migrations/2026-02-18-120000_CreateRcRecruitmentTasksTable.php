<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcRecruitmentTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'auto_increment' => true],
            'job_listing_id' => ['type' => 'INT', 'null' => false],
            'task_type'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'remarks'        => ['type' => 'TEXT', 'null' => true],
            'assigned_date'  => ['type' => 'DATE', 'null' => false],
            'due_date'       => ['type' => 'DATE', 'null' => false],
            'assigned_by'    => ['type' => 'INT', 'null' => false],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rc_recruitment_tasks');
    }

    public function down()
    {
        $this->forge->dropTable('rc_recruitment_tasks');
    }
}
