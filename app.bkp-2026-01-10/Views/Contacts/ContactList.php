<?= $this->extend('Templates/DashboardLayout') ?>

<?= $this->section('content') ?>
<!--begin::Row-->
<div class="row gy-5 g-xl-8">
    <!--begin::Col-->
    <div class="col-12">




        <div class="card mb-7">
            <div class="card-body">

                <!--begin::Toolbar-->
                <div class="d-flex flex-wrap flex-stack justify-content-between">
                    <h3 class="fw-bolder me-5 my-1">Contacts (<span id="contacts_count"><?php echo count($all_contacts); ?></span>)</h3>

                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-3 position-absolute ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" id="kt_filter_search" class="form-control form-control-sm border-body bg-body w-150px ps-10" placeholder="Search" onkeyup="myFunction()" />
                    </div>
                    <!--end::Search-->

                </div>
                <!--end::Toolbar-->

            </div>
        </div>
        <!-- <button id="single_contact_drawer_toggle" class="btn btn-primary .">Demos</button> -->






        <div class="row g-6 g-xl-9" id="contact_list">
            <?php
            foreach ($all_contacts as $contact) {

            ?>
                <!--begin::Col-->
                <div class="col-md-6 col-xxl-4 contact-card-wraper">
                    <!--begin::Card-->
                    <div class="card contact-card cursor-pointer" data-contact_data="<?php echo urlencode(json_encode($contact)); ?>">
                        <!--begin::Card body-->
                        <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-65px symbol-circle mb-5">
                                <?php
                                $attachment_array = json_decode($contact['attachment'], true);
                                $first_name = $contact['first_name'];

                                if (isset($attachment_array['avatar']) && !empty($attachment_array['avatar'])) {
                                    if (isset($attachment_array['avatar']['file']) && !empty($attachment_array['avatar']['file'])) {
                                ?>
                                        <img src="<?php echo base_url() . $attachment_array['avatar']['file']; ?>" alt="avatar" style="width: 100px; height: 100px; object-fit : cover">
                                    <?php
                                    } else {
                                    ?>
                                        <p class="mb-0 text-center rounded-circle bg-info bg-opacity-20 text-primary fs-1" style="width: 100px; height: 100px; line-height: 100px;"><?php echo substr($contact['employee_name'], 0, 1); ?></p>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <p class="mb-0 text-center rounded-circle bg-info bg-opacity-20 text-primary fs-1" style="width: 100px; height: 100px; line-height: 100px;"><?php echo substr($contact['employee_name'], 0, 1); ?></p>
                                <?php
                                }
                                ?>
                                <?php
                                $INTime = ($contact['INTime'] == null || $contact['INTime'] == "--:--") ? "" : $contact['INTime'];
                                $OUTTime = ($contact['OUTTime'] == null || $contact['OUTTime'] == "--:--") ? "" : $contact['OUTTime'];
                                if ($INTime == "") {
                                    $attendance_status = "Absent";
                                } else if ($INTime !== "" && $OUTTime == "") {
                                    $attendance_status = "Present";
                                } else if ($INTime !== "" && $OUTTime !== "") {
                                    $attendance_status = "Left for Today";
                                } else {
                                    $attendance_status = "--Coming Soon--";
                                }
                                ?>
                                <div class="<?php if ($attendance_status == 'Present') {
                                                echo 'bg-success';
                                            } elseif ($attendance_status == 'Absent') {
                                                echo 'bg-danger';
                                            } elseif ($attendance_status == 'Left for Today') {
                                                echo 'bg-grey-500';
                                            } else {
                                                echo 'bg-grey-200';
                                            } ?> position-absolute border border-4 border-white h-25px w-25px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Name-->
                            <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0 contact_employee_name"><?php echo $contact['employee_name']; ?></a>
                            <!--end::Name-->
                            <!--begin::Position-->
                            <div class="fw-bold text-gray-400 text-center mb-6"><?php echo $contact['designation_name'] . " at " . $contact['company_short_name'] . " in " . $contact['department_name'] . " department"; ?></div>
                            <!--end::Position-->
                            <!--end::Info-->
                            <!--begin::Info-->
                            <div class="d-flex flex-column align-items-start w-100 border border-gray-300 border-dashed rounded">
                                <!--begin::Stats-->
                                <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-gray-400">Reporting Manager</div>
                                    <div class="fs-6 fw-bolder text-gray-700"><?php echo $contact['reporting_manager_name']; ?></div>
                                </div>
                                <!--end::Stats-->

                                <!--begin::Stats-->
                                <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-gray-400">HOD</div>
                                    <div class="fs-6 fw-bolder text-gray-700"><?php echo $contact['hod_name']; ?></div>
                                </div>
                                <!--end::Stats-->

                                <!--begin::Stats-->
                                <div class="border border-left-0 border-right-0 border-top-0 border-bottom-0 border-gray-300 border-dashed w-100 py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-gray-400">Shift</div>
                                    <div class="fs-6 fw-bolder text-gray-700"><?php echo $contact['shift_name']; ?></div>
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            <?php
            }
            ?>
        </div>

        <!--begin::Demos drawer-->
        <div class="bg-body" id="single_contact_drawer" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'350px', 'lg': '475px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#single_contact_drawer_toggle" data-kt-drawer-close="#single_contact_drawer_close">
            <!--begin::Card-->
            <div class="card shadow-none rounded-0 w-100">
                <!--begin::Header-->
                <div class="card-header" style="min-height: 70px;">
                    <h3 class="card-title fw-bolder text-gray-700 text-center">Contact Details</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-icon btn-active-color-primary h-40px w-40px me-n6" id="single_contact_drawer_close">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </button>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body px-0 pt-0">

                    <div class="d-flex justify-content-between border-bottom border-gray-400 border-bottom-dotted py-3 px-3 mb-3">
                        <div class="me-7 d-flex flex-column">
                            <div class="symbol w-150px position-relative drawer_avatar" id="drawer_avatar"></div>
                            <div class="border border-gray-400 border-dashed rounded py-1 px-2 mt-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold fs-6 text-gray-600">Code</div>
                                    <div class="fw-bold fs-6 text-gray-900 drawer_internal_employee_id" id="drawer_internal_employee_id"></div>
                                </div>
                                <!--end::Number-->
                            </div>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center mb-2 position-relative">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1 drawer_employee_name" id="drawer_employee_name"></a>
                                <a href="#">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <i class="fa-solid fa-badge-check text-primary" style="font-size: 1.37rem;"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="d-flex flex-column fw-bold fs-6">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-circle-user text-hover-primary"></i>
                                    </span>
                                    <span class="drawer_designation_name" id="drawer_designation_name"></span>
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-building-user text-hover-primary"></i>
                                    </span>
                                    <span class="drawer_department_name" id="drawer_department_name"></span>
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-solid fa-house-building text-hover-primary"></i>
                                    </span>
                                    <span class="drawer_company_short_name" id="drawer_company_short_name"></span>
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-1">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa-duotone fa-location-dot text-hover-primary"></i>
                                    </span>
                                    <span class="drawer_desk_location" id="drawer_desk_location"></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-bottom border-gray-400 border-bottom-dotted py-3 px-3 mb-3">
                        <div class="fw-bold text-gray-400">Attendance Today</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_attendance_status" id="drawer_attendance_status">--Coming Soon--</div>
                    </div>

                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Reporting Manager</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_reporting_manager_name" id="drawer_reporting_manager_name"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">HOD</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_hod_name" id="drawer_hod_name"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Company</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_company_name" id="drawer_company_name"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Department</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_department_name" id="drawer_department_name"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Designation</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_designation_name" id="drawer_designation_name"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Joining Date</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_joining_date" id="drawer_joining_date"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Employement Status</div>
                        <div class="fs-6 fw-bolder text-gray-700 text-capitalize drawer_status" id="drawer_status"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Gender</div>
                        <div class="fs-6 fw-bolder text-gray-700 text-capitalize drawer_gender" id="drawer_gender"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Date Of Birth</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_date_of_birth" id="drawer_date_of_birth"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Work Email</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_work_email" id="drawer_work_email"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Personal Number</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_personal_mobile" id="drawer_personal_mobile"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Work Mobile Number</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_work_mobile" id="drawer_work_mobile"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Work Phone Extention</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_work_phone_extension_number" id="drawer_work_phone_extension_number"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Work Phone CUG Number</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_work_phone_cug_number" id="drawer_work_phone_cug_number"></div>
                    </div>
                    <div class="border border-left-0 border-right-0 border-top-0 border-gray-300 border-dashed w-100 py-3 px-6 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-gray-400">Desk Location</div>
                        <div class="fs-6 fw-bolder text-gray-700 drawer_desk_location" id="drawer_desk_location"></div>
                    </div>

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Demos drawer-->




    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<?= $this->section('javascript') ?>
