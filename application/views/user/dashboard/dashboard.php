<div class="social-wrapper">
  <div class="social" data-pages="social">

    <!-- START JUMBOTRON -->
    <div class="jumbotron" data-pages="parallax" data-social="cover">
      <div class="cover-photo">
        <img alt="Cover photo" src="<?= base_url(); ?>assets/cover/<?= $global_data[0]->cover_user; ?>" />
      </div>
      <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
        <div class="inner">
          <div class="pull-bottom bottom-left m-b-40">
            <h5 class="text-white no-margin"><?= date('l, d F Y'); ?></h5>
            <h3 class="text-white no-margin"><span class="bold"><?= $global_data[0]->website_name; ?></span></h3>
          </div>
        </div>
      </div>
    </div>
    <!-- END JUMBOTRON -->

    <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
      <?php echo $this->session->flashdata('message') ?>
      <?php if ($can_be_verified_member) : ?>
        <div class="card no-border bg-success-lighter full-width" data-social="item">
          <div class="card-body">
            <h4 class="m-t-0" style="font-weight: 500;">
              <img src="<?= base_url(); ?>files/frontend/images/verified.png" class="lazy m-r-10" alt="Verified member" title="Verified member" height="24px" style="vertical-align: sub;" />
              <b> Verified Member</b>
            </h4>
            <p>Selamat, anda dapat menjadi <b>Verified Member</b> kami. Dengan menjadi <b>Verified Member</b> anda dapat:</p>
            <ul>
              <li>Dapat membuat Paginated artikel</li>
              <li>Dapat membuat Polling</li>
              <li>Dapat membuat Quiz</li>
            </ul>
          </div>
          <div class="card-footer">
            <?php if (!is_null($latest_submission) && !is_null($latest_submission->reject_description)) : ?>
              <div class="alert alert-danger">
                <b>Alasan Penolakan Pengajuan Terakhir</b><br />
                <?php echo $latest_submission->reject_description ?>
              </div>
            <?php endif; ?>
            <a class="btn btn-complete" href="<?php echo base_url() ?>user_verified_member">Lakukan Pengajuan</a>
          </div>
        </div>
      <?php endif; ?>
      <!-- START ITEM -->
      <div class="card no-border bg-transparent full-width" data-social="item">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-t-30 p-b-0 ">
          <div class="row">
            <div class="col-md-6 m-b-0">
              <div class="container-xs-height">
                <div class="row-xs-height">

                  <div class="social-user-profile col-xs-height text-center col-top">
                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                      <?php
                      $picture = $this->session->userdata('user_picture');
                      $picture_from = $this->session->userdata('user_picture_from');
                      $picture_url = "";

                      if (($picture_from == 'facebook' || $picture_from == 'google' || $picture_from == 'twitter' || $picture_from == 'linkedin') && strlen(trim($picture)) > 0) {
                        $picture_url = $picture;
                      } else {
                        if (isset($picture) && strlen(trim($picture)) > 0) {
                          $picture_url = base_url() . 'assets/user/' . $picture;
                        } else {
                          $picture_url = base_url() . 'assets/user/default.png';
                        }
                      }
                      ?>
                      <img alt="<?= $this->session->userdata('user_name'); ?>" width="75" height="75" data-src-retina="<?= $picture_url; ?>" data-src="<?= $picture_url; ?>" src="<?= $picture_url; ?>">
                    </div>
                  </div>

                  <div class="col-xs-height p-l-20">
                    <p class="no-margin fs-18 lh-25">
                      <b>Hai <?= $this->session->userdata('user_name'); ?></b>
                      <?php if ($user[0]->verified == 1) { ?>
                        &nbsp;<img src="<?= base_url(); ?>files/frontend/images/verified.png" alt="Verified member" title="Verified member" height="18px" style="margin-top:-3px;" />
                      <?php } ?>
                    </p>
                    <p class="hint-text m-t-5">Selamat datang di halaman kontributor Hypeabis.</p>
                    <label class="label" style="background:#<?= $bg_color; ?>;color:#<?= $text_color; ?>">
                      <?= $level_name ?> (<?= number_format($point, 0, ',', '.'); ?> poin)
                    </label>
                  </div>

                </div>
              </div>
            </div>

            <div class="col-md-6 m-b-0 m-t-10 hidden-xs hidden-sm">
              <!--
              <h3 class="no-margin"><?= $this->session->userdata('user_name'); ?></h3>
              <p class="hint-text m-t-5">
              <?= $this->session->userdata('user_email'); ?> | <?= date('l, d F Y'); ?>
              </p>
              -->
              <a class="btn btn-complete pull-right" href="<?= base_url(); ?>user_content/index">Mulai Menulis</a>
            </div>
          </div>
        </div>
        <!-- END CONTAINER FLUID -->
      </div>
      <!-- END ITEM -->

      <!-- START ITEM -->
      <div class="card no-border bg-transparent full-width visible-xs visible-sm visible-md">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-t-30 p-b-30 ">
          <div class="row">
            <div class="col-4 col-sm-3 m-b-30">
              <center>
                <a href="<?= base_url(); ?>user_content">
                  <img src="<?= base_url(); ?>files/backend/img/icons/icon-article.png" class="img img-fluid" style="max-width:30px;" />
                  <p class="m-t-5">Artikel</p>
                </a>
              </center>
            </div>

            <div class="col-4 col-sm-3 m-b-30">
              <center>
                <a href="<?= base_url(); ?>user_point">
                  <img src="<?= base_url(); ?>files/backend/img/icons/icon-photo.png" class="img img-fluid" style="max-width:30px;" />
                  <p class="m-t-5">Hypephoto</p>
                </a>
              </center>
            </div>

            <div class="col-4 col-sm-3 m-b-30">
              <center>
                <a href="<?= base_url(); ?>user_point">
                  <img src="<?= base_url(); ?>files/backend/img/icons/icon-point.png" class="img img-fluid" style="max-width:30px;" />
                  <p class="m-t-5">Poin saya</p>
                </a>
              </center>
            </div>

            <div class="clearfix visible-xs hidden-sm hidden-md"></div>

            <div class="col-4 col-sm-3 m-b-30">
              <center>
                <a href="<?= base_url(); ?>user_point">
                  <img src="<?= base_url(); ?>files/backend/img/icons/icon-merchandise.png" class="img img-fluid" style="max-width:30px;" />
                  <p class="m-t-5">Merchandise</p>
                </a>
              </center>
            </div>

            <div class="clearfix hidden-xs"></div>

            <div class="col-4 col-sm-3 m-b-30">
              <center>
                <a href="<?= base_url(); ?>user_profile">
                  <img src="<?= base_url(); ?>files/backend/img/icons/icon-profile.png" class="img img-fluid" style="max-width:30px;" />
                  <p class="m-t-5">Profil</p>
                </a>
              </center>
            </div>

          </div>
        </div>
        <!-- END CONTAINER FLUID -->
      </div>
      <!-- END ITEM -->
    </div>

  </div>
</div>