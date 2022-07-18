<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8" />
    <title>Developer Area</title>
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
    
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/pace/pace-theme-flash.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-scrollbar/jquery.scrollbar.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/switchery/css/switchery.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-listslider/jquery-listslider.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/fileinput/css/fileinput.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/simple-line-icons/simple-line-icons.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/css/pages-icons.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" class="main-stylesheet" href="<?= base_url(); ?>files/backend/css/pages.css"/>
    
    <!-- Page Level CSS-->
    <? foreach((is_array($css_files) ? $css_files : array()) as $css){ ?>
      <?= $css; ?>
    <? } ?>
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
        <img src="<?= base_url(); ?>files/backend/img/logo_white.png" alt="logo" class="brand" data-src="<?= base_url(); ?>files/backend/img/logo_white.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_white_2x.png" height="40">
      </div>
      <!-- END SIDEBAR MENU HEADER-->
      <!-- START SIDEBAR MENU -->
      <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        <ul class="menu-items">
          <li class="m-t-30 ">
            <a href="<?= base_url(); ?>dev_dashboard">
              <span class="title">Dashboard</span>
            </a>
            <span class="icon-thumbnail <?= ($this->uri->segment(1) == 'dev_dashboard' ? 'bg-complete' : '') ?>"><i class="pg-home"></i></span>
          </li>
          <li class="">
            <a href="<?= base_url(); ?>dev_global">
              <span class="title">Global setting</span>
            </a>
            <span class="icon-thumbnail <?= ($this->uri->segment(1) == 'dev_global' ? 'bg-complete' : '') ?>"><i class="fa fa-globe"></i></span>
          </li>
          <li class="">
            <a href="<?= base_url(); ?>dev_admin">
              <span class="title">Super admin</span>
            </a>
            <span class="icon-thumbnail <?= ($this->uri->segment(1) == 'dev_admin' ? 'bg-complete' : '') ?>"><i class="fa fa-user"></i></span>
          </li>
          <li class="">
            <a href="<?= base_url(); ?>dev_module">
              <span class="title">Module</span>
            </a>
            <span class="icon-thumbnail <?= ($this->uri->segment(1) == 'dev_module' ? 'bg-complete' : '') ?>"><i class="fa fa-dropbox"></i></span>
          </li>
          <li class="">
            <a href="<?= base_url(); ?>dev_icon"><span class="title">Icons</span></a>
            <span class="icon-thumbnail"><i class="fa fa-dot-circle-o"></i></span>
          </li>
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
            <img class="logo-dark" src="<?= base_url(); ?>files/backend/img/logo.png" alt="logo" data-src="<?= base_url(); ?>files/backend/img/logo.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_2x.png" height="40">
            <img class="logo-white" src="<?= base_url(); ?>files/backend/img/logo_white.png" alt="logo" data-src="<?= base_url(); ?>files/backend/img/logo_white.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_white_2x.png" height="40">
          </div>
          <!-- START NOTIFICATION LIST -->
          <ul class="d-lg-inline-block d-none no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
            <li class="p-r-10 inline">
              <a href="#" class="header-icon pg pg-menu_lv" data-toggle-pin="sidebar"></a>
            </li>
          </ul>
          <!-- END NOTIFICATIONS LIST -->
        </div>
        <div class="d-flex align-items-center">
          <!-- START User Info-->
          <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
            <span class="semi-bold">Developer</span>
          </div>
          <div class="dropdown pull-right">
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="thumbnail-wrapper d32 circular inline">
              <img src="<?= base_url(); ?>files/backend/img/icons/icon-alien.png" alt="" data-src="<?= base_url(); ?>files/backend/img/icons/icon-alien.png" data-src-retina="<?= base_url(); ?>files/backend/img/icons/icon-alien.png" width="32" height="32">
              </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
              <a href="<?= base_url(); ?>dev_global" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
              <a href="<?= base_url(); ?>devarea/logout" class="clearfix bg-master-lighter dropdown-item">
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
                <a href="<?= base_url(); ?>dev_dashboard/terms" class="hint-text m-l-10 m-r-10">Terms &amp; Conditions</a>
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
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-unveil/jquery.unveil.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-ios-list/jquery.ioslist.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/classie/classie.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-listslider/jquery-listslider.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/fileinput/js/fileinput.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- END VENDOR JS -->
    
    <!-- BEGIN CORE JS -->
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/js/pages.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/js/scripts.js"></script>
    <!-- END CORE JS -->
    
    <!-- BEGIN PAGE LEVEL JS -->
    <? foreach((is_array($js_files) ? $js_files : array()) as $js){ ?>
      <?= $js; ?>
    <? } ?>
    <!-- END PAGE LEVEL JS -->
    
  </body>
</html>