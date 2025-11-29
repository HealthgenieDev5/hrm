<?php 
mail("developer@healthgenie.in", "My subject", "Some message");
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
                                        <label for="user_name" class="form-label">Your Name</label>
                                        <select class="form-select" aria-label="Default select example" id="employee_id" data-placeholder="Select Your Name">
                                        	<option></option>
                                        	<?php
                                        	// $sql = "select e.first_name as first_name, e.last_name as last_name, e.id as id from employees e left join companies c on c.id = e.company_id where c.id = '2'";
                                        	$sql = "select e.first_name as first_name, e.last_name as last_name, e.id as id from employees e left join companies c on c.id = e.company_id";
                                        	// $sql = "select e.first_name as first_name, e.last_name as last_name, e.id as id from employees e left join companies c on c.id = e.company_id where e.company_id='2'";
                                        	$query = mysqli_query($conn, $sql) or die('Unable to fetch employees from database <br>'. mysqli_error($conn));
                                        	if( mysqli_num_rows($query) > 0 ){
                                        		while( $row = mysqli_fetch_assoc($query) ){
                                        			?><option value="<?= $row['id'] ?>"><?= trim($row['first_name'].' '.$row['last_name']) ?></option><?php
                                        		}
                                        	}
                                        	?>
                                        </select>
                                    </div>

                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                    	<button class="btn btn-success d-flex align-items-center" type="button" id="send_otp_button">
                                    		<span class="button-text">Send OTP</span>
                                    		<span class="button-loader spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <span class="button-loader-text">Sending... </span>
                                    		<span class="otp-timer spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span> <span class="otp-timer-text">Resend OTP in <span class="timer"></span> seconds</span>
                                    	</button>
                                    	<div class="valid-feedback text-center"></div>
                                    	<div class="invalid-feedback text-center"></div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">OTP</label>
                                        <input type="text" name="otp" class="form-control" id="otp" placeholder="Ente OTP" required>
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
                                        <p class="text-center small mb-0">Couldn't find your name? Contact <a href="#">Developers</a></p>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            Designed by <a href="https://bootstrapmade.com/">HG IT</a>
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

		$(document).on('click', '#send_otp_button', function(e){
			e.preventDefault();
			var button = $(this);
			button.attr('disabled', true);
			button.addClass('loading');
			var employee_id = $("#employee_id").val();
			if( employee_id.length > 0 ){
				var data = {
					'employee_id': employee_id,
				};
				jQuery.ajax({
					url: '<?php echo SITE_URL."/controller/ajax/ajax-send-otp.php"; ?>',
					type: 'POST',
					data:  data,
					dataType: 'html',
				})
				.done(function(response_data){
					// console.log(response_data);
					var resultObj = JSON.parse(response_data);
					if( resultObj.message == 'success' ){
						button.removeClass('loading').addClass('timer-on');
						button.parent().find('.valid-feedback').html(resultObj.description).show();
						var timeLeft = 45;
						var elem = button.find('.timer');
						elem.html(timeLeft);
						var timer = setInterval(function(){
							if (timeLeft == -1) {
						        clearInterval(timer);
						        button.attr('disabled', false);
								button.removeClass('timer-on');
						    } else {
						        elem.html(timeLeft);
						        timeLeft--;
						    }
						}, 1000);
					}else{
						// alert(resultObj.message+': '+resultObj.description);
						button.parent().find('.invalid-feedback').html(resultObj.message+': '+resultObj.description).show();
						button.attr('disabled', false);
						button.removeClass('loading');
					}
				})
				.fail(function(){
					alert('There was an error while sending OTP to your registered mobile number');
					button.attr('disabled', false);
					button.removeClass('loading');
				});
			}else{
				alert('Please select your name');
				button.attr('disabled', false);
				button.removeClass('loading');
			}
		})

		$(document).on('submit', '#login_form', function(e){
			e.preventDefault();
			var employee_id = $('#employee_id').val();
			var otp = $('#otp').val();
			if( otp.length == 6){
				if( employee_id.length > 0){



					var button = $(this).find('button[type=submit]');
					button.attr('disabled', true);
					button.addClass('loading');
					var employee_id = $("#employee_id").val();
					if( employee_id.length > 0 ){
						var data = {
							'employee_id': employee_id,
							'otp': otp,
						};
						jQuery.ajax({
							url: '<?php echo SITE_URL."/controller/ajax/ajax-validate-login.php"; ?>',
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
							alert('There was an error while sending OTP to your registered mobile number');
							button.attr('disabled', false);
							button.removeClass('loading');
						});
					}else{
						alert('Please select your name');
						button.attr('disabled', false);
						button.removeClass('loading');
					}



				}else{
					alert('Please select your name');
				}
			}else{
				alert('Please enter valid otp'+otp.length);
			}
		})
	})
</script>
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php"); ?>