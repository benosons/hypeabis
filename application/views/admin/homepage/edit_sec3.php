<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
  <?= form_open("admin_homepage/addFeaturedAuthor" ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'quotation_form', 'autocomplete' => 'off')); ?>
    <div class="form-group m-t-0 p-t-0">
      <div class="row">
        <div class="col-md-8">
          <div class="row">
            <label class="col-12 control-label text-left">Nama user / member:</label>
            <div class="col-12 m-b-10">
              <input type="hidden" class="full-width id_user" name="id_user" id="id_user" required="yes"/>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="row">
            <label class="col-12 control-label text-left">Urutan:</label>
            <div class="col-12 m-b-10">
              <input type="number" name="author_order" class="form-control" value="99" required="yes"/>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="row">
            <label class="col-12 control-label text-left hidden-xs">&nbsp;</label>
            <div class="col-12">
              <button class="btn btn-block btn-complete" type="submit">Tambah</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?= form_close(); ?>
</div>

<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th class="text-nowrap" width="15%"><b>Action</b></th>
        <th class="text-nowrap"><b>Nama Penulis</b></th>
        <th class="text-nowrap"><b>Urutan</b></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($featured_author as $item){ ?>
        <tr>
          <td class="text-nowrap">
            <a href="<?= base_url(); ?>admin_homepage/deleteFeaturedAuthor/<?= $item->id_user; ?>" title="Remove from this section" class="btn btn-xs btn-info btn-need-confirmation" data-message="Anda yakin ingin menghapus penulis ini dari section penulis pilihan di homepage?"><span class="fa fa-times"></span></a>
          </td>
          <td><?= $item->name; ?></td>
          <td><?= $item->author_order; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">
	$(document).ready(function() {
    $('.id_user').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: '- Input nama user / member -',
      /*multiple:true,*/
      ajax: {
        dataType: 'json',
        url: '<?= base_url(); ?>admin_user/searchUserByKeyword',
        delay: 800,
        quietMillis: 250,
        data: function (params, page) {
          return {
            q: params
          };
        },
        results: function (data, page) {
          return { 
            results: data 
          };
        }
      }
    });
  });
</script>