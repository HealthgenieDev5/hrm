<?php
namespace App\Libraries;

class Hash{
	public static function make($password){
		// return password_hash($password, PASSWORD_DEFAULT);
		return md5($password);
	}

	public static function check($entered_password, $db_password){
		if( md5($entered_password) == $db_password ){
			return true;
		}else{
			return false;
		}
	}
}
?>