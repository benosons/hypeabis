<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <? if(isset($meta['title']) && strlen(trim($meta['title'])) > 0){ ?>
      <title>Bisnis Muda - <?= $meta['title']; ?></title>
    <? } else { ?>
      <title>Bisnis Muda</title>
    <? } ?>
    <? if(isset($meta['description']) && strlen(trim($meta['description'])) > 0){ ?>
      <meta name="description" content="<?= $meta['description']; ?>">
    <? } else { ?>
      <meta name="description" content="Bisnismuda.id merupakan portal untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha.">
    <? } ?>
    <meta name="author" content=" Bisnis Muda">
    <meta name="keywords" content="Bisnis muda, Berita bisnis, keuangan, finansial, wirausaha, entrepreneur, investasi, saham">
    
    <? if(isset($meta['title']) && strlen(trim($meta['title'])) > 0){ ?>
      <meta property="og:title" content="Bisnis Muda - <?= $meta['title']; ?>" />
    <? } else { ?>
      <meta property="og:title" content="Bisnis Muda" />
    <? } ?>
    <? if(isset($meta['description']) && strlen(trim($meta['description'])) > 0){ ?>
      <meta property="og:description" content="<?= $meta['description']; ?>" />
    <? } else { ?>
      <meta property="og:description" content="Bisnismuda.id merupakan portal untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha." />
    <? } ?>
    <meta property="og:url" content="<?= base_url(); ?><?= $this->uri->uri_string(); ?>" />
    <meta property="og:type" content="article" />
    <? if(isset($meta['picture']) && strlen(trim($meta['picture'])) > 0){ ?>
      <meta property="og:image" content="<?= $meta['picture']; ?>" />
    <? } ?>
    
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
    
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/pace/pace-theme-flash.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-scrollbar/jquery.scrollbar.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/switchery/css/switchery.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/jquery-listslider/jquery-listslider.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/fileinput/css/fileinput.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/css/pages-icons.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" class="main-stylesheet" href="<?= base_url(); ?>files/backend/css/pages.css"/>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    
    <!-- Important js file -->
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/pace/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/modernizr.custom.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/jquery/jquery-easy.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/popper/umd/popper.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>files/backend/plugins/bootstrap/js/bootstrap.min.js"></script>
  </head>
  
  <body class="fixed-header">
  
    <div class="register-container full-height sm-p-t-20 p-t-30">
      <div class="d-flex flex-column full-height">
        
        <? if(isset($global_data[0]->id_global) && (isset($global_data[0]->logo) > 0 && strlen(trim($global_data[0]->logo)) > 0)){ ?>
          
          <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="logo" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" class="img img-fluid login-logo"/>
          <p class="p-t-10">&nbsp;</p>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- START Login Form -->
          <?= form_open('user/validateLogin/' . $redirect_url, array('class' => 'p-t-15', 'id' => 'form-signup')); ?>
            <!-- START Form Control-->
            <div class="form-group form-group-default">
              <label>Email</label>
              <div class="controls m-t-5">
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
              </div>
            </div>
            <!-- END Form Control-->
            
            <!-- START Form Control-->
            <div class="form-group form-group-default">
              <label>Password</label>
              <div class="controls m-t-5">
                <input type="password" name="password" placeholder="Enter your password" class="form-control" required />
              </div>
            </div>
            <!-- END Form Control-->
            
            <div class="row">
              <div class="col-12 d-flex align-items-center justify-content-end">
                <a href="<?= base_url(); ?>user/forgot" class="m-t-10 m-b-10 bold small"><i class="fa fa-lock"></i> Forgot password?</a>
              </div>
              <div class="col-12">
                <button class="btn btn-complete btn-block m-t-10" type="submit"><i class="fa fa-sign-in"></i> &nbsp;Sign In</button>
              </div>
            </div>
          <?= form_close(); ?>
          <!--END Login Form-->
          
          <div class="row m-t-30">
            <div class="col-12">
              <p class="text-center small">
                Don't have an account? 
                <a href="<?= base_url(); ?>user/signup" class="bold" style="white-space:nowrap;">
                  <u>Create Account</u>
                </a>
              </p>
            </div>
          </div>
          
          <p class="text-center bold m-t-30">Or sign in with:</p>
          <div class="row">
            <div class="col-6 p-r-5 m-b-10">
              <a class="btn btn-block btn-primary btn-facebook" href="<?= (isset($fb_auth_url) && strlen(trim($fb_auth_url)) > 0 ? $fb_auth_url : ''); ?>">
                <span class="pull-left"><i class="fa fa-facebook"></i></span>
                <span class="">Facebook</span>
              </a>
            </div>
            <div class="col-6 p-l-5 m-b-10">
              <a class="btn btn-block btn-primary btn-google" href="<?= (isset($google_auth_url) && strlen(trim($google_auth_url)) > 0 ? $google_auth_url : ''); ?>">
                <span class="pull-left"><i class="fa fa-google"></i></span>
                <span class="">Google</span>
              </a>
            </div>
            <div class="col-6 p-r-5 m-b-10">
              <a class="btn btn-block btn-primary btn-twitter" href="<?= (isset($twitter_auth_url) && strlen(trim($twitter_auth_url)) > 0 ? $twitter_auth_url : ''); ?>">
                <span class="pull-left"><i class="fa fa-twitter"></i></span>
                <span class="">Twitter</span>
              </a>
            </div>
            <div class="col-6 p-l-5 m-b-10">
              <a class="btn btn-block btn-primary btn-linkedin" href="<?= (isset($linkedin_auth_url) && strlen(trim($linkedin_auth_url)) > 0 ? $linkedin_auth_url : ''); ?>">
                <span class="pull-left"><i class="fa fa-linkedin"></i></span>
                <span class="">LilnkedIn</span>
              </a>
            </div>
          </div>
          
        <? } else { ?>
        
          <img src="<?= base_url(); ?>files/backend/img/icons/icon-admin.png" alt="logo" class="brand" data-src="<?= base_url(); ?>files/backend/img/icons/icon-alien.png" data-src-retina="<?= base_url(); ?>files/backend/img/icons/icon-alien.png">
          <p class="p-t-35">
            <h4 class="fw-700">U P S . . </h4>
            
            <div class='alert alert-danger'>
              Project installation not complete. <br/>
              Please set project setting in developer area. <br/>
              (Contact Binari for more information).
            </div>
          </p>
        
        <? } ?>
        
        <div class="m-b-30 m-t-30 sm-p-r-15 sm-p-b-20 clearfix d-flex-md-up">
          <div class="col-md-12 no-padding d-flex align-items-center">
            <p class="hinted-text small inline sm-p-t-10">No part of this website or any of its contents may be reproduced, copied, modified or adapted, without the prior written consent of the author, unless otherwise indicated for stand-alone materials.</p>
          </div>
        </div>
        
      </div>
    </div>
  </body>
</html>