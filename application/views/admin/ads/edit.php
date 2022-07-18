<?php $this->load->view('admin/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <div class="d-flex">
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
              Edit Iklan
            </h4>

            <div>
              <?php if ($ads[0]->edit_id_admin) { ?>
                <a class="btn btn-complete <?php echo ((!is_null($locked_ads_id) && $ads[0]->id_ads !== $locked_ads_id) ? 'disabled' : '') ?>" href="<?php echo base_url() ?>admin_ads/unlock_edit/<?php echo $ads[0]->id_ads ?>">
                  <i class="fa fa-unlock mr-1"></i> Unlock Editor
                </a>
              <?php } else { ?>
                <a class="btn btn-danger <?php echo ((!is_null($locked_ads_id) && $ads[0]->id_ads !== $locked_ads_id) ? 'disabled' : '') ?>" href="<?php echo base_url() ?>admin_ads/lock_edit/<?php echo $ads[0]->id_ads ?>" style="height:">
                  <i class="fa fa-lock mr-1"></i> Lock Editor
                </a>
              <?php } ?>

              <?php if (!is_null($locked_ads_id) && $ads[0]->id_ads !== $locked_ads_id) { ?>
                <a class="btn btn-complete" href="<?php echo base_url() ?>admin_ads/edit/<?php echo $locked_ads_id ?>">
                  <i class="fa fa-pencil mr-1"></i> Edit Iklan Terkunci
                </a>
              <?php } ?>
            </div>
          </div>

          <?= $this->session->flashdata('message'); ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

            <?= form_open_multipart("admin_ads/saveEdit/" . $ads[0]->id_ads, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'ads_form', 'autocomplete' => 'off')); ?>
            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Ads type</label>
                <div class="col-md-6 col-xs-12">
                  <select name="ads_source_b" id="ads_source" class="full-width select_nosearch" onchange="typeChanged()" readonly="yes" disabled>
                    <option value="builtin" <?= ($ads[0]->ads_source == 'builtin' ? 'selected' : ''); ?>>Built-in</option>
                    <option value="googleads" <?= ($ads[0]->ads_source == 'googleads' ? 'selected' : ''); ?>>Google Ads</option>
                  </select>
                  <input type="hidden" name="ads_source" value="<?= $ads[0]->ads_source; ?>" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Placement</label>
                <div class="col-md-8 col-xs-12">
                  <select name="id_adstype_b" id="id_adstype" class="full-width select_withsearch" onchange="updateSize()" required readonly="yes" disabled>
                    <?php foreach ($ads_types as $type) { ?>
                      <option value="<?= $type->id_adstype; ?>" <?= ($ads[0]->id_adstype == $type->id_adstype ? 'selected' : ''); ?>>
                        <?= $type->ads_name; ?>
                      </option>
                    <?php } ?>
                  </select>
                  <input type="hidden" name="id_adstype" value="<?= $ads[0]->id_adstype; ?>" />

                  <?php foreach ($ads_types as $type) { ?>
                    <input type="hidden" id="ads_size_<?= $type->id_adstype; ?>" value="<?= $type->ads_width; ?>px x <?= $type->ads_height; ?>px" />
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- echo ($ads[0]->ads_source == 'googleads' ? 'style="display:none;"' : ''); -->

            <?php if (!is_null($ads[0]->id_user)) { ?>
              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Pembuat</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="name" id="name" value="<?= $ads[0]->user_name; ?>" required <?php echo ($ads[0]->id_user ? 'readonly' : '') ?> />
                  </div>
                </div>
              </div>
            <?php } ?>

            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Redirect URL</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="text" name="redirect_url" id="redirect_url" value="<?= $ads[0]->redirect_url; ?>" required <?php echo ($ads[0]->id_user ? 'readonly' : '') ?> />
                </div>
              </div>
            </div>

            <?php if ($ads[0]->id_user && $ads[0]->status === '2' && $ads[0]->revised_redirect_url) { ?>
              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Revisi Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="revised_redirect_url" id="revised_redirect_url" value="<?= $ads[0]->revised_redirect_url; ?>" readonly />
                  </div>
                </div>
              </div>
            <?php } ?>

            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Gambar Iklan</label>
                <div class="col-md-8 col-xs-12">
                  <?php if (strlen(trim($ads[0]->ads_pic)) > 0) { ?>
                    <div class="file-preview">
                      <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                          <img src="<?= base_url(); ?>assets/adv/<?= $ads[0]->ads_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $ads[0]->ads_pic; ?>" width="auto" style="max-height:100px">
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="file-preview-status text-center text-complete"></div>
                    </div>
                  <?php } ?>

                  <?php if (is_null($ads[0]->id_user)) { ?>
                    <input type="file" class="file" name="ads_pic" id="ads_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]' />
                    <p class="hint-text"><small>*Ukuran gambar: <span id="ads_pic_size"></span>. Maksimum size: 6MB</small></p>
                  <?php } ?>
                </div>
              </div>
            </div>

            <?php if ($ads[0]->id_user && $ads[0]->status === '2' && $ads[0]->revised_ads_pic) { ?>
              <div class="form-group" id="revised-ads-pic-form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Revisi Gambar Iklan</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if (strlen(trim($ads[0]->revised_ads_pic)) > 0) { ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                            <img src="<?= base_url(); ?>assets/adv/<?= $ads[0]->revised_ads_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $ads[0]->revised_ads_pic; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            <?php } ?>

            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Tanggal tayang</label>
                <div class="col-md-3">
                  <div class="input-daterange datepicker-range">
                    <input type="text" class="form-control <?php echo ($ads[0]->id_user ? '' : 'datepicker-range-start') ?>" name="start_date" value="<?= date('d-m-Y', strtotime($ads[0]->start_date)); ?>" id="start_date" required <?php echo ($ads[0]->id_user ? 'readonly' : '') ?> />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="input-daterange input-group datepicker-range">
                    <div class="input-prepend transparent m-t-5 m-r-10">To</div>
                    <input type="text" class="form-control <?php echo ($ads[0]->id_user ? '' : 'datepicker-range-finish') ?>" name="finish_date" value="<?= date('d-m-Y', strtotime($ads[0]->finish_date)); ?>" id="finish_date" required <?php echo ($ads[0]->id_user ? 'readonly' : '') ?> />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group googleads_wrapper" style="display:none;">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Google ads code</label>
                <div class="col-md-8 col-xs-12">
                  <textarea class="form-control" name="googleads_code" id="googleads_code" rows="5" required>
                      <?= $ads[0]->googleads_code; ?>
                    </textarea>
                </div>
              </div>
            </div>

            <?php if ($ads[0]->id_user) { ?>
              <?php if (!empty($ads[0]->reason_for_revision)) { ?>
                <div class="form-group builtin_wrapper">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Alasan Revisi</label>
                    <div class="col-md-8 col-xs-12">
                      <textarea class="form-control" rows="5" name="reason_for_revision" readonly><?php echo $ads[0]->reason_for_revision ?></textarea>
                    </div>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                  <div class="col-md-8">
                    <div class="radio radio-complete">
                      <?php if ($ads[0]->status === '2') { ?>
                        <input type="radio" value="2" name="status" id="status2" <?php echo ($ads[0]->status === '2' ? 'checked="checked"' : ''); ?>>
                        <label for="status2">Menunggu Approval</label>
                      <?php } else { ?>
                        <input type="radio" value="0" name="status" id="status0" <?php echo ($ads[0]->status === '0' ? 'checked="checked"' : ''); ?>>
                        <label for="status0">Menunggu Approval</label>
                      <?php } ?>

                      <input type="radio" value="1" name="status" id="status1" <?php echo ($ads[0]->status === '1' ? 'checked="checked"' : ''); ?>>
                      <label for="status1">Approve</label>

                      <?php if ($ads[0]->status === '2') { ?>
                        <input type="radio" value="-3" name="status" id="status-3" <?php echo ($ads[0]->status === '-3' ? 'checked="checked"' : ''); ?>>
                        <label for="status-3">Tolak</label>
                      <?php } else { ?>
                        <input type="radio" value="-2" name="status" id="status-2" <?php echo ($ads[0]->status === '-2' ? 'checked="checked"' : ''); ?>>
                        <label for="status-2">Tolak</label>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group collapse" id="form-group-reject-note">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Catatan Penolakan</label>
                  <div class="col-md-8">
                    <textarea name="reject_note" class="form-control" rows="5"><?php echo $ads[0]->reject_note ?? '' ?></textarea>
                  </div>
                </div>
              </div>
            <?php } ?>

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
              message: 'Harus diisi. '
            }
          }
        },
        start_date: {
          validators: {
            notEmpty: {
              message: 'Harus diisi. '
            }
          }
        },
        finish_date: {
          validators: {
            notEmpty: {
              message: 'Harus diisi. '
            }
          }
        },
        googleads_code: {
          validators: {
            notEmpty: {
              message: 'Harus diisi. '
            }
          }
        },
        status: {
          enabled: <?php echo $ads[0]->id_user ? "true" : "false" ?>,
          validators: {
            notEmpty: {
              message: 'Status harus diisi.'
            }
          }
        },
        reject_note: {
          enabled: false,
          validators: {
            notEmpty: {
              message: 'Catatan Penolakan harus diisi.'
            }
          }
        }
      }
    });

    typeChanged();
    updateSize();
    statusChanged();

    $('input[name=status]').change(statusChanged);
  });

  function statusChanged() {
    var value = $('input[name=status]:checked').val();
    var isRejected = value === '-2' || value === '-3';

    $('#form-group-reject-note').collapse(isRejected ? 'show' : 'hide');
    $('#ads_form').bootstrapValidator('enableFieldValidators', 'reject_note', isRejected);
  }

  function updateSize() {
    var id_adstype = $('#id_adstype').val();
    var size_str = $('#ads_size_' + id_adstype).val();
    $('#ads_pic_size').html(size_str);
  }

  function typeChanged() {
    var type = $('#ads_source').val();
    // console.log(type);
    if (type == 'builtin') {
      $('.builtin_wrapper').show();
      $('.googleads_wrapper').hide();
      $("#redirect_url").prop('required', true);
      $("#start_date").prop('required', true);
      $("#finish_date").prop('required', true);
      $("#googleads_code").prop('required', false);
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'redirect_url');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'start_date', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'start_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'finish_date', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'finish_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'googleads_code', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'googleads_code');
    } else {
      $('.builtin_wrapper').hide();
      $('.googleads_wrapper').show();
      $("#redirect_url").prop('required', false);
      $("#start_date").prop('required', false);
      $("#finish_date").prop('required', false);
      $("#googleads_code").prop('required', true);
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'redirect_url');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'start_date', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'start_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'finish_date', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'finish_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'googleads_code', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'googleads_code');
    }
  }
</script>