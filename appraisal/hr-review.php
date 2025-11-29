<?php $page_title = 'Review'; ?>
<?php include_once("./inc/header-top.php") ?>

<?php
if( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
	header('location:'.SITE_URL.'/login.php');
}

?>
<!--begin::Custom Css-->
<style type="text/css">
	#footer {
		position: fixed;
		z-index: 10;
		bottom: 0px;
		width: calc(100% - 300px);
	}
	body.toggle-sidebar #footer {
		width: 100%;
	}
	#main {
		margin-bottom: 87px;
	}
	.left-side{
		border-radius:20px 20px 0px 0px;
		/*background-color:#304767;*/
		background-color:#592a45;
	}
	.right-side{
		border-radius:0px 0px 20px 20px;
		/*background-color:#304767;*/
	}
	@media only screen and (min-width: 1200px){
		.left-side{
			border-radius:20px 0px 0px 20px;
		}
		.right-side{
			border-radius:0px 20px 20px 0px;
		}
	}
	#progressbar {
	    margin-bottom: 30px;
	    overflow: hidden;
	    color: lightgrey;
	}

	#progressbar .active {
	    color: #000000;
	}

	#progressbar li {
	    list-style-type: none;
	    font-size: 12px;
	    width: 25%;
	    float: left;
	    position: relative;
	}

	/*Icons in the ProgressBar*/
	#progressbar #performance:before {
	    font-family: FontAwesome;
	    /*content: "\f023";*/
	    /*content: "\f2be";*/
	    content: "\f140";
	}

	#progressbar #questions:before {
	    font-family: FontAwesome;
	    /*content: "\f007";*/
	    content: "\f128";
	}

	#progressbar #kra:before {
	    font-family: FontAwesome;
	    /*content: "\f09d";*/
	    content: "\f044";
	}

	#progressbar #personal_traits:before {
	    font-family: FontAwesome;
	    /*content: "\f09d";*/
	    content: "\f007";
	}

	#progressbar #confirm:before {
	    font-family: FontAwesome;
	    content: "\f00c";
	}

	/*ProgressBar before any progress*/
	#progressbar li:before {
	    width: 50px;
	    height: 50px;
	    line-height: 45px;
	    display: block;
	    font-size: 18px;
	    color: #ffffff;
	    background: lightgray;
	    border-radius: 50%;
	    margin: 0 auto 10px auto;
	    padding: 2px;
	    text-align: center;
	    position: relative;
	    z-index: 1;
	}

	/*ProgressBar connectors*/
	#progressbar li:after {
	    content: '';
	    width: 100%;
	    height: 2px;
	    background: lightgray;
	    position: absolute;
	    left: 0;
	    top: 25px;
	    z-index: 0;
	}

	/*Color number of the step and the connector before it*/
	#progressbar li.active:before, #progressbar li.active:after {
	    background: skyblue;
	}

	.form-step:not(:first-of-type) {
	    display: none;
	}

	.my-value-tooltip {
		top: 0;
		z-index: 1;
		padding: 5px 10px !important;
		margin-top: calc(-150% - 2px);
		left: 0;
		margin-left: calc(-50% + 2px);
	}
	.my-value-tooltip:after {
		content: "";
		background: transparent;
		width: 10px;
		height: 10px;
		position: absolute;
		bottom: -50%;
		left: 50%;
		transform: rotate(0deg) translate(-50%, -50%);
		border: 5px solid transparent;
		border-top: 5px solid rgba(var(--bs-dark-rgb),var(--bs-bg-opacity));
	}
	.rating-clear{
		height: 16px;
		line-height: 1;
	}
	table td{
		vertical-align: middle;
	}
