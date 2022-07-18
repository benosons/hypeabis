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
  
  <body class="fixed-header menu-pin">
  
    <div class="login-wrapper ">
      <!-- START Login Background Pic Wrapper-->
      <div class="bg-pic">
        <!-- START Background Pic-->
        <img src="<?= base_url(); ?>files/backend/img/bg-login.jpg" data-src="<?= base_url(); ?>files/backend/img/bg-login.jpg" data-src-retina="<?= base_url(); ?>files/backend/img/bg-login.jpg" alt="" class="lazy">
        <!-- END Background Pic-->
        <!-- START Background Caption-->
        <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-40 m-b-40 lh-45">
          <h2 class="text-white">
            Work hard. Stay humble. Be kind. Take the leap.
          </h2>
        </div>
        <!-- END Background Caption-->
      </div>
      <!-- END Login Background Pic Wrapper-->
      <!-- START Login Right Container-->
      <div class="login-container bg-white">
        <div class="p-l-30 m-l-20 p-r-30 m-r-20 p-t-20 m-t-20 sm-p-l-15 sm-p-r-15 sm-p-t-30">
        
          <? if(isset($global_data[0]->id_global) && (isset($global_data[0]->logo) > 0 && strlen(trim($global_data[0]->logo)) > 0)){ ?>
          
            <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="logo" data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" class="img img-fluid login-logo" />
            <p class="p-t-35">
              <b>Forgot Password</b>
              <hr/>
              Enter your account email address and we will send you a link to reset your password to your registered email.
            </p>
            
            <?= $this->session->flashdata('message'); ?>
            
            <!-- START Login Form -->
            <?= form_open('adminarea/submitForgotPassword', array('class' => 'p-t-15', 'id' => 'form-signup')); ?>
              <!-- START Form Control-->
              <div class="form-group form-group-default">
                <label>Email</label>
                <div class="controls m-t-5">
                  <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
                </div>
              </div>
              <!-- END Form Control-->
              
              <button class="btn btn-complete btn-block sm-m-b-10 m-t-20" type="submit"><i class="fa fa-refresh"></i> &nbsp;Request Password Reset</button>
              <button class="btn btn-block sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
            <?= form_close(); ?>
            <!--END Login Form-->
            
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
          
          <div class="m-t-40">
            <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
              <div class="col-sm-12 no-padding m-t-10">
                <p>
                  <small>
                    No part of this website or any of its contents may be reproduced, copied, modified or adapted, without the prior written consent of the author.
                  </small>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END Login Right Container-->
    </div>
  
  </body>
</html>