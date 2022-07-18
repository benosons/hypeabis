<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8" />
    <title>Administrator Area</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url(); ?>files/backend/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>files/backend/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>files/backend/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url(); ?>files/backend/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= base_url(); ?>files/backend/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#ffffff">
    
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">-->
    
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/pace/pace-theme-flash.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-scrollbar/jquery.scrollbar.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-datepicker/css/datepicker3.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/switchery/css/switchery.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-listslider/jquery-listslider.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/fileinput/css/fileinput.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/simple-line-icons/simple-line-icons.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/css/pages-icons.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" class="main-stylesheet" href="<?= base_url(); ?>files/backend/css/pages.css"/>
    
    <!-- Page Level CSS-->
    <?php foreach((is_array($css_files) ? $css_files : array()) as $css){ ?>
      <?= $css; ?>
    <?php } ?>
    <!-- End Page Level CSS-->
    
    <!-- Important js file -->
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/pace/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/modernizr.custom.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery/jquery-easy.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/popper/umd/popper.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap/js/bootstrap.min.js"></script>
  </head>
  
  <body class="fixed-header menu-pin">
  
    <!-- BEGIN SIDEBPANEL-->
    <nav class="page-sidebar" data-pages="sidebar">
      <!-- BEGIN SIDEBAR MENU HEADER-->
      <div class="sidebar-header">
        <?php if(isset($global_data[0]->logo_white) && strlen(trim($global_data[0]->logo_white)) > 0){ ?>
          <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="logo" class="brand" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" height="20">
        <?php } else { ?>
          <img src="<?= base_url(); ?>files/backend/img/logo_white.png" alt="logo" class="brand" data-src="<?= base_url(); ?>files/backend/img/logo_white.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_white_2x.png" height="20">
        <?php } ?>
      </div>
      <!-- END SIDEBAR MENU HEADER-->
      <!-- START SIDEBAR MENU -->
      <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        
        <?php $controller = strtolower($this->uri->segment(1)); ?>
        
        <ul class="menu-items p-b-20">
          <li class="m-t-30 ">
            <a href="<?= base_url(); ?>admin_dashboard">
              <span class="title">Dashboard</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'admin_dashboard' ? 'bg-complete' : '') ?>"><i class="pg-home"></i></span>
          </li>
          
          <?= $modules; ?>
        </ul>
        <div class="clearfix"></div>
      </div>
      <!-- END SIDEBAR MENU -->
    </nav>
    <!-- END SIDEBAR -->
    <!-- END SIDEBPANEL-->
    
    <!-- START PAGE-CONTAINER -->
    <div class="page-container ">
      
      <!-- START HEADER -->
      <div class="header">
        <!-- START MOBILE SIDEBAR TOGGLE -->
        <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
        </a>
        <!-- END MOBILE SIDEBAR TOGGLE -->
        <div class="">
          <div class="brand inline">
            <?php if(isset($global_data[0]->logo) && strlen(trim($global_data[0]->logo)) > 0){ ?>
              <img class="logo-dark" src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="logo" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" height="20">
              <img class="logo-white" src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="logo" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" height="20">
            <?php } else { ?>
              <img class="logo-dark" src="<?= base_url(); ?>files/backend/img/logo.png" alt="logo" data-src="<?= base_url(); ?>files/backend/img/logo.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_2x.png" height="20">
              <img class="logo-white" src="<?= base_url(); ?>files/backend/img/logo_white.png" alt="logo" data-src="<?= base_url(); ?>files/backend/img/logo_white.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_white_2x.png" height="20">
            <?php } ?>
          </div>
          <!-- START NOTIFICATION LIST -->
          <ul class="d-lg-inline-block d-none no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
            <li class="p-r-10 inline">
              <a href="#" class="header-icon pg pg-menu_lv" data-toggle-pin="sidebar"></a>
            </li>
            <li class="p-r-10 inline">
              <a href="<?= base_url(); ?>" class="btn btn-sm"><i class="fa fa-chevron-circle-left"></i> Back to Website</a>
            </li>
          </ul>
          <!-- END NOTIFICATIONS LIST -->
        </div>
        <div class="d-flex align-items-center">
          <!-- START User Info-->
          <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
            <span class="semi-bold"><?= $this->session->userdata('admin_name'); ?></span>
          </div>
          <div class="dropdown pull-right">
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="thumbnail-wrapper d32 circular inline">
              <?php $admin_photo = $this->session->userdata('admin_photo'); ?>
              <?php if(isset($admin_photo) && strlen(trim($admin_photo)) > 0){ ?>
                <img src="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>" alt="" data-src="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>" data-src-retina="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>" width="32" height="32">
              <?php } else { ?>
                <img src="<?= base_url(); ?>assets/admin/default.png" alt="" data-src="<?= base_url(); ?>assets/admin/default.png" data-src-retina="<?= base_url(); ?>assets/admin/default.png" width="32" height="32">
              <?php } ?>
              </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
              <a href="<?= base_url(); ?>admin_profile" class="dropdown-item"><i class="fa fa-cog"></i> My Account</a>
              <a href="<?= base_url(); ?>adminarea/logout" class="clearfix bg-master-lighter dropdown-item">
                <span class="pull-left">Logout</span>
                <span class="pull-right"><i class="pg-power"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- END HEADER -->
      
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper ">
        
        <!-- START PAGE CONTENT -->
        <div class="content">
          <!-- BEGIN MAIN CONTENT -->
          <?= $content; ?>
          <!-- END MAIN CONTENT -->
        </div>
        <!-- END PAGE CONTENT -->
        
        <!-- START COPYRIGHT -->
        <div class="container-fluid container-fixed-lg footer">
          <div class="copyright sm-text-center">
            <p class="small no-margin pull-left sm-pull-reset">
              <span class="hint-text">Copyright &copy; 2019 </span>
              <span class="hint-text">All rights reserved. </span>
              <span class="sm-block">
                <a href="<?= base_url(); ?>admin_dashboard/terms" class="hint-text m-l-10 m-r-10">Terms &amp; Conditions</a>
              </span>
            </p>
            <p class="small no-margin pull-right sm-pull-reset">
              <span class="hint-text">Hand-crafted by Binari</span>
            </p>
            <div class="clearfix"></div>
          </div>
        </div>
        <!-- END COPYRIGHT -->
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    
    <!-- BEGIN VENDOR JS -->
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-unveil/jquery.unveil.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-ios-list/jquery.ioslist.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/classie/classie.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-listslider/jquery-listslider.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/fileinput/js/fileinput.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- END VENDOR JS -->

    <script>
      var base_url = '<?php echo base_url() ?>';
    </script>
    
    <!-- BEGIN CORE JS -->
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/js/pages.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/js/scripts.js"></script>
    <!-- END CORE JS -->
    
    <!-- BEGIN PAGE LEVEL JS -->
    <?php foreach((is_array($js_files) ? $js_files : array()) as $js){ ?>
      <?= $js; ?>
    <?php } ?>
    <!-- END PAGE LEVEL JS -->
    
  </body>
</html>
