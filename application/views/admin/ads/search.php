<?php $search_param = $this->session->userdata('search_ads'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_ads/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
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
              <option value="newest" <?= ($search_param['sort_by'] == "newest" ? "selected" : ""); ?>>Tayang Terbaru</option>
              <option value="oldest" <?= ($search_param['sort_by'] == "oldest" ? "selected" : ""); ?>>Tayang Terlama</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Ads Type : </label>
        <div class="col-md-10">
          <div class="radio radio-complete">
            <input type="radio" value="all" name="ads_source" id="ads_source_all" <?= ($search_param['ads_source'] != 'builtin'  && $search_param['ads_source'] != 'googleads' ? 'checked="checked"' : ''); ?>>
            <label for="ads_source_all">All</label>
            <input type="radio" value="builtin" name="ads_source" id="ads_source_builtin" <?= ($search_param['ads_source'] == 'builtin' ? 'checked="checked"' : ''); ?>>
            <label for="ads_source_builtin">Built-in Ads</label>
            <input type="radio" value="googleads" name="ads_source" id="ads_source_googleads" <?= ($search_param['ads_source'] == 'googleads' ? 'checked="checked"' : ''); ?>>
            <label for="ads_source_googleads">Google Ads</label>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Placement : </label>
        <div class="col-md-6">
          <select class="full-width select_withsearch" name="id_adstype">
            <option value="all" <?= ($search_param['id_adstype'] == 'all' ? 'selected' : ''); ?>>All</option>
            <?php foreach($ads_types as $type){ ?>
              <option value="<?= $type->id_adstype; ?>" <?= ($search_param['id_adstype'] == $type->id_adstype ? 'selected' : ''); ?>>
                <?= $type->ads_name; ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    
    <div class="form-group" style="display:none;">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Search:</label>
        <div class="col-md-4 m-b-5">  
          <select class="full-width select_nosearch" name="search_by">
            <option value="ads_text" <?= ($search_param['search_by'] == "ads_text" ? "selected" : ""); ?>>Ads text</option>
            <option value="ads_textarea" <?= ($search_param['search_by'] == "ads_textarea" ? "selected" : ""); ?>>Ads textarea</option>
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
        </div>
      </div>
    </div>
  <?= form_close(); ?>
</div>