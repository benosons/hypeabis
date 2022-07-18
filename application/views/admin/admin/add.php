<?php $this->load->view('admin/admin/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add Administrator
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_account/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'admin_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Admin photo</label>
                  <div class="col-md-8 col-xs-12">
                    <input type="file" class="file" name="file_admin" id="file_admin" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->admin_pic_width; ?>px x <?= $this->admin_pic_height; ?>px. leave this field blank if you don't want to edit admin photo)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Name</label>
                  <div class="col-md-8 col-xs-12">                                                                  
                    <input class="form-control" type="text" name="name" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Username</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="text" name="username" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Email</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="text" name="email" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Contact number</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="text" name="contact_number" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="status" id="status1" checked="checked">
                      <label for="status1">Active</label>
                      <input type="radio" value="0" name="status" id="status0">
                      <label for="status0">Banned</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Password</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="password" name="password" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Re-Type Password</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="password" name="confirm_password" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Priviledges</label>
                  <div class="col-md-3 col-xs-12">
                    
                    <?php foreach($modules as $x => $module){ ?>
                      <?php if(strpos($this->session->userdata('admin_grant'), strtolower($module['module_redirect']) . '|') !== false || $this->session->userdata('admin_level') == 1){ ?>
                      <div class="checkbox check-complete">
                        <input type="checkbox" value="<?= strtolower($module['module_redirect']); ?>" id="module_<?= $module['id_module']; ?>" name="<?= strtolower($module['module_redirect']); ?>" <?= ($module['has_child'] == '1' ? 'disabled readonly="yes"' : ''); ?>>
                        <label for="module_<?= $module['id_module']; ?>"><?= $module['module_name']; ?></label>
                      </div>
                      <?php } ?>
                    <?php } ?>
                    
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
		$('#admin_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				name: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Name is required and cannot be empty'
						}
					}
				},
				username: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Username is required and cannot be empty'
						}
					}
				},
				email: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Email is required and cannot be empty'
						},
						emailAddress: {
							message: 'This input is not a valid email address'
						}
					}
				},
				contact_number: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Contact number is required and cannot be empty'
						}
					}
				},
				password: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Password is required and cannot be empty'
						},
						stringLength: {
							min: 6,
							message: 'Password must be more than 6 characters long'
						},
						identical: {
							field: 'confirm_password',
							message: 'Password and its confirm are not the same'
						}
					}
				},
				confirm_password: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Re-type password for confirmation'
						},
						stringLength: {
							min: 6,
							message: 'Password confirmation must be more than 6 characters long'
						},
						identical: {
							field: 'password',
							message: 'password confirmation and new password are not the same'
						}
					}
				}
			}
		});
	});
</script>