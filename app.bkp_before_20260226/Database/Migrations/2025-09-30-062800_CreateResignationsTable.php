<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResignationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'employee_id' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'Employee who submitted resignation',
            ],
            'resignation_date' => [
                'type' => 'DATE',
                'null' => false,
                'comment' => 'Date when HR received resignation email/letter',
            ],
            'resignation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for resignation',
            ],
            'submitted_by_hr' => [
                'type' => 'INT',
                'null' => false,
                'comment' => 'HR employee who recorded this resignation',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'withdrawn', 'completed'],
                'default' => 'active',
                'comment' => 'Current status of resignation',
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
        $this->forge->addKey('employee_id');
        $this->forge->addKey('status');
        $this->forge->addKey('resignation_date');

        // Foreign key constraints
        // Note: Uncomment if foreign key constraints are needed
        // $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('submitted_by_hr', 'employees', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('resignations');
    }

    public function down()
    {
        $this->forge->dropTable('resignations');
    }
}