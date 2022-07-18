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
            Pembatalan Iklan
          </h4>

          <?= $this->session->flashdata('message'); ?>

          <?php if (isset($ads->status) && $ads->status === '-1') { ?>
            <div class="alert alert-danger" role="alert">
              <b>Catatan Penolakan:</b><br>
              <?php echo $ads->reject_note ?>
            </div>
          <?php } ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'ads_cancel_form', 'autocomplete' => 'off']); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Posisi</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $form_value['ads_name'] ?? '' ?>" disabled>
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Tanggal tayang</label>
                  <div class="col-md-4">
                    <div class="input-daterange datepicker-range">
                      <input type="text" class="form-control" name="start_date" value="<?= date('d-m-Y', strtotime($ads->start_date)); ?>" id="start_date" disabled />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-daterange input-group datepicker-range">
                      <div class="input-prepend transparent m-t-5 m-r-10">To</div>
                      <input type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?= date('d-m-Y', strtotime($ads->finish_date)); ?>" id="finish_date" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <?php if ($ads->redirect_url) { ?>
                <div class="form-group builtin_wrapper">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Redirect URL</label>
                    <div class="col-md-8 col-xs-12">
                      <input class="form-control" type="text" name="redirect_url" id="redirect_url" value="<?= $ads->redirect_url; ?>" disabled />
                    </div>
                  </div>
                </div>
              <?php } ?>

              <?php if ($ads->ads_pic) { ?>
                <div class="form-group" id="ads-pic-form-group">
                  <div class="row">
                    <label class="col-md-3 control-label text-right sm-text-left">Gambar Iklan</label>
                    <div class="col-md-8 col-xs-12">
                      <?php if(strlen(trim($ads->ads_pic)) > 0){ ?>
                        <div class="file-preview">
                          <div class="file-preview-thumbnails">
                            <div class="file-preview-frame">
                               <img src="<?= base_url(); ?>assets/adv/<?= $ads->ads_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $ads->ads_pic; ?>" width="auto" style="max-height:100px">
                            </div>
                          </div>
                          <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Harga Per Hari</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="input-group">
                      <div class="input-group-prepend transparent">
                        <div class="input-group-text transparent">Rp. </div>
                      </div>
                      <input id="price_per_day" class="form-control text-right" type="number" name="price_per_day" value="<?php echo $ads->price_per_day ?>" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Subtotal</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="input-group">
                      <div class="input-group-prepend transparent">
                        <div class="input-group-text transparent">Rp. </div>
                      </div>
                      <input id="subtotal" class="form-control text-right" type="number" name="subtotal" value="<?php echo $ads->subtotal ?>" disabled />
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Nomor Rekening</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" id="account_number" name="account_number" value="<?php echo $form_value['account_number'] ?? '' ?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Nama Bank</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" id="account_bank" name="account_bank" value="<?php echo $form_value['account_bank'] ?? '' ?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Nama Pemilik Rekening</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" id="account_name" name="account_name" value="<?php echo $form_value['account_name'] ?? '' ?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Alasan Pembatalan</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" rows="5" name="reason" required><?php echo $form_value['reason'] ?? '' ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
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
  $('#ads_cancel_form').bootstrapValidator({
    message: 'This value is not valid',
      feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        account_number: {
          validators: {
            notEmpty: {
              message: 'Nomor Rekening harus diisi. '
            }
          }
        },
        account_bank: {
          validators: {
            notEmpty: {
              message: 'Nama Bank harus diisi. '
            }
          }
        },
        account_name: {
          validators: {
            notEmpty: {
              message: 'Nama Pemilik Rekening harus diisi. '
            }
          }
        },
        reason: {
          validators: {
            notEmpty: {
              message: 'Alasan Pembatalan harus diisi. '
            }
          }
        },
      }
  });
});
</script>
