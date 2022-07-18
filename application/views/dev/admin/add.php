<? $this->load->view('dev/admin/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <!-- Start Search Bar --> 
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("dev_admin/saveAdmin",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'admin_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Admin picture:</label>
                  <div class="col-md-6">
                    <input type="file" class="file" name="file_admin" id="file_admin" data-show-upload="false" data-show-close="false" data-show-preview="false" data-allowed-file-extensions='["jpg", "jpeg", "png"]' />
                    <p class="hint-text"><small>*(Size Recommendation: <?= $this->admin_pic_width; ?>px x <?= $this->admin_pic_height; ?>px.)</small></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Name:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="name" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Username:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="username" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Email:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="email" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Contact number:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="contact_number" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Password:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="password" name="password" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Re-Type Password:</label>
                  <div class="col-md-8">
                    <input class="form-control" type="password" name="confirm_password" />
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit setting</button>
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