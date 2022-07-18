<? $this->load->view('admin/menu/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Add Sub Menu
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_menu/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'menu_form', 'autocomplete' => 'off')); ?>
              <? if($this->session->userdata('admin_level') == '1'){ ?>
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Developer ID</label>
                    <div class="col-md-6 col-xs-12">
                      <input class="form-control" type="text" name="dev_code" />
                    </div>
                  </div>
                </div>
              <? } ?>
              
              <div class="form-group" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu parent</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="menu_parent" value="<?= $menu[0]->id_menu; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu parent</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text" name="menu_parent_name" value="<?= $menu[0]->menu_name; ?>" readonly="yes" disabled />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu name (English)</label>
                  <div class="col-md-6 col-xs-12">
                    <input class="form-control" type="text" name="menu_name"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu name (Bahasa)</label>
                  <div class="col-md-6 col-xs-12">
                    <input class="form-control" type="text" name="menu_name_lang1"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Menu picture</label>
                  <div class="col-md-7 col-xs-12">
                    <input type="file" class="file" name="file_pic" id="file_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                    <p class="hint-text">*(Size Recommendation: <?= $this->menu_pic_width; ?>px x <?= $this->menu_pic_height; ?>px. leave this field blank if you don't want to edit menu picture)</p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status:</label>
                  <div class="col-md-8">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="menu_status" id="menu_status1" checked="checked">
                      <label for="menu_status1">Active</label>
                      <input type="radio" value="0" name="menu_status" id="menu_status0">
                      <label for="menu_status0">Non active</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Redirect URL</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="redirect_url"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group" <?= ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Updatable</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="updatable" id="updatable1" checked="checked">
                      <label for="updatable1">Yes</label>
                      <input type="radio" value="0" name="updatable" id="updatable0">
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
                      <input type="radio" value="1" name="deletable" id="deletable1" checked="checked">
                      <label for="deletable1">Yes</label>
                      <input type="radio" value="0" name="deletable" id="deletable0">
                      <label for="deletable0">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Order</label>
                  <div class="col-md-2 col-xs-12">
                    <input class="form-control" type="number" name="menu_order" required="yes" />
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
		$('#menu_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				menu_name: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Menu name is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>