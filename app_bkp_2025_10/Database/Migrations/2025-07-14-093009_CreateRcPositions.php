<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcPositions extends Migration
{
    public function up()
    {


        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('rc_positions');
    }

    public function down()
    {
        $this->forge->dropTable('rc_positions');
    }
}
