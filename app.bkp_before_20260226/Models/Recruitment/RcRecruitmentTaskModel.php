<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcRecruitmentTaskModel extends Model
{
    protected $table         = 'rc_recruitment_tasks';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'job_listing_id',
        'task_type',
        'remarks',
        'assigned_date',
        'due_date',
        'assigned_by',
    ];

    public function getTasksForJob(int $jobListingId): array
    {
        $tasks = $this
            ->select('rc_recruitment_tasks.*')
            ->select("TRIM(CONCAT(assigner.first_name, ' ', assigner.last_name)) as assigned_by_name")
            ->join('employees as assigner', 'assigner.id = rc_recruitment_tasks.assigned_by', 'left')
            ->where('rc_recruitment_tasks.job_listing_id', $jobListingId)
            ->orderBy('rc_recruitment_tasks.created_at', 'DESC')
            ->findAll();

        $assigneeModel = new RcRecruitmentTaskAssigneeModel();
        foreach ($tasks as &$task) {
            $task['assignees'] = $assigneeModel->getAssigneesForTask((int) $task['id']);
        }
        return $tasks;
    }
}
