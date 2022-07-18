<div class="social-wrapper">
  <div class="social" data-pages="social">

    <!-- START JUMBOTRON -->
    <div class="jumbotron" data-pages="parallax" data-social="cover">
      <div class="cover-photo">
        <img alt="Cover photo" src="<?= base_url(); ?>assets/cover/<?= $this->session->userdata('global_cover'); ?>" />
      </div>
      <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
          <div class="pull-bottom bottom-left m-b-40">
            <h5 class="text-white no-margin"><?= date('l, d F Y'); ?></h5>
            <h3 class="text-white no-margin"><span class="bold"><?= $this->session->userdata('global_website_name'); ?> - Administrator</span></h3>
          </div>
        </div>
      </div>
    </div>
    <!-- END JUMBOTRON -->

    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
      <!-- START ITEM -->
      <div class="card no-border bg-transparent full-width hidden-xs hidden-sm mb-0" data-social="item">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-t-30 p-b-30 ">
          <div class="row">
            <div class="col-md-4">
              <div class="container-xs-height">
                <div class="row-xs-height">

                  <div class="social-user-profile col-xs-height text-center col-top">
                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                      <?php $admin_photo = $this->session->userdata('admin_photo'); ?>
                      <?php if (isset($admin_photo) && strlen(trim($admin_photo)) > 0) { ?>
                        <img alt="Avatar" width="55" height="55" data-src-retina="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>" data-src="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>" src="<?= base_url(); ?>assets/admin/<?= $admin_photo; ?>">
                      <?php } else { ?>
                        <img alt="Avatar" width="55" height="55" data-src-retina="<?= base_url(); ?>assets/admin/default.png" data-src="<?= base_url(); ?>assets/admin/default.png" src="<?= base_url(); ?>assets/admin/default.png">
                      <?php } ?>
                    </div>
                  </div>

                  <div class="col-xs-height p-l-20">
                    <h3 class="no-margin"><?= $this->session->userdata('admin_name'); ?></h3>
                    <p class="hint-text m-t-5">
                      <?= $this->session->userdata('admin_email'); ?> | <?= ($this->session->userdata('level') == '1' ? 'Super Administrator' : 'Administrator'); ?>
                    </p>
                    <a class="btn btn-complete" href="<?= base_url(); ?>admin_profile/index">Update My Profile</a>
                  </div>

                </div>
              </div>
            </div>

            <div class="col-md-6 m-t-10">
              <p class="no-margin fs-18 lh-25"><b>Hi <?= $this->session->userdata('admin_username'); ?>!</b> Welcome to administrator area.</p>
              <p class="hint-text m-t-5">Please use this feature wisely. Please notice that any changes you make from this page will affect directly to your website's frontend.</p>
            </div>
          </div>

        </div>
        <!-- END CONTAINER FLUID -->
      </div>
      <!-- END ITEM -->

      <?php $this->load->view('admin/dashboard/statistic'); ?>

      <!-- START ITEM -->
      <div class="card no-border bg-transparent full-width visible-xs visible-sm visible-md">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-t-30 p-b-30 ">
          <div class="row">
            <?php $x = 0; ?>
            <?php foreach ($modules as $module) { ?>
              <?php if (!$module['has_child']) { ?>
                <div class="col-4 col-sm-3 m-b-30">
                  <center>
                    <a href="<?= base_url(); ?><?= $module['module_redirect']; ?>" class="text-black">
                      <img src="<?= base_url(); ?>assets/icon/<?= $module['module_icon_big'] ?>" class="img img-fluid" style="max-width:30px;" />
                      <p class="m-t-5"><?= $module['module_name']; ?></p>
                    </a>
                  </center>
                </div>

                <?php $x++; ?>
                <?php if ($x % 4 == 0) { ?>
                  <div class="clearfix hidden-xs"></div>
                <?php } ?>
                <?php if ($x % 3 == 0) { ?>
                  <div class="clearfix visible-xs hidden-sm hidden-md"></div>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
        <!-- END CONTAINER FLUID -->
      </div>
      <!-- END ITEM -->
    </div>

  </div>
</div>
