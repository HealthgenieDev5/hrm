<?php
require_once("includes/config.php");
if( isset($_REQUEST['login_submit']) && !empty($_REQUEST['login_submit']) ){
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$login_sql = "select * from users where email='".$username."' and password = '".md5($password)."'";
	$login_query = mysqli_query($conn, $login_sql) or die( "unable to login ".mysqli_error($conn) );
	if( mysqli_num_rows($login_query) == 1 ){
		$_SESSION['CURRENT_USER'] = mysqli_fetch_assoc($login_query);
		$login_message = "Login Successfull";
	}else{
		$login_message = "Login Failed";
	}
}
?>
<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
	<meta charset="utf-8"/>
	<title>Metronic | Login Page 3</title>
	<meta name="description" content="Login page example"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>        <!--end::Fonts-->


	<!--begin::Page Custom Styles(used by this page)-->
	<link href="assets/css/pages/login/classic/login-3.css" rel="stylesheet" type="text/css"/>
	<!--end::Page Custom Styles-->

	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
	<!--end::Global Theme Styles-->

	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->

	<link rel="shortcut icon" href="assets/media/logos/favicon.ico"/>

</head>
<!--end::Head-->

<!--begin::Body-->
<body  id="kt_body"  class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading"  >

	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
		<div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
			<div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid" style="background-image: url(assets/media/bg/bg-1.jpg);">
				<div class="login-form text-center text-white p-7 position-relative overflow-hidden">
					<!--begin::Login Header-->
					<div class="d-flex flex-center mb-15">
						<a href="#">
							<img src="assets/media/logos/logo-letter-9.png" class="max-h-100px" alt=""/>
						</a>
					</div>
					<!--end::Login Header-->

					<!--begin::Login Sign in form-->
					<div class="login-signin">
						<div class="mb-20">
							<h3>Sign In To Admin</h3>
							<p class="opacity-60 font-weight-bold">Enter your details to login to your account:</p>
							<?php 
							if(isset($login_message) && !empty($login_message) ){ 
								?>
								<div class="pt-10" id="login_message">
									<?php echo $login_message; ?>
								</div>
								<?php
							} 
							?>
						</div>
						<form class="form" id="kt_login_signin_form" method="post">
							<div class="form-group">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5" type="text" placeholder="Email" name="username" autocomplete="off"/>
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5" type="password" placeholder="Password" name="password"/>
							</div>
							<div class="form-group d-flex flex-wrap justify-content-between align-items-center px-8">
								<div class="checkbox-inline">
									<label class="checkbox checkbox-outline checkbox-white text-white m-0">
										<input type="checkbox" name="remember"/>
										<span></span>
										Remember me
									</label>
								</div>
								<a href="javascript:;" id="kt_login_forgot" class="text-white font-weight-bold">Forget Password ?</a>
							</div>
							<div class="form-group text-center mt-10">
								<input type="submit" name="login_submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3" value="Sign In" />
							</div>
						</form>
						<div class="mt-10">
							<span class="opacity-70 mr-4">
								Don't have an account yet?
							</span>
							<a href="javascript:;" id="kt_login_signup" class="text-white font-weight-bold">Sign Up</a>
						</div>
					</div>
					<!--end::Login Sign in form-->

					<!--begin::Login Sign up form-->
					<div class="login-signup">
						<div class="mb-20">
							<h3>Sign Up</h3>
							<p class="opacity-60">Enter your details to create your account</p>
						</div>
						<form class="form text-center" id="kt_login_signup_form" >
							<div class="form-group ">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="Fullname" name="fullname"/>
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off"/>
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Password" name="password"/>
							</div>
							<div class="form-group">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Confirm Password" name="cpassword"/>
							</div>
							<div class="form-group text-left px-8">
								<div class="checkbox-inline">
									<label class="checkbox checkbox-outline checkbox-white text-white m-0">
										<input type="checkbox" name="agree"/>
										<span></span>
										I Agree the <a href="#" class="text-white font-weight-bold ml-1">terms and conditions</a>.
									</label>
								</div>
								<div class="form-text text-muted text-center"></div>
							</div>
							<div class="form-group">
								<button id="kt_login_signup_submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3 m-2">Sign Up</button>
								<button id="kt_login_signup_cancel" class="btn btn-pill btn-outline-white font-weight-bold opacity-70 px-15 py-3 m-2">Cancel</button>
							</div>
						</form>
					</div>
					<!--end::Login Sign up form-->

					<!--begin::Login forgot password form-->
					<div class="login-forgot">
						<div class="mb-20">
							<h3>Forgotten Password ?</h3>
							<p class="opacity-60">Enter your email to reset your password</p>
						</div>
						<form class="form" id="kt_login_forgot_form">
							<div class="form-group mb-10">
								<input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off"/>
							</div>
							<div class="form-group">
								<button id="kt_login_forgot_submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3 m-2">Request</button>
								<button id="kt_login_forgot_cancel" class="btn btn-pill btn-outline-white font-weight-bold opacity-70 px-15 py-3 m-2">Cancel</button>
							</div>
						</form>
					</div>
					<!--end::Login forgot password form-->
				</div>
			</div>
		</div>
		<!--end::Login-->
	</div>
	<!--end::Main-->


	<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
	<!--begin::Global Config(global config for global JS scripts)-->
	<script>
		var KTAppSettings = {
			"breakpoints": {
				"sm": 576,
				"md": 768,
				"lg": 992,
				"xl": 1200,
				"xxl": 1200
			},
			"colors": {
				"theme": {
					"base": {
						"white": "#ffffff",
						"primary": "#663259",
						"secondary": "#E5EAEE",
						"success": "#1BC5BD",
						"info": "#8950FC",
						"warning": "#FFA800",
						"danger": "#F64E60",
						"light": "#F3F6F9",
						"dark": "#212121"
					},
					"light": {
						"white": "#ffffff",
						"primary": "#F4E1F0",
						"secondary": "#ECF0F3",
						"success": "#C9F7F5",
						"info": "#EEE5FF",
						"warning": "#FFF4DE",
						"danger": "#FFE2E5",
						"light": "#F3F6F9",
						"dark": "#D6D6E0"
					},
					"inverse": {
						"white": "#ffffff",
						"primary": "#ffffff",
						"secondary": "#212121",
						"success": "#ffffff",
						"info": "#ffffff",
						"warning": "#ffffff",
						"danger": "#ffffff",
						"light": "#464E5F",
						"dark": "#ffffff"
					}
				},
				"gray": {
					"gray-100": "#F3F6F9",
					"gray-200": "#ECF0F3",
					"gray-300": "#E5EAEE",
					"gray-400": "#D6D6E0",
					"gray-500": "#B5B5C3",
					"gray-600": "#80808F",
					"gray-700": "#464E5F",
					"gray-800": "#1B283F",
					"gray-900": "#212121"
				}
			},
			"font-family": "Poppins"
		};
	</script>
	<!--end::Global Config-->

	<!--begin::Global Theme Bundle(used by all pages)-->
	<script src="assets/plugins/global/plugins.bundle.js"></script>
	<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>
	<!--end::Global Theme Bundle-->


	<!--begin::Page Scripts(used by this page)-->
	<!-- <script src="assets/js/pages/custom/login/login-general.js"></script> -->
	<!--end::Page Scripts-->


	<script type="text/javascript">
		function _showForm(form) {
	        var cls = 'login-' + form + '-on';
	        var form = 'kt_login_' + form + '_form';

	        $("#kt_login").removeClass('login-forgot-on');
	        $("#kt_login").removeClass('login-signin-on');
	        $("#kt_login").removeClass('login-signup-on');

	        $("#kt_login").addClass(cls);

	        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
	    }
		$(document).ready(function(){
			_showForm('signin');
		})

	 	$('#kt_login_forgot').on('click', function (e) {
        	e.preventDefault();
            _showForm('forgot');
        });
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();
            _showForm('signin');
        });

        $('#kt_login_signup').on('click', function (e) {
            e.preventDefault();
            _showForm('signup');
        });
        $('#kt_login_signup_cancel').on('click', function (e) {
            e.preventDefault();
            _showForm('signin');
        });
	</script>
	<?php if( isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER']) ){ ?>
        <script type="text/javascript">
        	$(document).ready(function(){
        		window.location.replace("<?php echo SITE_URL; ?>");
	   //      	var counter = 3;
	   //      	$("#login_message").append('Redirecting in <span id="redirect_timer">'+counter+'</span> seconds');
				// var interval = setInterval(function() {
				//     counter--;
				//     $("#redirect_timer").html(counter);
				//     if (counter == 0) {
				//         clearInterval(interval);
				//         window.location.replace("<?php echo SITE_URL; ?>");
				//     }
				// }, 1000);
	        })
        </script>
    <?php } ?>
</body>
<!--end::Body-->
</html>