<script src="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {


        var single_contact_drawer = document.querySelector("#single_contact_drawer");
        var drawer_instance = KTDrawer.getInstance(single_contact_drawer);

        $(document).on('click', '.contact-card', function(e) {
            e.preventDefault();
            var contactData = JSON.parse(decodeURIComponent(($(this).data('contact_data') + '').replace(/\+/g, '%20')));

            var attachment = JSON.parse(contactData.attachment);
            var first_name = contactData.first_name;
            if (attachment.hasOwnProperty('avatar')) {
                if (attachment.avatar.file.length) {
                    var avatar_html = '<img id="drawer_avatar" src="<?php echo base_url(); ?>' + attachment.avatar.file + '" alt="image" style="width: 150px; height: 150px; object-fit: cover">';
                } else {
                    var avatar_html = '<p class="mb-0 text-center rounded bg-info bg-opacity-20 text-primary fs-1" style="width: 150px; height: 150px; line-height: 150px;">' + first_name.charAt(0) + '</p>';
                }
            } else {
                var avatar_html = '<p class="mb-0 text-center rounded bg-info bg-opacity-20 text-primary fs-1" style="width: 150px; height: 150px; line-height: 150px;">' + first_name.charAt(0) + '</p>';
            }

            var INTime = (contactData.INTime == null || contactData.INTime == "--:--") ? "" : contactData.INTime;
            var OUTTime = (contactData.OUTTime == null || contactData.OUTTime == "--:--") ? "" : contactData.OUTTime;
            if (INTime == "") {
                var attendance_status = "Absent";
            } else if (INTime !== "" && OUTTime == "") {
                var attendance_status = "Present";
            } else if (INTime !== "" && OUTTime !== "") {
                var attendance_status = "Left for Today";
            } else {
                var attendance_status = "--Coming Soon--";
            }
            // protect employee personal mobile 
            var protectedPersonalMobile = ['889'];

            $('#single_contact_drawer').find('.drawer_attendance_status').html(attendance_status);
            $('#single_contact_drawer').find('.drawer_employee_name').html(contactData.employee_name);
            $('#single_contact_drawer').find('.drawer_avatar').html(avatar_html);
            $('#single_contact_drawer').find('.drawer_internal_employee_id').html(contactData.internal_employee_id);
            $('#single_contact_drawer').find('.drawer_designation_name').html(contactData.designation_name);
            $('#single_contact_drawer').find('.drawer_department_name').html(contactData.department_name);
            $('#single_contact_drawer').find('.drawer_company_short_name').html(contactData.company_short_name);
            $('#single_contact_drawer').find('.drawer_desk_location').html(contactData.desk_location);
            $('#single_contact_drawer').find('.drawer_reporting_manager_name').html(contactData.reporting_manager_name);
            $('#single_contact_drawer').find('.drawer_hod_name').html(contactData.hod_name);
            $('#single_contact_drawer').find('.drawer_company_name').html(contactData.company_name);
            $('#single_contact_drawer').find('.drawer_joining_date').html(contactData.joining_date);
            $('#single_contact_drawer').find('.drawer_status').html(contactData.status);
            $('#single_contact_drawer').find('.drawer_gender').html(contactData.gender);
            $('#single_contact_drawer').find('.drawer_date_of_birth').html(contactData.date_of_birth);

            if (protectedPersonalMobile.includes(contactData.internal_employee_id) || contactData.gender == 'female') {
                $('#single_contact_drawer').find('.drawer_personal_mobile').html('Protected');
            } else {
                $('#single_contact_drawer').find('.drawer_personal_mobile').html(contactData.personal_mobile);
            }
            $('#single_contact_drawer').find('.drawer_work_email').html(contactData.work_email);
            $('#single_contact_drawer').find('.drawer_work_mobile').html(contactData.work_mobile);
            $('#single_contact_drawer').find('.drawer_work_phone_extension_number').html(contactData.work_phone_extension_number);
            $('#single_contact_drawer').find('.drawer_work_phone_cug_number').html(contactData.work_phone_cug_number);
            drawer_instance.show();
        })

        drawer_instance.on("kt.drawer.show", function() {
            console.log("kt.drawer.show event is fired");
        });



    })

    function myFunction() {
        var input = document.getElementById('kt_filter_search');
        var filter = input.value.toUpperCase();
        var contact_list = document.getElementById("contact_list");
        var contact = contact_list.getElementsByClassName('contact-card-wraper');

        for (i = 0; i < contact.length; i++) {
            var a = contact[i].getElementsByClassName("contact_employee_name")[0];
            var txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                contact[i].style.display = "";
            } else {
                contact[i].style.display = "none";
            }
        }

        $('#contacts_count').html($(".contact-card-wraper:visible").length);
    }
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>