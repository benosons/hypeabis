<? $this->load->view('admin/slider/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit Slider
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_slider/saveEdit/" . $slider[0]->id_slider ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'slider_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slider name</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="slider_name" value="<?= $slider[0]->slider_name; ?>"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Slider size</label>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                      <div class="input-group-prepend">
                        <span class="input-group-text transparent">width: </span>
                      </div>
                      <input class="form-control" type="number" name="width" required="yes" value="<?= $slider[0]->width; ?>">
                      <div class="input-group-append">
                        <span class="input-group-text transparent">px</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                      <div class="input-group-prepend">
                        <span class="input-group-text transparent">height: </span>
                      </div>
                      <input class="form-control" type="number" name="height" required="yes" value="<?= $slider[0]->height; ?>">
                      <div class="input-group-append">
                        <span class="input-group-text transparent">px</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Thumbnail size</label>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                      <div class="input-group-prepend">
                        <span class="input-group-text transparent">width: </span>
                      </div>
                      <input class="form-control" type="number" name="width_thumb" required="yes" value="<?= $slider[0]->width_thumb; ?>">
                      <div class="input-group-append">
                        <span class="input-group-text transparent">px</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                      <div class="input-group-prepend">
                        <span class="input-group-text transparent">height: </span>
                      </div>
                      <input class="form-control" type="number" name="height_thumb" required="yes" value="<?= $slider[0]->height_thumb; ?>">
                      <div class="input-group-append">
                        <span class="input-group-text transparent">px</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group" <?= ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Updatable</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="updatable" id="updatable1" <?= ($slider[0]->updatable == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="updatable1">Yes</label>
                      <input type="radio" value="0" name="updatable" id="updatable0" <?= ($slider[0]->updatable == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="updatable0">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group" <?= ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Deletable</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="deletable" id="deletable1" <?= ($slider[0]->deletable == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="deletable1">Yes</label>
                      <input type="radio" value="0" name="deletable" id="deletable0" <?= ($slider[0]->deletable == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="deletable0">No</label>
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
		$('#slider_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				title: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Title is required and cannot be empty'
						}
					}
				},
				slider: {
					group: '.col-md-10',
					validators: {
						notEmpty: {
							message: 'Slider is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>