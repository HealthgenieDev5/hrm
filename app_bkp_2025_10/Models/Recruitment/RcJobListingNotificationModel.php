<?php

namespace App\Models\Recruitment;

use CodeIgniter\Model;

class RcJobListingNotificationModel extends Model
{
    protected $table            = 'rc_job_listing_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_listing_id',
        'user_id',
        'read_at',
    ];

    // No need for timestamps as we have read_at
    protected $useTimestamps = false;
}
