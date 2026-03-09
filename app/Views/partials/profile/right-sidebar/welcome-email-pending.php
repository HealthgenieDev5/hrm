<div class="shadow-sm mb-5">
    <table id="recently_joined" class="table table-sm table-striped table-row-bordered nowrap" style="font-size: 0.75rem !important">
        <thead>
            <tr>
                <th style="text-align: left"><strong>Employee</strong></th>
                <th style="text-align: center"><strong>D.O.J</strong></th>
                <th style="text-align: right"><strong></strong></th>
            </tr>
        </thead>
    </table>
</div>


<?= $this->section('javascript') ?>
<script>
    jQuery(document).ready(function($) {

        console.log("Hello from Welcome email");

        /*begin::recently_joined*/
        var recently_joined = $("#recently_joined").DataTable({
            "dom": '<"card"<"card-header py-0 pe-0"<"card-title"><"card-toolbar my-0"<"datatable-buttons-container me-1"B><"toolbar-buttons">f>><"card-body pt-1 pb-1"rt><"card-footer">>',
            "buttons": [],
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'All'],
            ],
            "ajax": {
                url: "<?= base_url('ajax/profile/get-welcome-email-waiting') ?>",
                error: function(jqXHR, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                },
                dataSrc: "",
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
                    data: "employee_name"
                },
                {
                    data: {
                        _: 'joining_date.formatted',
                        sort: 'joining_date.ordering',
                    },
                },
                {
                    data: "employee_id",
                    render: function(data, type, row, meta) {
                        return `<a href="#" data-id="${row.employee_id}" class="btn btn-icon btn-sm btn-bg-light btn-active-color-primary send-welcome-email p-0" style="width: max-content; height: max-content;" target="_blank"><span class="svg-icon svg-icon-3"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true" ></i></span></a>`;
                    }
                },
            ],
            "scrollX": true,
            "paging": false,
            "ordering": true,
            "columnDefs": [{
                "className": 'text-center small',
                "targets": '_all'
            }, ],
            // initComplete: function(json) {
            // let returned_data = json;
            // console.log(returned_data);
            // }
        });
        $('#recently_joined_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Pending welcome email</h3>');
        // $('#recently_joined_wrapper > .card > .card-header > .card-title').replaceWith('<h3 class="card-title my-0">Send welcome email</h3>');
        /*begin::upcoming_birthdays*/

        /*end::upcoming_birthdays*/


        $(document).on('click', '.send-welcome-email', function(e) {
            e.preventDefault();
            var button = $(this);
            var button_html = button.html();

            console.log(button_html);
            console.log(button.data('id'));

            $.ajax({
                method: "post",
                url: "<?php echo base_url('/ajax/hr/employee/send-welcome-email'); ?>",
                data: {
                    'employee_id': $(this).data('id')
                },
                success: function(response) {

                    console.log(response);
                    if (response.response_type == 'failed') {
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

                    if (response.response_type == 'success') {
                        Swal.fire({
                            html: response.response_description,
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                        }).then(function(e) {
                            $("#recently_joined").DataTable().ajax.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        html: "Ajax Failed while sending welcome email, Please contact administrator",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                    })
                }
            }).always(function() {
                button.html(button_html);
            });

            $(this).html(`<span class="svg-icon svg-icon-3"><i class="fa fa-refresh fa-spin" aria-hidden="true" ></i></span>`);
        })

    })
</script>
<?= $this->endSection() ?>