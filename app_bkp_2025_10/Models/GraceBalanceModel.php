<?php
namespace App\Models;
use CodeIgniter\Model;

class GraceBalanceModel extends Model{
	protected $table = 'grace_balance';
	protected $primaryKey       = 'id';
	protected $allowedFields = ['employee_id', 'minutes', 'year_month', 'updated_at'];

	public function updateOrCreate(array $searchConditions, array $data): bool
    {
        // First, attempt to find the record based on the search conditions
        $record = $this->where($searchConditions)->first();

        if ($record) {
            // If the record exists, perform an update
            $id = $record[$this->primaryKey];
            return $this->update($id, $data);
        } else {
            // If the record does not exist, perform an insert
            return $this->insert($data);
        }
    }
}
?>