<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12">
            



            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Holidays</h3>
                </div>
                <div class="card-body">
                    <form id="update_holiday" method="post">
                		<div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="required form-label">Holiday Code</label>
                                    <select class="form-select form-select-sm" id="holiday_code_update" name="holiday_code" data-control="select2" data-placeholder="Select Holiday Code" >
                                        <option value=""></option>
                                        <option value="HL" <?php echo $Holiday['holiday_code'] == 'HL' ? 'selected' : ''; ?>>HL</option>
                                        <option value="NH" <?php echo $Holiday['holiday_code'] == 'NH' ? 'selected' : ''; ?>>NH</option>
                                        <option value="RH" <?php echo $Holiday['holiday_code'] == 'RH' ? 'selected' : ''; ?>>RH</option>
                                        <option value="SPL HL" <?php echo $Holiday['holiday_code'] == 'SPL HL' ? 'selected' : ''; ?>>SPL HL</option>
                                    </select>
                                    <!--begin::Error Message-->
                                    <span class="text-danger d-block" id="holiday_code_error"></span>
                                    <!--end::Error Message-->
                                    <input type="hidden" name="holiday_id" value="<?php echo $Holiday['id']; ?>" />
                                    <!--begin::Error Message-->
                                    <span class="text-danger error-text d-block" id="holiday_id_error"></span>
                                    <!--end::Error Message-->
                                </div>
                                <div class="mb-3">
                                    <label class="required form-label">Holiday Name</label>
                                    <input type="text" id="holiday_name" name="holiday_name" class="form-control form-control-sm" placeholder="Holiday Name" value="<?php echo $Holiday['holiday_name']; ?>" oninput="$(this).next().html('')" />
                                    <!--begin::Error Message-->
                                    <span class="text-danger d-block" id="holiday_name_error"></span>
                                    <!--end::Error Message-->
                                </div>
                                <div class="mb-3">
                                    <label class="required form-label">Holiday Type</label>
                                    <input type="text" id="holiday_type" name="holiday_type" class="form-control form-control-sm" placeholder="Holiday Type" value="<?php echo $Holiday['holiday_type']; ?>" oninput="$(this).next().html('')" />
                                    <!--begin::Error Message-->
                                    <span class="text-danger d-block" id="holiday_type_error"></span>
                                    <!--end::Error Message-->
                                </div>
                                <div class="mb-3">
                                    <label class="required form-label">Is Special Holiday</label>
                                    <div class="switch-toggle form-control form-control-sm form-control-solid d-flex p-0 position-relative">
                                        <label for="switch_is_special_holiday_no" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                            No
                                            <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_no" value="no" checked>
                                        </label>
                                        <label for="switch_is_special_holiday_yes" class="text-center form-control form-control-sm bg-transparent border-0 position-relative" >
                                            Yes
                                            <input type="radio" name="is_special_holiday" class="opacity-0 position-absolute" id="switch_is_special_holiday_yes" value="yes" <?php echo $Holiday['is_special_holiday'] == 'yes' ? 'checked' : ''; ?>>
                                        </label>
                                        <a class="bg-success form-control form-control-sm p-0 position-absolute"></a>
                                    </div>
                                    <!--begin::Error Message-->
                                    <span class="text-danger d-block" id="is_special_holiday_error"></span>
                                    <!--end::Error Message-->
                                </div>
                                <div class="mb-3">
                                    <label class="required form-label">Holiday Date</label>
                                    <div class="input-group">
                                        <input type="text" id="holiday_date_edit" class="form-control form-control-sm" name="holiday_date" placeholder="Holiday Date" value="" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" oninput="$(this).parent().parent().next().html('')">
                                        <span class="input-group-text input-group-solid cursor-pointer parent-picker">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <!--begin::Error Message-->
                                    <span class="text-danger d-block" id="holiday_date_error"></span>
                                    <!--end::Error Message-->
                                </div>
                                <div class="mb-3">
                                    <button type="submit" id="update_holiday_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                </div>
                            </div>
                            <div class="col-lg-8" >
                                <div class="d-flex flex-wrap">
                                	<label class="form-label me-3">Select Company wise</label>
                                    <?php
                                    if( !empty($companies) ){
                                        foreach( $companies as $company ){
                                            ?>
                                            <label class="badge badge-primary me-3 mb-2">
                                                <input type="checkbox" class="company-selector" id="<?php echo 'company_'.$company['id']; ?>" data-company="<?php echo $company['id']; ?>">
                                                <span><?php echo $company['company_short_name']; ?></span>
                                            </label>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="d-flex flex-wrap">
                                	<label class="form-label me-3">Select Machine wise</label>
                                    <?php
                                    $machines = array_unique(array_column($AllEmployees, 'machine'));
                                    foreach( $machines as $machine ){
                                        ?>
                                        <label class="badge badge-info me-3 mb-2">
                                            <input type="checkbox" class="machine-selector" id="<?php echo 'machine_'.$machine; ?>" data-machine="<?php echo $machine; ?>">
                                            <span><?php echo strtoupper($machine); ?></span>
                                        </label>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <table id="update_list_of_employees" class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Employees</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($AllEmployees as $employee){
                                            ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input selected-employees" data-company_id="<?php echo $employee['company_id']; ?>" data-machine_id="<?php echo $employee['machine']; ?>" type="checkbox" name="employee_id[]" value="<?php echo $employee['id']; ?>" <?php echo !empty($Holiday['employees']) && in_array($employee['id'], $Holiday['employees']) ? 'checked' : ''; ?> />
                                                </td>
                                                <td>
                                                    <?php echo $employee['employee_name']." (".$employee['internal_employee_id'].") - ".$employee['department_name']." - ".$employee['company_short_name']; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </form>
                </div>
            </div>




        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <?= $this->section('javascript') ?>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){

            $('#holiday_date_edit').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: false,
                static : true,
                defaultDate: ["<?php echo $Holiday['holiday_date']; ?>"]
            })

            $(document).on('click', '.parent-picker', function(){
                $(this).parent().find('.flatpickr-input').focus();
            })

            var update_list_of_employees = $("#update_list_of_employees").DataTable({
                "dom": 'ft',
                "scrollX": true,
                "scrollY": '50vh',
                "paging" : false,
                /*buttons: [
		            {
		                text: 'Select All',
		                action: function ( e, dt, node, config ) {
		                    toggleAllEmployees();
		                }
		            },
		            {
		                text: 'Select HGIPL',
		                action: function ( e, dt, node, config ) {
		                    toggleHGIPLEmployees();
		                }
		            },
		            {
		                text: 'Select HG GGN',
		                action: function ( e, dt, node, config ) {
		                    toggleHGGGNEmployees();
		                }
		            },
		            {
		                text: 'Select GSTC',
		                action: function ( e, dt, node, config ) {
		                    toggleGSTCEmployees();
		                }
		            },
		            {
		                text: 'Select Heuer',
		                action: function ( e, dt, node, config ) {
		                    toggleHEUEREmployees();
		                }
		            },
		            {
		                text: 'Select Sinew',
		                action: function ( e, dt, node, config ) {
		                    toggleSINEWEmployees();
		                }
		            },
		        ]*/
            });

            

            // check initially if all employees of a machine is already selected
            var AllMachines = <?php echo json_encode($machines); ?>;
        	$.each(AllMachines, function(index, item){
        		var machine = item;
        		var AllOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]');
        		var SelectedOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]:checked');            	
            	if( AllOfThisMachine.length == SelectedOfThisMachine.length ){
        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', true);
        		}else{
        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', false);
        		}
        	});
        	// check initially if all employees of a company is already selected
        	var AllCompanies = <?php echo json_encode(array_column($companies, 'id')); ?>;
        	$.each(AllCompanies, function(index, item){
            		var company = item;
	        		var AllOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]');
	        		var SelectedOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]:checked');            	
	            	if( AllOfThisCompany.length == SelectedOfThisCompany.length ){
	        			$('.company-selector[data-company="'+company+'"]').prop('checked', true);
	        		}else{
	        			$('.company-selector[data-company="'+company+'"]').prop('checked', false);
	        		}
            	});

            $(document).on('change', '.company-selector', function(e){
            	e.preventDefault();
            	if( $(this).is(":checked") ){
            		$(this).prop('checked', true);
            		$('.selected-employees[data-company_id="'+$(this).data("company")+'"]').prop('checked', true);
            	}else{
            		$(this).prop('checked', false);
            		$('.selected-employees[data-company_id="'+$(this).data("company")+'"]').prop('checked', false);
            	}
            	var AllMachines = <?php echo json_encode($machines); ?>;
            	$.each(AllMachines, function(index, item){
            		var machine = item;
	        		var AllOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]');
	        		var SelectedOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]:checked');            	
	            	if( AllOfThisMachine.length == SelectedOfThisMachine.length ){
	        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', true);
	        		}else{
	        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', false);
	        		}
            	});
            });

            $(document).on('change', '.machine-selector', function(e){
            	e.preventDefault();
            	if( $(this).is(":checked") ){
            		$(this).prop('checked', true);
            		$('.selected-employees[data-machine_id="'+$(this).data("machine")+'"]').prop('checked', true);
            	}else{
            		$(this).prop('checked', false);
            		$('.selected-employees[data-machine_id="'+$(this).data("machine")+'"]').prop('checked', false);
            	}
            	var AllCompanies = <?php echo json_encode(array_column($companies, 'id')); ?>;
            	$.each(AllCompanies, function(index, item){
            		var company = item;
	        		var AllOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]');
	        		var SelectedOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]:checked');            	
	            	if( AllOfThisCompany.length == SelectedOfThisCompany.length ){
	        			$('.company-selector[data-company="'+company+'"]').prop('checked', true);
	        		}else{
	        			$('.company-selector[data-company="'+company+'"]').prop('checked', false);
	        		}
            	});
            });

            $(document).on('change', '.selected-employees', function(){
            	var company = $(this).data('company_id');
        		var AllOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]');
        		var SelectedOfThisCompany = $('.selected-employees[data-company_id="'+company+'"]:checked');            	
            	if( AllOfThisCompany.length == SelectedOfThisCompany.length ){
        			$('.company-selector[data-company="'+company+'"]').prop('checked', true);
        		}else{
        			$('.company-selector[data-company="'+company+'"]').prop('checked', false);
        		}

        		var machine = $(this).data('machine_id');
        		var AllOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]');
        		var SelectedOfThisMachine = $('.selected-employees[data-machine_id="'+machine+'"]:checked');            	
            	if( AllOfThisMachine.length == SelectedOfThisMachine.length ){
        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', true);
        		}else{
        			$('.machine-selector[data-machine="'+machine+'"]').prop('checked', false);
        		}
            });

            //begin::Update Holiday Ajax
            $(document).on('click', '#update_holiday_submit_button', function(e){
                e.preventDefault();
                var form = $('#update_holiday');
                var data = new FormData(form[0]);
                $.ajax({
                    method: "post",
                    url: "<?php echo base_url('/ajax/backend/master/update-holiday'); ?>",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log(response);
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
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                }).then(function (e) {
                                    form[0].reset();
                                    form.closest('.modal').modal('hide');
                                    $("#holidays_table").DataTable().ajax.reload();
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
                    }
                })
            })
            //end::Update Holiday Ajax




            
        })
    </script>
    <script>
        $(document).ready(function(){

            var toggleSwitch = $(this).find('.switch-toggle');
            toggleSwitch.each(function( index, thisSwitch){
                var checked_input = $(thisSwitch).find('label > input:checked').parent();
                var w = checked_input.outerWidth();
                var indexoflabel = checked_input.index();
                $(thisSwitch).find('a').css({ 'width': w, 'left' : indexoflabel*w});
            })

            $(document).on('click', '.switch-toggle > label', function(e){
                var w = $(this).outerWidth();
                $(this).find('input').prop('checked', true);
                $(this).parent().find('a').css({ 'width': w, 'left' : $(this).position().left});
            })
        })
    </script>
    <?= $this->endSection() ?>
<?= $this->endSection() ?>