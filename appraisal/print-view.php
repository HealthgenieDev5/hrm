<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_title = 'Print View';
include_once("./inc/config.php"); 
if( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
	header('location:'.SITE_URL.'/login.php');
}

ob_start();
?>

<div id="main" class="main" style="max-width: 992px; margin:50px auto;">	
	<style type="text/css">
		* {
			font-family: "Open Sans", sans-serif;
			margin: 0;
			padding: 0;
		}
		h1, h2, h3, h4, h5, h6 {
			font-family: "Nunito", sans-serif;
		}
		@font-face{
		    font-family:'FontAwesome';
		    src:url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.eot?v=4.7.0');
		    src:url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.eot?#iefix&v=4.7.0') format('embedded-opentype'),url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.woff2?v=4.7.0') format('woff2'),url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.woff?v=4.7.0') format('woff'),url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.ttf?v=4.7.0') format('truetype'),url('<?php echo SITE_URL; ?>/assets/fonts/fontawesome-webfont.svg?v=4.7.0#fontawesomeregular') format('svg');
		    font-weight:normal;
		    font-style:normal
		}
		.fa{
		    display:inline-block;
		    font:normal normal normal 14px/1 FontAwesome;
		    font-size:inherit;
		    text-rendering:auto;
		    -webkit-font-smoothing:antialiased;
		    -moz-osx-font-smoothing:grayscale
		}
		.fa-star:before {
			content:"\f005"
		}
		.fa-star-o:before {
			content:"\f006"
		}

		table.rating-table {
			border-collapse: separate;
			border-spacing: 0;
		}

		table.rating-table th, 
		table.rating-table td {
			border: solid 1px grey;
			border-style: none solid solid none;
			padding: 10px 15px;
		}

		table.rating-table tr:first-child th:first-child,
		table.rating-table tr:first-child td:first-child { 
			border-top-left-radius: 6px; 
		}
		table.rating-table tr:first-child th:last-child,
		table.rating-table tr:first-child td:last-child { 
			border-top-right-radius: 6px; 
		}

		table.rating-table tr:last-child th:first-child,
		table.rating-table tr:last-child td:first-child { 
			border-bottom-left-radius: 6px; 
		}
		table.rating-table tr:last-child th:last-child,
		table.rating-table tr:last-child td:last-child { 
			border-bottom-right-radius: 6px; 
		}

		table.rating-table tr:first-child th,
		table.rating-table tr:first-child td { 
			border-top-style: solid; 
		}
		table.rating-table tr th:first-child,
		table.rating-table tr td:first-child { 
			border-left-style: solid; 
		}

		@page { margin: 20px 40px !important; }
	</style>
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
			?>
			<div>
	    		<div style="border-radius: 20px 20px 0px 0px;background-color:#592a45; padding: 20px; color: #fff;">
	    			<div class="text mb-3 border-bottom">
	                    <h3 style="text-align: center; font-size: 28px; font-weight: 700; line-height: 1.5">Personal Information</h3>
	                    <p style="text-align: center; opacity: 0.7; font-size: 16px; font-style: italic; font-weight: 400; line-height: 1.5; margin-bottom: 16px">Please verify employee's personal information.<br><small style="font-size: 12px; line-height: 1.25; display: block;">In case the information is not correct, please contact HR</small></p>
	                </div>
	                <hr style="color: rgb(222, 226, 230); margin: 20px 0px; border: none; border-bottom: 1px solid;">
	                <table style="width: 100%">
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Name
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo trim($current_employee_data['first_name'].' '.$current_employee_data['last_name']); ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Company
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['company_name']; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Department
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['department_name']; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Designation
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['designation_name']; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Joining Date
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo date('d M Y', strtotime($current_employee_data['joining_date'])); ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Desk Location
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['desk_location']; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Company Addr
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['company_address']; ?>
	                			<?php echo !empty($current_employee_data['company_city']) ? ', '.$current_employee_data['company_city'] : ''; ?>
	                			<?php echo !empty($current_employee_data['company_state']) ? ', '.$current_employee_data['company_state'] : ''; ?>
	                			<?php echo !empty($current_employee_data['company_pincode']) ? ', '.$current_employee_data['company_pincode'] : ''; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Employee Code
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo $current_employee_data['internal_employee_id']; ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Review Period
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo date('Y', strtotime('-1 years')).'-'.date('Y'); ?>
	                		</td>
	                	</tr>
	                	<tr style="margin-bottom: 0px">
	                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #ffffff">
	                			Date
	                		</th>
	                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; color: #ffffff">
	                			<?php echo date('d M Y'); ?>
	                		</td>
	                	</tr>
	                </table>
	    		</div>

	    		<div style="border: 1px solid grey; padding: 20px; margin-top: -1px;" >
					<h3 style="text-align: center; font-size: 28px; font-weight: 700; line-height: 1.5">Performance</h3>
                    <p style="text-align: center; opacity: 0.7; font-size: 16px; font-style: italic; font-weight: 400; line-height: 1.5; margin-bottom: 16px">This section should be completed by employee before the conclusion of the appraisal</p>
					<hr style="color: rgb(68, 68, 68); margin: 20px 0px; border: none; border-bottom: 1px solid;">
	            	<div class="d-flex flex-column flex-grow-1 ">
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
							?>
							<table style="width: 100%">
							<?php
							$sr_no = 1;
							while( $row = mysqli_fetch_assoc($appr_performance_history_master_query) ){
								?>
								<tr style="margin-bottom: 8px">
			                		<th style="width: 40%; text-align: left; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; line-height: 1.5; color: #000000c2; vertical-align: top; padding-right: 20px">
			                			<?php echo '<b style="font-size:18px">'.$sr_no.'.</b> '.$row['parameter']; ?>
			                		</th>
			                		<td style="width: 60%; text-align: left; font-size: 16px; font-weight: 400; line-height: 1.5; vertical-align: top;">
			                			<p style="padding-bottom: 6px; border-bottom: 1px dotted grey; margin-bottom:8px">
			                				<?php echo $row['response']; ?>
			                			</p>
			                		</td>
			                	</tr>
								<?php
								$sr_no++;
							}
							?>
	                		</table>
							<?php
						}
						?>
	            	</div>
	            </div>

	            <div style="page-break-after: always;"></div>

	            <div style="border: 1px solid grey; padding: 20px; margin-top: -1px;" >
					<h3 style="text-align: center; font-size: 28px; font-weight: 700; line-height: 1.5">Questions</h3>
                    <p style="text-align: center; opacity: 0.7; font-size: 16px; font-style: italic; font-weight: 400; line-height: 1.5; margin-bottom: 16px">This section should be completed by Employee before the conclusion of the appraisal</p>
					<hr style="color: rgb(68, 68, 68); margin: 20px 0px; border: none; border-bottom: 1px solid;">
                	<div class="d-flex flex-column flex-grow-1 ">
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
								<div style="margin-bottom: 16px;">
									<p style="margin-bottom: 8px; font-size: 16px; font-weight: 700; line-height: 1.5;">
										<?php echo '<b style="font-size:18px">'.++$sr_no.'.</b> '.$row['question']; ?>
									</p>
									<p style="font-size: 14px; font-weight: 400; line-height: 1.5; border: 1px dotted grey; padding: 10px; border:1px dotted grey; border-radius:5px;">
										<?php echo $row['answer']; ?>
									</p>
								</div>
								<?php
							}
						}
						?>
            		</div>
	            </div>

	            <div style="page-break-after: always;"></div>

	            <div style="border: 1px solid grey; padding: 20px; margin-top: -1px;" >
					<h3 style="text-align: center; font-size: 28px; font-weight: 700; line-height: 1.5">KRA</h3>
                    <p style="text-align: center; opacity: 0.7; font-size: 16px; font-style: italic; font-weight: 400; line-height: 1.5;">This section should be completed by Employee and HOD on the basis of asigned key result area of the employee</p>
                    <p style="text-align: right; opacity: 0.7; font-size: 16px; font-weight: 700; line-height: 1.5; margin-bottom: 16px">Note: Rate from 1-10 where 10 is highest</p>
					<hr style="color: rgb(68, 68, 68); margin: 20px 0px; border: none; border-bottom: 1px solid;">
                	<div class="d-flex flex-column flex-grow-1 ">
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
							<table style="width: 100%" class="rating-table">
								<tr>
									<th style="width: 40%; text-align: left; font-size: 16px; font-weight: 700; line-height: 1.5; vertical-align: top; ">Functions & Accountabilities</th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.5; vertical-align: top; ">Self Rating</th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.5; vertical-align: top; "></th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.5; vertical-align: top; ">HOD Rating</th>
								</tr>
							<?php
							while( $row = mysqli_fetch_assoc($appr_kra_master_query) ){
								?>
								<tr>
									<td>
										<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['accountability']; ?></label>
									</td>
									<td style="text-align: center;">
										<?php echo !empty($row['self_rating']) ? $row['self_rating'] : ''; ?>
									</td>
									<td style="text-align: center;"></td>
									<td style="text-align: center;">
										<?php echo !empty($row['hod_rating']) ? $row['hod_rating'] : ''; ?>
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
	            </div>

	            
	            <div style="border: 1px solid grey; padding: 20px; margin-top: -1px;" >
					<h3 style="text-align: center; font-size: 28px; font-weight: 700; line-height: 1.5">Personal Traits</h3>
                    <p style="text-align: center; opacity: 0.7; font-size: 16px; font-style: italic; font-weight: 400; line-height: 1.5;">This section should be completed by Employee, Reporting Manager and HOD on the basis of personal traits</p>
                    <p style="text-align: right; opacity: 0.7; font-size: 16px; font-weight: 700; line-height: 1.5; margin-bottom: 16px">Note: Rate from 1-10 where 10 is highest</p>
                    <hr style="color: rgb(68, 68, 68); margin: 20px 0px; border: none; border-bottom: 1px solid;">
                	<div class="d-flex flex-column flex-grow-1">
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
							<table style="width: 100%" class="rating-table">
								<tr>
									<th style="width: 40%; text-align: left; font-size: 16px; font-weight: 700; line-height: 1.25; vertical-align: top;">Functions & Accountabilities</th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.25; vertical-align: top;">Self Rating</th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.25; vertical-align: top;">Reporting Manager Rating</th>
									<th style="width: 20%; text-align: center; font-size: 16px; font-weight: 700; line-height: 1.25; vertical-align: top;">HOD Rating</th>
								</tr>
							<?php
							while( $row = mysqli_fetch_assoc($appr_employee_personal_traits_master_query) ){
								?>
								<tr>
									<td>
										<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['personal_trait']; ?></label>
									</td>
									<td style="text-align: center;">
										<?php echo !empty($row['self_rating']) ? $row['self_rating'] : ''; ?>
									</td>
									<td style="text-align: center;">
										<?php echo !empty($row['reporting_manager_rating']) ? $row['reporting_manager_rating'] : ''; ?>
									</td>
									<td style="text-align: center;">
										<?php echo !empty($row['hod_rating']) ? $row['hod_rating'] : ''; ?>
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
	            </div>
	        </div>
			<?php
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

	<div style="">
		<form id="print_form" action="<?php echo SITE_URL; ?>/print.php" method="post" style="display: flex;align-items: center;justify-content: center;width: 100%;margin-top: 15px;">
			<input type="hidden" name="employee_id" value='<?php echo $current_employee_id; ?>' />
			<button type="submit" style="padding: 5px 15px;color: #fff;background-color: #0dcaf0;border: 1px solid #0dcaf0;cursor: pointer;border-radius: 4px;font-weight: 600;">Print</button>
		</form>
	</div>
</div>
