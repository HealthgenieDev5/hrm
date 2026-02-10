<?php
namespace App\Models;
use CodeIgniter\Model;

class CustomModel extends Model{
	
	public function CustomQuery( $sql ){
		// return $this->db->query($sql)->getResultArray();
		return $this->db->query($sql);
	}
}
?>