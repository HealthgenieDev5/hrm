<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SandwichRuleAnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'title' => 'Important: Sandwich Rule Update - Religious Holidays (RH)',
            'message' => '<div style="font-size: 16px; line-height: 1.6;">
                <p><strong>Dear Team,</strong></p>
                <p>This is to inform all employees that, effective <strong>1st December 2025</strong>, the <strong>Sandwich Rule</strong> will also apply to <strong>Religious Holidays (RH)</strong>.</p>
                <p>The rule shall be implemented in the same manner as for Sundays and other declared holidays.</p>
                <p><strong>What this means:</strong></p>
                <ul>
                    <li>If you are absent on the days immediately before and after a Religious Holiday (RH), the RH will also be counted as leave</li>
                    <li>This policy is being applied uniformly across the organization</li>
                    <li>Please plan your leave requests accordingly</li>
                </ul>
                <p>For any queries, please contact the HR department.</p>
                <p><em>HR Department<br>Healthgenie</em></p>
            </div>',
            'type' => 'warning',
            'priority' => 'high',
            'target_type' => 'all',
            'target_ids' => null,
            'start_date' => date('Y-m-d H:i:s'), // Start immediately
            'end_date' => date('Y-m-d H:i:s', strtotime('+30 days')), // Show for 30 days
            'is_active' => 1,
            'requires_acknowledgment' => 1,
            'show_once' => 1,
            'created_by' => 1, // Assuming admin user ID is 1
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Check if announcement already exists
        $builder = $this->db->table('announcements');
        $existing = $builder->where('title', $data['title'])->get()->getRowArray();

        if (!$existing) {
            $builder->insert($data);
            echo "Sandwich Rule announcement created successfully!\n";
        } else {
            echo "Sandwich Rule announcement already exists (ID: {$existing['id']})\n";
        }
    }
}
