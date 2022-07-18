<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-30 p-t-10 p-b-10">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>files/backend/img/icons/icon-point.png"/>
            </div>
          </div>
          <!-- END card -->
        </div>
        <div class="col-xl-8 col-lg-8 col-md-10">
          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <label class="label" style="background:#<?= $user[0]['bg_color']; ?>;color:#<?= $user[0]['text_color']; ?>">
                <?= $user[0]['level']; ?>
              </label>
              <br/>
              <h3 class="m-t-0 fw-700 text-heading-black">
                <?= number_format($user[0]['point'], 0, ',', '.'); ?> Poin
              </h3>
              <p class="m-b-10">Halaman ini digunakan untuk melihat history perolehan point Hypeabis anda. Kumpulkan poin sebanyak - banyaknya dan tukarkan dengan merchandise menarik dari Hypeabis.</p>
            </div>
          </div>
          <!-- END card -->
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- END JUMBOTRON -->
