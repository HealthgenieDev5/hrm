<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcSubdispositions extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'disposition_id'  => ['type' => 'INT', 'null' => true],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('disposition_id', 'rc_dispositions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rc_subdispositions');
    }

    public function down()
    {
        $this->forge->dropTable('rc_subdispositions');
    }
}
