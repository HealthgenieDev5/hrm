<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
ob_start();
$page_title = 'Print View';
?>
<?php include_once("./inc/header-top.php") ?>

<?php
if( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
	header('location:'.SITE_URL.'/login.php');
}

?>
<style type="text/css">
	.left-side{
		background-color:#592a45;
	}
	table td{
		vertical-align: middle;
	}
</style>
<?php include_once("./inc/header-bottom.php") ?>
<?php #include_once("./inc/page-header.php") ?>
<div id="main" class="main">

	<!-- <div class="section">
		<div class="container" style="max-width: 991px;">
		    <div class="card" style="border-radius: 20px;">
			    <div class="card-body p-0">
			    	<button class="btn btn-primary" onclick="print_file()">Print</button>
			    </div>
			</div>
		</div>
	</div> -->

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
			<div class="section" id="appraisal_print">
				<div class="container" style="max-width: 991px;">
				    <div class="card" id="appraisal_form" style="border-radius: 20px;">
					    <div class="card-body p-0">
					    	<!-- <table class="table" style="width: 100%; border: 1px solid red">
					    		<tr>
					    			<td colspan="2">
					    				<h3 class="text-white fw-bold">Personal Information</h3>
					    			</td>
					    		</tr>
					    		<tr>
					    			<td colspan="2">
					    				<p class="text-white fst-italic" style="opacity: 0.7; display: flex; ">
					    					<span>Please verify employee's personal information.</span>
					    					<small style="font-size: 12px; line-height: 1.25; display: block;">In case the information is not correct, please contact HR</small>
					    				</p>
					    			</td>
					    		</tr>
					    	</table> -->
					    	<div class="row m-0">
					    		<div class="col-xl-12 left-side d-flex flex-column flex-grow-1 py-4 px-4" style="border-radius: 20px 20px 0px 0px;">
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
					    		<div class="col-xl-12 right-side d-flex flex-column flex-grow-1 py-4 px-0" style="border-radius: 20px 20px 20px 20px;">

		                            <fieldset class="flex-grow-1 flex-column px-5 form-step d-flex" data-title="Performance" data-description="This section should be completed by Employee before the conclusion of the appraisal"  >
		                            	<h3 class="fw-bold text-center mt-3" id="step_title">Performance</h3>
					    				<p class="text-muted text-center fst-italic" id="step_description">This section should be completed by employee before the conclusion of the appraisal</p>
					    				<hr>
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
												while( $row = mysqli_fetch_assoc($appr_performance_history_master_query) ){
													?>
													<div class="row mb-3">
														<div class="col-md-4 d-flex align-items-center">
															<label class="form-label mb-0 me-3 fw-bold" style="font-size:14px"><?php echo $row['parameter']; ?></label>
														</div>
														<div class="col-md-8 d-flex align-items-center">
															<p class="form-control flex-grow-1 rounded-0 bg-transparent" style="border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" ><?php echo $row['response']; ?></p>
														</div>
													</div>
													<?php
												}
											}
											?>
		                            	</div>
		                            </fieldset>
		                        </div>
		                            
	                            <hr>

		                        <div class="col-xl-12 right-side d-flex flex-column flex-grow-1 py-4 px-0" style="border-radius: 20px 20px 20px 20px;">

		                            <fieldset class="flex-grow-1 flex-column px-5 form-step d-flex" data-title="Questions" data-description="This section should be completed by Employee before the conclusion of the appraisal">
		                            	<h3 class="fw-bold text-center mt-3" id="step_title">Questions</h3>
					    				<p class="text-muted text-center fst-italic" id="step_description">This section should be completed by Employee before the conclusion of the appraisal</p>
					    				<hr>
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
													<div class="row mb-3">
														<div class="col-md-12 d-flex align-items-center">
															<label class="form-label fw-bold" style="font-size:14px"><?php echo '<strong style="font-size:18px">'.++$sr_no.'.</strong> '.$row['question']; ?></label>
														</div>
														<div class="col-md-12 d-flex align-items-center">
															<p class="form-control flex-grow-1 bg-transparent" style="border: 1px dotted var(--bs-secondary); box-shadow: none; border-radius:5px" ><?php echo $row['answer']; ?></p>
														</div>
													</div>
													<?php
												}
											}
											?>
		                        		</div>
		                            </fieldset>
		                        </div>
		                            
	                            <hr>
	                            
		                        <div class="col-xl-12 right-side d-flex flex-column flex-grow-1 py-4 px-0" style="border-radius: 20px 20px 20px 20px;">

		                            <fieldset class="flex-grow-1 flex-column px-5 form-step d-flex" data-title="KRA" data-description="This section should be completed by Employee and HOD on the basis of asigned key result area of the employee">
		                            	<h3 class="fw-bold text-center mt-3" id="step_title">KRA</h3>
					    				<p class="text-muted text-center fst-italic" id="step_description">This section should be completed by Employee and HOD on the basis of asigned key result area of the employee</p>
					    				<hr>
		                            	<div class="table-responsive d-flex flex-column flex-grow-1 ">
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
		                            </fieldset>
		                        </div>
		                            
	                            <hr>
	                            
		                        <div class="col-xl-12 right-side d-flex flex-column flex-grow-1 py-4 px-0" style="border-radius: 20px 20px 20px 20px;">

		                            <fieldset class="flex-grow-1 flex-column px-5 form-step d-flex" data-title="Personal Traits" data-description="This section should be completed by Employee, Reporting Manager and HOD on the basis of personal traits">
		                            	<h3 class="fw-bold text-center mt-3" id="step_title">Personal Traits</h3>
					    				<p class="text-muted text-center fst-italic" id="step_description">his section should be completed by Employee, Reporting Manager and HOD on the basis of personal traits</p>
					    				<hr>
		                            	<div class="table-responsive d-flex flex-column flex-grow-1">
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
		                            </fieldset>
		                        </div>
					    	</div>
					    </div>
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
</div>
<?php #include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	var print_file = function(){
		var element = document.getElementById('appraisal_print');
		var opt = {
			margin:       1,
			filename:     'myfile.pdf',
			image:        { type: 'jpeg', quality: 1 },
			html2canvas:  { scale: 1 },
			jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
		};
		html2pdf().from(element).set(opt).save();
	}
</script>

<?php include_once("./inc/footer-bottom.php") ?>

<?php
$contents = ob_get_clean();
echo $contents;
/*define("DOMPDF_ENABLE_HTML5PARSER", true);
define("DOMPDF_ENABLE_FONTSUBSETTING", true);
define("DOMPDF_UNICODE_ENABLED", true);
define("DOMPDF_DPI", 120);
define("DOMPDF_ENABLE_REMOTE", true);
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($contents);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->set_base_path('assets/vendor/bootstrap/css/bootstrap.min.css');
$dompdf->stream();*/
?>