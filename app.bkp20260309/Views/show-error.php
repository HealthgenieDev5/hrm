<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
  <base href="">
  <title>
    <?php
    if (isset($page_title) && !empty($page_title)) {
      echo $page_title . ' | ' . config('App')->appName;
    } else {
      echo '404 - Page not found | ' . config('App')->appName;
    }
    ?>
  </title>
  </title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/media/logos/favicon.ico" />

  <base href="/">
  <!--begin::CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https:///fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Page Vendor Stylesheets(used by this page)-->
  <link href="<?php echo base_url(); ?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
  <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/custom/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />
  <!--end::Page Vendor Stylesheets-->
  <!--begin::Global Stylesheets Bundle(used by all pages)-->
  <link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
  <!--end::Global Stylesheets Bundle-->



  <!-- Tempus Dominus Styles -->
  <link href="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/solid.min.css" integrity="sha512-qzgHTQ60z8RJitD5a28/c47in6WlHGuyRvMusdnuWWBB6fZ0DWG/KyfchGSBlLVeqAz+1LzNq+gGZkCSHnSd3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


  <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
  <!--begin::Toggle Switch-->
  <link href="<?php echo base_url(); ?>assets/css/toggle-switch.css" rel="stylesheet" type="text/css" />
  <!--end::Toggle Switch-->
  <style>
    /* cyrillic-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCtr6Hw0aXpsog.woff2) format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCtr6Hw9aXpsog.woff2) format('woff2');
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCtr6Hw2aXpsog.woff2) format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCtr6Hw3aXpsog.woff2) format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCtr6Hw5aXo.woff2) format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w0aXpsog.woff2) format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w9aXpsog.woff2) format('woff2');
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w2aXpsog.woff2) format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w3aXpsog.woff2) format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w5aXo.woff2) format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 900;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w0aXpsog.woff2) format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 900;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w9aXpsog.woff2) format('woff2');
      unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 900;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w2aXpsog.woff2) format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 900;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w3aXpsog.woff2) format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Montserrat';
      font-style: normal;
      font-weight: 900;
      font-display: swap;
      src: url(//fonts.gstatic.com/s/montserrat/v24/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w5aXo.woff2) format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
  </style>
  <style>
    * {
      -webkit-box-sizing: border-box;
      box-sizing: border-box
    }

    body {
      padding: 0;
      margin: 0
    }

    #notfound {
      position: relative;
      height: 100vh
    }

    #notfound .notfound {
      position: absolute;
      left: 50%;
      top: 50%;
      -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%)
    }

    .notfound {
      max-width: 410px;
      width: 100%;
      text-align: center
    }

    .notfound .notfound-404 {
      min-height: 140px;
      position: relative;
      z-index: -1
    }

    .notfound .notfound-404 h1 {
      font-family: montserrat, sans-serif;
      font-size: 100px;
      margin: 0;
      font-weight: 900;
      position: absolute;
      left: 50%;
      -webkit-transform: translateX(-50%);
      -ms-transform: translateX(-50%);
      transform: translateX(-50%);
      background: url(<?php echo base_url(); ?>assets/media/404/bg.jpg) no-repeat;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-size: cover;
      background-position: center
    }

    .notfound h2 {
      font-family: montserrat, sans-serif;
      color: #000;
      font-size: 24px;
      font-weight: 700;
      /*text-transform:uppercase;*/
      margin-top: 0
    }

    .notfound p {
      font-family: montserrat, sans-serif;
      color: #000;
      font-size: 14px;
      font-weight: 400;
      margin-bottom: 20px;
      margin-top: 0
    }

    .notfound a {
      font-family: montserrat, sans-serif;
      font-size: 14px;
      text-decoration: none;
      text-transform: uppercase;
      background: #0046d5;
      display: inline-block;
      padding: 15px 30px;
      border-radius: 40px;
      color: #fff;
      font-weight: 700;
      -webkit-box-shadow: 0 4px 15px -5px #0046d5;
      box-shadow: 0 4px 15px -5px #0046d5
    }

    @media only screen and (max-width:767px) {
      .notfound .notfound-404 {
        height: 142px
      }

      .notfound .notfound-404 h1 {
        font-size: 112px
      }
    }
  </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:0px;--kt-toolbar-height-tablet-and-mobile:0px">
  <!-- <div class="bg-danger d-flex align-items-center justify-content-center" style="height: 100vh;"> -->
  <div id="notfound">
    <div class="notfound">
      <div class="notfound-404">
        <h1>Oops!</h1>
      </div>
      <h2><?php echo isset($message) && !empty($message) ? $message : '404 - Page not found'; ?></h2>
      <!-- <p>Sorry! Cannot seem to find the page you were looking for. </p>
          <a href="#">Go To Homepage</a> -->
    </div>
  </div>
  <!-- </div> -->
</body>

</html>