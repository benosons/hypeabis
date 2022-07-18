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