<?php $user_type = $this->session->admin_logged_in ? 'admin' : 'user' ?>
<div class="row p-t-40 m-b-40">
  <div class="col-md-8">
    <h4 id="next-page" class="m-t-0 m-b-15 fw-600 text-heading-black">
      Halaman selanjutnya:
    </h4>
  </div>
  <div class="col-md-4">
    <!-- START card -->
    <div class="card card-transparent m-b-0">
      <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
        <a href="<?= base_url() . $user_type ?>_content/addNextpage/<?php echo $content[0]->id_content ?>" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Tambah konten halaman selanjutnya">
          <i class="fa fa-plus"></i>
        </a>
      </div>
    </div>
    <!-- END card -->
  </div>
</div>

<?php echo $this->session->flashdata('next_page_message'); ?>

<?php if (count($content[0]->pages) > 0): ?>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr class="">
          <th class="text-nowrap" width="120px"><b>Action</b></th>
          <th class="text-nowrap" width="100px"><b>Halaman</b></th>
          <th class="text-nowrap"><b>Konten</b></th>
        </tr>
      </thead>
      <tbody>
        <?php $content_file_path = base_url() . 'assets/content/'; ?>
        <?php foreach ($content[0]->pages as $page): ?>
        <tr>
          <td class="text-nowrap">
            <a href="<?php echo  base_url() . $user_type ?>_content/editNextpage/<?php echo $page->id ?>" title="Edit konten" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
            <a href="<?php echo  base_url() . $user_type ?>_content/deleteNextpage/<?php echo $page->id ?>" title="Delete konten" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete content?"><span class="fa fa-times"></span></a>
          </td>
          <td><?php echo $page->page_no ?></td>
          <td><?php echo word_limiter(strip_tags(html_entity_decode($page->content)), 50) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
<div class="alert alert-danger">Belum ada halaman selanjutnya.</div>
<?php endif; ?>
