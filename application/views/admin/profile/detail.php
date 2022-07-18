<? $this->load->view('admin/profile/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <?= $this->session->flashdata('message'); ?>
          
          <div class="row">
            <div class="col-md-8 m-b-30">
              <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Account</h4>
              
              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                <?= form_open_multipart("admin_profile/updateAdmin",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'admin_form', 'autocomplete' => 'off')); ?>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-3 control-label text-right sm-text-left">Admin picture:</label>
                      <div class="col-md-9">
                        <? if(strlen(trim($admin[0]->admin_photo)) > 0){ ?>
                          <div class="file-preview">
                            <a href="<?= base_url(); ?>admin_profile/deleteAdminPic" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                            
                            <div class="file-preview-thumbnails">
                            <div class="file-preview-frame">
                               <img src="<?= base_url(); ?>assets/admin/<?= $admin[0]->admin_photo; ?>" class="file-preview-image" title="<?= $admin[0]->admin_photo; ?>" width="auto" style="max-height:100px">
                            </div>
                            </div>
                             <div class="clearfix"></div>   <div class="file-preview-status text-center text-success"></div>
                          </div>
                        <? } ?>
                        
                        <input type="file" class="file" name="file_admin" id="file_admin" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                        <p class="hint-text"><small>*(Size Recommendation: <?= $this->admin_pic_width; ?>px x <?= $this->admin_pic_height; ?>px.)</small></p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-3 control-label text-right sm-text-left">Name:</label>
                      <div class="col-md-9">
                        <input class="form-control" type="text" name="name" value="<?= $admin[0]->name; ?>" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-3 control-label text-right sm-text-left">Email:</label>
                      <div class="col-md-9">
                        <input class="form-control" type="text" name="email" value="<?= $admin[0]->email; ?>" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-3 control-label text-right sm-text-left">Contact number:</label>
                      <div class="col-md-9">
                        <input class="form-control" type="text" name="contact_number" value="<?= $admin[0]->contact_number; ?>" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                      <div class="col-md-9">
                        <button class="btn btn-complete sm-m-b-10" type="submit">Update account</button>
                        <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                      </div>
                    </div>
                  </div>
                  
                <?= form_close(); ?>
              </div>
            </div>
            
            <div class="col-md-4 m-b-30">
              <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Change password</h4>
              
              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                <?= form_open_multipart("admin_profile/updatePassword",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'admin_form', 'autocomplete' => 'off')); ?>
                
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-12 control-label text-left sm-text-left">Current password:</label>
                      <div class="col-md-12">
                        <input class="form-control" type="password" name="password" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-12 control-label text-left sm-text-left">New password:</label>
                      <div class="col-md-12">
                        <input class="form-control" type="password" name="new_password" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-12 control-label text-left sm-text-left">Re-type password:</label>
                      <div class="col-md-12">
                        <input class="form-control" type="password" name="confirm_password" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-9 p-l-10">
                        <button class="btn btn-complete sm-m-b-10" type="submit">Update password</button>
                      </div>
                    </div>
                  </div>
                
                <?= form_close(); ?>
              </div>
            </div>
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
					group: '.col-md-9',
					validators: {
						notEmpty: {
							message: 'Name is required and cannot be empty'
						}
					}
				},
				email: {
					group: '.col-md-9',
					validators: {
						notEmpty: {
							message: 'Email is required and cannot be empty'
						}
					}
				},
				contact_number: {
					group: '.col-md-9',
					validators: {
						notEmpty: {
							message: 'Contact number is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>