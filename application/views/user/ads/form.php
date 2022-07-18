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
            Edit Iklan
          </h4>

          <?= $this->session->flashdata('message'); ?>

          <?php if ($ads[0]->status === '-2') { ?>
            <div class="alert alert-danger" role="alert">
              <b>Catatan Penolakan:</b><br>
              <?php echo $ads[0]->reject_note ?>
            </div>
          <?php } ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

            <?= form_open_multipart("user_ads/save_edit/{$ads[0]->id_ads}", ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'ads_form', 'autocomplete' => 'off']); ?>
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
                    <input class="form-control" type="text" name="redirect_url" id="redirect_url" value="<?= $ads[0]->redirect_url; ?>" <?php echo ($ads[0]->status === '1' ? 'readonly' : '') ?> required/>
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

                    <?php if ($ads[0]->status !== '1') { ?>
                    <input type="file" class="file" name="ads_pic" id="ads_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]'/>
                    <p class="hint-text mb-0"><small>*Ukuran gambar: <?php echo $ads[0]->ads_width ?>px x <?php echo $ads[0]->ads_height ?>px. Maksimum size: 6MB</small></p>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <?php if ($ads[0]->status !== '1') { ?>
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                    <div class="col-md-8">
                      <div class="radio radio-complete">
                        <input type="radio" value="-1" name="status" id="content_status-1" <?php echo ($form_value['status'] === '-1' || $form_value['status'] !== '0' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status-1">Simpan sebagai draft</label>

                        <input type="radio" value="0" name="status" id="content_status0" <?php echo ($form_value['status'] === '0' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status0">Kirim Iklan</label>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <?php if ($ads[0]->status !== '1') { ?>
                      <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                    <?php } ?>
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
        redirect_url: {
          validators: {
            notEmpty: {
              message: 'Redirect URL harus diisi. '
            }
          }
        },
        ads_pic: {
          validators: {
            notEmpty: {
              message: 'Gambar Iklan harus diisi. '
            }
          }
        }
      }
  });

	$('input[name=status]').change(updateValidator);

  updateValidator();
});

function updateValidator() {
	var status = $('input[type=radio][name=status]:checked').val();

	if (status == '-1') {
		$("#ads_pic").removeAttr("data-required");
		$("#ads_pic").attr("data-required", "false");
		$("#ads_pic").removeAttr("required");
		$("#ads_pic").prop('required',false);
		$('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', false);
		$('#ads_form').bootstrapValidator('enableFieldValidators', 'ads_pic', false);
	}
	else {
		var hasFilePreview = $('#ads-pic-form-group .file-preview').length > 0;
    console.log(hasFilePreview);

		$("#ads_pic").attr("data-required", (!hasFilePreview).toString());
		$("#ads_pic").prop('required', !hasFilePreview);
		$('#ads_form').bootstrapValidator('enableFieldValidators', 'ads_pic', !hasFilePreview);
		$('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', true);
	}
}
</script>
