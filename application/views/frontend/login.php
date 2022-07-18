<section class="content-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <aside class="sidebar sticky-top">
          <div class="">
            <div class="side-newsletter">
                <div class="title-login">
                  <h3 class="mb-40">
                    <span>Login</span>
                  </h3>
                </div>
                <center>
                  <?= $this->session->flashdata('message'); ?>
                  <!-- START Login Form -->
                  <?= form_open('page/validateLogin/' . $redirect_url, array('class' => '', 'id' => 'form-signup')); ?>
                      <input type="email" name="email" placeholder="Email" class="mb-3" style="width:100%" autofocus>
                      <input type="password" name="password" placeholder="Password" style="width:100%">
                      
                      <div class="captcha mt-20">
                        <?= $this->recaptcha->render(); ?>
                      </div>
                      <div class="btn-login">
                        <button class="mt-20" type="submit"><i class="fas fa-angle-right"></i> SIGN IN</button>
                      </div>
                  <?= form_close(); ?>
                  <!--END Login Form-->  
                </center>
               
            </div>
          </div>
          <!-- end widget --> 
        </aside>
        <!-- end sidebar --> 
      </div>
      <!-- end col-6 --> 
    </div>
    <!-- end row --> 
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <p class="text-center-forgot-pass">
          <a href="<?= base_url(); ?>page/forgot"><i class="fa fa-lock"></i> Lupa password?</a>
        </p>
        <p class="text-center-sign-up">
            Belum punya akun? 
            <b>
              <a href="<?= base_url(); ?>page/signup/<?= $redirect_url; ?>" style="white-space:nowrap;">
                Daftar Sekarang
              </a>
            </b>
        </p>
      </div> 
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-6">
        <center><p>Atau login dengan:</p></center>
        <div class="form-socmed text-center">
          <a href="<?= (isset($fb_auth_url) && strlen(trim($fb_auth_url)) > 0 ? $fb_auth_url : ''); ?>" class="form-socmed--fb">
            <span class="icon icon-logo_fb icon-logo_fb-dims"></span>
          </a>
          <a href="<?= (isset($google_auth_url) && strlen(trim($google_auth_url)) > 0 ? $google_auth_url : ''); ?>" class="form-socmed--gp mr-auto">
            <span class="icon icon-logo_google icon-logo_google-dims"></span>
          </a>
        </div>
      </div> 
    </div>

  </div>
  <!-- end container --> 
</section>
