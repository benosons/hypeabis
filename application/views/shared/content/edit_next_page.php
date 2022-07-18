<?php $user_type = $this->session->admin_logged_in ? 'admin' : 'user' ?>
<?php $this->load->view("{$user_type}/content/sub_header"); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit Halaman Artikel <?php echo $content->title ?>
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          <? $content_value = $this->session->flashdata('content_value'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?php echo form_open_multipart("{$user_type}_content/saveEditNextPage/{$page->id}", ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Halaman</label>
                  <div class="col-md-9 col-xs-12">
                    <input class="form-control" type="number" name="page_no" min="2" max="<?php echo $max_page_no ?>" value="<?php echo $form_value['page_no'] ?>" required/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Konten</label>
                  <div class="col-md-9 col-xs-12">
                    <?php $content_file_path = base_url() . 'assets/content/'; ?>
                    <textarea class="ckeditor form-control" name="content" rows="20" required><?php echo str_replace("##BASE_URL##", $content_file_path, html_entity_decode($form_value['content'])); ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-8"> <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                    <input class="btn btn-info sm-m-b-10" type="submit" name="preview" value="Preview"/>
										<a class="btn btn-default sm-m-b-10" href="<?php echo base_url() . $user_type ?>_content/edit/<?php echo $content->id_content ?>#next-page"><i class="fa fa-chevron-circle-left"></i> Back</a>
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
		$('#content_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				page_no: {
					validators: {
						notEmpty: {
							message: 'Halaman harus diisi.'
						}
					}
				},
				content: {
					validators: {
						notEmpty: {
							message: 'Konten artikel harus diisi. '
						}
					}
				},
			}
		});
	});
</script>
