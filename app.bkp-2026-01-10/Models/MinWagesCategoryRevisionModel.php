<?php
namespace App\Models;
use CodeIgniter\Model;

class MinWagesCategoryRevisionModel extends Model{
	protected $table = 'minimum_wages_categories_revision';
	protected $allowedFields = ['minimum_wages_category_id', 'minimum_wages_category_name', 'minimum_wages_category_state', 'minimum_wages_category_value', 'created_by', 'minimum_wages_category_status', 'date_time', 'revised_by'];
}
?>