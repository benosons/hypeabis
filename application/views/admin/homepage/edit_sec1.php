<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th class="text-nowrap" width="15%"><b>Action</b></th>
        <th class="text-nowrap"><b>Judul</b></th>
        <th class="text-nowrap"><b>Kategori</b></th>
        <th class="text-nowrap"><b>Tanggal update</b></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($trending_article as $item){ ?>
        <tr>
          <td class="text-nowrap">
            <a href="<?= base_url(); ?>admin_homepage/deleteTrending/<?= $item->id_content; ?>" title="Remove from this section" class="btn btn-xs btn-info btn-need-confirmation" data-message="Anda yakin ingin menghapus konten ini dari homepage?"><span class="fa fa-times"></span></a>
          </td>
          <td>
            <b><?= $item->title; ?></b><br/>
            <?= base_url(); ?>content/article/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>
          </td>
          <td><?= (strlen(trim($item->category_name)) > 0 ? $item->category_name : '-'); ?></td>
          <td><?= date('d-M-Y H:i',strtotime($item->submit_date)); ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>