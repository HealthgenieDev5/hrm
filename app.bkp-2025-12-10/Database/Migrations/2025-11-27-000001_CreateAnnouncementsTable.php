<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        // Announcements table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['info', 'warning', 'success', 'danger'],
                'default'    => 'info',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default'    => 'medium',
            ],
            'target_type' => [
                'type'       => 'ENUM',
                'constraint' => ['all', 'department', 'designation', 'specific'],
                'default'    => 'all',
                'comment'    => 'Who should see this announcement',
            ],
            'target_ids' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Comma-separated IDs for department/designation/employee targets',
            ],
            'start_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'requires_acknowledgment' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'show_once' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => 'Show only once per user or every time they login',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('is_active');
        $this->forge->addKey('target_type');
        $this->forge->createTable('announcements');

        // Announcement acknowledgments table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'announcement_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'employee_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'acknowledged_at' => [
                'type' => 'DATETIME',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['announcement_id', 'employee_id']);
        $this->forge->addForeignKey('announcement_id', 'announcements', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('announcement_acknowledgments');
    }

    public function down()
    {
        $this->forge->dropTable('announcement_acknowledgments');
        $this->forge->dropTable('announcements');
    }
}
