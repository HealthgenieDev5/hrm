<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeAttachmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'employee_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Foreign key to employees table'
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Document title provided by user'
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => false,
                'comment'    => 'Relative path to uploaded file'
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Original filename'
            ],
            'file_extension' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
                'comment'    => 'File extension (pdf, jpg, doc, etc.)'
            ],
            'file_size' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'File size in bytes'
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Employee ID who uploaded the file'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('employee_id');

        // Foreign key commented out for flexibility (matching pattern in probation_notifications)
        // $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('employee_attachments');
    }

    public function down()
    {
        $this->forge->dropTable('employee_attachments');
    }
}
