<?php $page_title = 'Appraisal'; ?>
<?php include_once("./inc/header-top.php") ?>

<?php
if( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
	header('location:'.SITE_URL.'/login.php');
}
$current_employee_id = $_SESSION['login']['id'];
$sql = "select e.*, 
d.department_name as department_name, 
dg.designation_name as designation_name, 
c.company_name as company_name, 
c.address as company_address, 
c.city as company_city, 
c.state as company_state, 
c.pincode as company_pincode, 
aor.remarks as remarks 
from employees e 
left join departments d on d.id = e.department_id 
left join designations dg on dg.id = e.designation_id 
left join companies c on c.id = e.company_id 
left join appr_overall_rating aor on aor.employee_id = e.internal_employee_id
where e.id = '".$current_employee_id."'";
$query = mysqli_query($conn, $sql) or die('unable to fetch current employee data'. mysqli_error($conn));
if( mysqli_num_rows($query) == 1 ){
	$current_employee_data = mysqli_fetch_assoc($query);
}
/*echo '<pre>';
print_r($current_employee_data);
die();*/
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
	border-top-left-radius:20px;
	border-bottom-left-radius:20px;
	background-color:#304767;
}
.right-side{
	border-top-right-radius:20px;
	border-bottom-right-radius:20px;
}
.left-heading{
    color:#fff;
}
.steps-content{
    margin-top:30px;
    color:#fff;
}
.steps-content p{
    font-size:12px;
    margin-top:15px;
}
.progress-bar{
    list-style:none;
    background-color: transparent;
    font-size:13px;
    font-weight:700;
    counter-reset:container 0;
}
.progress-bar li{
	position:relative;
	counter-increment:container 1;
	color:#4f6581;
}
.progress-bar li::before{
    content:counter(container);
    line-height:23px;
    text-align:center;
    position:absolute;
    height:25px;
    width:25px;
    border:1px solid #4f6581 !important;
    border-radius:50%;
    left:-40px;
    top:-5px;
    z-index:1;
    background-color:#304767;
}
.progress-bar li::after{
    content: '';
    position: absolute;
    height: 90px;
    width: 2px;
    background-color: #4f6581;
    /*z-index: 1;*/
    left: -28px;
    top: -70px;
}
.progress-bar li.active::after{
    background-color: #fff;
}
.progress-bar li:first-child:after{
	display:none;  
}
.progress-bar li.active::before{
    color:#fff;
	border:1px solid #fff !important;
}
.progress-bar li.active{
    color:#fff;
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


	<div class="section">
		<div class="container">
		    <div class="card" style="border-radius: 20px;">
			    <div class="card-body p-0">
			    	<div class="row m-0">
			    		<div class="col-md-4 left-side flex-grow-1 py-4 px-5">
			    			<div class="left-heading">
			                    <h3>indeed</h3>
			                </div>
			                <div class="steps-content">
			                    <h3>Step <span class="step-number">1</span></h3>
			                    <p class="step-number-content active">Enter your personal information to get closer to companies.</p>
			                    <p class="step-number-content d-none">Get to know better by adding your diploma,certificate and education life.</p>
			                    <p class="step-number-content d-none">Help companies get to know you better by telling then about your past experiences.</p>
			                    <p class="step-number-content d-none">Add your profile piccture and let companies find youy fast.</p>
			                </div>
			                <ul class="progress-bar text-start m-0 px-0 py-5">
			                    <li class="ms-5 mt-0 active">Personal Information</li>
			                    <li class="ms-5 mt-5">Education</li>
			                    <li class="ms-5 mt-5">Work Experience</li>
			                    <li class="ms-5 mt-5">User Photo</li>
			                </ul>
			    		</div>
			    		<div class="col-md-8 right-side flex-grow-1 py-4 px-5">
			    			<div class="main h-100 d-flex flex-column active">
			                    <div class="text mb-3 border-bottom">
			                        <h2 class="fw-bold">Your Personal Information</h2>
			                        <p class="text-muted fst-italic">Please check your personal information</p>
			                    </div>

			                    <div class="flex-grow-1">
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Name :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo trim($current_employee_data['first_name'].' '.$current_employee_data['last_name']); ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Company :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo $current_employee_data['company_name']; ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Department :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo $current_employee_data['department_name']; ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Designation :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo $current_employee_data['designation_name']; ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Company Address :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0">
				                    			<?php echo $current_employee_data['company_address']; ?>
				                    			<?php echo !empty($current_employee_data['company_city']) ? ', '.$current_employee_data['company_city'] : ''; ?>
				                    			<?php echo !empty($current_employee_data['company_state']) ? ', '.$current_employee_data['company_state'] : ''; ?>
				                    			<?php echo !empty($current_employee_data['company_pincode']) ? ', '.$current_employee_data['company_pincode'] : ''; ?>
				                    		</p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Employee Code :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo $current_employee_data['internal_employee_id']; ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Review Period :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo date('Y', strtotime('-1 years')).'-'.date('Y'); ?></p>
				                    	</div>
				                    </div>
			                    	<div class="row mb-2">
				                    	<div class="col-md-4">
				                    		<label class="form-label fw-bold mb-0">Date :</label>
				                    	</div>
				                    	<div class="col-md-8">
				                    		<p class="mb-0"><?php echo date('d M Y'); ?></p>
				                    	</div>
				                    </div>
			                    </div>

			                    <div class="buttons d-flex align-items-center justify-content-center">
			                        <button class="btn btn-primary next_button">Next Step</button>
			                    </div>
			                </div>
			    		</div>
			    	</div>
			    </div>
		    </div>
		</div>
	</div>

	<form class="section dashboard">
		<div class="row">

			<!-- Left side columns -->
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title p-0 m-0">Your Details</h5>
						<div class="card-toolbar">
							<a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
							<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
								<li class="dropdown-header text-start">
									<h6>Filter</h6>
								</li>

								<li><a class="dropdown-item" href="#">Today</a></li>
								<li><a class="dropdown-item" href="#">This Month</a></li>
								<li><a class="dropdown-item" href="#">This Year</a></li>
							</ul>
						</div>
					</div>

					<div class="card-body p-3">

						<div class="row">
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 pe-3">Name :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="employee_name" id="employee_name" value="<?php echo ( isset($current_employee_data) && !empty($current_employee_data) ) ? trim($current_employee_data['first_name'].' '.$current_employee_data['last_name']) : ''; ?>" readonly/>
							</div>
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Company :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="company_name" id="company_name" value="<?php echo ( isset($current_employee_data) && !empty($current_employee_data) ) ? $current_employee_data['company_name'] : ''; ?>" readonly/>
							</div>
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Department :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="department_name" id="department_name" value="<?php echo ( isset($current_employee_data) && !empty($current_employee_data) ) ? $current_employee_data['department_name'] : ''; ?>" readonly/>
							</div>
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Designation :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="designation_name" id="designation_name" value="<?php echo ( isset($current_employee_data) && !empty($current_employee_data) ) ? $current_employee_data['designation_name'] : ''; ?>" readonly/>
							</div>
							<div class="col-md-8 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Company Address :</label>
								<textarea class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" name="company_address" id="company_address"><?php echo ( !empty($current_employee_data['company_state']) ) ? 'State: '.$current_employee_data['company_state'] : ''; ?><?php echo ( !empty($current_employee_data['company_city']) ) ? '&#13;&#10;City: '.$current_employee_data['company_city'] : ''; ?><?php echo ( !empty($current_employee_data['company_address']) ) ? '&#13;&#10;Address: '.$current_employee_data['company_address'] : ''; ?><?php echo ( !empty($current_employee_data['company_pincode']) ) ? '&#13;&#10;Pincode: '.$current_employee_data['company_pincode'] : ''; ?></textarea>
								<!-- <input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="designation_name" id="designation_name" value="" readonly/> -->
							</div>


							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Employee Code :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" name="internal_employee_id" id="internal_employee_id" value="<?php echo ( !empty($current_employee_data['internal_employee_id']) ) ? $current_employee_data['internal_employee_id'] : ''; ?>" readonly/>
							</div>
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Review Period :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" id="review_period" value="<?php echo date('Y', strtotime('-1 years')).'-'.date('Y'); ?>" readonly/>
							</div>
							<div class="col-md-4 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Date :</label>
								<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" id="current_date" value="<?php echo date('d M Y'); ?>" readonly/>
							</div>

							<!-- <div class="col-md-12 mb-3 d-flex align-items-center">
								<label class="form-label mb-0 me-3">Remarks :</label>
								<textarea class="form-control flex-grow-1 rounded-0 bg-transparent" style="width: auto !important; border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" name="remarks" id="remarks"><?php #echo ( !empty($current_employee_data['remarks']) ) ? $current_employee_data['remarks'] : ''; ?></textarea>
							</div> -->

							<input type="hidden" name="employee_id" id="employee_id" value="<?php echo ( !empty($current_employee_data['internal_employee_id']) ) ? $current_employee_data['internal_employee_id'] : ''; ?>" >
							<input type="hidden" name="reiew_period_from" id="reiew_period_from" value="<?php echo date('Y', strtotime('-1 years')); ?>" >
							<input type="hidden" name="reiew_period_to" id="reiew_period_to" value="<?php echo date('Y'); ?>" >
							<input type="hidden" name="year" id="year" value="<?php echo date('Y'); ?>" >

						</div>

					</div>

				</div>
			</div>
			<!-- End Left side columns -->

			<div class="col-lg-12">
				<div class="card">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title p-0 m-0">Performance</h5>
					</div>

					<div class="card-body p-3">
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
						left join appr_performance_history_response aphr on (aphr.parameter_id = aphm.id) and (aphr.employee_id = '".$current_employee_id."')
						where FIND_IN_SET('".$current_employee_department_id."', aphm.department_id) 
						and FIND_IN_SET('".$current_employee_company_id."', aphm.company_id) 
						and aphm.year = '".$current_year."' 
						group by aphm.id";



						/*echo '<pre>';
						print_r($appr_performance_history_master_sql);
						die();*/
						$appr_performance_history_master_query = mysqli_query($conn, $appr_performance_history_master_sql) or die('unable to fetch app performance questions'.mysqli_error($conn));
						if( mysqli_num_rows($appr_performance_history_master_query) > 0 ){
							while( $row = mysqli_fetch_assoc($appr_performance_history_master_query) ){
								?>
								<div class="row mb-3">
									<div class="col-md-4 d-flex align-items-center">
										<label class="form-label mb-0 me-3"><?php echo $row['parameter']; ?></label>
									</div>
									<div class="col-md-8 d-flex align-items-center">
										<input class="form-control flex-grow-1 rounded-0 bg-transparent" style="border: none; border-bottom: 1px dotted var(--bs-secondary); box-shadow: none;" type="text" id="appr_performance_history_response_<?php echo $row['id']; ?>" name="appr_performance_history_response_<?php echo $row['id']; ?>" value="<?php echo $row['response']; ?>">
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>

		</div>
	</form>

</main>
<!-- End #main -->

<?php include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<!--begin::Custom Javascript-->
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php") ?>
