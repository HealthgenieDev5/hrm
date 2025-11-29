<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<base href="">
		<title>
			<?php 
			if( isset($page_title) && !empty($page_title) ){
				echo $page_title.' | '.SITE_NAME;
			}else{
				echo SITE_NAME;
			}
			?>				
			</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		