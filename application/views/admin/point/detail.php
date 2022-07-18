<?php $this->load->view('admin/point/sub_header'); ?>

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
            <?= form_open("admin_point/updatePoint",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'global_setting_form', 'autocomplete' => 'off')); ?>
              <?php foreach($points as $point){ ?>
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-4 control-label text-right sm-text-left"><?= $point->trigger_str; ?></label>
                    <div class="col-md-2">
                      <input class="form-control" type="number" name="point_<?= $point->id_point; ?>" required="yes" value="<?= $point->point; ?>"/>
                    </div>
                    <?php if(isset($point->trigger_str_min) && strlen(trim($point->trigger_str_min)) > 0){ ?>
                      <label class="col-md-4 control-label text-right sm-text-left"><?= $point->trigger_str_min; ?></label>
                      <div class="col-md-2">
                        <input class="form-control" type="number" name="point_min_<?= $point->id_point; ?>" required="yes" value="<?= $point->point_min; ?>"/>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-4 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-6">
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