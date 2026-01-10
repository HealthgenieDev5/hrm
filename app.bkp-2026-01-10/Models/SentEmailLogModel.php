<?php

namespace App\Models;

use CodeIgniter\Model;

class SentEmailLogModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sent_email_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email_date', 'recipients', 'email_type', 'status', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function hasEmailBeenSentToday($emailType)
    {
        return $this->where('email_date', date('Y-m-d'))
                    ->where('email_type', $emailType)
                    ->countAllResults() > 0;
    }

    public function logEmailSent($recipients, $emailType, $status)
    {
        return $this->insert([
            'email_date' => date('Y-m-d'),
            'recipients' => json_encode($recipients),
            'email_type' => $emailType,
            'status' => $status
        ]);
    }
}
