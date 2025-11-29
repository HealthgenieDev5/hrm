<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table            = 'announcements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'message',
        'type',
        'priority',
        'target_type',
        'target_ids',
        'start_date',
        'end_date',
        'is_active',
        'requires_acknowledgment',
        'show_once',
        'created_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title'   => 'required|min_length[3]|max_length[255]',
        'message' => 'required',
        'type'    => 'required|in_list[info,warning,success,danger]',
        'priority' => 'required|in_list[low,medium,high,critical]',
        'target_type' => 'required|in_list[all,department,designation,specific]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get pending announcements for a specific employee
     *
     * @param int $employeeId
     * @return array
     */
    public function getPendingAnnouncementsForEmployee(int $employeeId): array
    {
        $this->select('announcements.*, announcement_acknowledgments.employee_id as acknowledged_by');
        $this->join('announcement_acknowledgments', 'announcement_acknowledgments.announcement_id = announcements.id', 'left');
        $this->where('is_active', 1);
        $this->where('requires_acknowledgment', 1);
        $this->where('start_date <=', date('Y-m-d H:i:s'));
        // $this->where('end_date >=', date('Y-m-d H:i:s'));
        $this->where('target_type', 'all');
        $this->orderBy('priority', 'DESC');
        $this->orderBy('created_at', 'DESC');
        return $this->get()->getResultArray();

        // $db = \Config\Database::connect();
        // $builder = $db->table($this->table);

        // $currentDateTime = date('Y-m-d H:i:s');

        // // Get employee details
        // $employeeModel = new EmployeeModel();
        // $employee = $employeeModel->find($employeeId);

        // if (!$employee) {
        //     return [];
        // }

        // // Build query for announcements
        // $builder->where('is_active', 1)
        //         ->where('requires_acknowledgment', 1)
        //         ->groupStart()
        //             ->where('start_date IS NULL')
        //             ->orWhere('start_date <=', $currentDateTime)
        //         ->groupEnd()
        //         ->groupStart()
        //             ->where('end_date IS NULL')
        //             ->orWhere('end_date >=', $currentDateTime)
        //         ->groupEnd();

        // // Filter by target type
        // $builder->groupStart()
        //     ->where('target_type', 'all')
        //     ->orGroupStart()
        //         ->where('target_type', 'department')
        //         ->where("FIND_IN_SET('{$employee['id_department']}', target_ids) >", 0)
        //     ->groupEnd()
        //     ->orGroupStart()
        //         ->where('target_type', 'designation')
        //         ->where("FIND_IN_SET('{$employee['id_designation']}', target_ids) >", 0)
        //     ->groupEnd()
        //     ->orGroupStart()
        //         ->where('target_type', 'specific')
        //         ->where("FIND_IN_SET('{$employeeId}', target_ids) >", 0)
        //     ->groupEnd()
        // ->groupEnd();

        // // Exclude already acknowledged announcements
        // $builder->whereNotIn('id', function($builder) use ($employeeId) {
        //     return $builder->select('announcement_id')
        //                   ->from('announcement_acknowledgments')
        //                   ->where('employee_id', $employeeId);
        // });

        // $builder->orderBy('priority', 'DESC')
        //         ->orderBy('created_at', 'DESC');

        // return $builder->get()->getResultArray();
    }

    /**
     * Get all active announcements (for admin view)
     *
     * @return array
     */
    public function getActiveAnnouncements(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get announcement statistics
     *
     * @param int $announcementId
     * @return array
     */
    public function getAnnouncementStats(int $announcementId): array
    {
        $db = \Config\Database::connect();

        $announcement = $this->find($announcementId);
        if (!$announcement) {
            return [];
        }

        // Count total acknowledgments
        $totalAcknowledged = $db->table('announcement_acknowledgments')
            ->where('announcement_id', $announcementId)
            ->countAllResults();

        // Get target employee count
        $targetCount = 0;
        $employeeModel = new EmployeeModel();

        switch ($announcement['target_type']) {
            case 'all':
                $targetCount = $employeeModel->where('is_active', 1)->countAllResults();
                break;
            case 'department':
                if ($announcement['target_ids']) {
                    $deptIds = explode(',', $announcement['target_ids']);
                    $targetCount = $employeeModel->whereIn('id_department', $deptIds)
                        ->where('is_active', 1)
                        ->countAllResults();
                }
                break;
            case 'designation':
                if ($announcement['target_ids']) {
                    $designationIds = explode(',', $announcement['target_ids']);
                    $targetCount = $employeeModel->whereIn('id_designation', $designationIds)
                        ->where('is_active', 1)
                        ->countAllResults();
                }
                break;
            case 'specific':
                if ($announcement['target_ids']) {
                    $targetCount = count(explode(',', $announcement['target_ids']));
                }
                break;
        }

        return [
            'announcement_id' => $announcementId,
            'total_target' => $targetCount,
            'total_acknowledged' => $totalAcknowledged,
            'pending' => $targetCount - $totalAcknowledged,
            'percentage' => $targetCount > 0 ? round(($totalAcknowledged / $targetCount) * 100, 2) : 0,
        ];
    }
}
