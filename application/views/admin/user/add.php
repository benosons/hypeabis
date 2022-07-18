<?php $this->load->view('admin/user/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add User
          </h4>

          <?= $this->session->flashdata('message'); ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open_multipart("admin_user/saveAdd", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'user_form', 'autocomplete' => 'off')); ?>
            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">User picture</label>
                <div class="col-md-8 col-xs-12">
                  <input type="file" class="file" name="picture" id="picture" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                  <p class="hint-text"><small>*(Size Recommendation: <?= $this->user_pic_width; ?>px x <?= $this->user_pic_height; ?>px.)</small></p>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Nama</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="text" name="name" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Profil singkat</label>
                <div class="col-md-8 col-xs-12">
                  <input type="text" name="profile_desc" class="form-control" maxlength="100">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Gender</label>
                <div class="col-md-8 col-xs-12">
                  <div class="radio radio-complete">
                    <input type="radio" value="1" name="gender" id="gender1">
                    <label for="gender1">Men</label>
                    <input type="radio" value="0" name="gender" id="gender0">
                    <label for="gender0">Women</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Email</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="text" name="email" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Tempat / Tanggal Lahir:</label>
                <div class="col-md-4 col-lg-3">
                  <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" />
                </div>
                <div class="col-md-4 col-lg-3">
                  <input type="text" class="form-control datepicker-component" name="dob" id="dob" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Nomor kontak</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="text" name="contact_number" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left" for="address">Alamat:</label>
                <div class="col-md-8">
                  <textarea class="form-control" rows="5" name="address" required><?= (isset($user[0]->address) ? $user[0]->address : ''); ?></textarea>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">URL Facebook</label>
                <div class="col-md-8 col-xs-12">
                  <div class="input-group tranparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">https://</span>
                    </div>
                    <input class="form-control" type="text" name="facebook" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">URL Twitter</label>
                <div class="col-md-8 col-xs-12">
                  <div class="input-group tranparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">https://</span>
                    </div>
                    <input class="form-control" type="text" name="twitter" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">URL Instagram</label>
                <div class="col-md-8 col-xs-12">
                  <div class="input-group tranparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">https://</span>
                    </div>
                    <input class="form-control" type="text" name="instagram" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Pekerjaan:</label>
                <div class="col-md-8">
                  <select name="id_job" class="full-width select_withsearch" required>
                    <option value="">- Pilih pekerjaan -</option>
                    <?php foreach ($job as $item) { ?>
                      <option value="<?= $item->id_job; ?>">
                        <?= $item->job_name; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Bidang:</label>
                <div class="col-md-8">
                  <select name="id_jobfield" class="full-width select_withsearch" required>
                    <option value="">- Pilih bidang -</option>
                    <?php foreach ($jobfield as $item) { ?>
                      <option value="<?= $item->id_jobfield; ?>">
                        <?= $item->job_field; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Interest:</label>
                <div class="col-md-8">
                  <select name="id_interest" class="full-width select_withsearch" required>
                    <option value="">- Pilih interest -</option>
                    <?php foreach ($interest as $item) { ?>
                      <option value="<?= $item->id_interest; ?>">
                        <?= $item->interest; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Status akun</label>
                <div class="col-md-8 col-xs-12">
                  <div class="radio radio-complete">
                    <input type="radio" value="1" name="status" id="status1" checked="checked">
                    <label for="status1">Active</label>
                    <input type="radio" value="0" name="status" id="status0">
                    <label for="status0">Banned</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Member status</label>
                <div class="col-md-8 col-xs-12">
                  <div class="radio radio-complete">
                    <input type="radio" value="0" name="verified" id="verified0" checked="checked">
                    <label for="verified0">Standard</label>
                    <input type="radio" value="1" name="verified" id="verified1">
                    <label for="verified1">Verified</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Confirm email</label>
                <div class="col-md-8 col-xs-12">
                  <div class="radio radio-complete">
                    <input type="radio" value="1" name="confirm_email" id="confirm_email1" checked="checked">
                    <label for="confirm_email1">Yes</label>
                    <input type="radio" value="0" name="confirm_email" id="confirm_email0">
                    <label for="confirm_email0">No</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Member internal</label>
                <div class="col-md-8 col-xs-12">
                  <div class="radio radio-complete">
                    <input type="radio" value="1" name="is_internal" id="is_internal1" checked="checked">
                    <label for="is_internal1">Yes</label>
                    <input type="radio" value="0" name="is_internal" id="is_internal0">
                    <label for="is_internal0">No</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Password</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="password" name="password" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Re-Type Password</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="password" name="confirm_password" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                <div class="col-md-9">
                  <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                  <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                </div>
              </div>
            </div>
            <?= form_close(); ?>

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
          group: '.col-md-8',
          validators: {
            notEmpty: {
              message: 'Name is required and cannot be empty'
            }
          }
        },
        email: {
          group: '.col-md-8',
          validators: {
            notEmpty: {
              message: 'Email is required and cannot be empty'
            },
            emailAddress: {
              message: 'This input is not a valid email address'
            }
          }
        },
        contact_number: {
          group: '.col-md-8',
          validators: {
            notEmpty: {
              message: 'Contact number is required and cannot be empty'
            }
          }
        },
        password: {
          group: '.col-md-8',
          validators: {
            notEmpty: {
              message: 'Password is required and cannot be empty'
            },
            stringLength: {
              min: 6,
              message: 'Password must be more than 6 characters long'
            },
            identical: {
              field: 'confirm_password',
              message: 'Password and its confirm are not the same'
            }
          }
        },
        confirm_password: {
          group: '.col-md-8',
          validators: {
            notEmpty: {
              message: 'Re-type password for confirmation'
            },
            stringLength: {
              min: 6,
              message: 'Password confirmation must be more than 6 characters long'
            },
            identical: {
              field: 'password',
              message: 'password confirmation and new password are not the same'
            }
          }
        }
      }
    });
  });
</script>