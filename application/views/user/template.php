<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php if(isset($meta['title']) && strlen(trim($meta['title'])) > 0){ ?>
      <title>Hypeabis - <?= $meta['title']; ?></title>
    <?php } else { ?>
      <title>Hypeabis</title>

    <?php } ?>
    <?php if(isset($meta['description']) && strlen(trim($meta['description'])) > 0){ ?>
      <meta name="description" content="<?= $meta['description']; ?>">
    <?php } else { ?>

      <meta name="description" content="Hypeabis merupakan portal untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha.">
    <?php } ?>
    <meta name="author" content=" Hypeabis">
    <meta name="keywords" content="Hypeabis, Berita bisnis, keuangan, finansial, wirausaha, entrepreneur, investasi, saham">

    
    <?php if(isset($meta['title']) && strlen(trim($meta['title'])) > 0){ ?>
      <meta property="og:title" content="Hypeabis - <?= $meta['title']; ?>" />
    <?php } else { ?>
      <meta property="og:title" content="Hypeabis" />
    <?php } ?>
    <?php if(isset($meta['description']) && strlen(trim($meta['description'])) > 0){ ?>
      <meta property="og:description" content="<?= $meta['description']; ?>" />
    <?php } else { ?>

      <meta property="og:description" content="Hypeabis merupakan portal untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha." />

    <?php } ?>
    <meta property="og:url" content="<?= base_url(); ?><?= $this->uri->uri_string(); ?>" />
    <meta property="og:type" content="article" />
    <?php if(isset($meta['picture']) && strlen(trim($meta['picture'])) > 0){ ?>
      <meta property="og:image" content="<?= $meta['picture']; ?>" />
    <?php } ?>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="7gHcVzJ_av6LXA24c4u6oVU1CTiC71hMTxwe49wjmyI" />
    
    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url(); ?>files/frontend/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>files/frontend/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>files/frontend/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url(); ?>files/frontend/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= base_url(); ?>files/frontend/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#3f3f3f">
    <meta name="theme-color" content="#ffffff">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
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
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap-validator/bootstrapValidator.js"></script>
  </head>
  
  <body class="fixed-header menu-pin">
  
    <!-- BEGIN SIDEBPANEL-->
    <nav class="page-sidebar" data-pages="sidebar">
      <!-- BEGIN SIDEBAR MENU HEADER-->
      <div class="sidebar-header">
        <a href="<?= base_url(); ?>">
          <?php if(isset($global_data[0]->logo_white) && strlen(trim($global_data[0]->logo_white)) > 0){ ?>
            <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="logo" class="brand" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>" height="20">
          <?php } else { ?>
            <img src="<?= base_url(); ?>files/backend/img/logo_white.png" alt="logo" class="brand" data-src="<?= base_url(); ?>files/backend/img/logo_white.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_white_2x.png" height="20">
          <?php } ?>
        </a>
      </div>
      <!-- END SIDEBAR MENU HEADER-->
      <!-- START SIDEBAR MENU -->
      <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        
        <?php $controller = strtolower($this->uri->segment(1)); ?>
        
        <ul class="menu-items">
          <li class="m-t-30 <?= ($controller == 'user_dashboard' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_dashboard">
              <span class="title">Dashboard</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_dashboard' ? 'bg-complete' : '') ?>"><i class="pg-home"></i></span>
          </li>
          
          <li class=" <?= ($controller == 'user_content' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_content">
              <span class="title">Artikel</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_content' ? 'bg-complete' : '') ?>"><i class="fa fa-pencil"></i></span>
          </li>

          <li class=" <?= ($controller == 'user_photo' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_photo">
              <span class="title">Hypephoto</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_photo' ? 'bg-complete' : '') ?>"><i class="fa fa-picture-o"></i></span>
          </li>

          <li class=" <?= ($controller == 'user_gallery' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_gallery">
              <span class="title">Virtual Gallery</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_gallery' ? 'bg-complete' : '') ?>"><i class="fa fa-picture-o"></i></span>
          </li>

          <?php if ($this->session->userdata('user_verified') === '1'): ?>
            <li class=" <?= ($controller == 'user_polling' ? 'active' : '') ?>">
              <a href="<?= base_url(); ?>user_polling">
                <span class="title">Polling</span>
              </a>
              <span class="icon-thumbnail <?= ($controller == 'user_polling' ? 'bg-complete' : '') ?>"><i class="fa fa-check-square"></i></span>
            </li>
            <li class=" <?= ($controller == 'user_quiz' ? 'active' : '') ?>">
              <a href="<?= base_url(); ?>user_quiz">
                <span class="title">Quiz</span>
              </a>
              <span class="icon-thumbnail <?= ($controller == 'user_quiz' ? 'bg-complete' : '') ?>"><i class="fa fa-question-circle"></i></span>
            </li>
          <?php endif; ?>

          <!-- <li class=" <?= (in_array($controller, ['user_ads', 'user_ads_order', 'user_ads_cancel']) ? 'active' : '') ?>"> -->
          <!--   <a href="<?= base_url(); ?>user_ads"> -->
          <!--     <span class="title">Iklan</span> -->
          <!--   </a> -->
          <!--   <span class="icon-thumbnail <?= (in_array($controller, ['user_ads', 'user_ads_order', 'user_ads_cancel']) ? 'bg-complete' : '') ?>"><i class="fa fa-bookmark"></i></span> -->
          <!-- </li> -->

          <li class=" <?= ($controller == 'user_point' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_point">
              <span class="title">Poin Saya</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_point' ? 'bg-complete' : '') ?>"><i class="fa fa-star"></i></span>
          </li>
          
          <li class=" <?= ($controller == 'user_merch' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_merch">
              <span class="title">Merchandise</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_merch' ? 'bg-complete' : '') ?>"><i class="fa fa-gift"></i></span>
          </li>
          
          <li class=" <?= ($controller == 'user_profile' ? 'active' : '') ?>">
            <a href="<?= base_url(); ?>user_profile">
              <span class="title">Profil</span>
            </a>
            <span class="icon-thumbnail <?= ($controller == 'user_profile' ? 'bg-complete' : '') ?>"><i class="fa fa-user"></i></span>
          </li>
          
          <li class="">
            <a href="<?= base_url(); ?>user/logout" class="btn-need-confirmation" data-message="Are you sure to logout?">
              <span class="title">Keluar</span>
            </a>
            <span class="icon-thumbnail"><i class="fa fa-sign-out"></i></span>
          </li>
          
          <li class="m-t-30">
            <a href="<?= base_url(); ?>page">
              <span class="title"><i class="fa fa-chevron-circle-left"></i> Kembali ke Website</span>
            </a>
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
        <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar"></a>
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
              <a href="<?= base_url(); ?>page" class="btn btn-sm"><i class="fa fa-chevron-circle-left"></i> Kembali ke Website</a>
            </li>
          </ul>
          <!-- END NOTIFICATIONS LIST -->
        </div>
        <div class="d-flex align-items-center">
          <!-- START User Info-->
          <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
            <span class="semi-bold"><?= $this->session->userdata('user_name'); ?></span>
          </div>
          <div class="dropdown pull-right">

            <?php

              $picture = $this->session->userdata('user_picture');
              $picture_from = $this->session->userdata('user_picture_from');
              $picture_url = "";
              
              if(($picture_from == 'facebook' || $picture_from == 'google' || $picture_from == 'twitter' || $picture_from == 'linkedin') && strlen(trim($picture)) > 0){
                $picture_url = $picture;
              }
              else{
                if(isset($picture) && strlen(trim($picture)) > 0){
                  $picture_url = base_url() . 'assets/user/' . $picture;
                }
                else{
                  if($this->session->userdata('user_gender') == '0'){
                    $picture_url = base_url() . 'assets/user/default-female.png';
                  }
                  else{
                    $picture_url = base_url() . 'assets/user/default.png';
                  }
                }
              }
            ?>
                
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="thumbnail-wrapper d32 circular inline">
                <img src="<?= $picture_url; ?>" alt="" data-src="<?= $picture_url; ?>" data-src-retina="<?= $picture_url; ?>" width="32" height="32">
              </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
              <a href="<?= base_url(); ?>user_profile" class="dropdown-item"><i class="fa fa-cog"></i> Profil Saya</a>
              <a href="<?= base_url(); ?>user/logout" class="clearfix bg-master-lighter dropdown-item">
                <span class="pull-left">Keluar</span>
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
        <div class="container-fluid d-print-none container-fixed-lg footer">
          <div class="copyright sm-text-center">
            <p class="small no-margin pull-left sm-pull-reset">
              <span class="hint-text">Copyright &copy; 2020 </span>
              <span class="hint-text">All rights reserved. </span>
            </p>
            <p class="small no-margin pull-right sm-pull-reset">
              <span class="hint-text">Dikembangkan Oleh: Bisnis Indonesia Group</span>
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
