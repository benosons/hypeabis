<?php $this->load->view('admin/contactsetting/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <?= $this->session->flashdata('message'); ?>
          
          <?php foreach($contactsetting as $contact){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
              <?= $contact->contact_title; ?>
            </h4>
            
            <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
              <?= form_open("admin_contactsetting/saveContactsetting/" . $contact->id_contactsetting ,array('class' => 'form-horizontal contactsetting_form', 'role' => 'form', 'id' => 'contactsetting_form', 'autocomplete' => 'off')); ?>
                <div class="form-group" style="display:none;">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Title</label>
                    <div class="col-md-8 col-xs-12">
                      <input class="form-control" type="text" name="contact_title" value="<?= $contact->contact_title; ?>"/>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Description</label>
                    <div class="col-md-8 col-xs-12">
                      <textarea class="form-control" name="contact_desc" rows="2"><?= $contact->contact_desc; ?></textarea>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left">Email notification</label>
                    <div class="col-md-8 col-xs-12">
                      <input class="form-control" type="email" name="contact_email" value="<?= $contact->contact_email; ?>" required="yes"/>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                    <div class="col-md-9">
                      <button class="btn btn-complete sm-m-b-10" type="submit">Update</button>
                    </div>
                  </div>
                </div>
              <?= form_close(); ?>
            </div>
            
          <?php } ?>

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
		$('.contactsetting_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
			}
		});
	});
</script>