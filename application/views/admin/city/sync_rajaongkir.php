<? $this->load->view('admin/city/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
        
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Sync city with Rajaongkir API
          </h4>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open("admin_city/submitProvinceSyncRajaongkir",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'city_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Province</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="province" class="full-width select_withsearch">
                      <option value="">- choose province -</option>
                      <? foreach($province as $item){ ?>
                        <option value="<?= $item->id_province; ?>"><?= $item->province_name; ?></option>
                      <? } ?>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group sync_loader" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-8 col-xs-12">
                    <span><img src='<?= base_url(); ?>files/backend/img/ajax-loader.gif'/> Syncronizing our database with Rajaongkir.com city data... This process may take a while... </span>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                  <div class="col-md-9">
                    <button class="btn btn-complete sm-m-b-10" type="submit" onclick="sync_clicked()">Submit</button>
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
  function sync_clicked(){
    $('.sync_loader').show();
  }
</script>