</style>
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php") ?>
<?php include_once("./inc/page-header.php") ?>
<main id="main" class="main">
	<div class="pagetitle">
		<h1>Appraisal</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="index.html">Home</a></li>
				<li class="breadcrumb-item active">Appraisal</li>
			</ol>
		</nav>
	</div>
	<!-- End Page Title -->

	<?php
	$logged_in_user_id = $_SESSION['login']['id'];
	$logged_in_user_role = $_SESSION['login']['user_role'];
	if( isset($_GET['employee_id']) && !empty($_GET['employee_id']) ){
		$current_employee_id = $_GET['employee_id'];
		$sql = "select e.*, 
		d.department_name as department_name, 
		d.hod_employee_id as hod_employee_id, 
		dg.designation_name as designation_name, 
		c.company_name as company_name, 
		c.address as company_address, 
		c.city as company_city, 
		c.state as company_state, 
		c.pincode as company_pincode, 
		u.role as user_role, 
		aor.review_period_from as review_period_from, 
		aor.review_period_to as review_period_to, 
		aor.overall_rating as overall_rating, 
		aor.remarks as remarks 
		from employees e 
		left join departments d on d.id = e.department_id 
		left join designations dg on dg.id = e.designation_id 
		left join companies c on c.id = e.company_id 
		left join users u on u.id = e.id
		left join appr_overall_rating aor on aor.employee_id = e.id
		where e.id = '".$current_employee_id."'";
		$query = mysqli_query($conn, $sql) or die('unable to fetch current employee data'. mysqli_error($conn));
		if( mysqli_num_rows($query) == 1 ){
			$current_employee_data = mysqli_fetch_assoc($query);

			if( $current_employee_data['reporting_manager_id'] == $logged_in_user_id || $current_employee_data['hod_employee_id'] == $logged_in_user_id || in_array($logged_in_user_role, array('admin', 'superuser')) ){
				$form_submitted = false;
				if( isset($_REQUEST) && !empty($_REQUEST) ){
					/*echo '<pre>';
					print_r($_REQUEST);
					echo '</pre>';*/
					$employee_id = ( isset($_REQUEST['employee_id']) && !empty($_REQUEST['employee_id']) ) ? $_REQUEST['employee_id'] : '';
					$review_period_from = ( isset($_REQUEST['review_period_from']) && !empty($_REQUEST['review_period_from']) ) ? $_REQUEST['review_period_from'] : '';
					$review_period_to = ( isset($_REQUEST['review_period_to']) && !empty($_REQUEST['review_period_to']) ) ? $_REQUEST['review_period_to'] : '';
					$year = ( isset($_REQUEST['year']) && !empty($_REQUEST['year']) ) ? $_REQUEST['year'] : '';

					if( !empty($employee_id) && !empty($year) ){
						##################appr_overall_rating##################
						/*$find_existing_in_appr_overall_rating_sql = "select * from appr_overall_rating where employee_id = '".$employee_id."' and year = '".$year."'";
						$find_existing_in_appr_overall_rating_query = mysqli_query($conn, $find_existing_in_appr_overall_rating_sql) or die('error while searching for existing appr_overall_rating entry'. mysqli_error($conn));
						if(mysqli_num_rows($find_existing_in_appr_overall_rating_query) == 1){
							$appr_overall_rating_id = mysqli_fetch_assoc($find_existing_in_appr_overall_rating_query)['id'];
							$update_in_appr_overall_rating_sql = "update appr_overall_rating set review_period_from = '".$review_period_from."', review_period_to = '".$review_period_to."' where id = '".$appr_overall_rating_id."'";
							$update_in_appr_overall_rating_query = mysqli_query($conn, $update_in_appr_overall_rating_sql) or die('error while updating appr_overall_rating entry'. mysqli_error($conn));
						}else{
							$insert_in_appr_overall_rating_sql = "insert into appr_overall_rating (review_period_from, review_period_to, employee_id, year) values ('".$review_period_from."', '".$review_period_to."', '".$employee_id."', '".$year."')";
							$insert_in_appr_overall_rating_query = mysqli_query($conn, $insert_in_appr_overall_rating_sql) or die('error while inserting appr_overall_rating entry'. mysqli_error($conn));
						}*/
						##################appr_overall_rating##################
						##################appr_performance_history_response##################
						/*if( isset($_REQUEST['appr_performance_history_response']) && !empty($_REQUEST['appr_performance_history_response']) ){
							foreach( $_REQUEST['appr_performance_history_response'] as $parameter_id => $response){
								$find_existing_in_appr_performance_history_response_sql = "select * from appr_performance_history_response where employee_id = '".$employee_id."' and year = '".$year."' and parameter_id = '".$parameter_id."'";
								$find_existing_in_appr_performance_history_response_query = mysqli_query($conn, $find_existing_in_appr_performance_history_response_sql) or die('error while searching for existing appr_performance_history_response entry'. mysqli_error($conn));
								if(mysqli_num_rows($find_existing_in_appr_performance_history_response_query) == 1){
									$appr_performance_history_response_id = mysqli_fetch_assoc($find_existing_in_appr_performance_history_response_query)['id'];
									$update_in_appr_performance_history_response_sql = "update appr_performance_history_response set response = '".$response."' where id = '".$appr_performance_history_response_id."'";
									$update_in_appr_performance_history_response_query = mysqli_query($conn, $update_in_appr_performance_history_response_sql) or die('error while updating appr_performance_history_response entry'. mysqli_error($conn));
								}else{
									$insert_in_appr_performance_history_response_sql = "insert into appr_performance_history_response (employee_id, parameter_id, response, year) values ('".$employee_id."', '".$parameter_id."', '".$response."', '".$year."')";
									$insert_in_appr_performance_history_response_query = mysqli_query($conn, $insert_in_appr_performance_history_response_sql) or die('error while inserting appr_performance_history_response entry'. mysqli_error($conn));
								}
							}
						}*/
						##################appr_performance_history_response##################
						##################appr_questions_response##################
						/*if( isset($_REQUEST['appr_questions_response']) && !empty($_REQUEST['appr_questions_response']) ){
							foreach( $_REQUEST['appr_questions_response'] as $question_id => $answer){
								$find_existing_in_appr_questions_response_sql = "select * from appr_questions_response where employee_id = '".$employee_id."' and year = '".$year."' and question_id = '".$question_id."'";
								$find_existing_in_appr_questions_response_query = mysqli_query($conn, $find_existing_in_appr_questions_response_sql) or die('error while searching for existing appr_questions_response entry'. mysqli_error($conn));
								if(mysqli_num_rows($find_existing_in_appr_questions_response_query) == 1){
									$appr_questions_response_id = mysqli_fetch_assoc($find_existing_in_appr_questions_response_query)['id'];
									$update_in_appr_questions_response_sql = "update appr_questions_response set answer = '".$answer."' where id = '".$appr_questions_response_id."'";
									$update_in_appr_questions_response_query = mysqli_query($conn, $update_in_appr_questions_response_sql) or die('error while updating appr_questions_response entry'. mysqli_error($conn));
								}else{
									$insert_in_appr_questions_response_sql = "insert into appr_questions_response (employee_id, question_id, answer, year) values ('".$employee_id."', '".$question_id."', '".$answer."', '".$year."')";
									$insert_in_appr_questions_response_query = mysqli_query($conn, $insert_in_appr_questions_response_sql) or die('error while inserting appr_questions_response entry'. mysqli_error($conn));
								}
							}
						}*/
						##################appr_questions_response##################
						##################appr_kra_response##################
						if( isset($_REQUEST['appr_kra_hod_rating']) && !empty($_REQUEST['appr_kra_hod_rating']) ){
							foreach( $_REQUEST['appr_kra_hod_rating'] as $accountability_id => $hod_rating){
								if( $hod_rating == '' ){
									$hod_rating = 'NULL';
								}
								$find_existing_in_appr_kra_response_sql = "select * from appr_kra_response where employee_id = '".$employee_id."' and year = '".$year."' and accountability_id = '".$accountability_id."'";
								$find_existing_in_appr_kra_response_query = mysqli_query($conn, $find_existing_in_appr_kra_response_sql) or die('error while searching for existing appr_kra_response entry'. mysqli_error($conn));
								if(mysqli_num_rows($find_existing_in_appr_kra_response_query) == 1){
									$appr_kra_response_id = mysqli_fetch_assoc($find_existing_in_appr_kra_response_query)['id'];
									$update_in_appr_kra_response_sql = "update appr_kra_response set hod_rating = '".$hod_rating."' where id = '".$appr_kra_response_id."'";
									$update_in_appr_kra_response_query = mysqli_query($conn, $update_in_appr_kra_response_sql) or die('error while updating appr_kra_response entry'. mysqli_error($conn));
								}else{
									$insert_in_appr_kra_response_sql = "insert into appr_kra_response (employee_id, accountability_id, hod_rating, year) values ('".$employee_id."', '".$accountability_id."', ".$hod_rating.", '".$year."')";
									$insert_in_appr_kra_response_query = mysqli_query($conn, $insert_in_appr_kra_response_sql) or die('error while inserting appr_kra_response entry'. mysqli_error($conn));
								}
							}
						}
						##################appr_kra_response##################
						##################appr_employee_personal_traits_reponse##################
						if( isset($_REQUEST['personal_trait_hod_rating']) && !empty($_REQUEST['personal_trait_hod_rating']) ){
							foreach( $_REQUEST['personal_trait_hod_rating'] as $personal_trait_id => $hod_rating){
								if( $hod_rating == '' ){
									$hod_rating = 'NULL';
								}
								$find_existing_in_appr_employee_personal_traits_reponse_sql = "select * from appr_employee_personal_traits_reponse where employee_id = '".$employee_id."' and year = '".$year."' and personal_trait_id = '".$personal_trait_id."'";
								$find_existing_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $find_existing_in_appr_employee_personal_traits_reponse_sql) or die('error while searching for existing appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								if(mysqli_num_rows($find_existing_in_appr_employee_personal_traits_reponse_query) == 1){
									$appr_employee_personal_traits_reponse_id = mysqli_fetch_assoc($find_existing_in_appr_employee_personal_traits_reponse_query)['id'];
									$update_in_appr_employee_personal_traits_reponse_sql = "update appr_employee_personal_traits_reponse set hod_rating = '".$hod_rating."' where id = '".$appr_employee_personal_traits_reponse_id."'";
									$update_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $update_in_appr_employee_personal_traits_reponse_sql) or die('error while updating appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								}else{
									$insert_in_appr_employee_personal_traits_reponse_sql = "insert into appr_employee_personal_traits_reponse (employee_id, personal_trait_id, hod_rating, year) values ('".$employee_id."', '".$personal_trait_id."', ".$hod_rating.", '".$year."')";
									$insert_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $insert_in_appr_employee_personal_traits_reponse_sql) or die('error while inserting appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								}
							}
						}
						if( isset($_REQUEST['personal_trait_reporting_manager_rating']) && !empty($_REQUEST['personal_trait_reporting_manager_rating']) ){
							foreach( $_REQUEST['personal_trait_reporting_manager_rating'] as $personal_trait_id => $reporting_manager_rating){
								if( $reporting_manager_rating == '' ){
									$reporting_manager_rating = 'NULL';
								}
								$find_existing_in_appr_employee_personal_traits_reponse_sql = "select * from appr_employee_personal_traits_reponse where employee_id = '".$employee_id."' and year = '".$year."' and personal_trait_id = '".$personal_trait_id."'";
								$find_existing_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $find_existing_in_appr_employee_personal_traits_reponse_sql) or die('error while searching for existing appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								if(mysqli_num_rows($find_existing_in_appr_employee_personal_traits_reponse_query) == 1){
									$appr_employee_personal_traits_reponse_id = mysqli_fetch_assoc($find_existing_in_appr_employee_personal_traits_reponse_query)['id'];
									$update_in_appr_employee_personal_traits_reponse_sql = "update appr_employee_personal_traits_reponse set reporting_manager_rating = '".$reporting_manager_rating."' where id = '".$appr_employee_personal_traits_reponse_id."'";
									$update_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $update_in_appr_employee_personal_traits_reponse_sql) or die('error while updating appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								}else{
									$insert_in_appr_employee_personal_traits_reponse_sql = "insert into appr_employee_personal_traits_reponse (employee_id, personal_trait_id, reporting_manager_rating, year) values ('".$employee_id."', '".$personal_trait_id."', ".$reporting_manager_rating.", '".$year."')";
									$insert_in_appr_employee_personal_traits_reponse_query = mysqli_query($conn, $insert_in_appr_employee_personal_traits_reponse_sql) or die('error while inserting appr_employee_personal_traits_reponse entry'. mysqli_error($conn));
								}
							}
						}
						##################appr_employee_personal_traits_reponse##################

						$form_submitted = true;
					}
				}

				?>
				<div class="section">
					<div class="container">
					    <form class="card" id="appraisal_form" style="border-radius: 20px;" method="post">
						    <div class="card-body p-0">
						    	<div class="row m-0">
						    		<div class="col-xl-4 left-side d-flex flex-column flex-grow-1 py-4 px-4">
						    			<div class="text mb-3 border-bottom">
					                        <h3 class="text-white fw-bold">Personal Information</h3>
					                        <p class="text-white fst-italic" style="opacity: 0.7;">Please verify employee's personal information.<br><small style="font-size: 12px; line-height: 1.25; display: block;">In case the information is not correct, please contact HR</small></p>
					                        <!-- <p class="text-muted fst-italic">Please verify your personal information.<br>In case the information is not correct, please contact Developers</p> -->
					                    </div>
						    			<div class="flex-grow-1">
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Name</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo trim($current_employee_data['first_name'].' '.$current_employee_data['last_name']); ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Company</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo $current_employee_data['company_name']; ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Department</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo $current_employee_data['department_name']; ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Designation</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo $current_employee_data['designation_name']; ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Joining Date</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo date('d M Y', strtotime($current_employee_data['joining_date'])); ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Desk Location</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo $current_employee_data['desk_location']; ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Company Addr</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0">
						                    			<?php echo $current_employee_data['company_address']; ?>
						                    			<?php echo !empty($current_employee_data['company_city']) ? ', '.$current_employee_data['company_city'] : ''; ?>
						                    			<?php echo !empty($current_employee_data['company_state']) ? ', '.$current_employee_data['company_state'] : ''; ?>
						                    			<?php echo !empty($current_employee_data['company_pincode']) ? ', '.$current_employee_data['company_pincode'] : ''; ?>
						                    		</p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Employee Code</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo $current_employee_data['internal_employee_id']; ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Review Period</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo date('Y', strtotime('-1 years')).'-'.date('Y'); ?></p>
						                    	</div>
						                    </div>
					                    	<div class="row mb-2">
						                    	<div class="col-md-5">
						                    		<label class="form-label text-white fw-bold mb-0">Date</label>
						                    	</div>
						                    	<div class="col-md-7">
						                    		<p class="text-white mb-0"><?php echo date('d M Y'); ?></p>
						                    	</div>
						                    	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $current_employee_data['id']; ?>" />
						                    	<input type="hidden" id="review_period_from" name="review_period_from" value="<?php echo date('Y', strtotime('-1 years')); ?>" />
						                    	<input type="hidden" id="review_period_to" name="review_period_to" value="<?php echo date('Y'); ?>" />
						                    	<input type="hidden" id="year" name="year" value="<?php echo date('Y'); ?>" />
						                    </div>
					                    </div>
						    		</div>
						    		<div class="col-xl-8 right-side d-flex flex-column flex-grow-1 py-4 px-0">
						    			<h3 class="fw-bold text-center mt-3" id="step_title">Performance</h3>
						    			<p class="text-muted text-center fst-italic" id="step_description">This section should be completed by employee before the conclusion of the appraisal</p>
						    			<ul id="progressbar" class="px-0 d-flex">
			                                <li class="text-center active" id="performance"><strong>Performance</strong></li>
			                                <li id="questions" class="text-center"><strong>Questions</strong></li>
			                                <li id="kra" class="text-center"><strong>KRA</strong></li>
			                                <li id="personal_traits" class="text-center"><strong>Personal Traits</strong></li>
			                                <li id="confirm" class="text-center"><strong>Finish</strong></li>
			                            </ul>

			                            <fieldset class="flex-grow-1 flex-column px-5 form-step" data-title="Performance" data-description="This section should be completed by Employee before the conclusion of the appraisal" style="<?php if($form_submitted == true){ echo 'display: none;'; } ?>" >
			                            	<div class="d-flex flex-column flex-grow-1 mb-5">
			                            		<?php
												$current_employee_department_id = $current_employee_data['department_id'];
												$current_employee_company_id = $current_employee_data['company_id'];
												$current_employee_id = $current_employee_data['id'];
												$current_year = date('Y');
												$appr_performance_history_master_sql  = "select 
												aphm.id as id, 
												aphm.parameter as parameter, 
												aphr.response as response 
												from appr_performance_history_master aphm 
												left join appr_performance_history_response aphr 
												on (aphr.parameter_id = aphm.id) 
												and (aphr.employee_id = '".$current_employee_id."')
												and (aphr.year = aphm.year)
												where FIND_IN_SET('".$current_employee_department_id."', aphm.department_id) 
												and FIND_IN_SET('".$current_employee_company_id."', aphm.company_id) 
												and aphm.year = '".$current_year."' 
												group by aphm.id order by aphm.priority asc";
												$appr_performance_history_master_query = mysqli_query($conn, $appr_performance_history_master_sql) or die('unable to fetch app performance questions'.mysqli_error($conn));
												if( mysqli_num_rows($appr_performance_history_master_query) > 0 ){
													while( $row = mysqli_fetch_assoc($appr_performance_history_master_query) ){
														?>
														<div class="row mb-3">
															<div class="col-md-4 d-flex align-items-center">
																<label class="form-label mb-0 me-3 fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['parameter']; ?></label>
															</div>
															<div class="col-md-8 d-flex align-items-center">
																<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" id="appr_performance_history_response_<?php echo $row['id']; ?>" name="appr_performance_history_response[<?php echo $row['id']; ?>]" value="<?php echo $row['response']; ?>" disabled>
															</div>
														</div>
														<?php
													}
												}
												?>
			                            	</div>
			                            	<div class="d-flex align-items-center justify-content-center">
			                            		<!-- <button class="btn btn-secondary mx-2 previous action-button-previous">Previous</button> -->
			                                	<button class="btn btn-primary mx-2 next action-button">Next</button>
			                            	</div>
			                            </fieldset>

			                            <fieldset class="flex-grow-1 flex-column px-5 form-step" data-title="Questions" data-description="This section should be completed by Employee before the conclusion of the appraisal">
			                            	<div class="d-flex flex-column flex-grow-1 mb-5">
			                            		<?php
												$current_employee_department_id = $current_employee_data['department_id'];
												$current_employee_company_id = $current_employee_data['company_id'];
												$current_employee_id = $current_employee_data['id'];
												$current_year = date('Y');
												$appr_questions_master_sql  = "select 
												aqm.id as id, 
												aqm.question as question, 
												aqr.answer as answer 
												from appr_questions_master aqm 
												left join appr_questions_response aqr 
												on (aqr.question_id = aqm.id) 
												and (aqr.employee_id = '".$current_employee_id."') 
												and (aqr.year = aqm.year)
												where FIND_IN_SET('".$current_employee_department_id."', aqm.department_id) 
												and FIND_IN_SET('".$current_employee_company_id."', aqm.company_id) 
												and aqm.year = '".$current_year."' 
												group by aqm.id order by aqm.priority asc";
												$appr_questions_master_query = mysqli_query($conn, $appr_questions_master_sql) or die('unable to fetch questions'.mysqli_error($conn));
												if( mysqli_num_rows($appr_questions_master_query) > 0 ){
													$sr_no = 0;
													while( $row = mysqli_fetch_assoc($appr_questions_master_query) ){
														?>
														<div class="row mb-3">
															<div class="col-md-12 d-flex align-items-center">
																<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['question']; ?></label>
															</div>
															<div class="col-md-12 d-flex align-items-center">
																<textarea class="form-control" id="appr_questions_response_<?php echo $row['id']; ?>" name="appr_questions_response[<?php echo $row['id']; ?>]" disabled ><?php echo $row['answer']; ?></textarea>
															</div>
														</div>
														<?php
													}
												}
												?>
			                        		</div>
			                            	<div class="d-flex align-items-center justify-content-center">
			                            		<button class="btn btn-secondary mx-2 previous action-button-previous">Previous</button>
			                                	<button class="btn btn-primary mx-2 next action-button">Next</button>
			                            	</div>
			                            </fieldset>

			                            <fieldset class="flex-grow-1 flex-column px-5 form-step" data-title="KRA" data-description="This section should be completed by Employee and HOD on the basis of asigned key result area of the employee">
			                            	<div class="table-responsive d-flex flex-column flex-grow-1 mb-5">
			                            		<?php
												$current_employee_department_id = $current_employee_data['department_id'];
												$current_employee_company_id = $current_employee_data['company_id'];
												$current_employee_id = $current_employee_data['id'];
												$current_employee_user_role = $current_employee_data['user_role'];
												$current_year = date('Y');
												$last_year = date('Y', strtotime('-1 years'));
												$appr_kra_master_sql  = "select 
												akm.id as id, 
												akm.accountability as accountability, 
												akr.self_rating as self_rating, 
												akr.hod_rating as hod_rating, 
												e.role as user_role 
												from appr_kra_master akm 
												left join appr_kra_response akr 
												on (akr.accountability_id = akm.id) 
												and (akr.employee_id = '".$current_employee_id."') 
												and (akr.year = akm.year)
												left join employees e on e.id = akr.employee_id
												where FIND_IN_SET('".$current_employee_department_id."', akm.department_id) 
												and FIND_IN_SET('".$current_employee_company_id."', akm.company_id) 
												and akm.year = '".$current_year."' 
												group by akm.id order by akm.priority asc";
												$appr_kra_master_query = mysqli_query($conn, $appr_kra_master_sql) or die('unable to fetch kra'.mysqli_error($conn));
												if( mysqli_num_rows($appr_kra_master_query) > 0 ){
													$sr_no = 0;
													?>
													<table class="table table-bordered">
														<tr>
															<th>Functions & Accountabilities</th>
															<th>Self Rating</th>
															<th>HOD Rating</th>
														</tr>
													<?php
													while( $row = mysqli_fetch_assoc($appr_kra_master_query) ){
														?>
														<tr>
															<td>
																<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['accountability']; ?></label>
															</td>
															<td>
																<input type="number" class="rating text-secondary" id="appr_kra_self_rating_<?php echo $row['id']; ?>" name="appr_kra_self_rating[<?php echo $row['id']; ?>]"  data-clearable="remove" data-inline=false value="<?php echo $row['self_rating']; ?>" data-readonly=true disabled />
															</td>
															<td>
																<input 
																type="number" 
																class="rating 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['hod_employee_id'] ){ 
																	echo 'text-secondary'; 
																}else{ 
																	echo 'text-warning'; 
																}
																?>" 
																id="appr_kra_hod_rating_<?php echo $row['id']; ?>" 
																name="appr_kra_hod_rating[<?php echo $row['id']; ?>]" 
																data-clearable="remove" 
																data-inline=false 
																value="<?php echo $row['hod_rating']; ?>" 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['hod_employee_id'] ){ 
																	?>data-readonly=true disabled<?php 
																} 
																?>
																/>
															</td>
														</tr>
														<?php
													}
													?>
													</table>
													<?php
												}
												?>
			                        		</div>
			                            	<div class="d-flex align-items-center justify-content-center">
			                            		<button class="btn btn-secondary mx-2 previous action-button-previous">Previous</button>
			                                	<button class="btn btn-primary mx-2 next action-button">Next</button>
			                            	</div>
			                            </fieldset>

			                            <fieldset class="flex-grow-1 flex-column px-5 form-step" data-title="Personal Traits" data-description="This section should be completed by Employee, Reporting Manager and HOD on the basis of personal traits">
			                            	<div class="table-responsive d-flex flex-column flex-grow-1 mb-5">
			                            		<?php
												$current_employee_department_id = $current_employee_data['department_id'];
												$current_employee_company_id = $current_employee_data['company_id'];
												$current_employee_id = $current_employee_data['id'];
												$current_employee_user_role = $current_employee_data['user_role'];
												$current_year = date('Y');
												$last_year = date('Y', strtotime('-1 years'));
												$appr_employee_personal_traits_master_sql  = "select 
												aeptm.id as id, 
												aeptm.personal_trait as personal_trait, 
												aeptr.self_rating as self_rating, 
												aeptr.reporting_manager_rating as reporting_manager_rating, 
												aeptr.hod_rating as hod_rating, 
												e.role as user_role 
												from appr_employee_personal_traits_master aeptm 
												left join appr_employee_personal_traits_reponse aeptr 
												on (aeptr.personal_trait_id = aeptm.id) 
												and (aeptr.employee_id = '".$current_employee_id."') 
												and (aeptr.year = aeptm.year)
												left join employees e on e.id = aeptr.employee_id
												where FIND_IN_SET('".$current_employee_department_id."', aeptm.department_id) 
												and FIND_IN_SET('".$current_employee_company_id."', aeptm.company_id) 
												and aeptm.year = '".$current_year."' 
												group by aeptm.id order by aeptm.priority asc";
												$appr_employee_personal_traits_master_query = mysqli_query($conn, $appr_employee_personal_traits_master_sql) or die('unable to fetch Personal Traits'.mysqli_error($conn));
												if( mysqli_num_rows($appr_employee_personal_traits_master_query) > 0 ){
													$sr_no = 0;
													
													?>
													<table class="table table-bordered">
														<tr>
															<th>Functions & Accountabilities</th>
															<th>Self Rating</th>
															<th>Reporting Manager Rating</th>
															<th>HOD Rating</th>
														</tr>
													<?php
													while( $row = mysqli_fetch_assoc($appr_employee_personal_traits_master_query) ){
														?>
														<tr>
															<td>
																<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['personal_trait']; ?></label>
															</td>
															<td>
																<input 
																type="number" 
																class="rating text-secondary" 
																id="personal_trait_self_rating_<?php echo $row['id']; ?>" 
																name="personal_trait_self_rating[<?php echo $row['id']; ?>]" 
																data-clearable="remove" 
																data-inline=false 
																value="<?php echo $row['self_rating']; ?>" 
																data-readonly=true 
																disabled
																/>
															</td>
															<td>
																<input 
																type="number" 
																class="rating 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['reporting_manager_id'] ){ 
																	echo 'text-secondary'; 
																}else{
																	echo 'text-warning'; 
																}
																?>
																" 
																id="personal_trait_reporting_manager_rating_<?php echo $row['id']; ?>" 
																name="personal_trait_reporting_manager_rating[<?php echo $row['id']; ?>]" 
																data-clearable="remove" 
																data-inline=false 
																value="<?php echo $row['reporting_manager_rating']; ?>" 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['reporting_manager_id'] ){ 
																	?>data-readonly=true disabled<?php 
																}
																?> 
																/>
															</td>
															<td>
																<input 
																type="number" 
																class="rating 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['hod_employee_id'] ){ 
																	echo 'text-secondary'; 
																}else{
																	echo 'text-warning'; 
																}
																?>
																" 
																id="personal_trait_hod_rating_<?php echo $row['id']; ?>" 
																name="personal_trait_hod_rating[<?php echo $row['id']; ?>]" 
																data-clearable="remove" 
																data-inline=false 
																value="<?php echo $row['hod_rating']; ?>" 
																<?php 
																if( $logged_in_user_id !== $current_employee_data['hod_employee_id'] ){ 
																	?>data-readonly=true disabled<?php 
																} 
																?> 
																/>
															</td>
														</tr>
														<?php
													}
													?>
													</table>
													<?php
												}
												?>
			                        		</div>
			                            	<div class="d-flex align-items-center justify-content-center">
			                            		<button class="btn btn-secondary mx-2 previous action-button-previous">Previous</button>
			                                	<button class="btn btn-primary mx-2 next action-button" id="submit_appraisal_form">Finish</button>
			                            	</div>
			                            </fieldset>

			                            <fieldset class="flex-grow-1 flex-column px-5 form-step" data-title="Finish" data-description="Your Appraisal Form has been submitted, This will be Reviewed by your Reporting manager and HOD" style="<?php if($form_submitted == true){ echo 'display: flex;'; } ?>" >
			                            	<div class="d-flex flex-column flex-grow-1 mb-5">
			                            		<h3 class="text-center">Success!</h3>
			                            		<div class="d-flex justify-content-center py-5">
			                            			<img src="https://img.icons8.com/color/96/000000/ok--v2.png"/>
			                            		</div>
			                            		<p class="text-center">Your Form has been submitted.</p>
			                        		</div>
			                            	<div class="d-flex align-items-center justify-content-center">
			                            		<!-- <button class="btn btn-secondary mx-2 previous action-button-previous">GO back</button> -->
			                            	</div>
			                            </fieldset>
						    		</div>
						    	</div>
						    </div>
					    </form>
					</div>
				</div>
				<?php

			}else{
				?>
				<h2>You are not the reporting manager of this employee</h2>
				<?php
				die();
			}
		}else{
			?>
			<h2>data not found of this employee</h2>
			<?php
			die();
		}
	}else{
		?>
		<h2>Invalid Request</h2>
		<?php
		die();
	}
	?>
