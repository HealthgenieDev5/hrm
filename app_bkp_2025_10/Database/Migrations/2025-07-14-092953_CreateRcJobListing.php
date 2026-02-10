<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcJobListing extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'auto_increment' => true],
            'job_title'           => ['type' => 'INT', 'null' => true],
            'company_id'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'department_id'         => ['type' => 'INT', 'null' => true],
            'min_experience' => ['type' => 'DECIMAL', 'constraint' => '4,1', 'default' => 0], // Stores 99.9
            'max_experience' => ['type' => 'DECIMAL', 'constraint' => '4,1', 'default' => 1],
            'min_budget'            => ['type' => 'BIGINT', 'null' => true],
            'max_budget'            => ['type' => 'BIGINT', 'null' => true],
            'interview_location'          => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'no_of_vacancy'         => ['type' => 'BIGINT', 'null' => true],
            'priority'              => ['type' => 'BIGINT', 'null' => true],
            'target_closure_date'   => ['type' => 'DATE', 'null' => true],
            'expected_closure_date' => ['type' => 'DATE', 'null' => true],
            'type_of_job'               => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'seating_location'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'system_required'           => ['type' => 'ENUM', 'constraint' => ['yes', 'no'], 'default' => 'no'],
            'reporting_to'              => ['type' => 'INT', 'null' => true],
            'salient_points'            => ['type' => 'TEXT', 'null' => true],
            'educational_qualification' => ['type' => 'TEXT', 'null' => true],
            'technical_test_required'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'iq_test_required'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'eng_test_required'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'operation_test_required'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'other_test_required'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'requirement'               => ['type' => 'TEXT', 'null' => true],
            'interview_location'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'shift_start'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'shift_end'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'specific_industry'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'attachment'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'review_schedule_3m'        => ['type' => 'TEXT', 'null' => true],
            'review_schedule_6m'        => ['type' => 'TEXT', 'null' => true],
            'review_schedule_12m'       => ['type' => 'TEXT', 'null' => true],
            'job_opening_date'          => ['type' => 'DATE'],
            'job_closing_date'          => ['type' => 'DATE', 'null' => true],
            'job_closing_reason'        => ['type' => 'TEXT', 'null' => true],
            'created_by'                => ['type' => 'INT', 'null' => true],
            'status'                  => ['type' => 'ENUM', 'constraint' => ['active', 'in progress', 'inactive', 'pending', 'draft', 'closed'], 'default' => 'active'],
            'created_at'                => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                => ['type' => 'DATETIME', 'null' => true]

        ]);
        $this->forge->addKey('id', true);
        //$this->forge->addForeignKey('position_id', 'rc_positions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rc_job_listing');
    }

    public function down()
    {
        $this->forge->dropTable('rc_job_listing');
    }
}
