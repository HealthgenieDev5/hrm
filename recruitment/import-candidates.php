<?php require_once("includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
	<base href="">
	<meta charset="utf-8"/>
	<title>HR Management</title>

	<?php require_once("includes/header-top.php"); ?>

	<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/fixedcolumns/4.0.1/css/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>

	<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>


	<link href="assets/css/custom-style.css" rel="stylesheet" type="text/css"/>
	<style type="text/css">
		/*div.DTFC_LeftHeadWrapper table, div.DTFC_RightHeadWrapper table {
			margin: 0px !important;
		}*/
		.dataTables_wrapper .dataTable{
			margin:  0px !important;
		}
		#create_listing,
		#update_listing{
			width: 768px;
			left: unset;
			right: -768px;
		}
		#update_listing.offcanvas.offcanvas-on,
		#update_listing.offcanvas.offcanvas-on{
			width: 768px;
			right: 0;
		}
		.filter-card{
			height: auto !important;
		}

		ul.filter-counts > li{
			margin-right: 0.5rem !important;
		}

		.slidertooltip {
			background: #cc96bf;
			color: #fff;
			position: absolute;
			top: -100%;
			left: 50%;
			transform: translateY(-25px) translateX(-50%);
			padding: 5px 8px;
			border-radius: 50px;
			min-width: 20px;
			text-align: center;
		}
		.slidertooltip:before{
			content: "";
			background: #cc96bf;
			color: #fff;
			position: absolute;
			bottom: -5%;
			left: 50%;
			transform: translateY(0) translateX(-50%) rotate(45deg);
			width: 15px;
			height: 15px;
			text-align: center;
			z-index: -1;
		}

	</style>
</head>
<!--end::Head-->