</main>
<!-- End #main -->

<?php include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<!--begin::Custom Javascript-->
<script type="text/javascript">
	$(document).ready(function(){
		var current_fs, next_fs, previous_fs; //fieldsets
		var opacity;
		// $(".next").click(function(e){
		$(".next:not(#submit_appraisal_form)").click(function(e){
			e.preventDefault();
		    current_fs = $(this).parent().parent();
		    next_fs = $(this).parent().parent().next();
		    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
		    next_fs.css({'display':'flex'});
		    $("#step_title").html(next_fs.data('title'));
		    $("#step_description").html(next_fs.data('description'));
		    current_fs.animate({opacity: 0}, {
		        step: function(now) {
		            opacity = 1 - now;
		            current_fs.css({
		                'display': 'none',
		                'position': 'relative'
		            });
		            next_fs.css({'opacity': opacity});
		        }, 
		        duration: 600
		    });
		});

		$(".previous").click(function(e){
			e.preventDefault();
		    current_fs = $(this).parent().parent();
		    previous_fs = $(this).parent().parent().prev();	
		    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");	
		    previous_fs.css({'display': 'flex'});
		    $("#step_title").html(previous_fs.data('title'));
		    $("#step_description").html(previous_fs.data('description'));
		    current_fs.animate({opacity: 0}, {
		        step: function(now) {
		            opacity = 1 - now;
		            current_fs.css({
		                'display': 'none',
		                'position': 'relative'
		            });
		            previous_fs.css({'opacity': opacity});
		        }, 
		        duration: 600
		    });
		});
	})
</script>
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php") ?>
