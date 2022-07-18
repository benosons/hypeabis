<?php $search_param = $this->session->userdata('search_user'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_user/submitSearch', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>

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
              <option value="article_desc" <?= ($search_param['sort_by'] == "article_desc" ? "selected" : ""); ?>>Most active</option>
              <option value="point_desc" <?= ($search_param['sort_by'] == "point_desc" ? "selected" : ""); ?>>Highest point</option>
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
            <input type="radio" value="all" name="status" id="status_all" <?= ($search_param['status'] != '1'  && $search_param['status'] != '0' ? 'checked="checked"' : ''); ?>>
            <label for="status_all">All</label>
            <input type="radio" value="1" name="status" id="status_1" <?= ($search_param['status'] == '1' ? 'checked="checked"' : ''); ?>>
            <label for="status_1">Active</label>
            <input type="radio" value="0" name="status" id="status_0" <?= ($search_param['status'] == '0' ? 'checked="checked"' : ''); ?>>
            <label for="status_0">Banned</label>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Confirm email : </label>
        <div class="col-md-10">
          <div class="radio radio-complete">
            <input type="radio" value="all" name="confirm_email" id="confirm_email_all" <?= ($search_param['confirm_email'] != '1'  && $search_param['confirm_email'] != '0' ? 'checked="checked"' : ''); ?>>
            <label for="confirm_email_all">All</label>
            <input type="radio" value="1" name="confirm_email" id="confirm_email_1" <?= ($search_param['confirm_email'] == '1' ? 'checked="checked"' : ''); ?>>
            <label for="confirm_email_1">Done</label>
            <input type="radio" value="0" name="confirm_email" id="confirm_email_0" <?= ($search_param['confirm_email'] == '0' ? 'checked="checked"' : ''); ?>>
            <label for="confirm_email_0">Need confirmation</label>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Member internal : </label>
        <div class="col-md-10">
          <div class="radio radio-complete">
            <input type="radio" value="all" name="is_internal" id="is_internal_all" <?= ($search_param['is_internal'] != '1'  && $search_param['confirm_email'] != '0' ? 'checked="checked"' : ''); ?>>
            <label for="is_internal_all">All</label>
            <input type="radio" value="1" name="is_internal" id="is_internal_1" <?= ($search_param['is_internal'] == '1' ? 'checked="checked"' : ''); ?>>
            <label for="is_internal_1">Yes</label>
            <input type="radio" value="0" name="is_internal" id="is_internal_0" <?= ($search_param['is_internal'] == '0' ? 'checked="checked"' : ''); ?>>
            <label for="is_internal_0">No</label>
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
          <option value="name" <?= ($search_param['search_by'] == "name" ? "selected" : ""); ?>>Name</option>
          <option value="email" <?= ($search_param['search_by'] == "email" ? "selected" : ""); ?>>Email</option>
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

        <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect" id="search_toggle_advanced" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '0' ? 'style="display:none"' : ''); ?>>
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