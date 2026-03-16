<?php

namespace App\Models;

use CodeIgniter\Model;

class ResignationRevisionModel extends Model
{
    protected $table = 'resignations_revision';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'resignation_id',
        'revision_data',
        'action',
        'action_by',
        'action_note',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';

    protected $validationRules = [
        'resignation_id' => 'required|integer',
        'revision_data' => 'required',
        'action' => 'required|in_list[created,updated,completed,withdrawn]',
        'action_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'resignation_id' => [
            'required' => 'Resignation ID is required',
            'integer' => 'Resignation ID must be a valid number'
        ],
        'revision_data' => [
            'required' => 'Revision data is required'
        ],
        'action' => [
            'required' => 'Action is required',
            'in_list' => 'Action must be one of: created, updated, completed, withdrawn'
        ],
        'action_by' => [
            'required' => 'Action by employee is required',
            'integer' => 'Action by must be a valid employee ID'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Save resignation revision
     *
     * @param int $resignation_id
     * @param array $resignation_data Complete resignation data
     * @param string $action Action type: created, updated, completed, withdrawn
     * @param int $action_by Employee ID who performed action
     * @param string|null $action_note Optional note
     * @return bool|int
     */
    public function saveRevision(int $resignation_id, array $resignation_data, string $action, int $action_by, ?string $action_note = null)
    {
        $data = [
            'resignation_id' => $resignation_id,
            'revision_data' => json_encode($resignation_data),
            'action' => $action,
            'action_by' => $action_by,
            'action_note' => $action_note,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Get revision history for a resignation
     *
     * @param int $resignation_id
     * @return array
     */
    public function getRevisionHistory(int $resignation_id)
    {
        $builder = $this->db->table($this->table . ' rr');
        $builder->select("
            rr.id,
            rr.resignation_id,
            rr.revision_data,
            rr.action,
            rr.action_note,
            rr.created_at,
            TRIM(CONCAT(e.first_name, ' ', e.last_name)) as action_by_name,
            e.internal_employee_id as action_by_emp_id
        ");
        $builder->join('employees e', 'e.id = rr.action_by', 'left');
        $builder->where('rr.resignation_id', $resignation_id);
        $builder->orderBy('rr.created_at', 'DESC');

        $results = $builder->get()->getResultArray();

        // Decode JSON data for each revision
        foreach ($results as &$revision) {
            $revision['revision_data'] = json_decode($revision['revision_data'], true);
        }

        return $results;
    }

    /**
     * Get latest revision for a resignation
     *
     * @param int $resignation_id
     * @return array|null
     */
    public function getLatestRevision(int $resignation_id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('resignation_id', $resignation_id);
        $builder->orderBy('created_at', 'DESC');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();

        if ($result) {
            $result['revision_data'] = json_decode($result['revision_data'], true);
        }

        return $result;
    }

    /**
     * Compare two revisions and get differences
     *
     * @param array $old_data
     * @param array $new_data
     * @return array Array of changes
     */
    public function compareRevisions(array $old_data, array $new_data): array
    {
        $changes = [];

        foreach ($new_data as $key => $new_value) {
            $old_value = $old_data[$key] ?? null;

            if ($old_value != $new_value) {
                $changes[$key] = [
                    'old' => $old_value,
                    'new' => $new_value
                ];
            }
        }

        return $changes;
    }
}
