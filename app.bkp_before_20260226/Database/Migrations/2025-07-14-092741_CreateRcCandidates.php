<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRcCandidates extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'listing_id'                 => ['type' => 'INT', 'null' => true],
            'first_name'                => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'last_name'                 => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'email'                     => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'alternate_email'           => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'mobile'                    => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'alternate_mobile'          => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'gender'                    => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'marital_status'            => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'date_of_birth'             => ['type' => 'DATE', 'null' => true],
            'present_address'           => ['type' => 'TEXT', 'null' => true],
            'present_city'              => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'present_state'             => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'present_pincode'           => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'permanent_address'         => ['type' => 'TEXT', 'null' => true],
            'permanent_city'            => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'permanent_state'           => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'permanent_pincode'         => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'total_experience_year'     => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '0'],
            'total_experience_month'    => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '0'],
            'relevent_experience_year'  => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '0'],
            'relevent_experience_month' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => '0'],
            'is_working'                => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
            'current_company'           => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'current_company_joining_date' => ['type' => 'DATE', 'null' => true],
            'current_designation'       => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'functional_area'           => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'role'                      => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'industry'                  => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'notice_period'             => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'annual_salary'             => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'last_drawn_salary'         => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'last_drawn_salary_date'    => ['type' => 'DATE', 'null' => true],
            'current_company_address'   => ['type' => 'VARCHAR', 'constraint' => 2000, 'default' => ''],
            'current_company_city'      => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'current_company_state'     => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => ''],
            'current_company_pincode'   => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
            'preferred_location'        => ['type' => 'VARCHAR', 'constraint' => 1000, 'default' => ''],
            'skills'                    => ['type' => 'TEXT', 'null' => true],
            'resume'                    => ['type' => 'VARCHAR', 'constraint' => 1000, 'default' => ''],
            'resume_headline'           => ['type' => 'TEXT', 'null' => true],
            'summary'                   => ['type' => 'TEXT', 'null' => true],
            'source'                   => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'source_url'              => ['type' => 'VARCHAR', 'constraint' => 1000, 'default' => ''],
            'status'                    => ['type' => 'ENUM', 'constraint' => ['applied', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected'], 'default' => 'applied'],
            'created_at'                => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                => ['type' => 'DATETIME', 'null' => true]
        ]);



        $this->forge->addKey('id', true);
        $this->forge->createTable('rc_candidates');
    }

    public function down()
    {
        $this->forge->dropTable('rc_candidates');
    }
}
