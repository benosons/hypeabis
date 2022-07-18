<?php $search_param = $this->session->userdata('search_dashboard'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open('admin_dashboard/submitSearch', ['role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off']); ?>

  <div class="form-group">
    <div class="row">
      <label class="col-md-2 control-label text-right sm-text-left m-b-5">Penulis : </label>
      <div class="col-md-6">
        <input type="hidden" name="author" id="author" class="full-width" value="<?php echo $search_param['author'] ?>">
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <label class="col-md-2 control-label text-right sm-text-left m-b-5">Editor : </label>
      <div class="col-md-6">
        <input type="hidden" name="admin" id="admin" class="full-width" value="<?php echo $search_param['admin'] ?>">
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
          <input type="text" class="form-control datepicker-range-start" name="start_date" value="<?= $search_param['start_date']; ?>" />
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group transparent">
          <div class="input-group-prepend">
            <span class="input-group-text transparent">Sampai</span>
          </div>
          <input type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?= $search_param['finish_date']; ?>" />
        </div>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
      <div class="col-md-9">
        <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-search"></i> Pencarian</button>
      </div>
    </div>
  </div>
  <?= form_close(); ?>
</div>

<script>
  $(document).ready(function() {
    $('#author').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: 'Semua',
      ajax: {
        dataType: 'json',
        url: '<?php echo base_url("admin_user/searchUserByKeyword"); ?>',
        delay: 800,
        quietMillis: 250,
        data: function(params, page) {
          return {
            q: params
          };
        },
        results: function(data, page) {
          return {
            results: data
          };
        }
      },
      initSelection: function(element, callback) {
        callback({
          id: '<?php echo ($search_param["author"] ?? '') ?>',
          text: '<?php echo ($author[0]->name ?? '') . " - (" . ($author[0]->email ?? '') . ")" ?>'
        })
      },
    });

    $('#admin').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: 'Semua',
      ajax: {
        dataType: 'json',
        url: '<?php echo base_url("admin_account/searchUserByKeyword"); ?>',
        delay: 800,
        quietMillis: 250,
        data: function(params, page) {
          return {
            q: params
          };
        },
        results: function(data, page) {
          return {
            results: data
          };
        }
      },
      initSelection: function(element, callback) {
        callback({
          id: '<?php echo ($search_param["admin"] ?? '') ?>',
          text: '<?php echo ($admin_name ?? '') ?>'
        })
      },
    });
  });
</script>