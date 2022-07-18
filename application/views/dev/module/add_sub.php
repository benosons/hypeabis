<? $this->load->view('dev/module/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Add Sub Module</h4>
          
          <!-- Start Search Bar --> 
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("dev_module/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'module_form', 'autocomplete' => 'off')); ?>
              <div class="form-group" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Module Parent</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="module_parent" value="<?= $module[0]->id_module; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Module Parent</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="module_parent_name" value="<?= $module[0]->module_name; ?>" readonly="yes" disabled />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Module Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="module_name" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Description</label>
                  <div class="col-md-8">
                    <textarea class="form-control" name="module_desc" rows="5"></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Big icon:</label>
                  <div class="col-md-8">
                    <input type="file" class="file" name="file_icon" id="file_icon" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                    <p class="hint-text"><small>*(Allowed file type: .jpg, .png. Size Recommendation: <?= $this->module_icon_width; ?>px x <?= $this->module_icon_height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu icon class</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="module_icon" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Redirection</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="module_redirect" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Order</label>
                  <div class="col-md-2">
                    <input class="form-control" type="number" name="module_order" />
                  </div>
                </div>
              </div>
              
              <div class="form-group show_sidebar_field">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status:</label>
                  <div class="col-md-8">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="module_status" id="module_status1" checked="checked">
                      <label for="module_status1">Show</label>
                      <input type="radio" value="0" name="module_status" id="module_status0">
                      <label for="module_status0">Hide</label>
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
		$('#module_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				module_name: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Module name is required and cannot be empty'
						}
					}
				},
        module_redirect: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Module redirection is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>
