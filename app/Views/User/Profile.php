<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>




<div class="row gy-5 g-xl-8">
    <div class="col-lg-6 col-xxl-8 order-2 order-lg-1">

        <!--begin::Attendance Stats-->
        <?= $this->include('partials/profile/attendance/attendance-stats') ?>
        <!--end::Attendance Stats-->

        <!--begin::Job Listing Notifications-->
        <?= $this->include('partials/profile/jobs-listing/job-listing-stats') ?>
        <!--end::Job Listing Notifications-->

        <!--begin::Punching Report-->
        <?php echo $this->include('partials/profile/attendance/punching-report'); ?>
        <!--End::Punching Report-->

        <!--begin::Leave Report-->
        <?php echo $this->include('partials/profile/attendance/leave-report'); ?>
        <!--End::Leave Report-->

        <!--begin::OD Report-->
        <?php echo $this->include('partials/profile/attendance/od-report'); ?>
        <!--End::OD Report-->

    </div>


    <div class="col-lg-6 col-xxl-4 order-1 order-lg-2 ">
        <!--begin::Profile Detail-->
        <?php echo $this->include('partials/profile/right-sidebar/profile-detail'); ?>
        <!--End::Profile Detail-->

        <!--begin::Reminder Button-->
        <?php echo $this->include('partials/profile/right-sidebar/reminder-button'); ?>
        <!--End::Reminder Button-->

        <!--begin::Leave balance-->
        <?php echo $this->include('partials/profile/right-sidebar/leave-balance'); ?>
        <!--End::Leave balance-->

        <!--begin::Requests-->
        <?php echo $this->include('partials/profile/right-sidebar/requests'); ?>
        <!--End::Requests-->

        <!--begin::Holiday Table-->
        <?php echo $this->include('partials/profile/right-sidebar/holiday-table'); ?>
        <!--End::Holiday Table-->

        <!--begin::Next month Leave balance-->
        <?php echo $this->include('partials/profile/right-sidebar/next-month-leave-balance'); ?>
        <!--End::Next month Leave balance-->

        <?php
        if (in_array(session()->get('current_user')['role'], ['superuser', 'hr'])) {
            // begin::Probation confirmation-pending
            // echo $this->include('partials/profile/right-sidebar/probation-confirmation-pending');
            // End::Probation confirmation-pending

            // begin::Recently joined
            // echo $this->include('partials/profile/right-sidebar/welcome-email-pending');
            // End::Recently joined
        }
        ?>

        <!--begin::Upcoming Birthday-->
        <?php echo $this->include('partials/profile/right-sidebar/upcoming-birthday'); ?>
        <!--End::Upcoming Birthday-->

    </div>
</div>

<!--begin::Probation Notification Modal-->
<?php #echo $this->include('partials/profile/probation/propbation-notification-modal'); 
?>
<!--End::Probation Notification Modal-->

<!--begin::Probation Confirmation Modal-->
<?php
// if (in_array(session()->get('current_user')['employee_id'], array_map('intval', explode(',', env('app.recruitmentManagerIds'))))):
//     echo $this->include('partials/profile/probation/hr-propbation-confirmation-modal');
// endif;
?>
<!--End::Probation Confirmation Modal-->


<!--begin::Announcement Popup-->
<?php echo $this->include('partials/profile/footer/announcement-popup'); ?>
<!--End::Announcement Popup-->

<!--begin::Birthday/Anniversary/Reminder Notification Modal-->
<?php
// if (!in_array(session()->get('current_user')['employee_id'], ['40'])) {
echo $this->include('partials/profile/footer/employee-notification-modal');
// }
?>
<!--End::Birthday/Anniversary/Reminder Notification Modal-->



