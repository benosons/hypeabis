<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
            <?php echo $heading_text ?> Posisi Iklan
          </h4>

          <?php echo $this->session->flashdata('message') ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'ad_position_form', 'autocomplete' => 'off']); ?>
            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Tanggal tayang</label>
                <div class="col-md-4">
                  <div class="input-daterange datepicker-range">
                    <input type="text" class="form-control datepicker-range-start" name="start_date" value="<?php echo $form_value['start_date'] ?>" id="start_date" data-date-start-date="<?php echo date('d-m-Y', strtotime('tomorrow')) ?>" required />
                  </div>
                </div>
                <label class="col-md-1 control-label text-right sm-text-left">Sampai</label>
                <div class="col-md-4">
                  <div class="input-daterange input-group datepicker-range">
                    <input type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?php echo $form_value['finish_date'] ?>" id="finish_date" required />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Placement</label>
                <div class="col-md-9 col-xs-12">
                  <input type="hidden" name="id_adstype" id="id_adstype" class="full-width" value="<?php echo $form_value['id_adstype'] ?>" <?php echo (isset($item) ? "data-except-id='{$item->id_ads_order_item}'" : '') ?> required>

                  <div id="adstype-preview-collapse" class="collapse <?php echo (isset($item) && $item->location_pic ? 'show' : '') ?>">
                    <div class="file-preview">
                      <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                          <a id="adstype-preview-link" href="<?php echo (isset($item) && $item->location_pic ? base_url('assets/adstype/' . $item->location_pic) : '') ?>" target="_blank">
                            <img id="adstype-preview" src="<?php echo (isset($item) && $item->location_pic ? base_url('assets/adstype/' . $item->location_pic) : '') ?>" class="file-preview-image" title="" width="auto" style="max-height:250px;">
                          </a>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Harga Per Hari</label>
                <div class="col-md-4 col-xs-12">
                  <div class="input-group">
                    <div class="input-group-prepend transparent">
                      <div class="input-group-text transparent">Rp. </div>
                    </div>
                    <input id="price_per_day" class="form-control text-left" type="number" name="price_per_day" value="<?php echo $form_value['price_per_day'] ?>" disabled />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row"> <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                <div class="col-md-9">
                  <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                  <input class="btn btn-complete sm-m-b-10 d-none" type="submit" name="submit_and_add_question" value="Submit & Add Question" />
                  <!-- <input class="btn btn&#45;info sm&#45;m&#45;b&#45;10" type="submit" name="preview" value="Preview"/> -->
                  <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?>
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
    $('#ad_position_form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        start_date: {
          validators: {
            notEmpty: {
              message: 'Pilih Tanggal Mulai Berlaku.'
            },
            date: {
              format: 'DD-MM-YYYY',
              message: 'Format tanggal harus DD-MM-YYYY'
            }
          }
        },
        finish_date: {
          validators: {
            notEmpty: {
              message: 'Pilih Tanggal Akhir Berlaku.'
            },
            date: {
              format: 'DD-MM-YYYY',
              message: 'Format tanggal harus DD-MM-YYYY'
            }
          }
        },
        id_adstype: {
          excluded: false,
          validators: {
            notEmpty: {
              message: 'Posisi Iklan harus diisi.'
            }
          }
        },
      }
    });

    $('#id_adstype').select2({
      ajax: {
        url: '<?php echo $base_url ?>/positions',
        data: function(term, page) {
          var exceptId = $(this).data('except-id');
          var data = {
            q: term,
            start_date: $('#start_date').val(),
            finish_date: $('#finish_date').val(),
          }

          if (exceptId) {
            data['except_id'] = exceptId;
          }

          return data
        },
        results: function(data, page) {
          return data;
        },
      },
      initSelection: function(element, callback) {
        callback({
          id: '<?php echo $form_value["id_adstype"] ?>',
          text: '<?php echo $form_value["ads_name"] ?>'
        })
      },
    });

    $('#id_adstype').on('change', function() {
      var selectData = $('#id_adstype').select2('data');

      $('#ad_position_form').bootstrapValidator('revalidateField', 'id_adstype');
      $('#price_per_day').val(selectData.price_per_day);

      setLocationPic(selectData.location_pic)
    })

    function setLocationPic(location_pic) {
      if (location_pic) {
        $('#adstype-preview').attr('src', location_pic);
        $('#adstype-preview-link').attr('href', location_pic);
        setTimeout(function() {
          $('#adstype-preview-collapse:not(.show)').collapse('show');
        }, 200);
      } else {
        $('#adstype-preview-collapse.show').collapse('hide');
        $('#adstype-preview').attr('src', '');
        $('#adstype-preview-link').attr('href', '');
      }
    }


    setTimeout(function() {
      $('#start_date').on('changeDate', function(selected) {
        $('#ad_position_form').bootstrapValidator('revalidateField', 'start_date');
        $('#id_adstype').select2('val', '');
      });

      $('#finish_date').on('changeDate', function() {
        $('#ad_position_form').bootstrapValidator('revalidateField', 'finish_date');
        $('#id_adstype').select2('val', '');
      });
    }, 100);
  });
</script>