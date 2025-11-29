<?php $page_title = 'Appraisal'; ?>
<?php include_once("./inc/header-top.php") ?>

<?php
  if( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
    header('location:'.SITE_URL.'/login.php');
  }
?>
<!--begin::Custom Css-->
<style type="text/css">
  #footer {
    position: fixed;
    bottom: 0px;
    width: calc(100% - 300px);
  }
  body.toggle-sidebar #footer {
    width: 100%;
  }
  #main {
  margin-bottom: 87px;
  }
</style>
<!--end::Custom Css-->
<?php include_once("./inc/header-bottom.php") ?>
<?php include_once("./inc/page-header.php") ?>





<main id="main" class="main">
  
  <div class="pagetitle">
    <h1>Appraisal</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Appraisal</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->
  
  <section class="section dashboard">
    <div class="row">
      
      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="card">
              
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  
                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                  <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
              </div>
              
              <div class="card-body">
                <h5 class="card-title">Reports <span>/Today</span></h5>
                
                
                
              </div>
              
            </div>
      </div>
      <!-- End Left side columns -->
      
    </div>
  </section>
  
</main>
<!-- End #main -->

<?php include_once("./inc/page-footer.php") ?>
<?php include_once("./inc/footer-top.php") ?>
<!--begin::Custom Javascript-->
<!--end::Custom Javascript-->
<?php include_once("./inc/footer-bottom.php") ?>
