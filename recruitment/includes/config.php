<?php
session_start();
/** The name of the database for SC APP **/
define('DB_NAME', 'hrm.recruitment');

/** MySQL database username **/
define('DB_USER', 'hgcrm');

/** MySQL database password **/
define('DB_PASSWORD', 'J2HxGcldIv1O86S');

/** MySQL hostname **/
define('DB_HOST', 'localhost');

/** SITE_URL **/
define('SITE_URL', 'http://hrm.healthgenie.in/recruitment');

/** SITE_NAME **/
define('SITE_NAME', 'HR Management');

/** BASE_PATH **/
define('BASE_PATH', '/home/healthgeniehrm/public_html/recruitment');

/**Time zone**/
date_default_timezone_set('Asia/Kolkata');

/**Connection variable**/
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/**Check connection**/
if(!$conn){
	?><h1 style='margin-top:50px; margin-left: 50px; font-family:cursive;'><?php
	die('Error when establishing database connection');
	?></h1><?php
}

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

date_default_timezone_set('Asia/Kolkata');
?>