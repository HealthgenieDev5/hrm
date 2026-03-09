<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementAcknowledgmentModel extends Model
{
    protected $table            = 'announcement_acknowledgments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'announcement_id',
        'employee_id',
        'acknowledged_at',
        'ip_address',
        'user_agent',
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'announcement_id' => 'required|integer',
        'employee_id'     => 'required|integer',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Record acknowledgment for an announcement
     *
     * @param int $announcementId
     * @param int $employeeId
     * @return bool
     */
    public function acknowledgeAnnouncement(int $announcementId, int $employeeId): bool
    {
        // Check if already acknowledged
        $existing = $this->where('announcement_id', $announcementId)
                         ->where('employee_id', $employeeId)
                         ->first();

        if ($existing) {
            return true; // Already acknowledged
        }

        $request = \Config\Services::request();

        $data = [
            'announcement_id' => $announcementId,
            'employee_id'     => $employeeId,
            'acknowledged_at' => date('Y-m-d H:i:s'),
            'ip_address'      => $request->getIPAddress(),
            'user_agent'      => $request->getUserAgent()->getAgentString(),
        ];

        return $this->insert($data) !== false;
    }

    /**
     * Check if employee has acknowledged an announcement
     *
     * @param int $announcementId
     * @param int $employeeId
     * @return bool
     */
    public function hasAcknowledged(int $announcementId, int $employeeId): bool
    {
        $result = $this->where('announcement_id', $announcementId)
                       ->where('employee_id', $employeeId)
                       ->first();

        return $result !== null;
    }

    /**
     * Get acknowledgment history for an announcement
     *
     * @param int $announcementId
     * @return array
     */
    public function getAcknowledgmentHistory(int $announcementId): array
    {
        $db = \Config\Database::connect();

        return $db->table($this->table . ' aa')
                  ->select('aa.*, e.name as employee_name, e.employee_code, d.name as department_name, des.designation as designation_name')
                  ->join('employees e', 'e.id = aa.employee_id', 'left')
                  ->join('departments d', 'd.id = e.id_department', 'left')
                  ->join('designations des', 'des.id = e.id_designation', 'left')
                  ->where('aa.announcement_id', $announcementId)
                  ->orderBy('aa.acknowledged_at', 'DESC')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Get pending employees for an announcement
     *
     * @param int $announcementId
     * @return array
     */
    public function getPendingEmployees(int $announcementId): array
    {
        $db = \Config\Database::connect();
        $announcementModel = new AnnouncementModel();

        $announcement = $announcementModel->find($announcementId);
        if (!$announcement) {
            return [];
        }

        $builder = $db->table('employees e');
        $builder->select('e.id, e.name, e.employee_code, d.name as department_name, des.designation as designation_name')
                ->join('departments d', 'd.id = e.id_department', 'left')
                ->join('designations des', 'des.id = e.id_designation', 'left')
                ->where('e.is_active', 1);

        // Filter based on target type
        switch ($announcement['target_type']) {
            case 'department':
                if ($announcement['target_ids']) {
                    $deptIds = explode(',', $announcement['target_ids']);
                    $builder->whereIn('e.id_department', $deptIds);
                }
                break;
            case 'designation':
                if ($announcement['target_ids']) {
                    $designationIds = explode(',', $announcement['target_ids']);
                    $builder->whereIn('e.id_designation', $designationIds);
                }
                break;
            case 'specific':
                if ($announcement['target_ids']) {
                    $employeeIds = explode(',', $announcement['target_ids']);
                    $builder->whereIn('e.id', $employeeIds);
                }
                break;
            // 'all' doesn't need additional filtering
        }

        // Exclude employees who have already acknowledged
        $builder->whereNotIn('e.id', function($subBuilder) use ($announcementId) {
            return $subBuilder->select('employee_id')
                             ->from('announcement_acknowledgments')
                             ->where('announcement_id', $announcementId);
        });

        return $builder->get()->getResultArray();
    }
}
