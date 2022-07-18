<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-30 p-t-10 p-b-10">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>files/backend/img/icons/icon-content.png"/>
            </div>
          </div>
          <!-- END card -->
        </div>
        <div class="col-xl-6 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black">Virtual Gallery</h3>
              <p class="m-b-10">Halaman ini digunakan untuk mengelola konten virtual gallery yang telah anda buat.</p>
            </div>
          </div>
          <!-- END card -->
        </div>
        
        <div class="col-xl-3 col-lg-4 ">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
              <a href="<?= base_url(); ?>user_gallery/add" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Add">
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

<?php $function_url = strtolower(trim($this->uri->segment(2))); ?>
<!-- Submenu Nav tabs -->
<div class="submenu-wrapper m-b-20" id="submenu-wrapper">
  <ul class="submenu nav binari-nav nav-tabs no-border" role="tablist">
    <li class="<?= ($function_url == '' || $function_url == 'index' ? 'active' : ''); ?>">
      <a href="<?= base_url(); ?>user_gallery">
        <i class="fa fa-list"></i> All
      </a>
    </li>
    <li class="<?= ($function_url == 'add' ? 'active' : '') ?>">
      <a href="<?= base_url(); ?>user_gallery/add">
        <i class="fa fa-plus"></i> Add
      </a>
    </li>
  </ul>
</div>
<!-- End Submenu Nav tabs -->
