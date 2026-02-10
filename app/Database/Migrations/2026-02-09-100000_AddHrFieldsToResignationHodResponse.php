<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHrFieldsToResignationHodResponse extends Migration
{
	public function up()
	{
		$this->forge->addColumn('resignation_hod_response', [
			'hr_id' => [
				'type' => 'INT',
				'null' => false,
				'default' => 0,
				'after' => 'manager_viewed_date',
			],
			'hr_viewed' => [
				'type' => 'ENUM',
				'constraint' => ['pending', 'viewed'],
				'null' => true,
				'default' => 'pending',
				'after' => 'hr_id',
			],
			'hr_viewed_date' => [
				'type' => 'DATETIME',
				'null' => true,
				'after' => 'hr_viewed',
			],
		]);
	}

	public function down()
	{
		$this->forge->dropColumn('resignation_hod_response', ['hr_id', 'hr_viewed', 'hr_viewed_date']);
	}
}
