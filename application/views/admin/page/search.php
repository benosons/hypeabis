<?php $search_param = $this->session->userdata('search_page'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_page/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    
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
      
      <div class="form-group" style="display:none;">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Status : </label>
          <div class="col-md-10">
            <div class="radio radio-complete">
              <input type="radio" value="all" name="page_status" id="page_status_all" <?= ($search_param['page_status'] != 'publish'  && $search_param['page_status'] != 'unpublish' ? 'checked="checked"' : ''); ?>>
              <label for="page_status_all">All</label>
              <input type="radio" value="publish" name="page_status" id="page_status_publish" <?= ($search_param['page_status'] == 'publish' ? 'checked="checked"' : ''); ?>>
              <label for="page_status_publish">Publish</label>
              <input type="radio" value="unpublish" name="page_status" id="page_status_unpublish" <?= ($search_param['page_status'] == 'unpublish' ? 'checked="checked"' : ''); ?>>
              <label for="page_status_unpublish">Unpublish</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Search:</label>
        <div class="col-md-4 m-b-5">  
          <select class="full-width select_nosearch" name="search_by">
            <option value="page_title" <?= ($search_param['search_by'] == "page_title" ? "selected" : ""); ?>>Page title</option>
            <option value="page_content" <?= ($search_param['search_by'] == "page_content" ? "selected" : ""); ?>>Page content</option>
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