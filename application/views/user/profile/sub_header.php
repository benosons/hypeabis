<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-30 p-t-10 p-b-10">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <?php if($this->session->userdata('user_gender') == '0'){ ?>
                <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>files/backend/img/icons/icon-admin-default-female.png"/>
              <?php } else { ?>
                <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>files/backend/img/icons/icon-admin-default.png"/>
              <?php } ?>
            </div>
          </div>
          <!-- END card -->
        </div>
        <div class="col-xl-6 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black">Akun Saya</h3>
              <p class="m-b-10">Digunakan untuk mengelola data akun dan profil anda</p>
            </div>
          </div>
          <!-- END card -->
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- END JUMBOTRON -->
