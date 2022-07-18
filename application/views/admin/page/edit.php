<?php $this->load->view('admin/page/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg page-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit Page
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_page/saveEdit/" . $page[0]->id_page ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'page_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Page title:</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="page_title" value="<?= $page[0]->page_title; ?>" />
                  </div>
                </div>
              </div>
              
              <div class="form-group page_header_field" style="display:none">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Page header:</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if(strlen(trim($page[0]->page_header)) > 0){ ?>
                      <div class="file-preview">
                        <a href="<?= base_url(); ?>admin_page/deletePic/<?= $page[0]->id_page; ?>" class="close fileinput-remove text-right btn-need-confirmation" data-message="Are sure to delete this picture?" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/page/<?= $page[0]->page_header; ?>" class="file-preview-image" title="<?= $page[0]->page_header; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>
                    
                    <input type="file" class="file" name="file_header" id="file_header" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->header_width; ?>px x <?= $this->header_height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Content:</label>
                  <div class="col-md-10 col-xs-12">
                    <?php $page_file_path = base_url() . "assets/content/"; ?>
                    <textarea class="ckeditor form-control" name="page_content" rows="10"><?= str_replace("##BASE_URL##", $page_file_path, html_entity_decode($page[0]->page_content)); ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status:</label>
                  <div class="col-md-8">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="page_status" id="page_status1" <?= ($page[0]->page_status == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="page_status1">Publish</label>
                      <input type="radio" value="0" name="page_status" id="page_status0" <?= ($page[0]->page_status == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="page_status0">Unpublish</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group" <?= ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Updatable:</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="updatable" id="updatable1" <?= ($page[0]->updatable == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="updatable1">Yes</label>
                      <input type="radio" value="0" name="updatable" id="updatable0" <?= ($page[0]->updatable == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="updatable0">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group" <?= ($this->session->userdata('admin_level') != '1' ? "style='display:none'" : ""); ?>>
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Deletable:</label>
                  <div class="col-md-8 col-xs-12">                                                                                            
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="deletable" id="deletable1" <?= ($page[0]->deletable == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="deletable1">Yes</label>
                      <input type="radio" value="0" name="deletable" id="deletable0" <?= ($page[0]->deletable == '0' ? 'checked="checked"' : ''); ?>>
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
		$('#page_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				page_title: {
					validators: {
						notEmpty: {
							message: 'Page title is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>