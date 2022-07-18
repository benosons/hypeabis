<section class="content-section">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-md-6">

        <h2 class="block-title-small heading-black m-b-20">
          <span>Reset Password</span>
        </h2>

        <?= $this->session->flashdata('message'); ?>

        <!-- START Login Form -->
        <?= form_open('page/submitResetPassword/' . $this->uri->segment(3), array('class' => 'p-t-15', 'id' => 'form-signup')); ?>
        <!-- START Form Control-->
        <div class="form-group form-group-default">
          <label>Password baru</label>
          <div class="controls m-t-5">
            <input type="password" class="form-control" name="new_password" placeholder="Enter your new password" minlength="8" required />
            <p class="hint-text text-left"><small>*8 character minimum.</small></p>
          </div>
        </div>
        <!-- END Form Control-->

        <!-- START Form Control-->
        <div class="form-group form-group-default">
          <label>Konfirmasi ulang password</label>
          <div class="controls m-t-5">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm your new password" minlength="8" required />
          </div>
        </div>
        <!-- END Form Control-->

        <button class="btn btn-complete btn-block m-t-20" type="submit"><i class="fa fa-refresh"></i> &nbsp;Change Password</button>
        <?= form_close(); ?>
        <!--END Login Form-->
      </div>

    </div>
  </div>
</section>