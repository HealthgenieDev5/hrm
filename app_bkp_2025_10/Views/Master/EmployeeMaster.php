<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
	<!--begin::Col-->
	<div class="col-12">


		<div class="card shadow-sm">
			<div class="card-header">
				<h3 class="card-title">Filters</h3>
				<div class="card-toolbar">
					<a href="<?= base_url('/backend/master/employee/add-new') ?>" class="btn btn-sm btn-light-primary"><i class="fa fa-plus"></i> Add New</a>
				</div>
			</div>
			<div class="card-body">
				<form class="card-toolbar w-100" id="filter_form">
					<div class="row w-100">

						<div class="col-md-2">
							<label class="form-label" for="company_id">Company</label>
							<select class="form-select form-select-sm" id="company_id" name="company_id[]" multiple data-control="select2" data-placeholder="Select a Company">
								<option value=""></option>
								<option value="all_companies">All Companies</option>
								<?php
								foreach ($Companies as $company_row) {
								?>
									<option value="<?php echo $company_row['id']; ?>"><?php echo $company_row['company_name']; ?></option>
								<?php
								}
								?>
							</select>
						</div>

						<div class="col-md-2">
							<label class="form-label" for="department_id">Department</label>
							<select class="form-select form-select-sm" id="department_id" name="department_id[]" multiple data-control="select2" data-placeholder="Select a Department">
								<option value=""></option>
								<option value="all_departments">All Departments</option>
							</select>
							<br>
							<small class="text-danger error-text" id="department_id_error"></small>
						</div>

						<div class="col-md-2">
							<label class="form-label"> &nbsp; </label><br>
							<input type="hidden" name="page" id="page_num_filter" value="1">
							<button type="submit" id="filter_form_submit" class="btn btn-sm btn-primary d-inline">
								<span class="indicator-label">Filter</span>
								<span class="indicator-progress">
									Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>




	</div>
	<!--end::Col-->

	<div class="col-12">
		<table id="employees_table" class="table table-custom table-hover nowrap">
			<thead class="bg-white">
				<tr>
					<!-- <th class="bg-white">
									<div class="checkbox checkbox-primary">
										<input id="select_all" type="checkbox" selected_rows="none">
										<label id="select_all_label" for="select_all" style="width: 44px; margin-bottom: 0px;">
											ALL
										</label>
									</div>
								</th> -->
					<th class="text-center bg-white"><strong>Code</strong></th>
					<th class="text-center bg-white"><strong>First Name</strong></th>
					<th class="text-center bg-white"><strong>Last Name</strong></th>
					<th class="text-center"><strong>Designation</strong></th>
					<th class="text-center"><strong>Reports to</strong></th>
					<th class="text-center"><strong>Department</strong></th>
					<th class="text-center"><strong>Company</strong></th>
					<th class="text-center"><strong>Work Email</strong></th>
					<th class="text-center"><strong>Ext no</strong></th>
					<th class="text-center"><strong>CUG no</strong></th>
					<th class="text-center"><strong>Desk Loc</strong></th>
					<th class="text-center"><strong>Shift</strong></th>
					<th class="text-center"><strong>D.O.J</strong></th>
					<th class="text-center"><strong>Notice Period</strong></th>
					<th class="text-center"><strong>Status</strong></th>

					<th class="text-center"><strong>Pan</strong></th>
					<th class="text-center"><strong>Adhar</strong></th>
					<th class="text-center"><strong>Bank Name</strong></th>
					<th class="text-center"><strong>Accoutn No</strong></th>
					<th class="text-center"><strong>second_saturday_fixed_off</strong></th>
					<th class="text-center"><strong>late_sitting_allowed</strong></th>
					<th class="text-center"><strong>over_time_allowed</strong></th>
					<th class="text-center"><strong>role</strong></th>
					<th class="text-center"><strong>highest_qualification</strong></th>
					<th class="text-center"><strong>total_experience</strong></th>
					<th class="text-center"><strong>Age</strong></th>
					<th class="text-center"><strong>permanent_address</strong></th>
					<th class="text-center"><strong>permanent_city</strong></th>
					<th class="text-center"><strong>permanent_district</strong></th>
					<th class="text-center"><strong>permanent_state</strong></th>
					<th class="text-center"><strong>permanent_pincode</strong></th>
					<th class="text-center"><strong>family_members</strong></th>
					<th class="text-center"><strong>present_address</strong></th>
					<th class="text-center"><strong>present_city</strong></th>
					<th class="text-center"><strong>present_district</strong></th>
					<th class="text-center"><strong>present_state</strong></th>
					<th class="text-center"><strong>present_pincode</strong></th>
					<th class="text-center"><strong>personal_email</strong></th>
					<th class="text-center"><strong>personal_mobile</strong></th>
					<th class="text-center"><strong>Father's Name</strong></th>
					<th class="text-center"><strong>gender</strong></th>
					<th class="text-center"><strong>marital_status</strong></th>
					<th class="text-center"><strong>Husband's Name</strong></th>
					<th class="text-center"><strong>date_of_anniversary</strong></th>
					<th class="text-center"><strong>work_mobile</strong></th>
					<th class="text-center"><strong>emergency_contact_number</strong></th>


					<th class="text-center"><strong>Date of leaving</strong></th>
					<th class="text-center"><strong>Machine</strong></th>
					<th class="text-center bg-white"><strong>Action</strong></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center bg-white"><strong>Code</strong></th>
					<th class="text-center bg-white"><strong>First Name</strong></th>
					<th class="text-center bg-white"><strong>Last Name</strong></th>
					<th class="text-center"><strong>Designation</strong></th>
					<th class="text-center"><strong>Reports to</strong></th>
					<th class="text-center"><strong>Department</strong></th>
					<th class="text-center"><strong>Company</strong></th>
					<th class="text-center"><strong>Work Email</strong></th>
					<th class="text-center"><strong>Ext no</strong></th>
					<th class="text-center"><strong>CUG no</strong></th>
					<th class="text-center"><strong>Desk Loc</strong></th>
					<th class="text-center"><strong>Shift</strong></th>
					<th class="text-center"><strong>D.O.J</strong></th>
					<th class="text-center"><strong>Notice Period</strong></th>
					<th class="text-center"><strong>Status</strong></th>
					<th class="text-center"><strong>Pan</strong></th>
					<th class="text-center"><strong>Adhar</strong></th>
					<th class="text-center"><strong>Bank Name</strong></th>
					<th class="text-center"><strong>Accoutn No</strong></th>
					<th class="text-center"><strong>second_saturday_fixed_off</strong></th>
					<th class="text-center"><strong>late_sitting_allowed</strong></th>
					<th class="text-center"><strong>over_time_allowed</strong></th>
					<th class="text-center"><strong>role</strong></th>
					<th class="text-center"><strong>highest_qualification</strong></th>
					<th class="text-center"><strong>total_experience</strong></th>
					<th class="text-center"><strong>Age</strong></th>
					<th class="text-center"><strong>permanent_address</strong></th>
					<th class="text-center"><strong>permanent_city</strong></th>
					<th class="text-center"><strong>permanent_district</strong></th>
					<th class="text-center"><strong>permanent_state</strong></th>
					<th class="text-center"><strong>permanent_pincode</strong></th>
					<th class="text-center"><strong>family_members</strong></th>
					<th class="text-center"><strong>present_address</strong></th>
					<th class="text-center"><strong>present_city</strong></th>
					<th class="text-center"><strong>present_district</strong></th>
					<th class="text-center"><strong>present_state</strong></th>
					<th class="text-center"><strong>present_pincode</strong></th>
					<th class="text-center"><strong>personal_email</strong></th>
					<th class="text-center"><strong>personal_mobile</strong></th>
					<th class="text-center"><strong>Father's Name</strong></th>
					<th class="text-center"><strong>gender</strong></th>
					<th class="text-center"><strong>marital_status</strong></th>
					<th class="text-center"><strong>Husband's Name</strong></th>
					<th class="text-center"><strong>date_of_anniversary</strong></th>
					<th class="text-center"><strong>work_mobile</strong></th>
					<th class="text-center"><strong>emergency_contact_number</strong></th>


					<th class="text-center"><strong>Date of leaving</strong></th>
					<th class="text-center"><strong>Machine</strong></th>
					<th class="text-center bg-white"><strong>Action</strong></th>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>



	</div>
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/dataTables.fixedColumns.min.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets/plugins/custom/pagination/pagination.js"></script> -->
<script type="text/javascript">
	const getDepatmentByCompany = async (company_id) => {
		$('#department_id').html('<option></option>');
		$('#department_id').append('<option value="all_departments">All Departments</option>');
		var data = {
			'company_id': company_id,
		};
		$.ajax({
			method: "post",
			url: "<?php echo base_url('/ajax/backend/reports/get-department-by-company-id'); ?>",
			data: data,
			success: function(response) {
				if (response.response_type == 'error') {
					$('#department_id_error').html(response.response_description);
				}

				if (response.response_type == 'success') {
					if (typeof response.response_data.departments != 'undefined') {
						var department_data = response.response_data.departments;
						$.each(department_data, function(index, department) {
							$('#department_id').append('<option value="' + department.id + '">' + department.department_name + ' - ' + department.company_short_name + '</option>');
						});
					}
				}
			},
			failed: function() {
				Swal.fire({
					html: "Ajax Failed while loading departments conditionally, Please contact administrator",
					icon: "error",
					buttonsStyling: !1,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn btn-primary"
					},
				})
			}
		});
	}

	jQuery(document).ready(function($) {

		getDepatmentByCompany($('#company_id').val());
		$(document).on('change', '#company_id', function() {
			$('#department_id_error').html('');
			getDepatmentByCompany($('#company_id').val());
		})

		/*begin::Show validation error message*/
		var response = "<?php echo session()->getFlashdata('error'); ?>";
		if (response.length) {
			Swal.fire({
				html: response,
				icon: "error",
				buttonsStyling: !1,
				confirmButtonText: "Ok, got it!",
				customClass: {
					confirmButton: "btn btn-primary"
				},
			})
		}
		/*end::Show validation error message*/

		//begin::Initialize Datatable
		var table = $("#employees_table").on('preXhr.dt', function(e, settings, data) {
				/*$('div.dataTables_length select').attr('data-control', 'select2');*/
			})
			.DataTable({
				"dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3 mb-md-0"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end php-pagination-container"p>>>>',
				"buttons": [{
						extend: 'excel',
						text: '<i class="fa-solid fa-file-excel"></i> Excel',
						className: 'btn btn-sm btn-light',
						exportOptions: {
							columns: function(idx, data, node) {
								var visibleColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 44, 45, 46, 47, 48];
								var additionalColumns = [25, 39, 40, 42]; // date_of_birth, fathers_name, gender, husband_name
								return visibleColumns.indexOf(idx) !== -1 || additionalColumns.indexOf(idx) !== -1;
							}
						}
					},
					'colvis'
				],
				"ajax": {
					url: "<?= base_url('ajax/load-employees') ?>",
					type: "POST",
					data: {
						filter: function() {
							return $('#filter_form').serialize();
						}
					},
					error: function(jqXHR, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
					},
					dataSrc: "data",
				},
				"deferRender": true,
				"processing": true,
				"language": {
					processing: '<div class="d-flex align-items-center justify-content-between h-100 m-auto" style="max-width:max-content"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-3 fs-1">Processing...</span></div>',
					emptyTable: '<div class="bg-white w-100 empty-table-message d-flex align-items-center justify-content-center h-100 position-absolute" style="top:0; left:0; z-index: 1;"><span class="ms-3 fs-1">No Data Found</span></div>',
					searchPlaceholder: "Search"
				},
				"oLanguage": {
					"sSearch": ""
				},
				"columns": [{
						data: "internal_employee_id"
					},
					{
						data: "first_name",
						render: function(data, type, row, meta) {
							return data.trim();
						}
					},
					{
						data: "last_name",
						render: function(data, type, row, meta) {
							return data.trim();
						}
					},
					{
						data: "designation_name"
					},
					{
						data: "reporting_manager_name"
					},
					{
						data: "department_name",
						render: function(data, type, row, meta) {
							var badge_class = "bg-secondary";
							if (data == 'IT') {
								badge_class = "bg-info bg-opacity-20";
							}
							return '<span class="badge text-dark fw-normal rounded-pill ' + badge_class + '">' + data + '</span>';
						}
					},
					{
						data: "company_short_name"
					},
					{
						data: "work_email"
					},
					{
						data: "work_phone_extension_number"
					},
					{
						data: "work_phone_cug_number"
					},
					{
						data: "desk_location"
					},
					{
						data: "shift_name",
						render: function(data, type, row, meta) {
							var link = "<?php echo base_url('/backend/master/shift'); ?>/" + row.shift_id;
							return '<a class="badge text-capitalize rounded-pill bg-info bg-opacity-20 text-black-50 cursor-pointer go-to-shift" href="' + link + '" target="_blank">' + data + '</a>';
						}
					},
					{
						data: {
							_: 'joining_date.formatted',
							sort: 'joining_date.ordering',
						}
					},
					{
						data: "notice_period"
					},
					{
						data: "status",
						render: function(data, type, row, meta) {
							var badge_class = "bg-secondary text-dark";
							if (data == 'active') {
								badge_class = "bg-success bg-opacity-20 text-success";
							}
							if (data == 'left') {
								badge_class = "bg-warning bg-opacity-25 text-black-50";
							}
							return '<span class="badge text-capitalize rounded-pill ' + badge_class + '">' + data + '</span>';
						}
					},
					{
						data: "attachment",
						render: function(data, type, row, meta) {
							var attachment = data;
							var pan_number = '';
							if (attachment.length) {
								var attachment_obj = JSON.parse(attachment);
								var panData = attachment_obj.pan ? attachment_obj.pan : false;
								if (panData) {
									pan_number = panData.number;
								}
							}

							return pan_number;
						}
					},
					{
						data: "attachment",
						render: function(data, type, row, meta) {
							var attachment = data;
							var adhar_number = '';
							if (attachment.length) {
								var attachment_obj = JSON.parse(attachment);
								var adharData = attachment_obj.adhar ? attachment_obj.adhar : false;
								if (adharData) {
									adhar_number = adharData.number;
								}
							}


							return adhar_number;
						}
					},
					{
						data: "attachment",
						render: function(data, type, row, meta) {
							var attachment = data;
							var bank_account_number = '';
							var bank_name = '';

							/*if( attachment.length ){
								var attachment_obj = JSON.parse(attachment);
								bank_name = attachment_obj.bank_account.name != null ? attachment_obj.bank_account.name : '';
							}*/
							return bank_name;
						}
					},
					{
						data: "attachment",
						render: function(data, type, row, meta) {
							var attachment = data;
							var bank_account_number = '';
							var bank_name = '';
							/*if( attachment.length ){
								var attachment_obj = JSON.parse(attachment);
								bank_account_number = attachment_obj.bank_account.number != null ? attachment_obj.bank_account.number : '';
							}*/
							return bank_account_number;
						}
					},
					{
						data: "second_saturday_fixed_off"
					},
					{
						data: "late_sitting_allowed"
					},
					{
						data: "over_time_allowed"
					},
					{
						data: "role"
					},
					{
						data: "highest_qualification"
					},
					{
						data: "total_experience"
					},
					{
						data: "date_of_birth",
						render: function(data, type, row, meta) {
							dob = new Date(data);
							var today = new Date();
							var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
							return age + ' years';
						}
					},
					{
						data: "permanent_address"
					},
					{
						data: "permanent_city"
					},
					{
						data: "permanent_district"
					},
					{
						data: "permanent_state"
					},
					{
						data: "permanent_pincode"
					},
					{
						data: "family_members"
					},
					{
						data: "present_address"
					},
					{
						data: "present_city"
					},
					{
						data: "present_district"
					},
					{
						data: "present_state"
					},
					{
						data: "present_pincode"
					},
					{
						data: "personal_email"
					},
					{
						data: "personal_mobile"
					},
					{
						data: "fathers_name"
					},
					{
						data: "gender"
					},
					{
						data: "marital_status"
					},
					{
						data: "husband_name"
					},
					{
						data: "date_of_anniversary"
					},
					{
						data: "work_mobile"
					},
					{
						data: "emergency_contact_number"
					},
					{
						data: {
							_: 'date_of_leaving.formatted',
							sort: 'date_of_leaving.ordering',
						}
					},
					{
						data: "machine",
						render: function(data, type, row, meta) {

							return '<strong class="text-capitalize">' + data + '</strong>';
						}
					},
					{
						data: "actions",
						render: function(data, type, row, meta) {
							var link = "<?php echo base_url('/backend/master/employee/edit/id'); ?>/" + row.id;
							/*return '<div class="btn-group">'+
							        '<a href="'+link+'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-employee">'+
							            '<span class="svg-icon svg-icon-3">'+
							                '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>'+
							            '</span>'+
							        '</a>'+
							        '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-employee" data-id="'+row.id+'">'+
							            '<span class="svg-icon svg-icon-3">'+
							                '<i class="fas fa-trash"></i>'+
							            '</span>'+
							        '</a>'+
							    '</div>';*/
							return '<div class="btn-group">' +
								'<a href="' + link + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-employee">' +
								'<span class="svg-icon svg-icon-3">' +
								'<i class="fa fa-pencil-alt" aria-hidden="true" ></i>' +
								'</span>' +
								'</a>' +
								'</div>';
						}
					},
				],
				"order": [],
				"scrollX": true,
				"scrollY": '400px',
				"paging": false,
				"fixedColumns": {
					left: 3,
					right: 1
				},
				"select": {
					"style": 'multi',
					"selector": 'td:first-child'
				},
				"columnDefs": [
					// { "className": 'select-checkbox', "targets": 0 },
					{
						"className": 'border-end border-secondary td-border-left text-center',
						"targets": [2]
					},
					{
						"className": 'border-start border-secondary td-border-left text-center',
						"targets": [-1]
					},
					{
						"className": 'text-center',
						"targets": '_all'
					},
					{
						targets: [16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43],
						visible: false
					},
					{
						targets: '_all',
						visible: true
					}
				],
				"createdRow": function(row, data, dataIndex) {
					$(row).attr('id', data.id);
					$(row).find('td:nth-child(1)').attr('data-row_id', data.id);
					/*var data_joining_date = data.joining_date;
					$( row ).find('td:nth-child(12)').attr('data-sort', data_joining_date);*/
				},
				/*"drawCallback": function (settings) {
				    var response = settings.json;
				    $(".dataTables_filter").css({'padding': '0'});
				    if( response ){
				        var data_count = parseInt(response.data_count);
				        var pageno = parseInt(response.pageno);
				        var per_page = parseInt(response.per_page);
				        var pages = Math.ceil(data_count / per_page);
				        var liElements = '';
				        var prev_disabled = 'disabled';
				        var prev_page_num = 1;
				        if( pageno > 1 ){
				            prev_disabled = '';
				            prev_page_num = pageno-1;
				        }
				        liElements += '<li class="paginate_button page-item previous '+prev_disabled+'">'+
				                '<a href="#" aria-controls="employees_table" data-page_num="'+prev_page_num+'" class="page-link"><i class="previous"></i></a>'+
				            '</li>';
				        var pageArray = [];
				        var first_page = 1;
				        var last_page = pages;
				        var current_page = pageno;

				        if (pages <= 5) {
				            for (let i = 1; i <= last_page; i++) {
				                pageArray.push(i);
				            }
				        } else if (Math.abs(current_page - first_page) < 3) {
				            pageArray = [1, 2, 3, 4, '...', last_page];
				        } 
				        else if (Math.abs(current_page - last_page) < 3) {
				            pageArray = [1, '...', last_page - 3, last_page - 2, last_page - 1, last_page];
				        } 
				        else if (last_page <= 6) {
				            for (let i = 1; i <= last_page; i++) {
				                pageArray.push(i);
				            }
				        } 
				        else {
				            pageArray = [1, '...', current_page - 1, current_page, current_page + 1, '...', last_page];
				        }
				        for (let i = 0; i < pageArray.length; i++) {
				            if (last_page < i) {
				                break;
				            } else {

				                if (pageArray[i] === '...') {
				                    liElements +=   '<li class="paginate_button page-item disabled" ><a href="#" aria-controls="employees_table" class="page-link">…</a></li>';
				                } else {
				                    liElements +=   '<li class="paginate_button page-item ' + (pageArray[i] === current_page ? 'active' : '') + '"><a href="#" aria-controls="employees_table" data-page_num="'+pageArray[i]+'" class="page-link">'+pageArray[i]+'</a></li>';
				                }
				            }
				        }
				        var next_disabled = 'disabled';
				        var next_page_num = pages;
				        if( pageno < pages ){
				            next_disabled = '';
				            next_page_num = pageno+1;
				        }
				        liElements += '<li class="paginate_button page-item next '+next_disabled+'">'+
				                '<a href="#" aria-controls="employees_table" data-page_num="'+next_page_num+'" class="page-link"><i class="next"></i></a>'+
				            '</li>';
				        $('.php-pagination-container').html('<div class="dataTables_paginate paging_simple_numbers d-flex justify-content-end" id="employees_table_paginate"><ul class="pagination"></ul></div>')
				        $('#employees_table_paginate > ul').html(liElements);
				    }                
				},*/
				"initComplete": function(settings, json) {}
			});

		$('#employees_table_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title">Employees</h3>');

		$("#select_all").change(function() {
			if (this.checked) {
				$(this).attr("selected_rows", "all");
				selected = [];
				table.rows().select();
				$("#select_all_label").html("None");
				$(".DTFC_LeftHeadWrapper table thead tr th #select_all_label").html("None");
				$(".DTFC_LeftHeadWrapper table thead tr th #select_all").prop("checked", true);
			} else {
				$(this).attr("selected_rows", "none");
				selected = [];
				table.rows().deselect();
				$("#select_all_label").html("All");
				$(".DTFC_LeftHeadWrapper table thead tr th #select_all_label").html("All");
				$(".DTFC_LeftHeadWrapper table thead tr th #select_all").prop("checked", false);
			}
		});

		$(document).on('click', '#employees_table_paginate > ul > li > a', function(e) {
			e.preventDefault();
			if ($(this).parent().hasClass('active')) {
				return false;
			}
			$('#filter_form').find('#page_num_filter').val($(this).data('page_num'));
			$('#filter_form_submit').trigger('click');
		})

		$(document).on('change', '#filter_form select, input:not(#page_num_filter)', function(e) {
			$('#filter_form').find('#page_num_filter').val('1');
		})
		//end::Initialize Datatable

		$(document).on('click', '#filter_form_submit', function(e) {
			e.preventDefault();
			var submitButton = $(this);
			submitButton.attr("data-kt-indicator", "on");
			submitButton.attr("disabled", "true");
			$("#employees_table").DataTable().ajax.reload(
				function(json) {
					submitButton.removeAttr("data-kt-indicator");
					submitButton.removeAttr("disabled");
				}
			);
		});

		//begin::Add Employee Ajax
		$(document).on('click', '#add_employee_submit_button', function(e) {
			e.preventDefault();
			var form = $('#add_employee');
			var data = new FormData(form[0]);
			$.ajax({
				method: "post",
				url: "<?php echo base_url('ajax/add-employee'); ?>",
				data: data,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.response_type == 'error') {
						if (response.response_description.length) {
							Swal.fire({
								html: response.response_description,
								icon: "error",
								buttonsStyling: !1,
								confirmButtonText: "Ok, got it!",
								customClass: {
									confirmButton: "btn btn-primary"
								},
							}).then(function(e) {
								if (typeof response.response_data.validation != 'undefined') {
									var validation = response.response_data.validation;
									$.each(validation, function(index, value) {
										form.find('#' + index + '_error').html(value);
									});
								}
							});
						}
					}

					if (response.response_type == 'success') {
						if (response.response_description.length) {
							Swal.fire({
								html: response.response_description,
								icon: "success",
								buttonsStyling: !1,
								confirmButtonText: "Ok, got it!",
								customClass: {
									confirmButton: "btn btn-primary"
								},
							}).then(function(e) {
								form[0].reset();
								form.closest('.modal').modal('hide');
								$("#employees_table").DataTable().ajax.reload();
							});
						}
					}
				},
				failed: function() {
					Swal.fire({
						html: "Ajax Failed, Please contact administrator",
						icon: "error",
						buttonsStyling: !1,
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn btn-primary"
						},
					})
				}
			})
		})
		//end::Add Employee Ajax

		//begin::Delete Employee Ajax
		$(document).on('click', '.delete-employee', function(e) {
			e.preventDefault();
			var employee_id = $(this).data('id');
			var data = {
				'employee_id': employee_id,
			};

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, delete it!',
				customClass: {
					confirmButton: "btn btn-sm btn-primary",
					cancelButton: "btn btn-sm btn-secondary"
				},
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "post",
						url: "<?php echo base_url('ajax/master/employee/delete-employee'); ?>",
						data: data,
						success: function(response) {
							if (response.response_type == 'error') {
								if (response.response_description.length) {
									Swal.fire({
										html: response.response_description,
										icon: "error",
										buttonsStyling: !1,
										confirmButtonText: "Ok, got it!",
										customClass: {
											confirmButton: "btn btn-primary"
										},
									})
								}
							}

							if (response.response_type == 'success') {
								if (response.response_description.length) {
									Swal.fire({
										html: response.response_description,
										icon: "success",
										buttonsStyling: !1,
										confirmButtonText: "Ok, got it!",
										customClass: {
											confirmButton: "btn btn-primary"
										},
									}).then(function() {
										$("#employees_table").DataTable().ajax.reload();
									})
								}
							}
						},
						failed: function() {
							Swal.fire({
								html: "Ajax Failed, Please contact administrator",
								icon: "error",
								buttonsStyling: !1,
								confirmButtonText: "Ok, got it!",
								customClass: {
									confirmButton: "btn btn-primary"
								},
							})
						}
					})
				}
			})
		})

	})
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>