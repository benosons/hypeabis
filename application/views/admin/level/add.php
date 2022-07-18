<?php $this->load->view('admin/level/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add New Level
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open("admin_level/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'level_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Level / Label Name</label>
                  <div class="col-md-6 col-xs-12">
                    <input class="form-control" type="text" name="level_name"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Minimum Point</label>
                  <div class="col-md-4 col-xs-12">
                    <input class="form-control" type="number" name="level_point"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Label Color</label>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">#</span>
                    </div>
                    <input class="form-control" type="text" name="bg_color"/>
                  </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Text Color</label>
                  <div class="col-md-4 col-xs-12">
                    <div class="input-group transparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">#</span>
                    </div>
                    <input class="form-control" type="text" name="text_color"/>
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
		$('#level_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				level_name: {
					validators: {
						notEmpty: {
							message: 'Level / label name is required and cannot be empty'
						}
					}
				},
        level_point: {
					validators: {
						notEmpty: {
							message: 'Minimum point is required and cannot be empty'
						}
					}
				},
        bg_color: {
					validators: {
						notEmpty: {
							message: 'Label color is required and cannot be empty'
						}
					}
				},
        text_color: {
					validators: {
						notEmpty: {
							message: 'Text color is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>