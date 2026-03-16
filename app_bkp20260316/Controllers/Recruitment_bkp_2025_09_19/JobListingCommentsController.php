<?php

namespace App\Controllers\Recruitment;

use App\Controllers\BaseController;
//use App\Models\Recruitment\RcJobListingCommentReadsModel;
use App\Models\Recruitment\RcJobListingCommentModel;
use App\Models\UserModel;


class JobListingCommentsController extends BaseController
{
    protected $commentReadsModel;
    protected $commentsModel;
    protected $userModel;
    protected $session;
    protected $currentUserId;

    public function __construct()
    {
        $this->session = session();

        //helper(['url', 'form', 'Form_helper', 'global_helper', 'Config_defaults_helper']);
        // $this->commentReadsModel = new RcJobListingCommentReadsModel();
        $this->commentsModel = new RcJobListingCommentModel();
        $this->userModel = new UserModel();
        $this->currentUserId = session()->get('current_user')['employee_id'];
    }

    public function addComment($jobId = null)
    {
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(400)->setJSON([
        //         'status' => 'error',
        //         'message' => 'Only AJAX requests are allowed'
        //     ]);
        // }

        $data = [
            'listing_id' => $this->request->getPost('job_id') ?? $jobId,
            'sender_id' => $this->currentUserId,
            'content' => $this->request->getPost('comment'),
            'type' => $this->request->getPost('type') ?? 'comment',
            'status' => 'active'
        ];

        $commentId = $this->commentsModel->insert($data);

        if ($commentId) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Comment added successfully',
                'comment_id' => $commentId
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add comment'
            ]);
        }
    }

    // public function getReceiverId($commentId)
    // {
    //     $comment = $this->commentReadsModel->where(['comment_id' => $commentId])->first();
    //     if ($comment) {
    //         return $comment['receiver_id'];
    //     } else {
    //         $comment = $this->userModel->where(['role' => 'hr', 'status' => 'active'])->findAll();
    //         return $comment['employee_id'];
    //     }
    // }

    public function getComments($listingId)
    {
        if ($this->request->isAJAX()) {
            $comments = $this->commentsModel
                ->select('rc_job_listing_comments.*')
                ->select('CONCAT(employees.first_name, " ", employees.last_name) AS sender_name')
                ->select('employees.attachment')
                ->join('employees', 'employees.id = rc_job_listing_comments.sender_id', 'left')
                ->where('listing_id', $listingId)
                ->orderBy('created_at', 'desc')
                ->findAll();

            foreach ($comments as &$comment) {
                $comment['created_at_formatted'] = date('M d, Y \a\t h:i A', strtotime($comment['created_at']));
                $comment['time_ago'] = $this->timeAgo($comment['created_at']);

                $comment['profile_picture'] = null;
                if (!empty($comment['attachment'])) {
                    $attachments = json_decode($comment['attachment'], true);
                    if (isset($attachments['avatar']['file']) && !empty($attachments['avatar']['file'])) {
                        $comment['profile_picture'] = base_url(ltrim($attachments['avatar']['file'], '/'));
                    }
                }

                // Remove the raw attachment data from response
                unset($comment['attachment']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'comments' => $comments,
                'count' => count($comments)
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    // public function markAsRead()
    // {
    //     $commentId = $this->request->getPost('comment_id');
    //     $receiverId = $this->request->getPost('receiver_id');

    //     $result = $this->commentReadsModel->markAsRead($commentId, $receiverId);

    //     if ($result) {
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Comment marked as read'
    //         ]);
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Failed to mark as read'
    //     ]);
    // }

    // public function getUnreadCount($receiverId)
    // {
    //     $count = $this->commentReadsModel->getUnreadCount($receiverId);

    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'unread_count' => $count
    //     ]);
    // }

    // public function updateComment()
    // {
    //     $db = \Config\Database::connect();

    //     $commentId = $this->request->getPost('comment_id');
    //     $content = $this->request->getPost('content');
    //     $senderId = $this->request->getPost('sender_id');

    //     $comment = $db->table('rc_job_listing_comments')
    //         ->where(['id' => $commentId, 'sender_id' => $senderId])
    //         ->get()
    //         ->getRowArray();

    //     if (!$comment) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Comment not found or unauthorized'
    //         ]);
    //     }

    //     $result = $db->table('rc_job_listing_comments')
    //         ->where('id', $commentId)
    //         ->update([
    //             'content' => $content,
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ]);

    //     if ($result) {
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Comment updated successfully'
    //         ]);
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Failed to update comment'
    //     ]);
    // }

    // public function deleteComment()
    // {
    //     $db = \Config\Database::connect();

    //     $commentId = $this->request->getPost('comment_id');
    //     $senderId = $this->request->getPost('sender_id');

    //     $comment = $db->table('rc_job_listing_comments')
    //         ->where(['id' => $commentId, 'sender_id' => $senderId])
    //         ->get()
    //         ->getRowArray();

    //     if (!$comment) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Comment not found or unauthorized'
    //         ]);
    //     }

    //     $db->transStart();

    //     $db->table('rc_job_listing_comment_reads')
    //         ->where('comment_id', $commentId)
    //         ->delete();

    //     $result = $db->table('rc_job_listing_comments')
    //         ->where('id', $commentId)
    //         ->delete();

    //     $db->transComplete();

    //     if ($db->transStatus() === FALSE) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'message' => 'Failed to delete comment'
    //         ]);
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Comment deleted successfully'
    //     ]);
    // }

    // private function addCommentReceivers($commentId, $senderId, $receiverIds)
    // {
    //     $data = [];
    //     foreach ($receiverIds as $receiverId) {
    //         $data[] = [
    //             'comment_id' => $commentId,
    //             'sender_id' => $senderId,
    //             'receiver_id' => $receiverId,
    //             'is_read' => 0,
    //             'delivered_at' => date('Y-m-d H:i:s'),
    //             'created_at' => date('Y-m-d H:i:s')
    //         ];
    //     }

    //     if (!empty($data)) {
    //         return $this->commentReadsModel->insertBatch($data);
    //     }

    //     return false;
    // }

    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'just now';
        } elseif ($time < 3600) {
            return floor($time / 60) . ' minutes ago';
        } elseif ($time < 86400) {
            return floor($time / 3600) . ' hours ago';
        } elseif ($time < 2592000) {
            return floor($time / 86400) . ' days ago';
        } elseif ($time < 31104000) {
            return floor($time / 2592000) . ' months ago';
        } else {
            return floor($time / 31104000) . ' years ago';
        }
    }
}
