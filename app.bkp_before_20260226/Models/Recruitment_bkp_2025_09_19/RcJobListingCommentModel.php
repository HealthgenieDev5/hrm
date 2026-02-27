<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcJobListingCommentModel extends Model
{
    protected $table = 'rc_job_listing_comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['listing_id', 'sender_id', 'content', 'type', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // public function getCommentsWithSender($listingId)
    // {
    //     return $this->select('rc_job_listing_comments.*, users.username as sender_name, emp.name as sender_full_name')
    //                ->join('users', 'users.employee_id = rc_job_listing_comments.sender_id', 'left')
    //                ->join('employees emp', 'emp.employee_id = rc_job_listing_comments.sender_id', 'left')
    //                ->where('listing_id', $listingId)
    //                ->orderBy('created_at', 'DESC')
    //                ->findAll();
    // }

    // public function getCommentsByStatus($listingId, $status = 'new')
    // {
    //     return $this->where(['listing_id' => $listingId, 'status' => $status])
    //                ->orderBy('created_at', 'DESC')
    //                ->findAll();
    // }
}
