<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResignationHodResponseTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'resignation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'employee_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'hod_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'hod_response' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'too_early', 'accept', 'rejected'],
                'default'    => 'pending',
            ],
            'hod_response_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'hod_rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'manager_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'manager_viewed' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'viewed'],
                'default'    => 'pending',
                'null'       => true,
            ],
            'manager_viewed_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('resignation_id');
        $this->forge->addKey('employee_id');
        $this->forge->addKey('hod_id');
        $this->forge->addKey('manager_id');

        $this->forge->createTable('resignation_hod_response');
    }

    public function down()
    {
        $this->forge->dropTable('resignation_hod_response');
    }
}
