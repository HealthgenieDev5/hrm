<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveRequestsModel extends Model{
	protected $table = 'leave_requests';
	protected $allowedFields = [
        'employee_id', 
        'from_date', 
        'to_date', 
        'type_of_leave', 
        'number_of_days', 
        'day_type', 
        'address_d_l', 
        'emergency_contact_d_l', 
        'reason_of_leave', 
        'sick_leave', 
        'backend_request', 
        'attachment', 
        'status', 
        'reviewed_by', 
        'reviewed_date', 
        'remarks'
    ];

    public function get_user_leaves( $user_id ){
    	if( isset($user_id) && !empty($user_id) ){
    		$builder = $this->db->table('leave_requests lr')
                            ->select('lr.*')
                            ->select('concat(e.first_name, " ", e.last_name) as reviewed_by_name')
                            ->join('employees e', 'e.id = lr.reviewed_by','left')
                            ->where('lr.employee_id = ', $user_id);
        	$query = $builder->get();
        	// return $query;
        	return $this->db->getlastQuery()->getQuery();
    	}else{
    		return null;
    	}
    }
}
?>