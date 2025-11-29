<?php $page_title = 'Department Data'; ?>
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
</style>
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php") ?>
<?php include_once("./inc/page-header-hr.php") ?>





<main id="main" class="main">

	<div class="pagetitle">
		<h1>Department Data</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
				<li class="breadcrumb-item active">Department Data</li>
			</ol>
		</nav>
	</div>
	<!-- End Page Title -->


	<div class="section">
		<div class="container">
		    
		    <div class="card">
		    	<div class="card-header">
		    		<div class="row">
		    			<div class="col-md-6">
		    				<h3 class="card-title mb-0 py-2">Form Submitted by employees</h3>
		    			</div>
		    			<div class="col-md-6">
		    				<form id="filter_form" class="row">
		    					<div class="col-md-5">
		    						<label class="form-label">Company</label>
		    						<select id="company_id" class="form-select" name="company_id[]" multiple data-placeholder="Select company">
		    							<option></option>
		    							<?php
		    							$companies_sql = "select * from companies";
		    							$companies_query = mysqli_query($conn, $companies_sql) or die('unable to fetch companies from database '.mysqli_error($conn));
		    							if( mysqli_num_rows($companies_query) > 0 ){
		    								while( $company_row = mysqli_fetch_assoc($companies_query) ){
		    									?>
		    									<option value="<?php echo $company_row['id']; ?>" <?php if( isset($_GET['company_id']) && !empty($_GET['company_id']) && in_array($company_row['id'], $_GET['company_id']) ){ echo 'selected'; } ?> ><?php echo $company_row['company_name']; ?></option>
		    									<?php
		    								}
		    							}
		    							?>
		    						</select>
		    					</div>
		    					<div class="col-md-5">
		    						<label class="form-label">Department</label>
		    						<select id="department_id" class="form-select" name="department_id[]" multiple data-placeholder="Select department">
		    							<option></option>
		    							<?php
		    							if( isset($_GET['company_id']) && !empty($_GET['company_id']) ){
		    								$company_ids = "('".implode("','", $_GET['company_id'])."')";
		    								$departments_sql = "select d.*, c.company_short_name as company_short_name from departments d left join companies c on c.id = d.company_id where c.id in ".$company_ids;
		    								$departments_query = mysqli_query($conn, $departments_sql) or die('unable to fetch departments from database '.mysqli_error($conn));
			    							if( mysqli_num_rows($departments_query) > 0 ){
			    								while( $department_row = mysqli_fetch_assoc($departments_query) ){
			    									?>
			    									<option value="<?php echo $department_row['id']; ?>" <?php if( isset($_GET['department_id']) && !empty($_GET['department_id']) && in_array($department_row['id'], $_GET['department_id']) ){ echo 'selected'; } ?> ><?php echo $department_row['department_name'].' - '.$department_row['company_short_name']; ?></option>
			    									<?php
			    								}
			    							}
		    							}
		    							/*else{
		    								$departments_sql = "select d.*, c.company_short_name as company_short_name from departments d left join companies c on c.id = d.company_id";
		    							}*/
		    							
		    							
		    							?>
		    						</select>
		    					</div>
		    					<div class="col-md-2">
		    						<label class="form-label"> &nbsp; </label><br>
		    						<button type="submit" id="form_filter_submit" class="btn btn-info">Filter</button>
		    					</div>
		    				</form>
		    			</div>
		    		</div>
		    	</div>
		    	<div class="card-body">
		    		<?php
		    		$current_employee_id = $_SESSION['login']['id'];
					$current_employee_role = $_SESSION['login']['user_role'];
					$sql = "select 
					e.id as employee_id, 
					trim(concat(e.first_name, ' ', e.last_name)) as employee_name, 
					trim(concat(e2.first_name, ' ', e2.last_name)) as reporting_manager_name, 
					trim(concat(e3.first_name, ' ', e3.last_name)) as hod_name, 
					d.department_name as department_name, 
					c.company_name as company_name 
					from appr_overall_rating aor 
					left join employees e on e.id = aor.employee_id
					left join employees e2 on e2.id = e.reporting_manager_id
					left join departments d on d.id = e.department_id 
					left join employees e3 on e3.id = d.hod_employee_id
					left join companies c on c.id = e.company_id 
					left join users u on u.id = e.id 
					where ( e.reporting_manager_id = '".$current_employee_id."' or d.hod_employee_id = '".$current_employee_id."' or '".$current_employee_role."' in ('admin', 'superuser') ) ";

					$filter_condition = "";
					if( isset($_REQUEST['company_id']) && !empty($_REQUEST['company_id']) ){
						$company_ids = "('".implode("','", $_GET['company_id'])."')";
						$filter_condition .= " and e.company_id in ".$company_ids;
					}
					if( isset($_REQUEST['department_id']) && !empty($_REQUEST['department_id']) ){
						$department_ids = "('".implode("','", $_GET['department_id'])."')";
						$filter_condition .= " and e.department_id in ".$department_ids;
					}
					$sql .= $filter_condition;

					/*echo '<pre>';
					print_r($sql);
					die();*/
					$query = mysqli_query($conn, $sql) or die('unable to fetch current employee data'. mysqli_error($conn));
					if( mysqli_num_rows($query) > 0 ){
						?>
						<table class="table">
							<tr>
								<th>Employee Name</th>
								<th>Department</th>
								<th>Company</th>
								<th>Reporting Manager</th>
								<th>HOD</th>
								<th>Action</th>
							</tr>
						<?php
						foreach( $query as $row ){
							?>
							<tr>
								<td><?php echo $row['employee_name']; ?></td>
								<td><?php echo $row['department_name']; ?></td>
								<td><?php echo $row['company_name']; ?></td>
								<td><?php echo $row['reporting_manager_name']; ?></td>
								<td><?php echo $row['hod_name']; ?></td>
								<td><a href="<?php echo SITE_URL.'/review.php?employee_id='.$row['employee_id']; ?>" target="_blank">Open</a></td>
							</tr>
							<?php
						}
						?>
						</table>
						<?php
					}else{
						?>
						<table class="table">
							<tr>
								<th>Employee Name</th>
								<th>Department</th>
								<th>Company</th>
								<th>Reporting Manager</th>
								<th>HOD</th>
								<th>Action</th>
							</tr>
							<tr>
								<td colspan="6">
									<p class="text-center py-3">No Data</p>
								</td>
							</tr>
						<?php
					}
		    		?>
		    	</div>
		    </div>

		</div>
	</div>

