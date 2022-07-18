<? $this->load->view('admin/subdistrict/sub_header'); ?>

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
            Sync subdistrict with Rajaongkir API
          </h4>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open("admin_subdistrict/submitProvinceAndCitySyncRajaongkir",array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'subdistrict_form', 'autocomplete' => 'off')); ?>
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Province</label>
                  <div class="col-md-8 col-xs-12">
                    <select name="province" class="full-width select_withsearch province_select" id="province_select" onchange="updateCity()">
                      <option value="">- choose province -</option>
                      <? foreach($province as $item): ?>
                        <option value="<?= $item->id_province; ?>"><?= $item->province_name; ?></option>
                      <? endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">City / District</label>
                  <div class="col-md-8 col-xs-12">
                    <div id="loader_wrapper"></div>
                    <select name="city" class="full-width select_withsearch city_select" id="city_select">
                      <option value="">- choose city / district -</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group sync_loader" style="display:none;">
                <div class="row">
                  <label class="col-md-2 control-label">&nbsp;</label>
                  <div class="col-md-10">
                    <span><img src='<?= base_url(); ?>files/backend/img/ajax-loader.gif'/> Syncronizing our database with Rajaongkir.com sub district data... This process may take a while... </span>
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
          
          <?= form_open("Admin_subdistrict/submitSyncRajaongkir/" . $this->uri->segment(3) . "/".$this->uri->segment(4),array('class' => 'form-horizontal')); ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap" rowspan="2"><center><b>ID</b></center></th>
                    <th class="text-nowrap" colspan="2"><center><b>ID R.O.</b></center></th>
                    <th class="text-nowrap" colspan="2"><center><b>Subdistrict name</b></center></th>
                    <th class="text-nowrap" rowspan="2" valign="center"><center><b>Status</b></center></th>
                    <th class="text-nowrap" rowspan="2" valign="center"><center><b>Action</b></center></th>
                  </tr>
                  <tr class="bg-master-lighter">
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                    <th class="text-nowrap"><center><b>Local</b></center></th>
                    <th class="text-nowrap"><center><b>R.O.</b></center></th>
                  </tr>
                </thead>
                
                <tbody>
                  <? $x = 0; ?>
                  <? foreach($city_data as $item){ ?>
                  
                    <tr>
                      <td colspan="11">
                        &nbsp;&nbsp;<b><?= strtoupper($item->city_type); ?> <?= strtoupper($item->city_name); ?></b>
                      </td>
                    </tr>
                    
                    <? if(isset($subdistrict_data[$x]) && is_array($subdistrict_data[$x]) && count($subdistrict_data[$x])){ ?>
                      <? foreach($subdistrict_data[$x] as $subdistrict){ ?>
                        <tr>
                          <?
                            $subdistrict['local_id_subdistrict'] = isset($subdistrict['local_id_subdistrict']) ? $subdistrict['local_id_subdistrict'] : "";
                            $subdistrict['local_id_subdistrict_rajaongkir'] = isset($subdistrict['local_id_subdistrict_rajaongkir']) ? $subdistrict['local_id_subdistrict_rajaongkir'] : "";
                            $subdistrict['subdistrict_id'] = isset($subdistrict['subdistrict_id']) ? $subdistrict['subdistrict_id'] : "";
                            $subdistrict['local_id_city_rajaongkir'] = isset($subdistrict['local_id_city_rajaongkir']) ? $subdistrict['local_id_city_rajaongkir'] : "";
                            $subdistrict['city_id'] = isset($subdistrict['city_id']) ? $subdistrict['city_id'] : "";
                            $subdistrict['local_subdistrict_name'] = isset($subdistrict['local_subdistrict_name']) ? $subdistrict['local_subdistrict_name'] : "";
                            $subdistrict['subdistrict_name'] = isset($subdistrict['subdistrict_name']) ? $subdistrict['subdistrict_name'] : "";
                            $subdistrict['local_subdistrict_code'] = isset($subdistrict['local_subdistrict_code']) ? $subdistrict['local_subdistrict_code'] : "";
                            $subdistrict['subdistrict_code'] = isset($subdistrict['subdistrict_code']) ? $subdistrict['subdistrict_code'] : "";
                            $subdistrict['match_status_str'] = isset($subdistrict['match_status_str']) ? $subdistrict['match_status_str'] : "";
                          ?>
                          
                          <td class="text-nowrap" <?= ($subdistrict['local_id_subdistrict'] > 0 ? "" : "style='background:#ffeded'") ?>><?= $subdistrict['local_id_subdistrict']; ?></td>
                          <td class="text-nowrap" <?= (strtolower($subdistrict['local_id_subdistrict_rajaongkir']) != strtolower($subdistrict['subdistrict_id']) ? "style='background:#ffeded'" : ""); ?>><?= $subdistrict['local_id_subdistrict_rajaongkir']; ?></td>
                          <td class="text-nowrap" <?= (strtolower($subdistrict['local_id_subdistrict_rajaongkir']) != strtolower($subdistrict['subdistrict_id']) ? "style='background:#ffeded'" : ""); ?>><?= $subdistrict['subdistrict_id']; ?></td>
                          
                          <td class="text-nowrap" <?= (strtolower($subdistrict['local_subdistrict_name']) != strtolower($subdistrict['subdistrict_name']) ? "style='background:#ffeded'" : ""); ?>>
                            <?= $subdistrict['local_subdistrict_name']; ?><br/>
                            <?= $subdistrict['local_subdistrict_code']; ?>
                          </td>
                          <td class="text-nowrap" <?= (strtolower($subdistrict['local_subdistrict_name']) != strtolower($subdistrict['subdistrict_name']) ? "style='background:#ffeded'" : ""); ?>>
                            <?= $subdistrict['subdistrict_name']; ?><br/>
                            <?= $subdistrict['subdistrict_code']; ?>
                          </td>
                          
                          <td class="text-nowrap"><?= $subdistrict['match_status_str']; ?></td>
                          <td class="text-nowrap">
                            <? if(isset($subdistrict['subdistrict_id']) && $subdistrict['subdistrict_id'] > 0){ ?>
                              <select name="subdistrict_sync_<?= $subdistrict['subdistrict_id']; ?>" class="full-width select_withsearch">
                            <? } else { ?>
                              <select name="local_subdistrict_sync_<?= $subdistrict['local_id_subdistrict']; ?>" class="full-width select_withsearch">
                            <? } ?>
                              <option value="">-</option>
                              <? if($subdistrict['local_id_subdistrict'] == null || strlen(trim($subdistrict['local_id_subdistrict'])) <= 0){ ?>
                                <option value="insert" <?= ($subdistrict['subdistrict_id'] > 0 && strlen(trim($subdistrict['local_id_subdistrict'])) <= 0 ? "selected" : ""); ?>>Insert as new sub district</option>
                              <? } ?>
                              <? if(isset($subdistrict['local_id_subdistrict']) && $subdistrict['local_id_subdistrict'] > 0){ ?>
                                <option value="delete_<?= $subdistrict['local_id_subdistrict']; ?>" <?= ($subdistrict['local_id_subdistrict'] > 0 && strlen(trim($subdistrict['subdistrict_id'])) <= 0 ? "selected" : ""); ?>>delete sub district</option>
                              <? } ?>
                              <? foreach($item->subdistrict_local as $sb): ?>
                                <option value="<?= $sb->id_subdistrict; ?>" <?= ((($subdistrict['match_status'] == "match" || $subdistrict['match_status'] == "suggested") && $subdistrict['local_id_subdistrict'] == $sb->id_subdistrict) ? "selected" : ""); ?>>Update - <?= $sb->subdistrict_name; ?></option>
                              <? endforeach; ?>
                            </select>
                          </td>
                        </tr>
                      <? } ?>
                    <? } ?>
                    
                    <? $x++; ?>
                  <? } ?>
                </tbody>
              </table>
            </div>
            
            <div class="pull-right m-t-40">
              <input type="button" value="Back" class="btn" onclick="history.go(-1)" />
              <input type="submit" class="btn btn-complete" value="Syncronize Subdistrict"/>
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
    
  function updateCity(){
		$('#loader_wrapper').empty();
		$('#loader_wrapper').html("<img src='<?= base_url(); ?>files/backend/img/ajax-loader.gif'/> Loading...");
		
		var province = $('#province_select').val();
		
		if(province > 0){
			$.ajax({
				'url' : '<?= base_url(); ?>' + 'admin_city/getCityByIDProvince',
				'type' : 'POST', //the way you want to send data to your URL
				'data' : {
          '<?= $this->security->get_csrf_token_name(); ?>' : '<?= $this->security->get_csrf_hash(); ?>',
					'id_province' : province
				},
				'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
					// if the request success..
					var obj = JSON.parse(data); // parse data from json to object..

					// if status not success, show message..
					if(obj.status == 'success'){
						$('#city_select').empty();
						$('#city_select').append("<option value=''>- choose city / district -</option>").trigger('change');
						
						$.each(obj.city_data, function(key, city) {
							$('#city_select').append("<option value='" + city.id_city + "'>" + city.city_type + " " + city.city_name + "</option>");
						});
					}
					else{
						$('#city_select').empty();
						$('#city_select').append("<option value=''>- choose city / district -</option>").trigger('change');
					}
				},
				'complete' : function(){
					$('#loader_wrapper').empty();
				}
			});
		}
		else{
			$('#city_select').empty();
			$('#city_select').append("<option value=''>- choose city / district -</option>").trigger('change');
			$('#loader_wrapper').empty();
		}
	}
</script>