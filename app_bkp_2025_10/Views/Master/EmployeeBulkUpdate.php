<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
	<!--begin::Row-->
	<div class="row gy-5 g-xl-8">
		<!--begin::Col-->
		<div class="col-12">


			<div class="card shadow-sm">
				<div class="card-header">
					<h3 class="card-title">Filters</h3>
				</div>
				<div class="card-body">
					<form class="card-toolbar w-100" id="bulk_update_form" enctype="multipart/form-data">
						<div class="row w-100">
							<div class="col-md-6">
			                    <label class="form-label" for="csv_file">File upload</label><br>
			                    <input type="file" name="csv_file" id="csv_file" placeholder="Csv File"/>
			                </div>
							<div class="col-md-2">
								<label class="form-label"> &nbsp; </label><br>
								<button type="submit" id="bulk_update_form_submit" class="btn btn-sm btn-primary d-inline">
									<span class="indicator-label">Update</span>
									<span class="indicator-progress">
										Please wait... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
									</span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
		<!--end::Col-->

		<div class="col-12" id="update_response">
		</div>
	</div>
	<!--end::Row-->

	<?= $this->section('javascript') ?>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/pagination/pagination.js"></script> -->
	<script type="text/javascript">

		jQuery(document).ready(function($){
			$(document).on('click', '#bulk_update_form_submit', function(e){
                e.preventDefault();
                var form = $('#bulk_update_form');
                var submitButton = $(this);
                submitButton.attr("data-kt-indicator", "on");
                submitButton.attr("disabled", "true");
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/backend/master/employee/bulk-update/save'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log(response);
                        submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");
                        if( response.response_type == 'error' ){
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                }).then(function (e) {
                                    $("#update_response").html("");
                                });
                            }
                        }

                        if( response.response_type == 'success' ){
                        	$("#update_response").html("");
                        	if( response.csv_data_response.length ){
                                $.each(response.csv_data_response, function(index, item) {
								    $("#update_response").append(item);
								});
                            }
                            if( response.response_description.length ){
                                Swal.fire({
                                    html: response.response_description,
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                });
                            }
                        }
                    },
                    failed: function(){
                        Swal.fire({
                            html: "Ajax Failed, Please contact administrator",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                        /*submitButton.removeAttr("data-kt-indicator");
                        submitButton.removeAttr("disabled");*/
                    }
                })
            })
		})
	</script>

	<?= $this->endSection() ?>
<?= $this->endSection() ?>