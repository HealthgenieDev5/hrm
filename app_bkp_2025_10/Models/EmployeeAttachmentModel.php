<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeAttachmentModel extends Model
{
    protected $table            = 'employee_attachments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employee_id',
        'title',
        'file_path',
        'file_name',
        'file_extension',
        'file_size',
        'uploaded_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    protected $validationRules = [
        'employee_id'    => 'required|integer',
        'title'          => 'required|max_length[255]',
        'file_path'      => 'required|max_length[500]',
        'file_name'      => 'required|max_length[255]',
        'file_extension' => 'required|max_length[10]'
    ];

    protected $validationMessages = [
        'employee_id' => [
            'required' => 'Employee ID is required',
            'integer'  => 'Employee ID must be a valid number'
        ],
        'title' => [
            'required'   => 'Document title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'file_path' => [
            'required'   => 'File path is required',
            'max_length' => 'File path cannot exceed 500 characters'
        ],
        'file_name' => [
            'required'   => 'File name is required',
            'max_length' => 'File name cannot exceed 255 characters'
        ],
        'file_extension' => [
            'required'   => 'File extension is required',
            'max_length' => 'File extension cannot exceed 10 characters'
        ]
    ];

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
    // protected $beforeDelete   = ['deletePhysicalFile'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getEmployeeAttachments($employeeId)
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }


    // protected function deletePhysicalFile(array $data)
    // {
    //     if (isset($data['id'])) {
    //         $attachmentId = is_array($data['id']) ? $data['id'][0] : $data['id'];
    //         $attachment = $this->find($attachmentId);

    //         if ($attachment && isset($attachment['file_path'])) {
    //             $filePath = WRITEPATH . str_replace('/uploads/', 'uploads/', $attachment['file_path']);
    //             if (file_exists($filePath)) {
    //                 @unlink($filePath);
    //             }
    //         }
    //     }

    //     return $data;
    // }


    public function getAttachmentCount($employeeId)
    {
        return $this->where('employee_id', $employeeId)->countAllResults();
    }


    public function getTotalFileSize($employeeId)
    {
        $result = $this->selectSum('file_size')
            ->where('employee_id', $employeeId)
            ->first();

        return $result['file_size'] ?? 0;
    }
}
