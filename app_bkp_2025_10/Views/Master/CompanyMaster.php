<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">




        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Companies</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#add_company_modal">
                        <i class="fa fa-plus"></i> Add New
                    </button>
                    <div class="modal fade" tabindex="-1" id="add_company_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="add_company" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Company</h5>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Company Name</label>
                                                <input type="text" name="company_name" class="form-control form-control-solid" placeholder="Company Name" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger d-block" id="company_name_error"></span>
                                                <!--end::Error Message-->
                                                <label class="required form-label">Company Short Name</label>
                                                <input type="text" name="company_short_name" class="form-control form-control-solid" placeholder="Company Short Name" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger d-block" id="company_short_name_error"></span>
                                                <!--end::Error Message-->
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <div class="form-group d-flex justify-content-center">
                                                    <div id="logo_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                        <div class="image-input-wrapper w-125px h-125px">
                                                            <a href="#" class="d-none w-100 h-100 overlay preview-button" data-bs-target="#logo_attachment_lightbox" data-bs-toggle="modal">
                                                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow"><i class="bi bi-eye-fill text-white fs-2x"></i></div>
                                                            </a>
                                                        </div>
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" id="logo_attachment" name="logo_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                            <input type="hidden" name="logo_attachment_remove" />
                                                        </label>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <div class="modal fade" id="logo_attachment_lightbox" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body" style="min-height: 70vh;">
                                                                        <iframe id="logo_attachment_lightbox_iframe" class="loaded_content" width="100%" height="100%" src=""></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <small class="text-danger error-text" id="logo_attachment_error"><?= isset($validation) ? display_error($validation, 'logo_attachment') : '' ?></small>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Company HOD</label>
                                                <input type="text" name="company_hod" class="form-control form-control-solid" placeholder="Company HOD" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger d-block" id="company_hod_error"></span>
                                                <!--end::Error Message-->
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" name="city" class="form-control form-control-solid" placeholder="City" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" name="state" class="form-control form-control-solid" placeholder="State" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Pincode</label>
                                                <input type="text" name="pincode" class="form-control form-control-solid" placeholder="Pincode" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Phone No</label>
                                                <input type="text" name="phone_number" class="form-control form-control-solid" placeholder="Phone No" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Name</label>
                                                <input type="text" name="contact_person_name" class="form-control form-control-solid" placeholder="Contact Person Name" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Mobile</label>
                                                <input type="text" name="contact_person_mobile" class="form-control form-control-solid" placeholder="Contact Person Mobile" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Email Id</label>
                                                <input type="text" name="contact_person_email_id" class="form-control form-control-solid" placeholder="Phone No" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control form-control-solid" placeholder="Address"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <input type="hidden" id="add_company_submit_field" name="add_company_submit_field" value="Add" />
                                        <button type="submit" id="add_company_submit_button" class="btn btn-sm btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" tabindex="-1" id="update_company_modal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form id="update_company" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Company</h5>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <span class="svg-icon svg-icon-2x"></span>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Company Name</label>
                                                <input type="text" name="company_name" class="form-control form-control-solid" placeholder="Company Name" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger error-text d-block" id="company_name_error"></span>
                                                <!--end::Error Message-->
                                                <input type="hidden" name="company_id" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger error-text d-block" id="company_id_error"></span>
                                                <!--end::Error Message-->
                                                <label class="required form-label">Company Short Name</label>
                                                <input type="text" name="company_short_name" class="form-control form-control-solid" placeholder="Company Short Name" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger error-text d-block" id="company_short_name_error"></span>
                                                <!--end::Error Message-->
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <div class="form-group d-flex justify-content-center">
                                                    <div id="logo_edit_attachment_select" class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url(<?php echo base_url(); ?>assets/media/svg/files/blank-image.svg)">
                                                        <div class="image-input-wrapper w-125px h-125px">
                                                        </div>
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change attachment">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" id="logo_edit_attachment" name="logo_edit_attachment" accept=".png, .jpg, .jpeg, .pdf" />
                                                            <input type="hidden" name="logo_edit_attachment_remove" />
                                                        </label>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove attachment">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                    </div>
                                                    <br>
                                                    <small class="text-danger error-text" id="logo_edit_attachment_error"><?= isset($validation) ? display_error($validation, 'logo_edit_attachment') : '' ?></small>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="required form-label">Company HOD</label>
                                                <input type="text" name="company_hod" class="form-control form-control-solid" placeholder="Company HOD" value="" oninput="$(this).next().html('')" />
                                                <!--begin::Error Message-->
                                                <span class="text-danger error-text d-block" id="company_hod_error"></span>
                                                <!--end::Error Message-->
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" name="city" class="form-control form-control-solid" placeholder="City" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" name="state" class="form-control form-control-solid" placeholder="State" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Pincode</label>
                                                <input type="text" name="pincode" class="form-control form-control-solid" placeholder="Pincode" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Phone No</label>
                                                <input type="text" name="phone_number" class="form-control form-control-solid" placeholder="Phone No" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Name</label>
                                                <input type="text" name="contact_person_name" class="form-control form-control-solid" placeholder="Contact Person Name" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Mobile</label>
                                                <input type="text" name="contact_person_mobile" class="form-control form-control-solid" placeholder="Contact Person Mobile" />
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Contact Person Email Id</label>
                                                <input type="text" name="contact_person_email_id" class="form-control form-control-solid" placeholder="Phone No" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control form-control-solid" placeholder="Address"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <input type="hidden" id="update_company_submit_field" name="update_company_submit_field" value="Add" />
                                        <button type="submit" id="update_company_submit_button" class="btn btn-sm btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="companies_table" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"><strong>Company ID</strong></th>
                            <th class="text-center"><strong>Company Name</strong></th>
                            <th class="text-center"><strong>Short Name</strong></th>
                            <th class="text-center"><strong>Logo</strong></th>
                            <th class="text-center"><strong>Company HOD</strong></th>
                            <th class="text-center"><strong>Address</strong></th>
                            <th class="text-center"><strong>City</strong></th>
                            <th class="text-center"><strong>State</strong></th>
                            <th class="text-center"><strong>Pincode</strong></th>
                            <th class="text-center"><strong>Phone No</strong></th>
                            <th class="text-center"><strong>Contact Person Name</strong></th>
                            <th class="text-center"><strong>Contact Person Mobile</strong></th>
                            <th class="text-center"><strong>Contact Person Email Id</strong></th>
                            <th class="text-center"><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><strong>Company ID</strong></th>
                            <th class="text-center"><strong>Company Name</strong></th>
                            <th class="text-center"><strong>Logo</strong></th>
                            <th class="text-center"><strong>Short Name</strong></th>
                            <th class="text-center"><strong>Company HOD</strong></th>
                            <th class="text-center"><strong>Address</strong></th>
                            <th class="text-center"><strong>City</strong></th>
                            <th class="text-center"><strong>State</strong></th>
                            <th class="text-center"><strong>Pincode</strong></th>
                            <th class="text-center"><strong>Phone No</strong></th>
                            <th class="text-center"><strong>Contact Person Name</strong></th>
                            <th class="text-center"><strong>Contact Person Mobile</strong></th>
                            <th class="text-center"><strong>Contact Person Email Id</strong></th>
                            <th class="text-center"><strong>Action</strong></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>




    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        var table = $("#companies_table").DataTable({
            "buttons": [],
            "ajax": {
                url: "<?= base_url('ajax/load-companies') ?>",
                type: "POST",
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
                    data: "id"
                },
                {
                    data: "company_name"
                },
                {
                    data: "company_short_name"
                },
                {
                    data: "logo_url",
                    render: function(data, type, row, meta) {
                        if (data.length) {
                            var logo_url_full = '<?php echo base_url(); ?>' + data;
                        } else {
                            var logo_url_full = '<?php echo base_url(); ?>/assets/media/svg/files/blank-image.svg';
                        }
                        return '<img src="' + logo_url_full + '" class="w-100px h-100px" style="object-fit: contain" />';
                    }
                },
                {
                    data: "company_hod_name"
                },
                {
                    data: "address"
                },
                {
                    data: "city"
                },
                {
                    data: "state"
                },
                {
                    data: "pincode"
                },
                {
                    data: "phone_number"
                },
                {
                    data: "contact_person_name"
                },
                {
                    data: "contact_person_mobile"
                },
                {
                    data: "contact_person_email_id"
                },
                {
                    data: "actions",
                    render: function(data, type, row, meta) {
                        return '<div class="d-flex justify-content-center">' +
                            '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-company" data-id="' + row.id + '">' +
                            '<span class="svg-icon svg-icon-3">' +
                            '<i class="fa fa-pencil-alt" aria-hidden="true" ></i>' +
                            '</span>' +
                            '</a>' +
                            '<a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-company" data-id="' + row.id + '">' +
                            '<span class="svg-icon svg-icon-3">' +
                            '<i class="fas fa-trash"></i>' +
                            '</span>' +
                            '</a>' +
                            '</div>';
                    }
                },
            ],
            "order": [],
            "scrollX": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "paging": false,
        });
        //end::Initialize Datatable

        //begin::Add Company Ajax
        $(document).on('click', '#add_company_submit_button', function(e) {
            e.preventDefault();
            var form = $('#add_company');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/add-company'); ?>",
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
                                $("#companies_table").DataTable().ajax.reload();
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
        //end::Add Company Ajax

        //begin::Delete Company Ajax
        $(document).on('click', '.delete-company', function(e) {
            e.preventDefault();

            var company_id = $(this).data('id');
            var data = {
                'company_id': company_id,
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
                        url: "<?php echo base_url('ajax/delete-company'); ?>",
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
                                        $("#companies_table").DataTable().ajax.reload();
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
        //end::Delete Company Ajax

        //begin::Open Edit Company Modal
        $(document).on('click', '.edit-company', function(e) {
            e.preventDefault();
            var company_id = $(this).data('id');
            var data = {
                'company_id': company_id,
            };

            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/get-company'); ?>",
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
                        if (typeof response.response_data.company != 'undefined') {
                            var company_data = response.response_data.company;
                            $("form#update_company").find('small.error-text').html('');
                            $("form#update_company").find('input[name="company_id"]').val(company_data.id);
                            $("form#update_company").find('input[name="company_name"]').val(company_data.company_name);
                            $("form#update_company").find('input[name="company_short_name"]').val(company_data.company_short_name);
                            $("form#update_company").find('input[name="company_hod"]').val(company_data.company_hod);
                            $("form#update_company").find('input[name="city"]').val(company_data.city);
                            $("form#update_company").find('input[name="state"]').val(company_data.state);
                            $("form#update_company").find('input[name="pincode"]').val(company_data.pincode);
                            $("form#update_company").find('input[name="phone_number"]').val(company_data.phone_number);
                            $("form#update_company").find('input[name="contact_person_name"]').val(company_data.contact_person_name);
                            $("form#update_company").find('input[name="contact_person_mobile"]').val(company_data.contact_person_mobile);
                            $("form#update_company").find('input[name="contact_person_email_id"]').val(company_data.contact_person_email_id);
                            $("form#update_company").find('textarea[name="address"]').html(company_data.address);

                            var logo_url = company_data.logo_url;
                            if (logo_url.length) {
                                var logo_url_full = '<?php echo base_url("public"); ?>' + logo_url;
                                $("div#logo_edit_attachment_select").removeClass("image-input-empty");
                                $("div#logo_edit_attachment_select > div.image-input-wrapper").css("background-image", "url('" + logo_url_full + "')");
                            } else {
                                var logo_url_full = '<?php echo base_url("public"); ?>/assets/media/svg/files/blank-image.svg';
                                $("div#logo_edit_attachment_select").addClass("image-input-empty");
                                $("div#logo_edit_attachment_select > div.image-input-wrapper").css("background-image", "");
                            }

                            $("#update_company_modal").modal('show');
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
        //end::Open Edit Company Modal

        //begin::Update Company Ajax
        $(document).on('click', '#update_company_submit_button', function(e) {
            e.preventDefault();
            var form = $('#update_company');
            var data = new FormData(form[0]);
            $.ajax({
                method: "post",
                url: "<?php echo base_url('ajax/update-company'); ?>",
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
                                    console.log();
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
                                $("#companies_table").DataTable().ajax.reload();
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
        //end::Update Company Ajax

    })
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>