<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Revisi Iklan
          </h4>

          <?= $this->session->flashdata('message'); ?>

          <?php if ($ads[0]->status === '-3') { ?>
            <div class="alert alert-danger" role="alert">
              <b>Catatan Penolakan:</b><br>
              <?php echo $ads[0]->reject_note ?>
            </div>
          <?php } ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

            <?= form_open_multipart("user_ads/save_revise/{$ads[0]->id_ads}", ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'ads_form', 'autocomplete' => 'off']); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Posisi</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $form_value['ads_name'] ?? '' ?>" disabled>
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Tanggal tayang</label>
                  <div class="col-md-4">
                    <div class="input-daterange datepicker-range">
                      <input type="text" class="form-control" name="start_date" value="<?= date('d-m-Y', strtotime($ads[0]->start_date)); ?>" id="start_date" disabled />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-daterange input-group datepicker-range">
                      <div class="input-prepend transparent m-t-5 m-r-10">To</div>
                      <input type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?= date('d-m-Y', strtotime($ads[0]->finish_date)); ?>" id="finish_date" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="redirect_url" id="redirect_url" value="<?= $ads[0]->redirect_url; ?>" disabled />
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Revisi Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="revised_redirect_url" id="revised_redirect_url" value="<?= $form_value['revised_redirect_url']; ?>" />
                  </div>
                </div>
              </div>

              <div class="form-group" id="ads-pic-form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Gambar Iklan</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if(strlen(trim($ads[0]->ads_pic)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/adv/<?= $ads[0]->ads_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $ads[0]->ads_pic; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="form-group" id="revised-ads-pic-form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Revisi Gambar Iklan</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if(strlen(trim($ads[0]->revised_ads_pic)) > 0){ ?>
                      <div class="file-preview">
                        <a href="<?php echo $base_url ?>/delete_revised_picture/<?php echo $ads[0]->id_ads ?>" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/adv/<?= $ads[0]->revised_ads_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $ads[0]->revised_ads_pic; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>

                    <input type="file" class="file" name="revised_ads_pic" id="revised_ads_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]'/>
                    <p class="hint-text mb-0"><small>*Ukuran gambar: <?php echo $ads[0]->ads_width ?>px x <?php echo $ads[0]->ads_height ?>px. Maksimum size: 6MB</small></p>
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Alasan Revisi</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" rows="5" name="reason_for_revision" required><?php echo $form_value['reason_for_revision'] ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                    <button class="btn btn-default sm-m-b-10" type="button" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
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
  $('#ads_form').bootstrapValidator({
    message: 'This value is not valid',
      feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        revised_redirect_url: {
          validators: {
            callback: {
              message: 'Revisi Redirect URL atau Revisi Gambar Iklan harus diisi.',
              callback: function (value, validator, $field) {
                var pictureValue = validator.getFieldElements('revised_ads_pic').val();

                if (!value && !pictureValue) {
                  validator.updateStatus('revised_ads_pic', validator.STATUS_INVALID);
                  return false;
                }

                validator.updateStatus('revised_ads_pic', validator.STATUS_VALID);
                return true;
              }
            }
          }
        },
        revised_ads_pic: {
          validators: {
            callback: {
              message: 'Revisi Gambar Iklan atau Revisi Redirect URL harus diisi.',
              callback: function (value, validator, $field) {
                var redirectValue = validator.getFieldElements('revised_redirect_url').val();

                if (!value && !redirectValue) {
                  validator.updateStatus('revised_redirect_url', validator.STATUS_VALID);
                  return false;
                }

                validator.updateStatus('revised_redirect_url', validator.STATUS_VALID);
                return true;
              }
            }
          }
        },
        reason_for_revision: {
          validators: {
            notEmpty: {
              message: 'Alasan Revisi harus diisi. '
            }
          }
        }
      }
  });
});
</script>
