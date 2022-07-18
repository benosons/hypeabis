<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-30 p-t-10 p-b-10">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>files/backend/img/icons/icon-lamp.png" alt="">
            </div>
          </div>
          <!-- END card -->
        </div>
        <div class="col-xl-7 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black">Module Manager</h3>
              <p class="m-b-10">
                Module will be shown as sidemenu in administrator area. 
                You can create and manage module list from this page, set module redirection, icon, etc. 
                Please note that you're still need to create controller, model and view file manually for administrator area.
              </p>
            </div>
          </div>
          <!-- END card -->
        </div>
        <div class="col-xl-3 col-lg-4 ">
          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
              <a href="<?= base_url(); ?>dev_module/add" class="btn btn-lg btn-perfect-rounded btn-complete m-b-5">
                <i class="fa fa-plus"></i>
              </a>
            </div>
          </div>
          <!-- END card -->
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- END JUMBOTRON -->

<!-- Submenu Nav tabs -->
<? $function_url = strtolower(trim($this->uri->segment(2))); ?>
<div class="submenu-wrapper m-b-20" id="submenu-wrapper">
  <ul class="submenu nav binari-nav nav-tabs no-border">
      <li class="<?= ($function_url == '' || $function_url == 'index' ? 'active' : '') ?>">
        <a href="<?= base_url(); ?>dev_module/index">
          <i class="fa fa-list"></i> Module List
        </a>
      </li>
      <li class="<?= ($function_url == 'add' ? 'active' : '') ?>">
        <a href="<?= base_url(); ?>dev_module/add">
          <i class="fa fa-plus"></i> Add Module
        </a>
      </li>
  </ul>
</div>
<!-- End Submenu Nav tabs -->