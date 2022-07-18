<?php $this->load->view('admin/ads_voucher/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
            <?php echo $heading_text ?> Voucher Iklan
          </h4>
          

          <?php echo $this->session->flashdata('message') ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Tanggal berlaku</label>
                <div class="col-md-4">
                  <div class="input-daterange datepicker-range">
                    <input type="text" class="form-control datepicker-range-start" name="start_date" value="<?php echo $form_value['start_date'] ?>" id="start_date" required/>
                  </div>
                </div>
                <label class="col-md-1 control-label text-right sm-text-left">Sampai</label>
                <div class="col-md-4">
                  <div class="input-daterange input-group datepicker-range">
                    <input type="text" class="form-control datepicker-range-finish" name="end_date"  value="<?php echo $form_value['end_date'] ?>" id="end_date" required/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Kode</label>
                <div class="col-md-9 col-xs-12">
                  <input class="form-control" type="text" name="code" value="<?php echo $form_value['code'] ?>" maxlength="100" required />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Nilai</label>
                <div class="col-md-9 col-xs-12">
                  <div class="input-group">
                    <div class="input-group-prepend transparent">
                      <div class="input-group-text transparent">Rp. </div>
                    </div>
                    <input class="form-control text-right" type="number" name="value" value="<?php echo $form_value['value'] ?>" min="1" required/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row"> <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
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
    $('[name=id_user]').select2();

    $('#content_form').bootstrapValidator({
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
        end_date: {
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
        code: {
          validators: {
            notEmpty: {
              message: 'Kode Voucher harus diisi.'
            }
          }
        },
        value: {
          validators: {
            notEmpty: {
              message: 'Nilai Voucher harus diisi.'
            },
          }
        },
      }
    });
    setTimeout(function () {
      $('#start_date').on('changeDate', function (selected) {
        $('#content_form').bootstrapValidator('revalidateField', 'start_date');
      });

      $('#end_date').on('changeDate', function () {
        $('#content_form').bootstrapValidator('revalidateField', 'end_date');
      });
    }, 100);
  });
</script>
