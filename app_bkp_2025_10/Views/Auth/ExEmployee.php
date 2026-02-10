<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<title>Healthgenie / GSTC Attendance System</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/media/logos/favicon.ico" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Global Stylesheets Bundle(used by all pages)-->
	<link href="<?php echo base_url(); ?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
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
		<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url('<?php echo base_url(); ?>/assets/media/illustrations/sketchy-1/14.png'">
			<!--begin::Content-->
			<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
				<!--begin::Logo-->
				<a href="<?php echo base_url(); ?>" class="mb-12">
					<img alt="Logo" src="<?php echo base_url(); ?>/assets/media/logos/logo-healthgenie.png" class="" />
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
					<form class="form w-100" id="kt_sign_in_form" action="<?php echo base_url('ex-employee'); ?>" method="post">
						<!--begin::Heading-->
						<div class="text-center mb-10">
							<!--begin::Title-->
							<h1 class="text-dark mb-3">Ex Employee Login</h1>
							<!--end::Title-->
							<!--begin::Link-->
							<div class="text-gray-400 fw-bold fs-4">Existing Employee? <a href="<?= base_url('/'); ?>" class="link-primary fw-bolder">Login</a></div>
							<!--end::Link-->
						</div>
						<!--begin::Heading-->
						<!--begin::Input group-->
						<div class="fv-row mb-10">
							<!--begin::Label-->
							<label class="form-label fs-6 fw-bolder text-dark">Mobile Number Or Adhar Number</label>
							<!--end::Label-->
							<!--begin::Input-->
							<input class="form-control form-control-lg form-control-solid" type="text" name="username" autocomplete="off" value="<?= set_value('username') ?>" />
							<!--end::Input-->
							<div class="text-muted">Enter either your Mobile Number or Adhar Number</div>
							<!--begin::Error Message-->
							<span class="text-danger"><?= isset($validation) ? display_error($validation, 'username') : '' ?></span>
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
						</div>
						<!--end::Actions-->

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
		var hostUrl = "<?php echo base_url(); ?>/assets/";
	</script>
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<!-- <script src="<?php echo base_url(); ?>/assets/js/custom/authentication/sign-in/general.js"></script> -->
	<!--end::Page Custom Javascript-->

	<script type="text/javascript">
		$(document).ready(function() {
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
				if (username.length) {
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