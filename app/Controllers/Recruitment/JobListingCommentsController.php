<?php

namespace App\Controllers\Recruitment;

use App\Controllers\BaseController;
//use App\Models\Recruitment\RcJobListingCommentReadsModel;
use App\Models\Recruitment\RcJobListingCommentModel;
use App\Models\Recruitment\RcJobListingModel;
use App\Models\UserModel;


class JobListingCommentsController extends BaseController
{
    protected $commentsModel;
    protected $jobListingModel;
    protected $userModel;
    protected $session;
    protected $currentUserId;
    protected $hrExecutive = 52;

    public function __construct()
    {
        $this->session = session();

        //helper(['url', 'form', 'Form_helper', 'global_helper', 'Config_defaults_helper']);
        $this->commentsModel = new RcJobListingCommentModel();
        $this->jobListingModel = new RcJobListingModel();
        $this->userModel = new UserModel();
        $this->currentUserId = session()->get('current_user')['employee_id'] ?? null;
    }

    public function addComment($jobId = null)
    {
        $listingId = $this->request->getPost('job_id') ?? $jobId;
        $commentType = $this->request->getPost('type') ?? 'comment';
        $parentCommentId = $this->request->getPost('parent_comment_id');

        $data = [
            'listing_id' => $listingId,
            'sender_id' => $this->currentUserId,
            'content' => $this->request->getPost('comment'),
            'type' => $commentType,
            'status' => 'active'
        ];

        if ($commentType === 'resolution') {
            $targetIssueId = null;

            if (!empty($parentCommentId)) {
                // Get the parent comment that user specifically selected
                $parentComment = $this->commentsModel
                    ->where('id', $parentCommentId)
                    ->where('listing_id', $listingId)
                    ->first();

                if ($parentComment) {
                    // If user replied to an issue, link to that issue
                    if ($parentComment['type'] === 'issue') {
                        $targetIssueId = $parentComment['id'];
                        $data['parent_comment_id'] = $parentComment['id'];
                    }
                    // If user replied to a resolution, find its original issue but keep the UI's parent choice
                    else if ($parentComment['type'] === 'resolution') {
                        // Keep the user's choice for parent_comment_id (could be replying to specific resolution)
                        $data['parent_comment_id'] = $parentComment['id'];

                        // But find the original issue to mark as closed
                        if (!empty($parentComment['parent_comment_id'])) {
                            $originalIssue = $this->commentsModel
                                ->where('id', $parentComment['parent_comment_id'])
                                ->where('type', 'issue')
                                ->first();
                            if ($originalIssue) {
                                $targetIssueId = $originalIssue['id'];
                            }
                        }
                    }
                }
            } else {
                // Fallback: Find the latest active issue if no parent specified
                $parentIssue = $this->commentsModel
                    ->where('listing_id', $listingId)
                    ->where('type', 'issue')
                    ->where('status', 'active')
                    ->orderBy('created_at', 'DESC')
                    ->first();

                if ($parentIssue) {
                    $targetIssueId = $parentIssue['id'];
                    $data['parent_comment_id'] = $parentIssue['id'];
                }
            }

            // Mark the target issue as closed
            if ($targetIssueId) {
                $this->commentsModel->update($targetIssueId, [
                    'status' => 'closed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $commentId = $this->commentsModel->insert($data);

        if ($commentId) {
            $this->createCommentNotification($commentId, $listingId, $this->currentUserId);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Comment added successfully',
                'comment_id' => $commentId,
                'trail_info' => $commentType === 'resolution' && isset($parentIssue) ? [
                    'parent_issue_id' => $parentIssue['id'],
                    'is_linked' => true
                ] : ['is_linked' => false]
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add comment'
            ]);
        }
    }

    private function createCommentNotification($commentId, $listingId, $senderId)
    {
        $jobListing = $this->jobListingModel
            ->select('rc_job_listing.*, departments.hod_employee_id')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->where('rc_job_listing.id', $listingId)
            ->first();

        if (!$jobListing) {
            return false;
        }

        $receiverId = $this->determineNotificationReceiver($jobListing, $senderId);

        if ($receiverId && $receiverId != $senderId) {
            log_message('info', "Comment notification would be sent to user {$receiverId} for comment {$commentId}");
            return true;
        }

        return false;
    }

    // private function determineNotificationReceiver($jobListing, $senderId)
    // {
    //     $hrExecutive = $this->hrExecutive;
    //     $createdBy = $jobListing['created_by'];
    //     $hodId = $jobListing['hod_employee_id'];
    //     $approvedByHrExecutive = $jobListing['approved_by_hr_executive'];
    //     $approvedByHod = $jobListing['approved_by_hod'] ?? null;
    //     $approvedByHrManager = $jobListing['approved_by_hr_manager'];

    //     if (empty($approvedByHrExecutive)) {
    //         if ($senderId == $hrExecutive) {
    //             return $createdBy;
    //         } else {
    //             return $hrExecutive;
    //         }
    //     }

    //     if (!empty($approvedByHrExecutive) && empty($approvedByHod)) {
    //         if ($senderId == $hrExecutive) {
    //             return $hodId;
    //         } else {
    //             return $hrExecutive;
    //         }
    //     }

    //     if (!empty($approvedByHrExecutive) && !empty($approvedByHod) && empty($approvedByHrManager)) {
    //         if ($senderId == $hrExecutive) {
    //             return $hodId;
    //         } else {
    //             return $hrExecutive;
    //         }
    //     }

    //     if (!empty($approvedByHrManager)) {
    //         if ($senderId == $hrExecutive) {
    //             return $hodId;
    //         } else {
    //             return $hrExecutive;
    //         }
    //     }

    //     return $hrExecutive;
    // }


    private function determineNotificationReceiver($jobListing, $senderId)
    {
        $hrExecutive  = $this->hrExecutive;
        $createdBy    = $jobListing['created_by'];
        $hodId        = $jobListing['hod_employee_id'];
        $approvedExec = $jobListing['approved_by_hr_executive'];
        $approvedHod  = $jobListing['approved_by_hod'] ?? null;
        $approvedMgr  = $jobListing['approved_by_hr_manager'];

        if (empty($approvedExec)) {
            return $senderId == $hrExecutive ? $createdBy : $hrExecutive;
        }

        if (empty($approvedHod)) {
            return $senderId == $hrExecutive ? $hodId : $hrExecutive;
        }

        if (empty($approvedMgr)) {
            return $senderId == $hrExecutive ? $hodId : $hrExecutive;
        }

        return $senderId == $hrExecutive ? $hodId : $hrExecutive;
    }



    public function markIssuesAsClosed()
    {
        $jobId = $this->request->getPost('job_id');
        $currentUserId = $this->currentUserId;

        if (!$jobId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Job ID is required'
            ]);
        }

        $jobListing = $this->jobListingModel
            ->select('rc_job_listing.*, departments.hod_employee_id')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->where('rc_job_listing.id', $jobId)
            ->first();

        $activeResolutions = $this->commentsModel
            ->where('listing_id', $jobId)
            ->where('type', 'resolution')
            ->where('status', 'active')
            ->findAll();

        $closedCount = 0;
        foreach ($activeResolutions as $resolution) {
            $expectedReceiver = $this->determineNotificationReceiver($jobListing, $resolution['sender_id']);

            if ($expectedReceiver == $currentUserId && $resolution['sender_id'] != $currentUserId) {
                $this->commentsModel->update($resolution['id'], [
                    'status' => 'closed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $closedCount++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Marked {$closedCount} resolutions as closed",
            'closed_count' => $closedCount
        ]);
    }

    public function getComments($listingId)
    {
        if ($this->request->isAJAX()) {
            $comments = $this->commentsModel->getCommentsWithTrails($listingId);

            $issueCounter = 0;

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

                if ($comment['type'] === 'issue') {
                    $issueCounter++;
                    $comment['trail_number'] = $issueCounter;
                    $comment['trail_type'] = 'issue';
                } elseif ($comment['type'] === 'resolution' && !empty($comment['parent_comment_id'])) {
                    $parentTrailNumber = 1; // Default
                    foreach ($comments as $parentComment) {
                        if ($parentComment['id'] == $comment['parent_comment_id'] && $parentComment['type'] === 'issue') {
                            $parentTrailNumber = isset($parentComment['trail_number']) ? $parentComment['trail_number'] : 1;
                            break;
                        }
                    }
                    $comment['trail_number'] = $parentTrailNumber;
                    $comment['trail_type'] = 'resolution';
                    $comment['parent_issue_id'] = $comment['parent_comment_id'];
                } else {
                    $comment['trail_number'] = null;
                    $comment['trail_type'] = 'standalone';
                }

                unset($comment['attachment']);
            }

            usort($comments, function ($a, $b) {
                if ($a['trail_number'] != $b['trail_number']) {
                    return ($a['trail_number'] ?? 999) - ($b['trail_number'] ?? 999);
                }
                if ($a['trail_type'] != $b['trail_type']) {
                    return $a['trail_type'] === 'issue' ? -1 : 1;
                }
                return strtotime($a['created_at']) - strtotime($b['created_at']);
            });

            return $this->response->setJSON([
                'status' => 'success',
                'comments' => $comments,
                'count' => count($comments),
                'trail_summary' => [
                    'total_issues' => $issueCounter,
                    'total_resolutions' => count(array_filter($comments, fn($c) => $c['type'] === 'resolution'))
                ]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    public function getNotifications()
    {
        try {
            $currentUserId = $this->currentUserId;

            if (!$currentUserId) {
                return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
            }

            $allComments = $this->commentsModel
                ->select('rc_job_listing_comments.*, rc_job_listing_comments.created_at as comment_created_at')
                ->select('rc_job_listing.*, rc_job_listing.id as job_id')
                ->select('CONCAT(sender.first_name, " ", sender.last_name) as sender_name')
                ->select('companies.company_short_name')
                ->select('departments.hod_employee_id')
                ->join('rc_job_listing', 'rc_job_listing.id = rc_job_listing_comments.listing_id', 'left')
                ->join('companies', 'companies.id = rc_job_listing.company_id', 'left')
                ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
                ->join('employees as sender', 'sender.id = rc_job_listing_comments.sender_id', 'left')
                ->where('rc_job_listing_comments.status', 'active')
                ->where('rc_job_listing_comments.sender_id !=', $currentUserId)
                ->orderBy('rc_job_listing_comments.created_at', 'DESC')
                ->findAll();

            $userNotifications = [];
            foreach ($allComments as $comment) {
                $receiverId = $this->determineNotificationReceiver($comment, $comment['sender_id']);
                if ($receiverId == $currentUserId) {
                    $userNotifications[] = $comment;
                }
            }

            $groupedNotifications = [];
            $totalUnread = 0;

            foreach ($userNotifications as $notification) {
                $jobId = $notification['job_id'];

                if (!isset($groupedNotifications[$jobId])) {
                    $groupedNotifications[$jobId] = [
                        'job_id' => $jobId,
                        'job_title' => $notification['job_title'],
                        'company_name' => $notification['company_short_name'],
                        'unread_count' => 0,
                        'latest_message' => null,
                        'latest_sender' => null,
                        'latest_time' => null
                    ];
                }

                $groupedNotifications[$jobId]['unread_count']++;
                $totalUnread++;

                if (
                    !$groupedNotifications[$jobId]['latest_time'] ||
                    strtotime($notification['comment_created_at']) > strtotime($groupedNotifications[$jobId]['latest_time'])
                ) {
                    $groupedNotifications[$jobId]['latest_message'] = strip_tags($notification['content']);
                    $groupedNotifications[$jobId]['latest_sender'] = $notification['sender_name'];
                    $groupedNotifications[$jobId]['latest_time'] = $notification['comment_created_at'];
                    $groupedNotifications[$jobId]['latest_type'] = $notification['type'];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'notifications' => array_values($groupedNotifications),
                'total_unread' => $totalUnread
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'getNotifications error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to load notifications'
            ]);
        }
    }

    public function markAsRead()
    {
        $jobId = $this->request->getPost('job_id');
        $currentUserId = $this->currentUserId;

        if (!$jobId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Job ID is required'
            ]);
        }

        // For now, we'll just return success since we're not using a reads tracking table
        // This can be implemented with session storage or other mechanisms if needed
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Notifications marked as read'
        ]);
    }

    public function getUnreadCount()
    {
        $currentUserId = $this->currentUserId;

        $allComments = $this->commentsModel
            ->select('rc_job_listing_comments.*, rc_job_listing.*')
            ->select('departments.hod_employee_id')
            ->join('rc_job_listing', 'rc_job_listing.id = rc_job_listing_comments.listing_id', 'left')
            ->join('departments', 'departments.id = rc_job_listing.department_id', 'left')
            ->where('rc_job_listing_comments.status', 'active')
            ->where('rc_job_listing_comments.sender_id !=', $currentUserId)
            ->findAll();
        //print_r($allComments);
        $count = 0;
        foreach ($allComments as $comment) {
            $receiverId = $this->determineNotificationReceiver($comment, $comment['sender_id']);
            if ($receiverId == $currentUserId) {
                $count++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $count
        ]);
    }

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
