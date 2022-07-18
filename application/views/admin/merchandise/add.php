<? $this->load->view('admin/merchandise/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add Merchandise
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_merchandise/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'merchandise_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Name</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="merch_name"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Description</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" name="merch_desc" rows="5"></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="file" class="file" name="merch_pic" id="merch_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]' required="yes"/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Quota / Qty</label>
                  <div class="col-md-2 col-xs-6">
                    <input class="form-control" type="number" name="merch_quota"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Poin</label>
                  <div class="col-md-2 col-xs-6">
                    <input class="form-control" type="number" name="merch_point"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Publish</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="merch_publish" id="merch_publish1" checked="checked">
                      <label for="merch_publish1">yes</label>
                      <input type="radio" value="0" name="merch_publish" id="merch_publish0">
                      <label for="merch_publish0">No</label>
                    </div>
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
		$('#merchandise_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				merch_name: {
					validators: {
						notEmpty: {
							message: 'Merchandise name is required and cannot be empty'
						}
					}
				},
        merch_pic: {
					validators: {
						notEmpty: {
							message: 'Merchandise picture is required and cannot be empty'
						}
					}
				},
        merch_quota: {
					validators: {
						notEmpty: {
							message: 'Merchandise quota is required and cannot be empty'
						}
					}
				},
        merch_point: {
					validators: {
						notEmpty: {
							message: 'Merchandise point is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>