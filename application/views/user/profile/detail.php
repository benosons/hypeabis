<?php $this->load->view('user/profile/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <?= $this->session->flashdata('message'); ?>

          <div class="row">
            <div class="col-md-8 m-b-30">
              <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Profil</h4>

              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                <?= form_open_multipart("user_profile/updateUser", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'user_form', 'autocomplete' => 'off')); ?>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Nama:</label>
                    <div class="col-md-9">
                      <input class="form-control" type="text" name="name" value="<?= $user[0]->name; ?>" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Profil singkat:</label>
                    <div class="col-md-9">
                      <input type="text" name="profile_desc" class="form-control" maxlength="100" value="<?= $user[0]->profile_desc; ?>">
                      <p class="help-block">*Maksimum 100 karakter</p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Gender</label>
                    <div class="col-md-9 col-xs-12">
                      <div class="radio radio-complete">
                        <input type="radio" value="1" name="gender" id="gender1" <?= ($user[0]->gender == '1' ? 'checked="checked"' : ''); ?>>
                        <label for="gender1">Men</label>
                        <input type="radio" value="0" name="gender" id="gender0" <?= ($user[0]->gender == '0' ? 'checked="checked"' : ''); ?>>
                        <label for="gender0">Women</label>
                      </div>
                    </div>
                  </div>
                </div>

                <?php
                $dob = isset($user[0]->dob) ? $user[0]->dob : '';
                $is_valid_date = $this->global_lib->validateDate($dob, 'Y-m-d');
                ?>
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Tempat / Tanggal Lahir:</label>
                    <div class="col-md-4 col-lg-4">
                      <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?= (isset($user[0]->tempat_lahir) ? $user[0]->tempat_lahir : ''); ?>" />
                    </div>
                    <div class="col-md-5 col-lg-5">
                      <input type="text" class="form-control datepicker-component" name="dob" id="dob" value="<?= ($is_valid_date ? date('d-m-Y', strtotime($user[0]->dob)) : ''); ?>" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Nomor kontak:</label>
                    <div class="col-md-9">
                      <input class="form-control" type="text" name="contact_number" value="<?= $user[0]->contact_number; ?>" />
                      <p class="help-block">*Nomor kontak anda tidak akan disebarluaskan</p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left" for="address">Alamat:</label>
                    <div class="col-md-9">
                      <textarea class="form-control" rows="5" name="address" required><?= (isset($user[0]->address) ? $user[0]->address : ''); ?></textarea>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">URL Facebook:</label>
                    <div class="col-md-9">
                      <div class="input-group tranparent">
                        <div class="input-group-prepend">
                          <span class="input-group-text transparent">https://</span>
                        </div>
                        <input class="form-control" type="text" name="facebook" value="<?= $user[0]->facebook; ?>" />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">URL Twitter:</label>
                    <div class="col-md-9">
                      <div class="input-group tranparent">
                        <div class="input-group-prepend">
                          <span class="input-group-text transparent">https://</span>
                        </div>
                        <input class="form-control" type="text" name="twitter" value="<?= $user[0]->twitter; ?>" />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">URL Instagram:</label>
                    <div class="col-md-9">
                      <div class="input-group tranparent">
                        <div class="input-group-prepend">
                          <span class="input-group-text transparent">https://</span>
                        </div>
                        <input class="form-control" type="text" name="instagram" value="<?= $user[0]->instagram; ?>" />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Foto profil:</label>
                    <div class="col-md-9">
                      <?php
                      $picture = $user[0]->picture;
                      $picture_from = $user[0]->picture_from;
                      $picture_url = "";

                      if ($picture_from == 'facebook' || $picture_from == 'google' || $picture_from == 'twitter' || $picture_from == 'linkedin') {
                        $picture_url = $picture;
                      } else {
                        if (isset($picture) && strlen(trim($picture)) > 0) {
                          $picture_url = base_url() . 'assets/user/' . $picture;
                        } else {
                          $picture_url = base_url() . 'assets/user/default.png';
                        }
                      }
                      ?>
                      <?php if (strlen(trim($picture)) > 0) { ?>
                        <div class="file-preview">
                          <?php if ($picture_from == 'web') { ?>
                            <a href="<?= base_url(); ?>user_profile/deleteUserPic" class="close fileinput-remove text-right btn-need-confirmation" data-message="Are you sure want to remove this picture?" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                          <?php } ?>
                          <div class="file-preview-thumbnails">
                            <div class="file-preview-frame">
                              <img src="<?= $picture_url; ?>" class="file-preview-image img img-thumbnail" title="<?= $picture; ?>" width="auto" style="max-height:100px">
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="file-preview-status text-center text-success"></div>
                        </div>
                      <?php } ?>

                      <input type="file" class="file" name="picture" id="picture" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                      <p class="hint-text"><small>*(Size Recommendation: <?= $this->user_pic_width; ?>px x <?= $this->user_pic_height; ?>px.)</small></p>
                    </div>
                  </div>
                </div>

                <div class="form-group" style="display:none">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left" for="cover">Cover file:</label>
                    <div class="col-md-9">
                      <?php if (strlen(trim($user[0]->cover)) > 0) { ?>
                        <div class="file-preview">
                          <a href="<?= base_url(); ?>user_profile/deleteUserCover" class="close fileinput-remove text-right btn-need-confirmation" data-message="Are you sure want to remove this cover?" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                          <div class="file-preview-thumbnails">
                            <div class="file-preview-frame">
                              <img src="<?= base_url(); ?>assets/user-cover/<?= $user[0]->cover; ?>" class="file-preview-image" title="<?= $user[0]->cover; ?>" width="auto" style="max-height:75px">
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="file-preview-status text-center text-success"></div>
                        </div>
                      <?php } ?>
                      <input type="file" class="file" name="cover" id="cover" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]' />
                      <p class="hint-text"><small>*(Size Recommendation: <?= $this->cover_width; ?>px x <?= $this->cover_height; ?>px.)</small></p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Pekerjaan:</label>
                    <div class="col-md-9">
                      <select name="id_job" class="full-width select_withsearch" required>
                        <option value="">- Pilih pekerjaan -</option>
                        <?php foreach ($job as $item) { ?>
                          <option value="<?= $item->id_job; ?>" <?= ($item->id_job == $user[0]->id_job ? 'selected' : ''); ?>>
                            <?= $item->job_name; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Bidang:</label>
                    <div class="col-md-9">
                      <select name="id_jobfield" class="full-width select_withsearch" required>
                        <option value="">- Pilih bidang -</option>
                        <?php foreach ($jobfield as $item) { ?>
                          <option value="<?= $item->id_jobfield; ?>" <?= ($item->id_jobfield == $user[0]->id_jobfield ? 'selected' : ''); ?>>
                            <?= $item->job_field; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Interest:</label>
                    <div class="col-md-9">
                      <select name="id_interest" class="full-width select_withsearch" required>
                        <option value="">- Pilih interest -</option>
                        <?php foreach ($interest as $item) { ?>
                          <option value="<?= $item->id_interest; ?>" <?= ($item->id_interest == $user[0]->id_interest ? 'selected' : ''); ?>>
                            <?= $item->interest; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Hubungkan akun:</label>
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-sm-6">
                          <?php if (strlen(trim($user[0]->oauth_uid_facebook)) > 0) { ?>
                            <a class="btn btn-xs btn-block btn-complete btn-facebook m-t-5 btn-need-confirmation" data-message="Are you sure want to disconnect your account with Facebook?" href="<?= base_url(); ?>user_profile/disconnectFacebook">
                              <span class="pull-left"><i class="fa fa-facebook"></i></span> &nbsp;&nbsp;
                              <span class="">Disconnect</span>
                            </a>
                          <?php } else { ?>
                            <a class="btn btn-xs btn-block btn-complete btn-facebook m-t-5" href="<?= (isset($fb_auth_url) && strlen(trim($fb_auth_url)) > 0 ? $fb_auth_url : ''); ?>">
                              <span class="pull-left"><i class="fa fa-facebook"></i></span> &nbsp;&nbsp;
                              <span class="">Connect</span>
                            </a>
                          <?php } ?>
                        </div>
                        <div class="col-sm-6">
                          <?php if (strlen(trim($user[0]->oauth_uid_google)) > 0) { ?>
                            <a class="btn btn-xs btn-block btn-complete btn-google m-t-5 btn-need-confirmation" data-message="Are you sure want to disconnect your account with Google?" href="<?= base_url(); ?>user_profile/disconnectGoogle">
                              <span class="pull-left"><i class="fa fa-google"></i></span> &nbsp;&nbsp;
                              <span class="">Disconnect</span>
                            </a>
                          <?php } else { ?>
                            <a class="btn btn-xs btn-block btn-complete btn-google m-t-5" href="<?= (isset($google_auth_url) && strlen(trim($google_auth_url)) > 0 ? $google_auth_url : ''); ?>">
                              <span class="pull-left"><i class="fa fa-google"></i></span> &nbsp;&nbsp;
                              <span class="">Connect</span>
                            </a>
                          <?php } ?>
                        </div>

                        <!-- <div class="col-sm-6">
                            <?php if (strlen(trim($user[0]->oauth_uid_twitter)) > 0) { ?>
                              <a class="btn btn-xs btn-block btn-complete btn-twitter m-t-5 btn-need-confirmation" data-message="Are you sure want to disconnect your account with Twitter?" href="<?= base_url(); ?>user_profile/disconnectTwitter">
                                <span class="pull-left"><i class="fa fa-twitter"></i></span> &nbsp;&nbsp;
                                <span class="">Disconnect</span>
                              </a>
                            <?php } else { ?>
                              <a class="btn btn-xs btn-block btn-complete btn-twitter m-t-5" href="<?= (isset($twitter_auth_url) && strlen(trim($twitter_auth_url)) > 0 ? $twitter_auth_url : ''); ?>">
                                <span class="pull-left"><i class="fa fa-twitter"></i></span> &nbsp;&nbsp;
                                <span class="">Connect</span>
                              </a>
                            <?php } ?>
                          </div>
                          <div class="col-sm-6">
                            <?php if (strlen(trim($user[0]->oauth_uid_linkedin)) > 0) { ?>
                              <a class="btn btn-xs btn-block btn-complete btn-linkedin m-t-5 btn-need-confirmation" data-message="Are you sure want to disconnect your account with LinkedIn?" href="<?= base_url(); ?>user_profile/disconnectLinkedin">
                                <span class="pull-left"><i class="fa fa-linkedin"></i></span> &nbsp;&nbsp;
                                <span class="">Disconnect</span>
                              </a>
                            <?php } else { ?>
                              <a class="btn btn-xs btn-block btn-complete btn-linkedin m-t-5" href="<?= (isset($linkedin_auth_url) && strlen(trim($linkedin_auth_url)) > 0 ? $linkedin_auth_url : ''); ?>">
                                <span class="pull-left"><i class="fa fa-linkedin"></i></span> &nbsp;&nbsp;
                                <span class="">Connect</span>
                              </a>
                            <?php } ?>
                          </div> -->
                        <div class="col-12 m-t-10">
                          <p class="help-block">*Hubungkan akun anda dengan akun sosial media yang anda miliki untuk membuat akun anda lebih aman dan mudah untuk login ke Bisnis Muda.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                    <div class="col-md-9">
                      <button class="btn btn-complete sm-m-b-10" type="submit">Ubah</button>
                      <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Kembali</button>
                    </div>
                  </div>
                </div>

                <?= form_close(); ?>
              </div>
            </div>

            <div class="col-md-4 m-b-30">
              <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Email &amp; Password</h4>

              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

                <?php if (strlen(trim($user[0]->password)) <= 0) { ?>
                  <p>Akun anda terdaftar dengan menggunakan akun sosial media. Buat password Bisnis Muda anda agar anda dapat langsung login tanpa akun sosial media di kemudian hari.</p>
                <?php } ?>

                <?= form_open_multipart("user_profile/updateAccount", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'admin_form', 'autocomplete' => 'off')); ?>

                <div class="form-group">
                  <div class="row">
                    <label class="col-12 control-label text-left sm-text-left">Email:</label>
                    <div class="col-12">
                      <input class="form-control" type="text" name="email" value="<?= $user[0]->email; ?>" />
                    </div>
                  </div>
                </div>

                <?php if (strlen(trim($user[0]->password)) > 0) { ?>
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-12 control-label text-left sm-text-left">Password saat ini:</label>
                      <div class="col-md-12">
                        <input class="form-control" type="password" name="password" />
                        <p class="help-block">*Isi apabila anda ingin mengganti password</p>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-12 control-label text-left sm-text-left">Password baru:</label>
                    <div class="col-md-12">
                      <input class="form-control" type="password" name="new_password" minlength="8" />
                      <p class="help-block">*Minimum 8 karakter.</p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <label class="col-md-12 control-label text-left sm-text-left">Konfirmasi password:</label>
                    <div class="col-md-12">
                      <input class="form-control" type="password" name="confirm_password" />
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-9 p-l-10">
                      <button class="btn btn-complete sm-m-b-10" type="submit">Ubah</button>
                    </div>
                  </div>
                </div>

                <?= form_close(); ?>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>

  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#user_form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        name: {
          group: '.col-md-9',
          validators: {
            notEmpty: {
              message: 'Name is required and cannot be empty'
            }
          }
        },
        contact_number: {
          group: '.col-md-9',
          validators: {
            notEmpty: {
              message: 'Contact number is required and cannot be empty'
            }
          }
        }
      }
    });
  });
</script>