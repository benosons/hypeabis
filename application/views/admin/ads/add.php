<?php $this->load->view('admin/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Buat Iklan
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_ads/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'ads_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Ads type</label>
                  <div class="col-md-6 col-xs-12">
                    <select name="ads_source" id="ads_source" class="full-width select_nosearch" onchange="typeChanged()">
                      <option value="builtin">Built-in</option>
                      <option value="googleads">Google Ads</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Placement</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="id_adstype" id="id_adstype" class="full-width select_withsearch" onchange="updateSize()" required>
                      <?php foreach($ads_types as $type){ ?>
                        <option value="<?= $type->id_adstype; ?>">
                          <?= $type->ads_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                    
                    <?php foreach($ads_types as $type){ ?>
                      <input type="hidden" id="ads_size_<?= $type->id_adstype; ?>" value="<?= $type->ads_width; ?>px x <?= $type->ads_height; ?>px" />
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="redirect_url" id="redirect_url" required/>
                  </div>
                </div>
              </div>
              
              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Upload file</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="file" class="file" name="ads_pic" id="ads_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]'  required/>
                    <p class="hint-text"><small>*Ukuran gambar: <span id="ads_pic_size"></span>. Maksimum size: 6MB</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group builtin_wrapper">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Tanggal tayang</label>
                  <div class="col-md-3">
                    <div class="input-daterange datepicker-range">
                      <input type="text" class="form-control datepicker-range-start" name="start_date" id="start_date" required/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-daterange input-group datepicker-range">
                      <div class="input-prepend transparent m-t-5 m-r-10">To</div>
                      <input type="text" class="form-control datepicker-range-finish" name="finish_date" id="finish_date" required/>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group googleads_wrapper" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Google ads code</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" name="googleads_code" id="googleads_code" rows="5" required></textarea>
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
        ads_pic: {
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
				}
			}
		});
    
    typeChanged();
    updateSize();

    setTimeout(function () {
      $('#start_date').on('changeDate show', function (selected) {
        $('#ads_form').bootstrapValidator('revalidateField', 'start_date');
      });

      $('#finish_date').on('changeDate show', function () {
        $('#ads_form').bootstrapValidator('revalidateField', 'finish_date');
      });
    }, 100);
	});
  
  function updateSize(){
    var id_adstype = $('#id_adstype').val();
    var size_str = $('#ads_size_' + id_adstype).val();
    $('#ads_pic_size').html(size_str);
  }
  
  function typeChanged(){
    var type = $('#ads_source').val();
    if(type == 'builtin'){
      $('.builtin_wrapper').show();
      $('.googleads_wrapper').hide();
      $("#redirect_url").prop('required',true);
      $("#ads_pic").prop('required',true);
      $("#start_date").prop('required',true);
      $("#finish_date").prop('required',true);
      $("#googleads_code").prop('required',false);
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'redirect_url');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'ads_pic', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'ads_pic');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'start_date', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'start_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'finish_date', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'finish_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'googleads_code', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'googleads_code');
    }
    else{
      $('.builtin_wrapper').hide();
      $('.googleads_wrapper').show();
      $("#redirect_url").prop('required',false);
      $("#ads_pic").prop('required',false);
      $("#start_date").prop('required',false);
      $("#finish_date").prop('required',false);
      $("#googleads_code").prop('required',true);
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'redirect_url', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'redirect_url');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'ads_pic', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'ads_pic');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'start_date', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'start_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'finish_date', false);
      $('#ads_form').bootstrapValidator('revalidateField', 'finish_date');
      $('#ads_form').bootstrapValidator('enableFieldValidators', 'googleads_code', true);
      $('#ads_form').bootstrapValidator('revalidateField', 'googleads_code');
    }
  }
</script>
