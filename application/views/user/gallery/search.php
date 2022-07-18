<?php $search_param = $this->session->userdata('search_content'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('user_content/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    
    <div id="search_wrapper" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
      <div class="form-group" style="display:none;">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Tampilkan:</label>
          <div class="col-md-3 m-b-5">
            <div class="input-group transparent">
              <input class="form-control" type="text" name="per_page" value="<?= ($search_param['per_page']); ?>">
              <div class="input-group-append">
                <span class="input-group-text transparent">data / halaman</span>
              </div>
            </div>
          </div>
          <div class="col-md-3 m-b-5">
            <div class="input-group transparent">
              <div class="input-group-prepend">
                <span class="input-group-text transparent">Urutkan</span>
              </div>
              <select class="full-width select_nosearch" name="sort_by">
                <option value="default" <?= ($search_param['sort_by'] == "default" ? "selected" : ""); ?>>Default</option>
                <option value="newest" <?= ($search_param['sort_by'] == "newest" ? "selected" : ""); ?>>Terbaru</option>
                <option value="oldest" <?= ($search_param['sort_by'] == "oldest" ? "selected" : ""); ?>>Terlama</option>
                <option value="name_asc" <?= ($search_param['sort_by'] == "name_asc" ? "selected" : ""); ?>>Alphabetical (A-Z)</option>
                <option value="name_desc" <?= ($search_param['sort_by'] == "name_desc" ? "selected" : ""); ?>>Alphabetical (Z-A)</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Status : </label>
          <div class="col-md-10">
            <div class="radio radio-complete">
              <input type="radio" value="all" name="content_status" id="content_status_all" <?= ($search_param['content_status'] != 'publish'  && $search_param['content_status'] != 'unpublish' ? 'checked="checked"' : ''); ?>>
              <label for="content_status_all">Semua</label>
              <input type="radio" value="publish" name="content_status" id="content_status_publish" <?= ($search_param['content_status'] == 'publish' ? 'checked="checked"' : ''); ?>>
              <label for="content_status_publish">Tayang</label>
              <input type="radio" value="unpublish" name="content_status" id="content_status_unpublish" <?= ($search_param['content_status'] == 'unpublish' ? 'checked="checked"' : ''); ?>>
              <label for="content_status_unpublish">Menunggu approval</label>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Kategori : </label>
          <div class="col-md-6">
            <select class="full-width select_withsearch" name="category">
              <option value="all" <?= ($search_param['category'] == 'all' ? 'selected' : ''); ?>>Semua</option>
              <?php foreach($categories as $item){ ?>
                <option value="<?= $item['id_category']; ?>" <?= ($search_param['category'] == $item['id_category'] ? "selected" : ""); ?>><?= $item['category_name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="row">
          <label class="col-md-2 control-label text-right sm-text-left m-b-5">Publish Date : </label>
          <div class="col-md-3">
           <div class="input-group tranparent">
              <div class="input-group-prepend">
                <span class="input-group-text transparent">Mulai</span>
              </div>
              <input type="text" class="form-control datepicker-component" id="datepicker-component" name="start_date" value="<?= $search_param['start_date']; ?>" />
            </div>
          </div>
          <div class="col-md-3">
            <div class="input-group transparent">
              <div class="input-group-prepend">
                <span class="input-group-text transparent">Sampai</span>
              </div>
              <input type="text" class="form-control datepicker-component" id="datepicker-component" name="finish_date" value="<?= $search_param['finish_date']; ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Pencarian:</label>
        <div class="col-md-4 m-b-5">  
          <select class="full-width select_nosearch" name="search_by">
            <option value="title" <?= ($search_param['search_by'] == "title" ? "selected" : ""); ?>>Judul</option>
            <option value="short_desc" <?= ($search_param['search_by'] == "short_desc" ? "selected" : ""); ?>>Deskripsi singkat</option>
            <option value="content" <?= ($search_param['search_by'] == "content" ? "selected" : ""); ?>>Konten</option>
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
          <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-search"></i> Pencarian</button>
          
          <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect" id="search_toggle_advanced"<?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '0' ? 'style="display:none"' : ''); ?>>
            &nbsp;&nbsp; <i class="fa fa-chevron-circle-down"></i> &nbsp;Pencarian lengkap
          </a>
          <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect" id="search_toggle_simple" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
            &nbsp;&nbsp; <i class="fa fa-chevron-circle-up"></i> &nbsp;Pencarian cepat
          </a>
          <input type="hidden" id="search_collapsed" name="search_collapsed" value="<?= ($search_param['search_collapsed']); ?>" />
        </div>
      </div>
    </div>
  <?= form_close(); ?>
</div>