<?php 
include_once("./inc/header-top.php");
$page_title = 'Logout';
?>
<!--begin::Custom Css-->
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php"); ?>

<main>
    <div class="container">

       <?php
       session_destroy();
       header('location:'.SITE_URL.'/login.php');
       ?> 

    </div>
</main>
<!-- End #main -->

<?php #include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php"); ?>
<!--begin::Custom Javascript-->
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php"); ?>