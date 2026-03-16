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
        $acknowledgmentModel = new AnnouncementAcknowledgmentModel();
        $acknowledged = $acknowledgmentModel->where('employee_id', $employeeId)
            ->findColumn('announcement_id');

        $this->select('announcements.*')
            ->where('is_active', 1)
            ->where('requires_acknowledgment', 1)
            ->where('start_date <=', date('Y-m-d H:i:s'))
            ->where('target_type', 'all')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC');

        if (!empty($acknowledged)) {
            $this->whereNotIn('id', $acknowledged);
        }

        return $this->findAll();
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

        $totalAcknowledged = $db->table('announcement_acknowledgments')
            ->where('announcement_id', $announcementId)
            ->countAllResults();

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
