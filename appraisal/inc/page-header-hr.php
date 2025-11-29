  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?php echo SITE_URL; ?>" class="logo d-flex align-items-center">
        <img src="<?php echo SITE_URL; ?>/assets/img/gstc-logo.png" alt="Logo">
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <!-- <span class="d-lg-block">NiceAdmin</span> -->
      </a>
      <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
    </div>
    <!-- End Logo -->

    <div class="search-bar">
      <?php
      if( in_array( $_SESSION['login']['user_role'], array('tl', 'hod', 'admin', 'superuser', 'hr') ) ){
        ?><a href="<?php echo SITE_URL; ?>/admin.php" class="btn btn-sm btn-outline-danger me-3" style="">Department Data</a><?php
      }

      if( in_array( $_SESSION['login']['user_role'], array('hr') ) ){
        ?><a href="<?php echo SITE_URL; ?>/hr-panel.php" class="btn btn-sm btn-outline-info me-3" style="">Company Data</a><?php
      }
      ?>
      
    </div>
    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-md-block dropdown-toggle ps-2"><?php echo isset($_SESSION['login']) ? trim($_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name']) : ''; ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo isset($_SESSION['login']) ? trim($_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name']) : ''; ?></h6>
              <span><?php echo isset($_SESSION['login']) ? $_SESSION['login']['designation_name'] : ''; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?php echo SITE_URL.'/logout.php'; ?>">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav>
    <!-- End Icons Navigation -->

  </header><!-- End Header -->
