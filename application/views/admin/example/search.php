<? $search_param = $this->session->userdata('search_example'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_example/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    
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
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Example Radio : </label>
          <div class="col-md-10">
            <div class="radio radio-complete">
              <input type="radio" value="all" name="example_radio" id="example_radio_all" <?= ($search_param['example_radio'] != '1'  && $search_param['example_radio'] != '0' ? 'checked="checked"' : ''); ?>>
              <label for="example_radio_all">All</label>
              <input type="radio" value="1" name="example_radio" id="example_radio_1" <?= ($search_param['example_radio'] == '1' ? 'checked="checked"' : ''); ?>>
              <label for="example_radio_1">Yes</label>
              <input type="radio" value="0" name="example_radio" id="example_radio_0" <?= ($search_param['example_radio'] == '0' ? 'checked="checked"' : ''); ?>>
              <label for="example_radio_0">No</label>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Example select : </label>
          <div class="col-md-6">
            <select class="full-width select_withsearch" name="example_select_withsearch">
              <option value="all" <?= ($search_param['example_select_withsearch'] == 'all' ? 'selected' : ''); ?>>All</option>
              <option value="0" <?= ($search_param['example_select_withsearch'] == '0' ? 'selected' : ''); ?>>-</option>
              <option value="value1" <?= ($search_param['example_select_withsearch'] == 'value1' ? 'selected' : ''); ?>>Option example 1</option>
              <option value="value2" <?= ($search_param['example_select_withsearch'] == 'value2' ? 'selected' : ''); ?>>Option example 2</option>
              <option value="value3" <?= ($search_param['example_select_withsearch'] == 'value3' ? 'selected' : ''); ?>>Option example 3</option>
              <option value="value4" <?= ($search_param['example_select_withsearch'] == 'value4' ? 'selected' : ''); ?>>Option example 4</option>
              <option value="value5" <?= ($search_param['example_select_withsearch'] == 'value5' ? 'selected' : ''); ?>>Option example 5</option>
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
            <option value="example_text" <?= ($search_param['search_by'] == "example_text" ? "selected" : ""); ?>>Example text</option>
            <option value="example_textarea" <?= ($search_param['search_by'] == "example_textarea" ? "selected" : ""); ?>>Example textarea</option>
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