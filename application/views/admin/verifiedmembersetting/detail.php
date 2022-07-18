<?php $this->load->view('admin/verifiedmembersetting/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open('admin_verifiedmembersetting/update',array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'global_setting_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">Minimum Point</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="verified_member_point" value="<?php echo $form_value['verified_member_point'] ?>" required/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-md-3 control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-8 col-xs-12">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Update setting</button>
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
