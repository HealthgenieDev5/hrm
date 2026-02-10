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
				<div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">






					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<!--begin::Form-->
					<form class="form w-100 mb-10" id="ex_employee_otp_form" action="<?php echo base_url('ex-employee/validate-otp'); ?>" method="post">

						<!--begin::Heading-->
						<div class="text-center mb-10">
							<!--begin::Title-->
							<h1 class="text-dark mb-3">Two Step Verification</h1>
							<!--end::Title-->
							<!--begin::Sub-title-->
							<div class="text-muted fw-bold fs-5 mb-5">Enter the verification code we sent to</div>
							<!--end::Sub-title-->
							<!--begin::Mobile no-->
							<div class="fw-bolder text-dark fs-3">******<?= substr(session()->get('otp')['otp_mobile'], -4) ?></div>
							<!--end::Mobile no-->
						</div>
						<!--end::Heading-->
						<!--begin::Section-->
						<div class="mb-10 px-md-10">
							<!--begin::Label-->
							<div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit security code</div>
							<!--end::Label-->
							<!--begin::Input group-->
							<div class="d-flex flex-wrap flex-stack">
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit1" oninput="moveToNext(this, 'digit2')" onkeydown="moveToPrev(event, this, 'digit1')" />
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit2" oninput="moveToNext(this, 'digit3')" onkeydown="moveToPrev(event, this, 'digit1')" />
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit3" oninput="moveToNext(this, 'digit4')" onkeydown="moveToPrev(event, this, 'digit2')" />
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit4" oninput="moveToNext(this, 'digit5')" onkeydown="moveToPrev(event, this, 'digit3')" />
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit5" oninput="moveToNext(this, 'digit6')" onkeydown="moveToPrev(event, this, 'digit4')" />
								<input type="text" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2" id="digit6" oninput="moveToNext(this, 'digit6')" onkeydown="moveToPrev(event, this, 'digit5')" />
							</div>
							<!--begin::Input group-->
							<input type="hidden" id="otp" name="otp" />
							<input type="hidden" id="mobile_number" name="mobile_number" value="<?= session()->get('otp')['otp_mobile'] ?>" />
						</div>
						<!--end::Section-->
						<!--begin::Submit-->
						<div class="d-flex flex-center">
							<button type="submit" id="ex_employee_otp_submit" class="btn btn-lg btn-primary fw-bolder">
								<span class="indicator-label">Submit</span>
								<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
						<!--end::Submit-->

					</form>
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->
					<!--end::Form-->



					<!--begin::Notice-->
					<div class="text-center fw-bold fs-5">
						<span class="text-muted me-1">Didn’t get the code ?</span>
						<a href="#" class="link-primary fw-bolder fs-5 me-1">Resend</a>
						<span class="text-muted me-1">or</span>
						<a href="#" class="link-primary fw-bolder fs-5">Call Us</a>
					</div>
					<!--end::Notice-->





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
		function moveToNext(current, nextFieldID) {
			if (current.value.length === 1) {
				document.getElementById(nextFieldID).focus();
			}
		}

		function moveToPrev(event, current, prevFieldID) {
			if (event.key === 'Backspace' && current.value === '') {
				document.getElementById(prevFieldID).focus();
			}
		}


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

			$(document).on('click', '#ex_employee_otp_submit', function(e) {
				e.preventDefault();
				var $this = $(this);
				$this.attr("data-kt-indicator", "on");
				var digit1 = $("#digit1").val();
				var digit2 = $("#digit2").val();
				var digit3 = $("#digit3").val();
				var digit4 = $("#digit4").val();
				var digit5 = $("#digit5").val();
				var digit6 = $("#digit6").val();
				var otp = `${digit1}${digit2}${digit3}${digit4}${digit5}${digit6}`;

				// $('#ex_employee_otp_form').submit();
				// var username = $('#kt_sign_in_form').find('input[name=username]').val();
				if (otp.length == 6) {
					$("#otp").val(otp);
					// $('#kt_sign_in_form').submit();
					$('#ex_employee_otp_form').submit();
				} else {
					Swal.fire({
						text: "Please Enter 6 digit OTP sent to your mobile number ******<?= substr(session()->get('otp')['otp_mobile'], -4) ?>",
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