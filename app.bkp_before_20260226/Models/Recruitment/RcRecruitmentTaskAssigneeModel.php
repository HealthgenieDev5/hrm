<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcRecruitmentTaskAssigneeModel extends Model
{
    protected $table         = 'rc_recruitment_task_assignees';
    protected $useTimestamps = true;
    protected $allowedFields = ['task_id', 'assigned_to', 'status'];

    public function getAssigneesForTask(int $taskId): array
    {
        return $this
            ->select('rc_recruitment_task_assignees.*')
            ->select("TRIM(CONCAT(e.first_name, ' ', e.last_name)) as assigned_to_name")
            ->join('employees as e', 'e.id = rc_recruitment_task_assignees.assigned_to', 'left')
            ->where('rc_recruitment_task_assignees.task_id', $taskId)
            ->findAll();
    }
}
