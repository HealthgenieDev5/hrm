<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>
        <?php
        if (isset($page_title) && !empty($page_title)) {
            echo $page_title . ' | eTimeOfiice';
        } else {
            echo 'eTimeOfiice';
        }
        ?>
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/media/logos/favicon.ico" />
    <!--begin::Fonts-->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> -->
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <!-- <link href="<?php #echo base_url(); 
                        ?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="<?php #echo base_url(); 
                        ?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" /> -->
    <!--end::Global Stylesheets Bundle-->

    <base href="/">
    <!--begin::CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!--end::Fonts-->


    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="<?php echo base_url(); ?>/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/plugins/custom/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->


    <link href="<?php echo base_url(); ?>/assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="<?php echo base_url(); ?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!-- Tempus Dominus Styles -->
    <!-- <link href="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" crossorigin="anonymous" /> -->

    <!--end::CSS-->



    <!-- Tempus Dominus Styles -->
    <!-- <link href="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet" crossorigin="anonymous"> -->
    <!-- <link href="<?php echo base_url(); ?>/assets/plugins/custom/datetimepicker/css/tempusdominus-bootstrap-4.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="https://www.jqueryscript.net/demo/Material-Time-Picker-Plugin-jQuery-MDTimePicker/mdtimepicker.css" rel="stylesheet" /> -->
    <link href="<?php echo base_url(); ?>/assets/plugins/custom/md/mdtimepicker.css" rel="stylesheet" type="text/css" />
    <!-- Tempus Dominus Styles -->





    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.1.1/css/all.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css" />


    <link href="<?php echo base_url(); ?>/assets/plugins/custom/bootstrap5-dt-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

    <!-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> -->


    <link href="<?php echo base_url(); ?>/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <!--begin::Toggle Switch-->
    <link href="<?php echo base_url(); ?>/assets/css/toggle-switch.css" rel="stylesheet" type="text/css" />
    <!--end::Toggle Switch-->
    <style>
        @media (min-width: 992px) {
            .header-fixed.toolbar-fixed .wrapper {
                padding-top: calc(16px + var(--kt-toolbar-height));
            }
        }

        @media (max-width: 991.9px) {
            .header-fixed.toolbar-fixed .wrapper {
                padding-top: calc(16px + var(--kt-toolbar-height));
            }
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed" style=" --kt-toolbar-height: 55px; --kt-toolbar-height-tablet-and-mobile: 55px;">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">


                <!--begin::Page Header-->
                <?= $this->include('Templates/exemployee-page-header'); ?>
                <!--end::Page Header-->


                <!--begin::Content-->
                <div
                    class="content d-flex flex-column flex-column-fluid"
                    id="kt_content">
                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        <div id="kt_content_container" class="container-xxl">

                            <!--begin::Page Content-->
                            <?= $this->renderSection('content'); ?>
                            <!--end::Page Content-->

                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div
                        class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-bold me-1">2022©</span>
                            <a
                                href="https://keenthemes.com"
                                target="_blank"
                                class="text-gray-800 text-hover-primary">Keenthemes</a>
                        </div>
                        <!--end::Copyright-->
                        <!--begin::Menu-->
                        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
                            <li class="menu-item">
                                <a
                                    href="https://keenthemes.com"
                                    target="_blank"
                                    class="menu-link px-2">About</a>
                            </li>
                            <li class="menu-item">
                                <a
                                    href="https://devs.keenthemes.com"
                                    target="_blank"
                                    class="menu-link px-2">Support</a>
                            </li>
                            <li class="menu-item">
                                <a
                                    href="https://1.envato.market/EA4JP"
                                    target="_blank"
                                    class="menu-link px-2">Purchase</a>
                            </li>
                        </ul>
                        <!--end::Menu-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none">
                <rect
                    opacity="0.5"
                    x="13"
                    y="6"
                    width="13"
                    height="2"
                    rx="1"
                    transform="rotate(90 13 6)"
                    fill="black" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="<?php echo base_url(); ?>/assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>