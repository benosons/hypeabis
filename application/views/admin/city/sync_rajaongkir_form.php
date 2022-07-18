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
          
          <?= form_open("Admin_city/submitSyncRajaongkir/" . $province_data[0]->id_province,array('class' => 'form-horizontal')); ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap" colspan="2"><center><b>ID City R.O.</b></center></th>
                    <th class="text-nowrap" colspan="2"><center><b>City type</b></center></th>
                    <th class="text-nowrap" colspan="2"><center><b>City name</b></center></th>
                    <th class="text-nowrap" rowspan="2" valign="center"><center><b>Sync Status</b></center></th>
                    <th class="text-nowrap" rowspan="2" valign="center"><center><b>Suggested Action</b></center></th>
                  </tr>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                  </tr>
                </thead>
                
                <tbody>
                  <? $x = 0; ?>
                  <? foreach($city_match as $item){ ?>
                    <tr>
                      <td class="text-nowrap" <?= (strtolower($item['local_id_city_rajaongkir']) != strtolower($item['ro_id_city']) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['local_id_city_rajaongkir']; ?>
                      </td>
                      <td class="text-nowrap" <?= (strtolower($item['local_id_city_rajaongkir']) != strtolower($item['ro_id_city']) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['ro_id_city']; ?>
                      </td>
                      <td class="text-nowrap" <?= (strtolower($item['local_city_type']) != strtolower($item['ro_city_type']) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['local_city_type']; ?>
                      </td>
                      <td class="text-nowrap" <?= (strtolower($item['local_city_type']) != strtolower($item['ro_city_type']) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['ro_city_type']; ?>
                      </td>
                      <td class="text-nowrap" <?= ((strtolower($item['local_city_name']) != strtolower($item['ro_city_name'])) || (strtolower($item['local_city_code']) != strtolower($item['ro_city_code'])) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['local_city_name']; ?><br/>
                        <?= $item['local_city_code']; ?>
                      </td>
                      <td class="text-nowrap" <?= ((strtolower($item['local_city_name']) != strtolower($item['ro_city_name'])) || (strtolower($item['local_city_code']) != strtolower($item['ro_city_code'])) ? "style='background:#ffeded'" : ""); ?>>
                        <?= $item['ro_city_name']; ?><br/>
                        <?= $item['ro_city_code']; ?>
                      </td>
                      <td class="text-nowrap"><?= $item['match_status_str']; ?></td>
                      <td class="text-nowrap">
                        <? if(isset($item['ro_id_city']) && $item['ro_id_city'] > 0){ ?>
                          <select name="city_sync_<?= $item['ro_id_city']; ?>" class="full-width select_withsearch">
                        <? } else { ?>
                          <select name="local_city_sync_<?= $item['local_id_city']; ?>" class="full-width select_withsearch">
                        <? } ?>
                          <option value="">-</option>
                          <? if($item['local_id_city'] == null || strlen(trim($item['local_id_city'])) <= 0){ ?>
                            <option value="insert" <?= ($item['ro_id_city'] > 0 && strlen(trim($item['local_id_city'])) <= 0 ? "selected" : ""); ?>>Insert as new city/district</option>
                          <? } ?>
                          <? if(isset($item['local_id_city']) && $item['local_id_city'] > 0){ ?>
                            <option value="delete_<?= $item['local_id_city']; ?>" <?= ($item['local_id_city'] > 0 && strlen(trim($item['ro_id_city'])) <= 0 ? "selected" : ""); ?>>Delete city/district</option>
                          <? } ?>
                          <? foreach($city as $ct){ ?>
                            <option value="<?= $ct->id_city; ?>" <?= ((($item['match_status'] == "match" || $item['match_status'] == "suggested") && $item['local_id_city'] == $ct->id_city) ? "selected" : ""); ?>>Update as <?= $ct->city_type; ?> <?= $ct->city_name; ?></option>
                          <? } ?>
                        </select>
                      </td>
                    </tr>
                    
                    <? $x++; ?>
                  <? } ?>
                </tbody>
              </table>
            </div>
            
            <div class="pull-right m-t-40">
              <input type="button" value="Back" class="btn" onclick="history.go(-1)" onclick="sync_clicked()" />
              <input type="submit" class="btn btn-complete" value="Syncronize City"/>
            </div>
        <?= form_close(); ?>

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