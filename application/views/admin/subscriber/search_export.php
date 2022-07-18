<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_subscriber/submitExport', array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Sort by:</label>
        <div class="col-md-6 m-b-5">
          <select class="full-width select_nosearch" name="sort_by">
            <option value="default">Default</option>
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
            <option value="name_asc">Alphabetical (A-Z)</option>
            <option value="name_desc">Alphabetical (Z-A)</option>
          </select>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Subscribe date : </label>
        <div class="col-md-4">
         <div class="input-group tranparent">
            <div class="input-group-prepend">
              <span class="input-group-text transparent">Start from</span>
            </div>
            <input type="text" class="form-control" id="datepicker-component" name="start_date" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-group transparent">
            <div class="input-group-prepend">
              <span class="input-group-text transparent">Until</span>
            </div>
            <input type="text" class="form-control" id="datepicker-component" name="finish_date" />
          </div>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 control-label text-right sm-text-left m-b-5">Filter by:</label>
        <div class="col-md-4 m-b-5">  
          <select class="full-width select_nosearch" name="search_by">
            <option value="name">Name</option>
            <option value="email">Email</option>
          </select>
        </div>
        <div class="col-md-2 m-b-5">  
          <select class="full-width select_nosearch" name="operator">
            <option value="like">=</option>
            <option value="not like">!=</option>
          </select>
        </div>
        <div class="col-md-4 m-b-5">
          <input class="form-control" type="text" name="keyword" placeholder="Keyword...">
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <div class="row">
        <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
        <div class="col-md-9">
          <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-upload"></i> Export</button>
        </div>
      </div>
    </div>
  <?= form_close(); ?>
</div>