</main>
<!-- End #main -->

<?php include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<!--begin::Custom Javascript-->
<script type="text/javascript">
	$(document).ready(function($){
		$('#company_id').select2({
			theme: "bootstrap-5",
			dropdownParent: $("#company_id").parent()
		}).on('select2:open', function (e) {
			document.querySelector('.select2-search__field').focus();
		});

		$('#department_id').select2({
			theme: "bootstrap-5",
			dropdownParent: $("#company_id").parent()
		}).on('select2:open', function (e) {
			document.querySelector('.select2-search__field').focus();
		});

		$(document).on('change', '#company_id', function(e){
			e.preventDefault();
			$('#department_id').html('<option></option>');
			var company_ids = $(this).val();
			if( company_ids.length <= 0 ){
				return false;
			}
			var data = {
				'company_ids': company_ids,
			};
			jQuery.ajax({
				url: '<?php echo SITE_URL."/controller/ajax/ajax-get-departments-by-company.php"; ?>',
				type: 'POST',
				data:  data,
				dataType: 'html',
			})
			.done(function(response_data){
				// console.log(response_data);
				var resultObj = JSON.parse(response_data);
				if( resultObj.message == 'success' ){
					var departments = resultObj.data;
					$.each(departments, function(index, item){
						$('#department_id').append('<option value="'+item.id+'">'+item.department_name+' - '+item.company_short_name+'</option>');
					});
				}else{
					alert(resultObj.message+':: '+resultObj.description);
				}
			})
			.fail(function(){
				alert('There was an error while fetching departments of selected companies');
			});
		})
	});
</script>
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php") ?>
