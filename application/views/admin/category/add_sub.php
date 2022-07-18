<?php $this->load->view('admin/category/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Tambah sub kategori
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_category/saveAdd",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'category_form', 'autocomplete' => 'off')); ?>
              <div class="form-group" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Category Parent</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="category_parent" value="<?= $category[0]->id_category; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Category Parent</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="category_parent_name" value="<?= $category[0]->category_name; ?>" readonly="yes" disabled />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Nama kategori</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <input class="form-control" type="text" name="category_name"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">View mode</label>
                  <div class="col-md-7 col-xs-12">                                                                                            
                    <select class="full-width select_nosearch" name="category_view">
                      <option value="list">List</option>
                      <option value="card">Cards</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Show sidebar</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="show_sidebar" id="show_sidebar1" checked="checked">
                      <label for="show_sidebar1">Yes</label>
                      <input type="radio" value="0" name="show_sidebar" id="show_sidebar0">
                      <label for="show_sidebar0">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Category picture</label>
                  <div class="col-md-7 col-xs-12">
                    <input type="file" class="file" name="file_pic" id="file_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                    <p class="hint-text">*(Size Recommendation: <?= $this->category_pic_width; ?>px x <?= $this->category_pic_height; ?>px. leave this field blank if you don't want to edit category picture)</p>
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
                    <input class="form-control" type="number" name="order" required="yes" />
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
		$('#category_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				category_name: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Category name is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>