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
	<style type="text/css">
		.form-control,
		.form-select,
		.form-control.form-control-solid,
		.form-control.form-control-solid.active,
		.form-control.form-control-solid:active,
		.form-control.form-control-solid:focus {
			border-color: #a8a8a8;
		}
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
	<!--begin::Main-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Authentication - Sign-in -->
		<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url('<?php echo base_url(); ?>assets/media/illustrations/sketchy-1/14.png'">
			<!--begin::Content-->
			<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
				<!--begin::Logo-->
				<a href="<?php echo base_url(); ?>" class="mb-12">
					<img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo-healthgenie.png" class="" />
				</a>
				<!--end::Logo-->
				<!--begin::Wrapper-->
				<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">






					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<form class="form w-100" id="kt_sign_in_form" action="<?php echo base_url('login-validate'); ?>" method="post">
						<!--begin::Heading-->
						<div class="text-center mb-10">
							<!--begin::Title-->
							<h1 class="text-dark mb-3">Sign In to HRM</h1>
							<!--end::Title-->
							<!--begin::Link-->
							<!-- <div class="text-gray-400 fw-bold fs-4">New Here?
								<a href="<?php #echo base_url('signup');
											?>" class="link-primary fw-bolder">Create an Account</a></div> -->
							<div class="text-gray-400 fw-bold fs-4">New Employee?
								<a href="#" class="link-primary fw-bolder">Request for Account</a>
							</div>
							<!--end::Link-->
						</div>
						<!--begin::Heading-->
						<!--begin::Input group-->
						<div class="fv-row mb-10">
							<!--begin::Label-->
							<label class="form-label fs-6 fw-bolder text-dark">Username or Email ID</label>
							<!--end::Label-->
							<!--begin::Input-->
							<input class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off" value="<?= set_value('username') ?>" />
							<!--end::Input-->
							<div class="text-muted">Enter either your UserName or Work Email or Personal Email</div>
							<!--begin::Error Message-->
							<span class="text-danger"><?= isset($validation) ? display_error($validation, 'username') : '' ?></span>
							<!--end::Error Message-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="fv-row mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-stack mb-2">
								<!--begin::Label-->
								<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
								<!--end::Label-->
								<!--begin::Link-->
								<a href="<?php echo base_url('password-reset'); ?>" class="link-primary fs-6 fw-bolder">Forgot Password ?</a>
								<!--end::Link-->
							</div>
							<!--end::Wrapper-->
							<!--begin::Input-->
							<div class="position-relative">
								<input class="form-control form-control-lg form-control-solid" type="password" name="password" id="password" autocomplete="off" value="<?= set_value('password') ?>" />
								<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="passwordVisibility">
									<i class="bi bi-eye-slash fs-2"></i>
									<i class="bi bi-eye fs-2 d-none"></i>
								</span>
							</div>
							<!--end::Input-->
							<!--begin::Error Message-->
							<span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>
							<!--end::Error Message-->
						</div>
						<!--end::Input group-->
						<!--begin::Actions-->
						<div class="text-center">
							<!--begin::Submit button-->
							<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
								<span class="indicator-label">Continue</span>
								<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
							<!--end::Submit button-->
							<!--begin::Separator-->
							<!-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div> -->
							<!--end::Separator-->
							<!--begin::Google link-->
							<!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
								<img alt="Logo" src="<?php echo base_url(); ?>assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Continue with Google
							</a> -->
							<!--end::Google link-->
							<!--begin::Google link-->
							<!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
								<img alt="Logo" src="<?php echo base_url(); ?>assets/media/svg/brand-logos/facebook-4.svg" class="h-20px me-3" />Continue with Facebook
							</a> -->
							<!--end::Google link-->
							<!--begin::Google link-->
							<!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100">
								<img alt="Logo" src="<?php echo base_url(); ?>assets/media/svg/brand-logos/apple-black.svg" class="h-20px me-3" />Continue with Apple
							</a> -->
							<!--end::Google link-->
						</div>
						<!--end::Actions-->

						<div class="text-center mt-10">
							<div class="text-gray-400 fw-bold fs-4">Ex-Employee?
								<a href="<?php echo base_url('ex-employee'); ?>" class="link-primary fw-bolder ms-3">Relieving Documents</a>
							</div>
						</div>


					</form>
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->






				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Content-->
			<!--begin::Footer-->
			<div class="d-flex flex-center flex-column-auto p-10">
				<!--begin::Links-->
				<div class="d-flex align-items-center fw-bold fs-6">
					<a href="#" class="text-muted text-hover-primary px-2">About</a>
					<a href="#" class="text-muted text-hover-primary px-2">Organisation Chart</a>
					<a href="#" class="text-muted text-hover-primary px-2">Knowledge Base</a>
				</div>
				<!--end::Links-->
			</div>
			<!--end::Footer-->
		</div>
		<!--end::Authentication - Sign-in-->
	</div>
	<!--end::Root-->
	<!--end::Main-->
	<!--begin::Javascript-->
	<script>
		var hostUrl = "<?php echo base_url(); ?>assets/";
	</script>
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<!-- <script src="<?php echo base_url(); ?>assets/js/custom/authentication/sign-in/general.js"></script> -->
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		$(document).ready(function() {

			$(document).on('click', '#passwordVisibility', function() {
				if ($("#password").attr("type") == "password") {
					$("#password").attr("type", "text");
					$(this).find("i").toggleClass('d-none');
				} else {
					$("#password").attr("type", "password");
					$(this).find("i").toggleClass('d-none');
				}

			})

			/*begin::Show validation error message*/
			var $response = "<?php echo session()->getFlashdata('fail'); ?>";
			if ($response.length) {
				Swal.fire({
					html: $response,
					icon: "error",
					buttonsStyling: !1,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn btn-primary"
					},
				})
			}
			/*end::Show validation error message*/

			$(document).on('click', '#kt_sign_in_submit', function(e) {
				e.preventDefault();
				var $this = $(this);
				$this.attr("data-kt-indicator", "on");
				var username = $('#kt_sign_in_form').find('input[name=username]').val();
				var password = $('#kt_sign_in_form').find('input[name=password]').val();
				if (username.length && password.length) {
					$('#kt_sign_in_form').submit();
				} else {
					Swal.fire({
						text: "Sorry, looks like there are some errors, please try again.",
						icon: "error",
						buttonsStyling: !1,
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn btn-primary"
						},
					}).then(
						function(e) {
							$this.removeAttr("data-kt-indicator");
						}
					);
				}
			})


		})
	</script>

	<!--end::Javascript-->
</body>
<!--end::Body-->

</html>