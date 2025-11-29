<?php require_once("includes/config.php"); ?>
<?php if( !isset($_SESSION['CURRENT_USER']) ){ header('Location: '.SITE_URL.'/login.php'); } ?>
<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
    <base href="">
    <meta charset="utf-8"/>
    <title>HR Management | Resume Upload</title>

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
                                            <h3 class="card-title align-items-start flex-column m-auto">
                                                <span class="card-label font-weight-bolder text-dark">Import Candidates</span>
                                            </h3>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <div class="card-body pt-2 pb-0">
                                            

                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <form enctype="multipart/form-data" method="post" id="upload_form">
                                                            
                                                        <div class="form-group">
                                                            <label for="resume">Select Resumes <span class="text-danger">*</span></label>
                                                            <input type="file" id="imported_file" class="form-control" name="imported_file[]" multiple>
                                                            <small>You can select multiple file</small><br>
                                                            <small>File name should be in format:  id-{candidate id}.extension example: id-123.pdf, id-10.docx</small>
                                                        </div>

                                                        <div class="form-group">
                                                            <input type="submit" id="import_form_submit" class="btn btn-primary form-control" name="import_form_submit" value="Upload">
                                                        </div>

                                                    </form>
                                                    <?php
                                                    if( isset($_FILES['imported_file']) ){
														$allowed_extensions=array('pdf','doc','docx','jpg','jpeg','png');
                                                        #create upload folder
                                                        $uploads = BASE_PATH."/uploads";
                                                        $year = date('Y');
                                                        $month = date('m');
                                                        $year_folder = $uploads."/".$year;
                                                        $month_folder = $uploads."/".$year."/".$month;
                                                        if(!is_dir($year_folder)){
                                                            mkdir($year_folder, 0774, true) or die("unable to create ".$year_folder." folder");
                                                        }
                                                        if(!is_dir($month_folder)){
                                                            mkdir($month_folder, 0774, true) or die("unable to create ".$month_folder." folder");
                                                        }

                                                        $upload_folder = BASE_PATH."/uploads/".$year."/".$month."/";
                                                        $attachment_folder_url = SITE_URL."/uploads/".$year."/".$month;


                                                        #function to change file name
                                                        function file_rename($filename, $folder, $ext){
                                                            $full_path = $folder.$filename;
                                                            if (!file_exists($full_path)) {
                                                                return $full_path;
                                                            }else{
                                                                $basename =  basename( $filename,".".$ext);     
                                                                $first_part = substr($basename, 0, -2);
                                                                $last_part = substr($basename, -2);
                                                                $dash = substr($last_part, 0, 1);
                                                                $file_number = substr($last_part, 1, 1);
                                                                if(is_numeric($file_number) && $dash == "-"){
                                                                    $file_number++;
                                                                    $last_part = $dash.$file_number;
                                                                }else{
                                                                    $last_part.="-1";
                                                                }
                                                                $new_file_name = $first_part . $last_part . "." . $ext;
                                                                $new_path = $folder . $new_file_name;
                                                                $newfolder = $folder;
                                                                $newext =  $ext;
                                                                return file_rename($new_file_name, $newfolder, $newext);
                                                            }   
                                                        }

                                                        $message = array();
                                                        $candidate_ids = array();
														$uploaded_files_array=array();
														$current_resume=array();
                                                        $current_data_all=array();
                                                        foreach( $_FILES['imported_file']['name'] as $index => $file_name){
                                                            $uploadOk = 1;
                                                            
                                                            if( substr(pathinfo($file_name)['filename'], 0, 3) !== 'id-'){
                                                            	$message['file_error'][] = "Filename format is not as per our requirement ".$file_name;
                                                            }else{
                                                            	
                                                            	// $candidate_id = str_replace('id-', '', pathinfo($file_name)['filename']);
	                                                            $candidate_id = substr(pathinfo($file_name)['filename'], 3);
	                                                            if( !is_numeric($candidate_id) ){
	                                                            	$message['file_error'][] = "candidate id not found in filename ".$file_name;
	                                                            }else{
		                                                            $fileTmpName = $_FILES['imported_file']['tmp_name'][$index];
																	$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
																	// echo $extension;
																	$file_name_new = pathinfo($file_name)['filename'].'_'.date('Y-m-d-H-i-s').".".$extension; 
		                                                            $target_file = $upload_folder . basename($file_name_new);
		                                                            
		                                                            $fileSize = $_FILES["resume_file"]["size"][$index]/(1024*1024);
		                                                            #Check file size
		                                                            if ($fileSize > 5) {
		                                                                $message['file_error'][] = "Sorry, your file is too large! Max 5mb is allowed! ".$file_name;
		                                                            }else{
																		if(!in_array($extension,$allowed_extensions)) {
																			#Allow certain file formats
																			$message['file_error'][] = "Sorry, only ".implode(', ', $allowed_extensions)." files are allowed. ".$file_name;
																		}else{
																			$check_existing_candidate = mysqli_query($conn, "select * from candidates where id=".$candidate_id) or die("Error while searching for candidate with id ".$candidate_id.mysqli_error($conn));
																			if( mysqli_num_rows($check_existing_candidate) == 1 ){
																				#Check if file already exists and change file name if necessary
																				$target_file = str_replace(" ", "-", $target_file);
																				$target_file = str_replace("%20", "-", $target_file);
																				$target_file = file_rename(basename($target_file), $upload_folder, $extension);
																				if (move_uploaded_file($fileTmpName, $target_file)) {
																					$uploaded_files_array[$candidate_id]=$target_file;
																					$resume_file = str_replace(BASE_PATH, "", $target_file);
                                                                                    #save current data to a variable in order to use it when resume is uploaded successfully
                                                                                    $curr_data = array();
																					while( $row = mysqli_fetch_assoc($check_existing_candidate) ){
                                                                                        $curr_data = $row;
																						$curr_resume = $row['resume'];
																					}
                                                                                    $curr_data['candidate_id'] = $curr_data['id'];
                                                                                    $curr_data['updated_by'] = $_SESSION['CURRENT_USER']['id'];
                                                                                    unset($curr_data['id']);
                                                                                    $current_data_all[$candidate_id] = $curr_data;
																					$current_resume[$candidate_id] = $curr_resume;
																					$sql = "update candidates set resume = '".addslashes($resume_file)."' where id = ".$candidate_id;
																					$update_query = mysqli_query($conn, $sql) or die("unable to update resume of candidate with id ".$candidate_id.mysqli_error($conn));
																					if($update_query){
																						$message['upload_success'][$candidate_id] = "File was uploaded and successfully updated the entry ".$file_name;
																					}else{
																						$message['file_error'][] = "File uploaded but there was a problem updating entry in database. Try uploading from candidate panel ".$file_name;
																					}
																				} else {
																					$message['file_error'][] = "Sorry, there was an error uploading your file. ".$file_name;
																				}
																			}else{
																				$message['file_error'][] = "Candidate doesnot exist ".$file_name;
																			}
																		}
																	}
		                                                        
																}
															}

                                                        }
														
														
                                                        if( isset($message['file_error']) ){														
															foreach($uploaded_files_array as $candidate_id=>$uploaded_file){
																if(unlink($uploaded_file)){
																	$sql = "update candidates set resume = '".$current_resume[$candidate_id]."' where id = ".$candidate_id;
																	// echo $sql;
																	$update_query = mysqli_query($conn, $sql) or die("unable to update resume of candidate with id ".$candidate_id.mysqli_error($conn));
																}
															}
                                                            $errors = $message['file_error'];
                                                            foreach( $errors as $candidate_id => $error_message ){
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <?php echo $error_message; ?>
                                                                </div>
                                                                <?php
                                                            }
                                                        }elseif( isset($message['upload_success']) ){
                                                            $upload_success = $message['upload_success'];
                                                            foreach( $upload_success as $candidate_id => $success_message ){
                                                                #save revision
                                                                $current_data = array_filter($current_data_all[$candidate_id]);
                                                                /*echo '<pre>';
                                                                print_r($current_data);
                                                                echo '</pre>';*/
                                                                $current_data_keys = array_keys($current_data);
                                                                $current_data_values = array_values($current_data);
                                                                foreach( $current_data_values as $key => $value ){
                                                                    $current_data_values[$key] = addslashes($value);
                                                                }
                                                                $current_data_keys_imploded = implode(',', $current_data_keys);
                                                                $current_data_values_imploded = implode("','", $current_data_values);
                                                                $revision_sql = "insert into candidates_revision (".$current_data_keys_imploded.") values ('".$current_data_values_imploded."')";
                                                                $revision_query = mysqli_query($conn, $revision_sql) or die("unable to save revision Please contact administrator ".mysqli_error($conn));
                                                                #save revision
                                                                ?>
                                                                <div class="alert alert-success" role="alert">
                                                                    <?php echo $success_message; ?> <a href="<?php echo SITE_URL.'/candidate.php?id='.$candidate_id; ?>" target="_blank">Open</a>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
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
                    'ajax_for'      :  'import_candidates',
                    'db_fields'     :   $db_fields,
                    'csv_fields'    :   $csv_fields,
                    // 'agent_id'       :   $agent_id,
                    'csv_data'      :   $csv_data,
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

