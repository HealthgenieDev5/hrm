<?php
if( !isset($page_title) || $page_title == '' ){
  $page_title = ( isset($_GET['pt']) && !empty($_GET['pt']) ) ? $_GET['pt'] : 'Pages / Not Found 404 - NiceAdmin Bootstrap Template';
}
?>
<?php include_once("./inc/header-top.php") ?>
<!--begin::Custom Css-->
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php") ?>
<?php #include_once("./inc/page-header.php") ?>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1><?php echo ( isset($_GET['t']) && !empty($_GET['t']) ) ? $_GET['t'] : '404'; ?></h1>
        <h2><?php echo ( isset($_GET['m']) && !empty($_GET['m']) ) ? $_GET['m'] : 'The page you are looking for doesn\'t exist.'; ?></h2>
        <!-- <a class="btn" href="index.html">Back to home</a> -->
        <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
        <div class="credits">
          Designed by <a href="https://bootstrapmade.com/">HG IT</a>
        </div>
      </section>

    </div>
  </main><!-- End #main -->


<?php #include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<!--begin::Custom Javascript-->
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php") ?>