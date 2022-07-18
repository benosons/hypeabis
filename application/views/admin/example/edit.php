<? $this->load->view('admin/example/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit Example
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_example/saveEdit/" . $example[0]->id_example ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'example_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Normal Text</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="example_text" value="<?= $example[0]->example_text; ?>"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Textarea</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" name="example_textarea" rows="5"><?= $example[0]->example_textarea; ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Text Editor</label>
                  <div class="col-md-10 col-xs-12">
                    <? $content_file_path = base_url() . "assets/content/"; ?>
                    <textarea class="ckeditor form-control" name="example_texteditor" rows="10">
                      <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($example[0]->example_texteditor)); ?>
                    </textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">File upload (non picture)</label>
                  <div class="col-md-8 col-xs-12">
                    <?
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
                    
                    <? if(strlen(trim($example[0]->example_file)) > 0){ ?>
                      <?= $example[0]->example_file; ?>
                    <? } ?>
                    <input type="file" class="file" name="example_file" id="example_file" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='[<?= $allowed_format_att; ?>]'/>
                    <p class="hint-text"><small>*(Allowed format: <?= $allowed_format_str; ?>. Leave this field blank if you don't want to upload file)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture upload (no thumbnail)</label>
                  <div class="col-md-8 col-xs-12">
                    <? if(strlen(trim($example[0]->example_pic)) > 0){ ?>
                      <div class="file-preview">
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/example/<?= $example[0]->example_pic; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $example[0]->example_pic; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <? } ?>
                    
                    <input type="file" class="file" name="example_pic" id="example_pic" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. leave this field blank if you don't want to edit example picture)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Picture upload (with thumbnail)</label>
                  <div class="col-md-8 col-xs-12">
                    <? if(strlen(trim($example[0]->example_pic2)) > 0){ ?>
                      <div class="file-preview">
                        <a href="<?= base_url(); ?>admin_example/deletePic/<?= $example[0]->id_example; ?>" class="close fileinput-remove text-right btn-need-confirmation" data-message="Are you sure want to remove this picture?" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                        <div class="file-preview-thumbnails">
                          <div class="file-preview-frame">
                             <img src="<?= base_url(); ?>assets/example/<?= $example[0]->example_pic2; ?>" class="file-preview-image img-thumbnail img-fluid" title="<?= $example[0]->example_pic2; ?>" width="auto" style="max-height:100px">
                          </div>
                        </div>
                        <div class="clearfix"></div><div class="file-preview-status text-center text-complete"></div>
                      </div>
                    <? } ?>
                    
                    <input type="file" class="file" name="example_pic2" id="example_pic2" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "png"]'/>
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. leave this field blank if you don't want to edit example picture)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Dropdown (without search)</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="example_select_nosearch" class="full-width select_nosearch">
                      <option value="value1" <?= ($example[0]->example_select_nosearch == 'value1' ? 'selected' : ''); ?>>Option example 1</option>
                      <option value="value2" <?= ($example[0]->example_select_nosearch == 'value2' ? 'selected' : ''); ?>>Option example 2</option>
                      <option value="value3" <?= ($example[0]->example_select_nosearch == 'value3' ? 'selected' : ''); ?>>Option example 3</option>
                      <option value="value4" <?= ($example[0]->example_select_nosearch == 'value4' ? 'selected' : ''); ?>>Option example 4</option>
                      <option value="value5" <?= ($example[0]->example_select_nosearch == 'value5' ? 'selected' : ''); ?>>Option example 5</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Dropdown (with search)</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="example_select_withsearch" class="full-width select_withsearch">
                      <option value="value1" <?= ($example[0]->example_select_withsearch == 'value1' ? 'selected' : ''); ?>>Option example 1</option>
                      <option value="value2" <?= ($example[0]->example_select_withsearch == 'value2' ? 'selected' : ''); ?>>Option example 2</option>
                      <option value="value3" <?= ($example[0]->example_select_withsearch == 'value3' ? 'selected' : ''); ?>>Option example 3</option>
                      <option value="value4" <?= ($example[0]->example_select_withsearch == 'value4' ? 'selected' : ''); ?>>Option example 4</option>
                      <option value="value5" <?= ($example[0]->example_select_withsearch == 'value5' ? 'selected' : ''); ?>>Option example 5</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Radio button</label>
                  <div class="col-md-8 col-xs-12">
                    <div class="radio radio-complete">
                      <input type="radio" value="1" name="example_radio" id="example_radio1" <?= ($example[0]->example_radio == '1' ? 'checked="checked"' : ''); ?>>
                      <label for="example_radio1">yes</label>
                      <input type="radio" value="0" name="example_radio" id="example_radio0" <?= ($example[0]->example_radio == '0' ? 'checked="checked"' : ''); ?>>
                      <label for="example_radio0">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Date picker</label>
                  <div class="col-md-4 col-xs-12">
                    
                    
                    
                    <input type="text" class="form-control datepicker-component" name="example_datepicker"/>
                  </div>
                  <div class="col-md-4 col-xs-12">
                    <input type="text" class="form-control datepicker-component-disablepast" name="example_datepicker_disablepast"/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Date range picker</label>
                  <div class="col-md-6">
                    <div class="input-daterange input-group datepicker-range">
                      <input type="text" class="form-control datepicker-range-start" name="example_daterangepicker_start" />
                      <div class="input-group-addon transparent">To</div>
                      <input type="text" class="form-control datepicker-range-finish" name="example_daterangepicker_finish" />
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Month picker</label>
                  <div class="col-md-4 col-xs-12">
                    <input type="text" class="form-control monthpicker" name="example_monthpicker"/>
                  </div>
                  <div class="col-md-4 col-xs-12">
                    <input type="text" class="form-control monthpicker-disablepast" name="example_monthpicker_disablepast"/>
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
		$('#example_form').bootstrapValidator({
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
				example: {
					group: '.col-md-10',
					validators: {
						notEmpty: {
							message: 'Example is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>