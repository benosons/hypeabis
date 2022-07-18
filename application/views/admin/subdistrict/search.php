<? $search_param = $this->session->userdata('search_subdistrict'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_subdistrict/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    
    <div id="search_wrapper" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Show:</label>
          <div class="col-md-3 m-b-5">
            <div class="input-group transparent">
              <input class="form-control" type="text" name="per_page" value="<?= ($search_param['per_page']); ?>">
              <div class="input-group-append">
                <span class="input-group-text transparent">data / page</span>
              </div>
            </div>
          </div>
          <div class="col-md-3 m-b-5">
            <div class="input-group transparent">
              <div class="input-group-prepend">
                <span class="input-group-text transparent">Sort by</span>
              </div>
              <select class="full-width select_nosearch" name="sort_by">
                <option value="default" <?= ($search_param['sort_by'] == "default" ? "selected" : ""); ?>>Default</option>
                <option value="newest" <?= ($search_param['sort_by'] == "newest" ? "selected" : ""); ?>>Newest</option>
                <option value="oldest" <?= ($search_param['sort_by'] == "oldest" ? "selected" : ""); ?>>Oldest</option>
                <option value="name_asc" <?= ($search_param['sort_by'] == "name_asc" ? "selected" : ""); ?>>Alphabetical (A-Z)</option>
                <option value="name_desc" <?= ($search_param['sort_by'] == "name_desc" ? "selected" : ""); ?>>Alphabetical (Z-A)</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Province : </label>
          <div class="col-md-6">
            <div class="radio radio-complete">
              <select name="province" class="full-width select_withsearch province_select_search" id="province_select_search" onchange="updateCitySearch()">
                <? if($search_param['province'] == 'all'){ ?>
                  <option value="all" selected>All</option>
                <? } else { ?>
                  <option value="all">All</option>
                <? } ?>
                
                <? foreach($province as $item){ ?>
                  <option value="<?= $item->id_province; ?>" <?= ($search_param['province'] == $item->id_province ? "selected" : ""); ?>><?= $item->province_name; ?></option>
                <? } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">City : </label>
          <div class="col-md-6">
            <div id="loader_wrapper"></div>
            <select name="city" class="full-width select_withsearch city_select_search" id="city_select_search">
              <? if($search_param['city'] == 'all'){ ?>
                <option value="all" selected>All</option>
              <? } else { ?>
                <option value="all">All</option>
              <? } ?>
              
              <? if($search_param['province'] != 'all' && $search_param['province'] > 0){ ?>
                <? foreach($city as $item){ ?>
                  <option value="<?= $item->id_city; ?>" <?= ($search_param['city'] == $item->id_city ? "selected" : ""); ?>><?= $item->city_type; ?> <?= $item->city_name; ?></option>
                <? } ?>
              <? } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Search:</label>
        <div class="col-md-4 m-b-5">  
          <select class="full-width select_nosearch" name="search_by">
            <option value="subdistrict_name" <?= ($search_param['search_by'] == "subdistrict_name" ? "selected" : ""); ?>>Subdistrict name</option>
          </select>
        </div>
        <div class="col-md-2 m-b-5">
          <select class="full-width select_nosearch" name="operator">
            <option value="like" <?= ($search_param['operator'] == "like" ? "selected" : ""); ?>>=</option>
            <option value="not like" <?= ($search_param['operator'] == "not like" ? "selected" : ""); ?>>!=</option>
          </select>
        </div>
        <div class="col-md-4 m-b-5">
          <input class="form-control" type="text" name="keyword" placeholder="Keyword..." value="<?= $search_param['keyword']; ?>">
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
        <div class="col-md-9">
          <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-search"></i> Search</button>
          
          <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect" id="search_toggle_advanced"<?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '0' ? 'style="display:none"' : ''); ?>>
            &nbsp;&nbsp; <i class="fa fa-chevron-circle-down"></i> &nbsp;Advanced search
          </a>
          <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect" id="search_toggle_simple" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
            &nbsp;&nbsp; <i class="fa fa-chevron-circle-up"></i> &nbsp;Quick search
          </a>
          <input type="hidden" id="search_collapsed" name="search_collapsed" value="<?= ($search_param['search_collapsed']); ?>" />
        </div>
      </div>
    </div>
  <?= form_close(); ?>
</div>

<script type="text/javascript">
	function updateCitySearch(){
		$('#loader_wrapper').empty();
		$('#loader_wrapper').html("<img src='<?= base_url(); ?>files/backend/img/ajax-loader.gif'/> Loading...");
		
		var province = $('#province_select_search').val();
    
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
						$('#city_select_search').empty();
						$('#city_select_search').append("<option value='all'>All</option>").trigger('change');
						
						$.each(obj.city_data, function(key, city) {
							$('#city_select_search').append("<option value='" + city.id_city + "'>" + city.city_type + " " + city.city_name + "</option>");
              console.log(city.city_type + ". " + city.city_name);
						});
					}
					else{
						$('#city_select_search').empty();
						$('#city_select_search').append("<option value='all'>All</option>").trigger('change');
					}
				},
				'complete' : function(){
					$('#loader_wrapper').empty();
				}
			});
		}
		else{
			$('#city_select_search').empty();
			$('#city_select_search').append("<option value='all'>All</option>").trigger('change');
			$('#loader_wrapper').empty();
		}
	}
</script>