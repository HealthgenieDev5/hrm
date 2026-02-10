<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcCandidatesTrash extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'candidate_id'  => ['type' => 'INT', 'null' => true],
            'data'          => ['type' => 'JSON'],
            'deleted_by'    => ['type' => 'INT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true, 'default' => null]

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('candidate_id', 'rc_candidates', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('deleted_by', 'employees', 'id', 'SET_NULL', 'SET_NULL');
        $this->forge->createTable('rc_candidates_trash');
    }

    public function down()
    {
        $this->forge->dropTable('rc_candidates_trash');
    }
}
