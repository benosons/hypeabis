<section class="content-section">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-md-6">

        <div class="title-login">
          <h3 class="mb-40">
            <span>Lupa Password</span>
          </h3>
        </div>
        
        <?= $this->session->flashdata('message'); ?>

        <!-- START Login Form -->
        <?= form_open('page/submitForgotPassword', array('class' => '', 'id' => 'form-signup')); ?>
        <!-- START Form Control-->
        <div class="form-group form-group-default">
          <label class="m-b-0">Email</label>
          <div class="controls m-t-5">
            <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
          </div>
        </div>
        <!-- END Form Control-->

        <button class="btn btn-complete btn-block sm-m-b-10 m-t-20" type="submit"><i class="fa fa-refresh"></i> &nbsp;Reset Password</button>
        <p class="text-center-forgot-pass">
          <a href="<?= base_url(); ?>page/login" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
        </p>
        <?= form_close(); ?>
        <!--END Login Form-->
      </div>

    </div>
  </div>
</section>