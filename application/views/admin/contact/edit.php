<?php $this->load->view('admin/contact/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Message Detail
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_contact/saveEdit/" . $contact[0]->id_contact ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'contact_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Normal Text</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="contact_text" value="<?= $contact[0]->contact_text; ?>"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Textarea</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" name="contact_textarea" rows="5"><?= $contact[0]->contact_textarea; ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Text Editor</label>
                  <div class="col-md-10 col-xs-12">
                    <? $content_file_path = base_url() . "assets/content/"; ?>
                    <textarea class="ckeditor form-control" name="contact_texteditor" rows="10">
                      <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($contact[0]->contact_texteditor)); ?>
                    </textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">File upload (non picture)</label>
                  <div class="col-md-8 col-xs-12">
                    <?php
                      $allowed_format = explode('|', $this->allowed_format);
                      $allowed_format_att = "";
                      $allowed_format_str = "";
                      foreach($allowed_format as $index => $format){
                        $allowed_format_att .= ($index > 0 ? ', ' : '');
                        $allowed_format_str .= ($index > 0 ? ', ' : '');
                        $allowed_format_att .= '"' . $format. '"';
                        $allowed_format_str .= $format;
                      }
                    ?>
                    
                    <?php if(strlen(trim($contact[0]->contact_file)) > 0){ ?>
                      <?= $contact[0]->contact_file; ?>
                    <?php } ?>
                    <input type="file" class="file" name="contact_file" id="contact_file" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='[<?= $allowed_format_att; ?>]'/>
                    <p class="hint-text"><small>*(Allowed format: <?= $allowed_format_str; ?>. Leave this field blank if you don't want to upload file)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture upload (no thumbnail)</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if(strlen(trim($contact[0]->contact_pic)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/contact/<?= $contact[0]->contact_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $contact[0]->contact_pic; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>
                    
                    <input type="file" class="file" name="contact_pic" id="contact_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. leave this field blank if you don't want to edit contact picture)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture upload (with thumbnail)</label>
                  <div class="col-md-8 col-xs-12">
                    <?php if(strlen(trim($contact[0]->contact_pic2)) > 0){ ?>
                      <div class="file-preview">
                        <a href="<?= base_url(); ?>admin_contact/deletePic/<?= $contact[0]->id_contact; ?>" class="close fileinput-remove text-right btn-need-confirmation" data-message="Are you sure want to remove this picture?" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/contact/<?= $contact[0]->contact_pic2; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $contact[0]->contact_pic2; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <?php } ?>
                    
                    <input type="file" class="file" name="contact_pic2" id="contact_pic2" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. leave this field blank if you don't want to edit contact picture)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Dropdown (without search)</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="contact_select_nosearch" class="full-width select_nosearch">
                      <option value="value1" <?= ($contact[0]->contact_select_nosearch == 'value1' ? 'selected' : ''); ?>>Option contact 1</option>
                      <option value="value2" <?= ($contact[0]->contact_select_nosearch == 'value2' ? 'selected' : ''); ?>>Option contact 2</option>
                      <option value="value3" <?= ($contact[0]->contact_select_nosearch == 'value3' ? 'selected' : ''); ?>>Option contact 3</option>
                      <option value="value4" <?= ($contact[0]->contact_select_nosearch == 'value4' ? 'selected' : ''); ?>>Option contact 4</option>
                      <option value="value5" <?= ($contact[0]->contact_select_nosearch == 'value5' ? 'selected' : ''); ?>>Option contact 5</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Dropdown (with search)</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="contact_select_withsearch" class="full-width select_withsearch">
                      <option value="value1" <?= ($contact[0]->contact_select_withsearch == 'value1' ? 'selected' : ''); ?>>Option contact 1</option>
                      <option value="value2" <?= ($contact[0]->contact_select_withsearch == 'value2' ? 'selected' : ''); ?>>Option contact 2</option>
                      <option value="value3" <?= ($contact[0]->contact_select_withsearch == 'value3' ? 'selected' : ''); ?>>Option contact 3</option>
                      <option value="value4" <?= ($contact[0]->contact_select_withsearch == 'value4' ? 'selected' : ''); ?>>Option contact 4</option>
                      <option value="value5" <?= ($contact[0]->contact_select_withsearch == 'value5' ? 'selected' : ''); ?>>Option contact 5</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Radio button</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="contact_radio" id="contact_radio1" <?= ($contact[0]->contact_radio == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="contact_radio1">yes</label>
                      <input type="radio" value="0" name="contact_radio" id="contact_radio0" <?= ($contact[0]->contact_radio == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="contact_radio0">No</label>
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
		$('#contact_form').bootstrapValidator({
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
				contact: {
					group: '.col-md-10',
					validators: {
						notEmpty: {
							message: 'Contact is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>