<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        //showAnnouncementPopup();
        oneYearEmpAnniversary();
        setTimeout(function() {
            sendAbsentWithoutLeaveNotification();
        }, 10000);
        setTimeout(function() {
            sendAbsentWithoutLeaveNotificationHeuerOnly();
        }, 15000);
    });

    function oneYearEmpAnniversary() {
        const currentDate = new Date();
        const oneYearAgo = new Date(currentDate);
        oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1); // Adjust year safely

        const oneYearAgoDate = oneYearAgo.toISOString().split('T')[0]; // Format YYYY-MM-DD

        $.ajax({ // Ensure jQuery is used
            url: "<?= base_url('/ajax/hr/employee/get-one-year-anniversary-employees') ?>",
            type: "POST",
            contentType: 'application/json',
            data: JSON.stringify({
                date: oneYearAgoDate
            }),
            dataType: "json",
            success: function(response) {
                if ($.trim(response.status) === 'success' && response.data) {

                    console.log('Anniversary Employees:', response.data);
                    var DOA_employees = $("#DOA_employees").DataTable({
                        "dom": '<"card"<"card-header"<"card-title"><"card-toolbar"<"datatable-buttons-container me-1"B>f<"toolbar-buttons">>><"card-body pt-1 pb-1"rt><"card-footer pt-5 pb-3"<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-start mb-3"><"col-sm-12 col-md-7 d-flex align-items-center justify-content-start justify-content-md-end">>>>',
                        "data": response.data,
                        "columns": [{

                                "data": function(row) {
                                    return row.first_name + ' ' + row.last_name;
                                },
                                "title": "Employee Name"
                            },
                            {
                                "data": "joining_date",
                                "title": "Date of Joining",
                                "render": function(data, type, row) {
                                    return moment(data).format('DD-MMM-YYYY');
                                }
                            },
                            {
                                "data": "anniversary_date",
                                "title": "1st Anniversary Date",
                                "render": function(data, type, row) {
                                    return moment(data).format('DD-MMM-YYYY');
                                }
                            }
                        ],
                        "order": [
                            [1, 'asc']
                        ],
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        "scrollX": true,
                        "scrollY": "300px",
                        "scrollCollapse": true,
                        "paging": false,
                        "buttons": []
                    });
                    $("#DOA_employees").closest(".card").find(".card-title").text("Completed 1 Year");
                } else {
                    console.error('Unexpected response:', response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Failed to fetch anniversary employees:', errorThrown,
                    'Status:', textStatus,
                    'Response:', jqXHR.responseText);
            }
        });
    }

    function sendAbsentWithoutLeaveNotification() {
        $.ajax({
            url: "<?= base_url('/ajax/hr/employee/send-absent-without-leave-notification') ?>",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if ($.trim(response.response_type) === 'failed') {
                    console.log('Failed to send absent without leave notification');
                }
            }
        });


    }

    function sendAbsentWithoutLeaveNotificationHeuerOnly() {
        $.ajax({
            url: "<?= base_url('/ajax/hr/employee/send-absent-without-leave-notification-heuer-only') ?>",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if ($.trim(response.response_type) === 'failed') {
                    console.log('Failed to send absent without leave notification');
                }
            }
        });


    }

    function showAnnouncementPopup() {
        window.onload = function() {
            setTimeout(() => {
                const modalElement = document.getElementById('announcementPopup');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal with id "announcementPopup" not found.');
                }
            }, 2000); // Show modal after 2 seconds
        };
    }

    jQuery(document).ready(function($) {

        $(document).on('input', '.form-control', function() {
            $(this).parent().find('.error-text').html('');
        })
        $(document).on('change', '.leave-control.flatpickr-input', function() {
            $(this).parent().parent().parent().find('.error-text').html('');
        })
        $(document).on('click', '.parent-picker', function() {
            $(this).parent().find('.flatpickr-input').focus();
        })

        $('.image-input').each(function() {
            var id = $(this).attr('id');
            var imageInputElement = document.querySelector("#" + id);
            var imageInput = KTImageInput.getInstance(imageInputElement);

            var iframe_src_backup = '';

            imageInput.on("kt.imageinput.changed", function() {
                var fileInput = $("#" + id).find("input[type=file]")[0];
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");
                var reader = new FileReader();
                reader.onload = function(e) {
                    lightboxIframe.attr('src', e.target.result);
                    imageInputWrapper.find('.preview-button').removeClass('d-none').addClass('d-block');
                    var extension = fileInput.files[0].name.split('.').pop().toLowerCase();
                    switch (extension) {
                        case 'pdf':
                            imageInputWrapper.css({
                                'background-image': 'url(<?php echo base_url(); ?>assets/media/svg/files/pdf.svg)'
                            });
                            break;
                        default:
                            break;
                    }
                }
                reader.readAsDataURL(fileInput.files[0]);
            });

            imageInput.on("kt.imageinput.change", function() {
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
                if (iframe_src_backup == '') {
                    iframe_src_backup = lightboxIframe.attr('src');
                }
            });

            imageInput.on("kt.imageinput.canceled", function() {
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
                if (iframe_src_backup !== '') {
                    lightboxIframe.attr('src', iframe_src_backup);
                } else {
                    var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                    imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                    lightboxIframe.attr('src', '');
                }
            });

            imageInput.on("kt.imageinput.removed", function() {
                var imageInputWrapper = $("#" + id + " .image-input-wrapper");
                imageInputWrapper.find('.preview-button').removeClass('d-block').addClass('d-none');
                var lightboxIframe = $($("#" + id).find(".preview-button").data("bs-target")).find("iframe");;
                lightboxIframe.attr('src', '');
            });
        });
    })
</script>
<?php
if (!empty($probationPopUpEmployees)) {
    #echo $this->include('partials/profile/probation/manager-probation-popup');
}
?>

<?= $this->include('partials/profile/footer/address-confirmation-model') ?>
<?= $this->include('partials/profile/resignation/resignation-modal') ?>
<?= $this->include('partials/profile/jobs-listing/notification-modal') ?>
<?= $this->endSection() ?>
<?= $this->endSection() ?>