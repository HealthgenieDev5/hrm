<?php 
$page_title = 'Login';
include_once("./inc/header-top.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if( isset( $_SESSION['login'] ) && !empty( $_SESSION['login'] ) ){
	header('location:'.SITE_URL);
}
?>
<!--begin::Custom Css-->
<style type="text/css">
	.select2-container--bootstrap-5.select2-container--focus .select2-selection, 
	.select2-container--bootstrap-5.select2-container--open .select2-selection {
		box-shadow: none !important;
	}
	.btn .button-loader,
	.btn .button-loader-text,
	.btn .otp-timer,
	.btn .otp-timer-text{
		display: none;
	}
	.btn .button-text{
		display: block;
	}
	.btn.loading .button-loader,
	.btn.loading .button-loader-text{
		display: block;
	}
	.btn.timer-on .otp-timer,
	.btn.timer-on .otp-timer-text{
		display: block;
	}
	.btn.loading .button-text,
	.btn.timer-on .button-text{
		display: none;
	}
</style>
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php"); ?>

<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="./assets/img/gstc-logo.png" alt="">
                                <!-- <span class="d-none d-lg-block">GSTC</span> -->
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                    <p class="text-center small">Enter your username & password to login</p>
                                </div>

                                <form id="login_form" class="row g-3">
                                    <div class="col-12">
                                        <label for="user_name" class="form-label required">Username</label>
                                        <input type="text" name="user_name" class="form-control" id="user_name" placeholder="Enter your username" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-12">
                                        <label for="user_pass" class="form-label">Password</label>
                                        <input type="password" name="user_pass" class="form-control" id="user_pass" placeholder="Enter your password" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">
                                        	<span class="button-text">Login</span>
                                    		<span class="button-loader spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <span class="button-loader-text">Verifying... </span>
                                    	</button>
                                    	<div class="valid-feedback text-center"></div>
                                    	<div class="invalid-feedback text-center"></div>
                                    </div>
                                    <div class="col-12">
                                        <p class="text-center small mb-0">Trouble logging in? <br>Please contact <a href="#">Developers</a> on EXT: 455</p>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            Designed by <a href="https://healthgenie.in/">HG IT</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</main>
<!-- End #main -->

<?php #include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php"); ?>
<!--begin::Custom Javascript-->
<script type="text/javascript">
	$(document).ready(function($){
		$('#employee_id').select2({
			theme: "bootstrap-5",
			dropdownParent: $("#employee_id").parent()
		}).on('select2:open', function (e) {
			document.querySelector('.select2-search__field').focus();
		});

		$(document).on('submit', '#login_form', function(e){
			e.preventDefault();
			var user_name = $('#user_name').val();
			var user_pass = $('#user_pass').val();
			var button = $(this).find('button[type=submit]');
			button.attr('disabled', true);
			button.addClass('loading');

			if( user_name.length >= 5 ){
				if( user_pass.length >= 5 ){
					
					var data = {
						'user_name': user_name,
						'user_pass': user_pass,
					};
					jQuery.ajax({
						url: '<?php echo SITE_URL."/controller/ajax/ajax-validate-login-with-username-password.php"; ?>',
						type: 'POST',
						data:  data,
						dataType: 'html',
					})
					.done(function(response_data){
						// console.log(response_data);
						var resultObj = JSON.parse(response_data);
						if( resultObj.message == 'success' ){
							button.parent().find('.valid-feedback').html(resultObj.message+': '+resultObj.description).show();
							button.attr('disabled', false);
							button.removeClass('loading');
							window.location.replace("<?php echo SITE_URL; ?>");
						}else{
							button.parent().find('.invalid-feedback').html(resultObj.message+': '+resultObj.description).show();
							button.attr('disabled', false);
							button.removeClass('loading');
						}
					})
					.fail(function(){
						alert('There was an error while logging you in, Please contact developer');
						button.attr('disabled', false);
						button.removeClass('loading');
					});

				}else{
					alert('Password should be minimum 5 charecters');
					button.attr('disabled', false);
					button.removeClass('loading');
				}
			}else{
				alert('User Name should be minimum 5 charecters');
				button.attr('disabled', false);
				button.removeClass('loading');
			}
			
		})
	})
</script>
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php"); ?>