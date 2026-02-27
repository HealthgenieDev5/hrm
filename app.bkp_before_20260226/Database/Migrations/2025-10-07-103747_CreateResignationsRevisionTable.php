<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResignationsRevisionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'resignation_id' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'Reference to original resignation record',
            ],
            'revision_data' => [
                'type' => 'TEXT',
                'null' => false,
                'comment' => 'Complete resignation data stored as JSON string',
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['created', 'updated', 'completed', 'withdrawn'],
                'null' => false,
                'comment' => 'Type of action performed',
            ],
            'action_by' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'Employee who performed this action',
            ],
            'action_note' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional notes about the change',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('resignation_id');
        $this->forge->addKey('created_at');

        $this->forge->createTable('resignations_revision');
    }

    public function down()
    {
        $this->forge->dropTable('resignations_revision');
    }
}
