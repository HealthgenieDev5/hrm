<?php
session_start();
/** The name of the database for SC APP **/
define('DB_NAME', 'hrm.healthgenie.in');

/** MySQL database username **/
define('DB_USER', 'hgcrm');

/** MySQL database password **/
define('DB_PASSWORD', '9pEiLO6EmmoIeTU@!@#$%^');

/** MySQL hostname **/
define('DB_HOST', 'localhost');

/** SITE_URL **/
define('SITE_URL', 'http://hrm.healthgenie.in/appraisal');

/** SITE_NAME **/
define('SITE_NAME', 'GSTC Appraisal');

/** BASE_PATH **/
define('BASE_PATH', '/home/healthgeniehrm/public_html/appraisal');

/** Redirect if configuration failure **/
if(!defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASSWORD') || !defined('DB_HOST') || !defined('SITE_URL') || !defined('BASE_PATH')){
	$page_title = 'Config Error';
	$title = 'Error';
	$message = 'DB Detailes are not correct';
	header('location:'.SITE_URL.'/404.php?pt='.$page_title.'&t='.$title.'&m='.$message);
	die();
}

/**Time zone**/
date_default_timezone_set('Asia/Kolkata');

/**Connection variable**/
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/**Check connection**/
if(!$conn){
	$page_title = 'DB Error';
	$title = 'Error';
	$message = 'Error when establishing database connection';
	header('location:'.SITE_URL.'/404.php?pt='.$page_title.'&t='.$title.'&m='.$message);
	die();
}

// include(BASE_PATH."/functions/mail/mail-function.php");
/** That's all, stop editing! **/

if( !function_exists('base64_url_encode') ) {
	function base64_url_encode($input) {
		return strtr( base64_encode($input), '+/=', '._-' );
	}
}

if( !function_exists('base64_url_decode') ) {
	function base64_url_decode($input) {
		return base64_decode( strtr($input, '._-', '+/=') );
	}
}


?>