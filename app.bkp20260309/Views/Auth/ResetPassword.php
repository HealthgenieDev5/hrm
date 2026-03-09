<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Healthgenie / GSTC Attendance System</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="robots" content="noindex, nofollow">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/media/logos/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - New password -->
			<div 
			class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" 
			style="background-image: url(<?php echo base_url(); ?>assets/media/illustrations/sketchy-1/14.png)">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="<?php echo base_url(); ?>" class="mb-12">
						<img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo-healthgenie.png" class="h-40px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-550px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">

						<?php 
						if( isset($error_message) && !empty($error_message) ){ 
							?>
							<div class="text-gray-400 fw-bold fs-5 text-center">
								<?= $error_message ?>. Please <a href="<?= base_url('password-reset') ?>" class="link-primary fw-bolder">Try again</a>
							</div>
							<?php 
						}else{ 
							?>
							<!--begin::Form-->
							<form class="form w-100" id="password_reset_form" method="post">
								<!--begin::Heading-->
								<div class="text-center mb-10">
									<!--begin::Title-->
									<h1 class="text-dark mb-3">Setup New Password</h1>
									<!--end::Title-->
									<!--begin::Link-->
									<div class="text-gray-400 fw-bold fs-5">Already have reset your password ? <a href="<?= base_url('login'); ?>" class="link-primary fw-bolder">Sign in here</a></div>
									<!--end::Link-->
								</div>
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="mb-10 fv-row">
									<!--begin::Wrapper-->
									<div class="mb-1">
										<!--begin::Label-->
										<label class="form-label fw-bolder text-dark fs-6">Password</label>
										<!--end::Label-->
										<!--begin::Input wrapper-->
										<div class="position-relative mb-3">
											<input class="form-control form-control-lg form-control-solid" type="password" id="password" placeholder="" name="password" autocomplete="off" />
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="passwordVisibility">
												<i class="bi bi-eye-slash fs-2"></i>
												<i class="bi bi-eye fs-2 d-none"></i>
											</span>
											<small class="text-danger error-text" id="password_error"></small>
										</div>
										<!--end::Input wrapper-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Hint-->
									<div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
									<!--end::Hint-->
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
									<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="password_confirmation" name="password_confirmation" autocomplete="off" />
									<small class="text-danger error-text" id="password_confirmation_error"></small>
								</div>
								<!--end::Input group-->
								<!--begin::Action-->
								<div class="text-center">
									<input type="hidden" name="token" id="token" value="<?= $token ?>" />
									<button type="submit" class="btn btn-lg btn-primary fw-bolder">
										<span class="indicator-label">Submit</span>
										<span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
							<?php 
						} 
						?>
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="d-flex flex-center flex-column-auto p-10">
					<!--begin::Links-->
					<div class="d-flex align-items-center fw-bold fs-6">
						<a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>
						<a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
						<a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
					</div>
					<!--end::Links-->
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Authentication - New password-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo base_url(); ?>assets/";</script>
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$(document).on('click', '#passwordVisibility', function(){
					if( $("#password").attr("type") == "password" ){
						$("#password").attr("type", "text");
						$(this).find("i").toggleClass('d-none');
					}else{
						$("#password").attr("type", "password");
						$(this).find("i").toggleClass('d-none');
					}
					
				})
				$(document).on('submit', '#password_reset_form', function(e){
	                e.preventDefault();
                	var form = $(this);
	                var password = $('#password').val();
	                var password_confirmation = $('#password_confirmation').val();
	                var token = $('#token').val();

	                var submitButton = $(this).find('button[type=submit]');
	                submitButton.attr("data-kt-indicator", "on");
	                submitButton.attr("disabled", "true");
	                $('.error-text').html('');
	                var data = {
	                	'password' : password,
	                	'password_confirmation' : password_confirmation,
	                	'token' : token,
	                };
	                $.ajax({
	                    method: "post",
	                    url: "<?php echo base_url('ajax/password-reset/new-password'); ?>",
	                    data: data,
	                    // processData: false,
	                    // contentType: false,
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
	                                    if( typeof response.response_data.validation != 'undefined' ){
	                                        var validation = response.response_data.validation;
	                                        $.each(validation, function(index, value){
	                                            form.find('#'+index+'_error').html(value);
	                                        });
	                                    }
	                                });
	                            }
	                        }

	                        if( response.response_type == 'success' ){
	                            if( response.response_description.length ){
	                                Swal.fire({
	                                    html: response.response_description,
	                                    icon: "success",
	                                    buttonsStyling: !1,
	                                    confirmButtonText: "Go to login page",
	                                    customClass: { confirmButton: "btn btn-primary" },
	                                }).then((result) => {
                    					if (result.isConfirmed) {
                    						// window.location.href
                    						window.location.replace("<?= base_url('login'); ?>");
                    					}
                    				});
	                            }
	                        }
	                    },
	                    failed: function(){
	                        Swal.fire({
	                            html: "Connection Failed, Please contact administrator",
	                            icon: "error",
	                            buttonsStyling: !1,
	                            confirmButtonText: "Ok, got it!",
	                            customClass: { confirmButton: "btn btn-primary" },
	                        })
	                        submitButton.removeAttr("data-kt-indicator");
	                        submitButton.removeAttr("disabled");
	                    }
	                })
	            })
			})
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>