<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcJobListingCommentReadsModel extends Model
{
    protected $table = 'rc_job_listing_comment_reads';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'comment_id',
        'sender_id',
        'receiver_id',
        'is_read',
        'read_at',
        'delivered_at',
        'notification_sent',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    // public function markAsRead($commentId, $receiverId)
    // {
    //     return $this->where(['comment_id' => $commentId, 'receiver_id' => $receiverId])
    //         ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
    //         ->update();
    // }

    // public function getUnreadCount($receiverId)
    // {
    //     return $this->where(['receiver_id' => $receiverId, 'is_read' => 0])->countAllResults();
    // }

    // public function getReadStatus($commentId, $receiverId = null)
    // {
    //     $query = $this->where('comment_id', $commentId);

    //     if ($receiverId) {
    //         $query->where('receiver_id', $receiverId);
    //     }

    //     return $query->findAll();
    // }
}
