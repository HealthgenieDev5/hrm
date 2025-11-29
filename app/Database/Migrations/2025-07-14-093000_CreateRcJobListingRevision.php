<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcJobListingRevision extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'listing_id'    => ['type' => 'INT', 'null' => true],
            'field_name'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'old_value'     => ['type' => 'TEXT', 'null' => true],
            'new_value'     => ['type' => 'TEXT', 'null' => true],
            'updated_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]

        ]);

        $this->forge->addKey('id', true);
        //$this->forge->addForeignKey('listing_id', 'rc_job_listing', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('updated_by', 'employees', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('rc_job_listing_revision');
    }

    public function down()
    {
        $this->forge->dropTable('rc_job_listing_revision');
    }
}
