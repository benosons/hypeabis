<section class="content-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <aside class="sidebar sticky-top">
          <div class="">
            <div class="side-newsletter">
              <div class="title-login">
                <h3 class="mb-40">
                  <span>Buat Akun</span>
                </h3>
              </div>
              <center>

                <?= $this->session->flashdata('message'); ?>
                <!-- START Login Form -->
                <?= form_open('page/submitSignup/' . $redirect_url, array('class' => '', 'id' => 'form-signup', 'autocomplete' => 'off')); ?>
                <div class="form-signup">
                  <label>Nama</label>
                </div>
                <input type="text" name="name" placeholder="Enter Your name..." class="mb-3" style="width:100%" required />

                <div class="form-signup">
                  <label>Email</label>
                </div>
                <input type="email" name="email" placeholder="Enter Your email..." class="mb-3" style="width:100%" required />

                <!--
                <div class="form-signup">
                  <label>Nomor Telp. / HP</label>
                </div>
                <input type="text" name="contact_number" placeholder="Enter Your contact number..." onkeypress="return onlyNumberKey(event)" class="mb-3" style="width:100%" required />
                
                <div class="form-signup">
                  <label>Pekerjaan</label>
                </div>
                <div class="form-signup mb-3" style="position:relative;float:left;width:100%;">
                  <select name="id_job" class=" select2_withsearch" required style="width:100%">
                    <option value="">- Pilih pekerjaan -</option>
                    <?php foreach ($job as $item) { ?>
                      <option value="<?= $item->id_job; ?>"><?= $item->job_name; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-signup">
                  <label>Bidang</label>
                </div>
                <div class="form-signup mb-3" style="position:relative;float:left;width:100%;">
                  <select name="id_jobfield" class="select2_withsearch" required style="width:100%">
                    <option value="">- Pilih bidang -</option>
                    <?php foreach ($jobfield as $item) { ?>
                      <option value="<?= $item->id_jobfield; ?>"><?= $item->job_field; ?></option>
                    <?php } ?>
                  </select>
                </div>
                    -->

                <div class="form-signup" style="position:relative;float:left;width:100%;">
                  <label>Interest</label>
                </div>
                <div class="form-signup mb-3" style="position:relative;float:left;width:100%;">
                  <select name="id_interest" class="select2_withsearch" required style="width:100%">
                    <option value="">- Pilih interest -</option>
                    <?php foreach ($interest as $item) { ?>
                      <option value="<?= $item->id_interest; ?>"><?= $item->interest; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-signup">
                  <label>Password</label>
                </div>
                <input type="password" name="password" placeholder="Enter Your password" minlength="8" required style="width:100%" autocomplete="new-password" />
                <p class="hint-text text-left"><small>*8 character minimum.</small></p>

                <div class="form-signup">
                  <label>Konfirmasi Password</label>
                </div>
                <input type="password" name="confirm_password" placeholder="Confirm your password" minlength="8" required class="mb-3" style="width:100%" autocomplete="new-password" />

                <center>
                  <?= $this->recaptcha->render(); ?>
                </center>
                <div class="btn-login">
                  <button class="mt-20" type="submit"><i class="fas fa-angle-right"></i> DAFTAR SEKARANG</button>
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
        <p class="text-center-sign-up">
          Sudah punya akun?
          <b>
            <a href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>" style="white-space:nowrap;">
              Login disini
            </a>
          </b>
        </p>
      </div>
    </div>
  </div>
  <!-- end container -->
</section>