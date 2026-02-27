<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHrResponseToProbationHodResponse extends Migration
{
    public function up()
    {
        $fields = [
            'hr_manager_id' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'HR Manager assigned to review (typically 52, 40, or 93)'
            ],
            'hr_response' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'remind_later', 'confirmed'],
                'null' => true,
                'default' => null,
                'comment' => 'HR Manager action status'
            ],
            'hr_response_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When HR Manager took action'
            ]
        ];

        $this->forge->addColumn('probation_hod_response', $fields);

        // Add indexes for better query performance
        $this->db->query('ALTER TABLE probation_hod_response ADD INDEX idx_hr_response (hr_response)');
        $this->db->query('ALTER TABLE probation_hod_response ADD INDEX idx_hr_manager_id (hr_manager_id)');
    }

    public function down()
    {
        // Drop indexes first
        $this->db->query('ALTER TABLE probation_hod_response DROP INDEX IF EXISTS idx_hr_response');
        $this->db->query('ALTER TABLE probation_hod_response DROP INDEX IF EXISTS idx_hr_manager_id');

        // Drop columns
        $this->forge->dropColumn('probation_hod_response', [
            'hr_manager_id',
            'hr_response',
            'hr_response_date'
        ]);
    }
}
