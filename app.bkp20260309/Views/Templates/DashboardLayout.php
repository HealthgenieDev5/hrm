<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<base href="">
	<title>
		<?php
		if (isset($page_title) && !empty($page_title)) {
			echo $page_title . ' | ' . config('App')->appName;
		} else {
			echo config('App')->appName;
		}
		?>
	</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/media/logos/favicon.ico" />

	<base href="/">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />



	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">


	<link href="<?php echo base_url(); ?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/plugins/custom/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />


	<link href="<?php echo base_url(); ?>assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo base_url(); ?>assets/plugins/custom/md/mdtimepicker.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.1.1/css/all.css" />

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css" />


	<link href="<?php echo base_url();  ?>/assets/plugins/custom/bootstrap5-dt-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />

	<link href="<?php echo base_url(); ?>assets/css/toggle-switch.css" rel="stylesheet" type="text/css" />
	<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet"> -->


</head>
<!--end::Head-->
<!--begin::Body-->

<!-- <body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:0px;--kt-toolbar-height-tablet-and-mobile:0px"> -->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
	<!--begin::Main-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="page d-flex flex-row flex-column-fluid">
			<!--begin::Aside Menu-->
			<?= $this->include('Templates/AsideMenu'); ?>
			<!--end::Aside Menu-->

			<!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

				<!--begin::Page Header-->
				<?= $this->include('Templates/PageHeader'); ?>
				<!--end::Page Header-->

				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Post-->
					<div class="post d-flex flex-column-fluid" id="kt_post">
						<!--begin::Container-->
						<div id="kt_content_container" class="container-fluid">

							<?php
							// print_r($_SESSION);
							?>


							<?= $this->renderSection('content'); ?>




						</div>
						<!--end::Container-->
					</div>
					<!--end::Post-->
				</div>
				<!--end::Content-->


				<!--begin::Footer-->
				<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
						<!--begin::Copyright-->
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted fw-bold me-1">© 2022</span>
							<a href="#" target="_blank" class="text-gray-800 text-hover-primary">Healthgenie</a>
						</div>
						<!--end::Copyright-->
						<!--begin::Menu-->
						<ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
							<li class="menu-item">
								<a href="#" target="_blank" class="menu-link px-2">About</a>
							</li>
							<li class="menu-item">
								<a href="#" target="_blank" class="menu-link px-2">Organisation Chart</a>
							</li>
							<li class="menu-item">
								<a href="#" target="_blank" class="menu-link px-2">Knowledge Base</a>
							</li>
						</ul>
						<!--end::Menu-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Footer-->

			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Root-->
	<!--end::Main-->

	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
		<span class="svg-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
				<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
				<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
			</svg>
		</span>
		<!--end::Svg Icon-->
	</div>
	<!--end::Scrolltop-->

	<!--Begin::Pop Up to take approval on manual gate pass for user-->
	<div class="position-fixed bottom-0 end-0" style="z-index: 999999">
		<div id="kt_docs_toast_toggle" class="toast p-3 bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
			<div class="toast-header">
				<span class="svg-icon svg-icon-2 svg-icon-primary me-3">...</span>
				<strong class="me-auto">Pending Gate Pass Request | HRM App</strong>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body bg-white bg-opacity-50">
				<b>Please take written approval on your gate pass</b><br>
				Take the Manual Gate Pass from HR department, Fill Up, and Get it approved from your HOD and present at the Gate at the time of leaving
			</div>
		</div>
	</div>
	<!--End::Pop Up to take approval on manual gate pass for user-->

	<!--begin::Javascript-->
	<script>
		var hostUrl = "<?php echo base_url(); ?>assets/";
	</script>

	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<script>
		$(document).on('click', 'a.swal2-confirm', function(e) {
			e.preventDefault();
			swal.close()
		});
		const check_days_interval = (from_id, to_id, element_to_clear, element_to_display) => {
			var from_date = $('#' + from_id).val();
			var to_date = $('#' + to_id).val();
			if (from_date.length && to_date.length) {
				var dt1 = new Date(from_date);
				var dt2 = new Date(to_date);
				var time_difference = dt2.getTime() - dt1.getTime();
				var result = time_difference / (1000 * 60 * 60 * 24);
				var number_of_days = result + 1;
				if (number_of_days <= 0) {
					var number_of_days = '';
					Swal.fire({
						html: 'Number of days can not be negative or 0',
						icon: "error",
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn btn-primary"
						},
						didRender: function(x) {
							$(".swal2-popup.swal2-modal").removeAttr("tabindex");
							var buttons = $(".swal2-popup > .swal2-actions > button");
							buttons.each(function(index, elem) {
								var btnClass = $(this).attr("class");
								var btnStyle = $(this).attr("style");
								var btnHtml = $(this).html();
								var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
								$(this).replaceWith(newButton);
							})
						},
						didClose: function(x) {
							$('#' + element_to_clear).val('').focus();
						},
					})
				}
			} else {
				var number_of_days = '';
			}
			$('#' + element_to_display).val(number_of_days);
		}
		const check_time_interval = (from_id, to_id, element_to_clear, element_to_display, allow_same_day) => {
			var from_date = $('#' + from_id).val();
			var to_date = $('#' + to_id).val();
			if (from_date.length && to_date.length) {
				var dt1 = new Date(from_date);
				var dt2 = new Date(to_date);
				var time_difference = dt2.getTime() - dt1.getTime();
				var total_minutes = time_difference / (1000 * 60);
				var hrs = Math.floor(total_minutes / 60);
				var hrs = String(hrs).padStart(2, '0');
				var mins = total_minutes % 60;
				var mins = String(mins).padStart(2, '0');
				if (hrs <= 0 && mins <= 0) {
					var number_of_days = '';
					Swal.fire({
						html: 'Number of days can not be negative or 0',
						icon: "error",
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn btn-primary"
						},
						didRender: function(x) {
							$(".swal2-popup.swal2-modal").removeAttr("tabindex");
							var buttons = $(".swal2-popup > .swal2-actions > button");
							buttons.each(function(index, elem) {
								var btnClass = $(this).attr("class");
								var btnStyle = $(this).attr("style");
								var btnHtml = $(this).html();
								var newButton = '<a class="' + btnClass + '" style="' + btnStyle + '">' + btnHtml + '</a>';
								$(this).replaceWith(newButton);
							})
						},
						didClose: function(x) {
							$('#' + element_to_clear).val('').focus();
						},
					})
				} else {
					var number_of_days = hrs + ':' + mins;
				}
			} else {
				var number_of_days = '';
			}
			$('#' + element_to_display).val(number_of_days);
		}
	</script>

	<script type="text/javascript">
		const button = document.getElementById('kt_docs_toast_toggle_button');
		const toastElement = document.getElementById('kt_docs_toast_toggle');
		const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
		/*toast.show();*/
		setInterval(function() {
			$.ajax({
				method: "post",
				url: "<?php echo base_url('ajax/check-gate-pass-request-today'); ?>",
				processData: false,
				contentType: false,
				success: function(response) {

					console.log(response);
					if (response == 'Available') {
						toast.show();
					}
				},
				failed: function() {
					console.log('Ajax Failed, Please contact administrator');
				}
			})
		}, 900000);

		/*toast.show();*/
	</script>

	<?= $this->renderSection('javascript'); ?>

	<!--end::Javascript-->
</body>
<!--end::Body-->

</html>