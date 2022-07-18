<?php $module = $this->global_lib->getModuleDetail($module_name); ?>

<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-20 p-t-10 p-b-0">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <?php if (isset($module[0]->module_icon_big) && strlen(trim($module[0]->module_icon_big)) > 0) : ?>
                <img class="demo-mw-600 mw-100" src="<?php echo base_url(); ?>assets/icon/<?php echo $module[0]->module_icon_big; ?>" alt="">
              <?php endif; ?>
            </div>
          </div>
          <!-- END card -->
        </div>

        <div class="col-xl-6 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black"><?php echo (count($module) > 0 ? $module[0]->module_name : 'Foto') ?></h3>
              <p class="m-b-10">
                Syarat dan ketentuan:
                <ul>
                  <li>Konten Hypephoto berisi minimal 1 foto dan deskripsi masing - masing foto wajib diisi.</li>
                  <li>Dengan mengupload konten Hypephoto, anda menyatakan menyetujui <a href="#">Syarat dan Ketentuan</a> yang berlaku.</li>
                </ul>
              </p>
            </div>
          </div>
          <!-- END card -->
        </div>

        <div class="col-xl-3 col-lg-4 ">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
              <?php if (!$is_admin) { ?>
                <a href="<?php echo $base_url ?>/add" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Tambah Konten Hypephoto">
                  <i class="fa fa-plus"></i>
                </a>
              <?php } ?>
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
<div class="submenu-wrapper m-b-10" id="submenu-wrapper">
  <ul class="submenu nav binari-nav nav-tabs no-border" role="tablist">
    <li class="<?php echo ($function_url == '' || $function_url == 'index' ? 'active' : ''); ?>">
      <a href="<?php echo $base_url ?>">
        <i class="fa fa-list"></i> Semua
      </a>
    </li>
    <?php if (!$is_admin) { ?>
      <li class="<?php echo ($function_url == 'add' ? 'active' : '') ?>">
        <a href="<?php echo $base_url ?>/add">
          <i class="fa fa-plus"></i> Tambah Konten Hypephoto
        </a>
      </li>
    <?php } ?>
  </ul>
</div>
<!-- End Submenu Nav tabs -->
