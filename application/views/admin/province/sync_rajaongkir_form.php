<? $this->load->view('admin/province/sub_header'); ?>

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
            Sync province with Rajaongkir API
          </h4>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <a href="<?= base_url(); ?>admin_province/startSyncRajaongkir" class="btn btn-complete" onclick="sync_clicked()">Sync All Province</a>
            <span class="sync_loader" style="display:none"><br/><br/><img src='<?= base_url(); ?>files/backend/img/ajax-loader.gif'/> Syncronizing our database with Rajaongkir.com province data... This process may take a while... </span>
          </div>
          
          <?= form_open("Admin_province/submitSyncRajaongkir",array('class' => 'form-horizontal')); ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap" colspan="2"><center><b>ID R.O.</b></center></th>
                    <th class="text-nowrap" colspan="2"><center><b>Province name</b></center></th>
                    <th class="text-nowrap" rowspan="2"><center><b>Match Status</b></center></th>
                    <th class="text-nowrap" rowspan="2"><center><b>Action</b></center></th>
                  </tr>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>Server</b></center></th>
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                  </tr>
                </thead>
                
                <tbody>
                  <? $x = 0; ?>
                  <? foreach($province_all as $item){ ?>
                  
                    <tr>
                      <td class="text-nowrap" <?= ($item['local_id_province_rajaongkir'] != $item['ro_id_province'] ? "style='background:#f9bbbb'" : ""); ?>>
                        <?= $item['local_id_province_rajaongkir']; ?>
                      </td>
                      <td class="text-nowrap" <?= ($item['local_id_province_rajaongkir'] != $item['ro_id_province'] ? "style='background:#f9bbbb'" : ""); ?>>
                        <?= $item['ro_id_province']; ?>
                      </td>
                      <td class="text-nowrap" <?= ($item['local_province_name'] != $item['ro_province_name'] ? "style='background:#f9bbbb'" : ""); ?>>
                        <?= $item['local_province_name']; ?><br/>
                        <?= $item['local_province_code']; ?>
                      </td>
                      <td class="text-nowrap" <?= ($item['local_province_name'] != $item['ro_province_name'] ? "style='background:#f9bbbb'" : ""); ?>>
                        <?= $item['ro_province_name']; ?><br/>
                        <?= $item['ro_province_code']; ?>
                      </td>
                      <td class="text-nowrap"><?= $item['match_status_str']; ?></td>
                      <td class="text-nowrap">
                        <? if(isset($item['ro_id_province']) && $item['ro_id_province'] > 0){ ?>
                          <select name="province_sync_<?= $item['ro_id_province']; ?>" class="full-width select_withsearch">
                        <? } else { ?>
                          <select name="local_province_sync_<?= $item['local_id_province']; ?>" class="full-width select_withsearch">
                        <? } ?>
                          <option value="">-</option>
                          <? if($item['local_id_province'] == null || strlen(trim($item['local_id_province'])) <= 0){ ?>
                            <option value="insert" <?= ($item['ro_id_province'] > 0 && strlen(trim($item['local_id_province'])) <= 0 ? "selected" : ""); ?>>Insert as new province</option>
                          <? } ?>
                          <? if(isset($item['local_id_province']) && $item['local_id_province'] > 0){ ?>
                            <option value="delete_<?= $item['local_id_province']; ?>" <?= ($item['local_id_province'] > 0 && strlen(trim($item['ro_id_province'])) <= 0 ? "selected" : ""); ?>>Delete province</option>
                          <? } ?>
                          <? foreach($province as $prov){ ?>
                            <option value="<?= $prov->id_province; ?>" <?= ((($item['match_status'] == "match" || $item['match_status'] == "suggested") && $item['local_id_province'] == $prov->id_province) ? "selected" : ""); ?>>Update as <?= $prov->province_name; ?></option>
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
              <input type="button" value="Back" class="btn" onclick="history.go(-1)" />
              <input type="submit" class="btn btn-complete" value="Sync Province"/>
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