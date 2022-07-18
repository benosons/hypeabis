<?php $class_url = strtolower(trim($this->uri->segment(1))); ?>
<?php $function_url = strtolower(trim($this->uri->segment(2))); ?>

<!-- START JUMBOTRON -->
<div class="jumbotron d-print-none m-b-0">
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
              <h3 class="m-t-0 fw-700 text-heading-black">Iklan</h3>
              <p class="m-b-10">Halaman ini digunakan untuk mengelola iklan anda.</p>
            </div>
          </div>
          <!-- END card -->
        </div>
        
        <?php if (in_array($class_url, ['user_ads', 'user_ads_order']) && in_array($function_url, ['', 'index', 'view'])) { ?>
        <div class="col-xl-3 col-lg-4 ">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
              <a href="<?php echo base_url() ?>user_ads_order/add_position" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Pesan Posisi Iklan">
                <i class="fa fa-plus"></i>
              </a>
            </div>
          </div>
          <!-- END card -->
        </div>
        <?php } ?>
      </div>
      
    </div>
  </div>
</div>
<!-- END JUMBOTRON -->

<!-- Submenu Nav tabs -->
<div class="submenu-wrapper d-print-none m-b-20" id="submenu-wrapper">
  <ul class="submenu nav binari-nav nav-tabs no-border" role="tablist">
    <li class="<?= (($class_url == 'user_ads' && $function_url == '' || $function_url == 'index') ? 'active' : ''); ?>">
      <a href="<?php echo base_url() ?>user_ads">
        <i class="fa fa-list"></i> Semua
      </a>
    </li>
    <li class="<?= (($class_url == 'user_ads_order' && $function_url == '' || $function_url == 'index') ? 'active' : ''); ?>">
      <a href="<?php echo base_url() ?>user_ads_order">
        <i class="fa fa-list"></i> Transaksi
      </a>
    </li>
    <li class="<?= (($class_url == 'user_ads_cancel' && $function_url == '' || $function_url == 'index') ? 'active' : ''); ?>">
      <a href="<?php echo base_url() ?>user_ads_cancel">
        <i class="fa fa-list"></i> Pembatalan
      </a>
    </li>
    <li class="<?= (($class_url == 'user_ads_order' && $function_url == 'cart') ? 'active' : ''); ?>">
      <a href="<?php echo base_url() ?>user_ads_order/cart">
        <i class="fa fa-plus"></i> Pesan Iklan
      </a>
    </li>
  </ul>
</div>
<!-- End Submenu Nav tabs -->
