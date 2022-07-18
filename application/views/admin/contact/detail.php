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
            <div class="form-horizontal" id="contact_form">
            
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Submit date</label>
                  <div class="col-md-4 col-xs-12">
                    <input class="form-control" type="text" name="submit_date" value="<?= date('d-M-Y H:i', strtotime($contact[0]->submit_date)); ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Subject</label>
                  <div class="col-md-8 col-xs-12">
                    <label class="label label-info"><?= $contact[0]->contact_title; ?></label>
                  </div>
                </div>
              </div>
            
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Name</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="name" value="<?= $contact[0]->name; ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Email</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="email" value="<?= $contact[0]->email; ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Contact number</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="contact_number" value="<?= $contact[0]->phone; ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Textarea</label>
                  <div class="col-md-8 col-xs-12">
                    <textarea class="form-control" name="contact_textarea" rows="5" readonly="yes" disabled><?= $contact[0]->message; ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
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