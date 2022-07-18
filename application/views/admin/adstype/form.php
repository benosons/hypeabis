<?php $this->load->view('admin/adstype/sub_header'); ?>

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
            <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off']); ?>
            <div class="form-group">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Nama</label>
                <div class="col-md-9 col-xs-12">
                  <input class="form-control" type="text" name="ads_name" value="<?php echo $adstype->ads_name ?>" maxlength="100" disabled />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Harga</label>
                <div class="col-md-4 col-xs-12">
                  <div class="input-group">
                    <div class="input-group-prepend transparent">
                      <div class="input-group-text transparent">Rp. </div>
                    </div>
                    <input class="form-control text-left" type="number" name="price_per_day" value="<?php echo $form_value['price_per_day'] ?>" required autofocus />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Urutan</label>
                <div class="col-md-2 col-xs-12">
                  <input class="form-control text-left" type="number" name="ads_order" value="<?php echo $form_value['ads_order'] ?>" required/>
                </div>
              </div>
            </div>

            <div class="form-group builtin_wrapper">
              <div class="row">
                <label class="col-md-3 control-label text-right sm-text-left">Gambar Lokasi</label>
                <div class="col-md-9 col-xs-12">
                  <?php if(strlen(trim($adstype->location_pic)) > 0){ ?>
                    <div class="file-preview">
                      <div class="file-preview-thumbnails">
                        <div class="file-preview-frame">
                           <a href="<?= base_url(); ?>assets/adstype/<?= $adstype->location_pic; ?>" target="_blank">
                             <img src="<?= base_url(); ?>assets/adstype/<?= $adstype->location_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $adstype->location_pic; ?>" width="auto" style="max-height:250px">
                           </a>
                        </div>
                      </div>
                      <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                    </div>
                  <?php } ?>

                  <input type="file" class="file" name="location_pic" id="location_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]'/>
                  <p class="hint-text"><small>*(Maksimal 5MB. Rekomendasi ukuran: <?php echo $this->pic_width; ?>px x <?php echo $this->pic_height; ?>px. Format file yang diperbolehkan: jpg, png &amp; gif.)</small></p>
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
    $('form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        price_per_day: {
          validators: {
            notEmpty: {
              message: 'Harga Posisi Iklan harus diisi.'
            },
          }
        },
        ads_order: {
          validators: {
            notEmpty: {
              message: 'Urutan Posisi Iklan harus diisi.'
            },
          }
        },
      }
    });
  });
</script>
