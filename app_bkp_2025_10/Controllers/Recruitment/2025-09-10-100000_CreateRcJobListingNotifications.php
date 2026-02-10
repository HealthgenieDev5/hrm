<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcJobListingNotifications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'job_listing_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['job_listing_id', 'user_id']);
        $this->forge->createTable('rc_job_listing_notifications');
    }

    public function down()
    {
        $this->forge->dropTable('rc_job_listing_notifications');
    }
}