<!--begin::Body-->
<body  id="kt_body"  class="header-fixed header-mobile-fixed sidebar-enabled page-loading"  >
	<!--begin::Main-->
	<!--begin::Header Mobile-->
	<?php require_once('includes/header-mobile.php'); ?>
	<!--end::Header Mobile-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Aside-->
			<?php include_once('includes/aside-left.php'); ?>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
				<!--begin::Page Header-->
				<?php //include_once('includes/page-header.php'); ?>
				<!--end::Page Header-->




				<!--begin::Content-->
				<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Entry-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="container-fluid">
							<!--begin::Dashboard-->
							<!--begin::Row-->
							<div class="row">
								<div class="col-xl-12">
									<!--begin::Base Table Widget 5-->
									<div class="card card-custom card-stretch gutter-b">
										<!--begin::Header-->
										<div class="card-header border-0 pt-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label font-weight-bolder text-dark">Import Candidates</span>
											</h3>
										</div>
										<!--end::Header-->
										<!--begin::Body-->
										<div class="card-body pt-2 pb-0">
											

											<div class="row">
												<div class="col-md-6">
													<form enctype="multipart/form-data" method="post" id="upload_form">
															
														<div class="form-group">
															<label for="resume">Upload CSV File <span class="text-danger">*</span></label>
															<input type="file" id="imported_file" class="form-control" name="imported_file">
														</div>

														<div class="form-group">
															<input type="submit" id="import_form_submit" class="btn btn-primary form-control" name="import_form_submit">
														</div>

													</form>
												</div>
												<div class="col-md-6">
													<div class="card card-custom gutter-b" style="box-shadow: none; border: 1px solid #EBEDF3;">
														<div class="card-header">
															<div class="card-title">
																<h3 class="card-label">DB Fields</h3>
															</div>
														</div>
														<div class="card-body ">
															<div class="clearfix mb-5">
																<h3>CSV Fields Trash</h3>
																<ul class="header-list header-list-csv" id="csv_fields_backup" >
																</ul>
																<style type="text/css">
																	.header-list-csv{
																		margin: 0px;
																		padding: 0px;
																		width: 100%;
																		min-height: 50px;
																		border: 2px dashed gray;
																	}
																	.header-list-csv li {
																		max-width: max-content;
																		display: inline-block !important;
																		margin:  5px;
																		border-top-width: 1px !important;
																		padding: 0.25rem 0.75rem;
																		background: rgba(0,0,0,0.25);
																	}
																	li > i.trash-button{
																		cursor: pointer;
																	}
																</style>
															</div>
															<div class="d-flex justify-content-between">
																<h6 class="text-center flex-grow-1">DB Fields</h6>
																<h6 class="text-center flex-grow-1">CSV Fields</h6>
															</div>
															<div class="d-flex justify-content-between">
																<?php
																$db_fields_query = mysqli_query($conn, "show columns from candidates") or die("some error");
																$columns_array = array();
																foreach($db_fields_query as $key){
																	$columns_array[] = $key['Field'];
																}
																if( isset($_FILES['imported_file']) ){
																	$filename=$_FILES["imported_file"]["tmp_name"];
																	if($_FILES["imported_file"]["size"] > 0){
																		$file = fopen($filename, "r");
																		$header 	= 	fgetcsv($file);
																		?>
																		<ul class="list-group flex-grow-1" style="width: 50%;" id="dbFields">
																			<?php
																			foreach( $header as $head){
																				foreach( $columns_array as $column){
																					if( $head == $column ){
																						?>
																						<li class="list-group-item d-flex align-items-center justify-content-between"><?php echo $column; ?></li>
																						<?php
																					}
																				}
																			}
																			foreach( $columns_array as $column){
																				if( !in_array($column, $header) ){
																					?>
																					<li class="list-group-item d-flex align-items-center justify-content-between"><?php echo $column; ?></li>
																					<?php
																				}
																			}
																			?>
																		</ul>
																		<ul class="list-group header-list flex-grow-1" id="csv_fields" style="min-height: 100px; background: rgba(0,0,0,0.10); width: 50%;">
																			<?php
																			foreach( $header as $csv_field){
																				?>
																				<li class="list-group-item d-flex align-items-center justify-content-between"><span class="mr-3"><?php echo $csv_field; ?></span><i class="fa fa-times trash-button"></i></li>
																				<?php
																			}
																			?>
																		</ul>
																		<?php
																		$csv_data 	= 	array();
																		while (($data = fgetcsv($file)) !== FALSE){
																			$csv_data[] = array_combine($header, $data);
																			// $csv_data[] = $data;
																		}
																		// echo "<pre>"; print_r($csv_data); echo "</pre>"; die();
																	}
																}
																?>
															</div>
															<div class="clearfix mt-3">
																<button id="import_button" class="btn btn-primary form-control">Import</button>
															</div>
														</div>
													</div>
												</div>
											</div> 
										</div>
										<!--end::Body-->
									</div>
									<!--end::Base Table Widget 5-->
								</div>
							</div>
							<!--end::Row-->
							<!--end::Dashboard-->		
						</div>
					<!--end::Container-->
					</div>
					<!--end::Entry-->
				</div>
				<!--end::Content-->





				<!--begin::Footer-->
				<?php include_once('includes/page-footer.php'); ?>
				<!--end::Footer-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->

	



	<?php include_once('includes/theme-modal-and-offcanvas.php'); ?>


	<?php include_once('includes/footer-top.php'); ?>

	
	<script src="assets/js/offcanvas.js"></script>
	<script src="assets/js/custom-script.js"></script>

	<script type="text/javascript">
		var _tooltip = jQuery.fn.tooltip;
		var _datepicker = jQuery.fn.datepicker;
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		jQuery.fn.tooltip = _tooltip;
		jQuery.fn.datepicker = _datepicker;
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$( "#dbFields" ).sortable();
			$( "#csv_fields, #csv_fields_backup" ).sortable({
				connectWith: ".header-list"
			});
		})
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click', '#csv_fields li i.trash-button', function(e){
				e.preventDefault();
				$(this).parent().detach().appendTo("#csv_fields_backup");
			})

			$(document).on('click', '#csv_fields_backup li i.trash-button', function(e){
				e.preventDefault();
				$(this).parent().detach().appendTo("#csv_fields");
			})

			$(document).on('click', '#import_button', function(e){
				e.preventDefault();
				var $db_fields = [];
				var $csv_fields = [];
				var $csv_data = <?php echo json_encode($csv_data); ?>;
				// console.log($csv_data);
				// return false;
				var $agent_id = <?php echo $_SESSION['CURRENT_USER']['id']; ?>;
				$('#dbFields > li').each(function(index, value){
					$db_fields.push( $(this).text() );
				});
				$('#csv_fields > li').each(function(index, value){
					$csv_fields.push( $(this).text() );
				});
				var data = {
					'ajax_for'		:  'import_candidates',
					'db_fields' 	:	$db_fields,
					'csv_fields' 	:	$csv_fields,
					// 'agent_id' 		:	$agent_id,
					'csv_data' 		:	$csv_data,
				};
				$.ajax({
					url: '<?php echo SITE_URL."/controller/ajax.php"; ?>',
					type: 'POST',
					data:  data,
					dataType: 'html',
				})
				.done(function(response_data){
					console.log('import response', response_data);
					// return false;
					var response = JSON.parse(response_data);
					if(response.response == 'success'){
						toastr.success("All Candidates Imported.", "Success");
						$("#upload_form").after('<div class="clearfix" id="redirect_timer">Candidates imported Redirecting to homepage in <span></span> seconds...</div>');
						var counter = 3;
						var interval = setInterval(function() {
							$("#redirect_timer > span").html(counter);
						    if (counter == 0) {
						        window.location.href = "<?php echo SITE_URL; ?>";
						    }
						    counter--;
						}, 1000);
					}else if(response.response == 'failed'){
						toastr.error(response.description, "Failed");
						console.log(response.errors);
					}
				})
				.fail(function(){
					alert('Import Failed');
				});
			})
		})
	</script>

</body>
<!--end::Body-->
</html>

