<?php $this->load->view('user/content/sub_header'); ?>
<?php $tab_id = $this->uri->segment(3); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <?php if (strtolower($this->uri->segment(2)) == 'search') { ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
          <?php } ?>

          <?= $this->session->flashdata('message'); ?>

          <!-- Start Search Bar -->
          <?php $this->load->view('user/content/search'); ?>
          <!-- End Search Bar -->

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Judul</b></th>
                  <th class="text-nowrap"><b>Kategori</b></th>
                  <th class="text-nowrap"><b>Tanggal Update</b></th>
                  <th class="text-nowrap" width="175px;"><b>Published</b></th>
                  <th class="text-nowrap"><b><i class="fa fa-eye"></i></b></th>
                  <th class="text-nowrap"><b><i class="fa fa-comment"></i></b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach ($content as $item) { ?>
                  <tr>
                    <td class="text-nowrap">
                      <?php if ($item->edit_id_admin && $item->edit_id_admin != $this->session->userdata('id_admin')) { ?>
                        <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-pencil"></span></button>
                        <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-times"></span></button>
                      <?php } else { ?>
                        <a href="<?= base_url(); ?>user_content/edit/<?= $item->id_content; ?>" title="Edit Content" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                        <a href="<?= base_url(); ?>user_content/delete/<?= $item->id_content; ?>" title="Delete Content" class="btn btn-xs btn-info btn-need-confirmation" data-message="Anda yakin ingin menghapus artikel ini?"><span class="fa fa-times"></span></a>
                      <?php } ?>
                      <a href="<?= base_url(); ?>read/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>" title="Preview" class="btn btn-xs btn-info" target="_blank"><span class="fa fa-eye"></span></a>
                    </td>
                    <td>
                      <b><?= $item->title; ?></b><br />
                      <?= base_url(); ?>read/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>
                    </td>
                    <td><?= (strlen(trim($item->category_name)) > 0 ? $item->category_name : '-'); ?></td>
                    <td><?= date('d-M-Y, H:i', strtotime($item->submit_date)); ?></td>
                    <td>
                      <?php if ($item->content_status == '1') { ?>
                        <label class="label label-success">Published</label>
                      <?php } else if ($item->content_status == '0') { ?>
                        <label class="label label-warning">Menunggu Approval</label>
                      <?php } else { ?>
                        <label class="label label-default">Draft</label>
                      <?php } ?>
                    </td>
                    <td><?= number_format(ceil($item->read_count), 0, ',', '.'); ?></td>
                    <td><?= number_format($item->comment_count, 0, ',', '.'); ?></td>
                  </tr>
                  <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <?php if ($x == 0) { ?>
            <p class="m-t-40">Belum ada data artikel.</p>
          <?php } ?>

          <?= $this->pagination->create_links(); ?>

        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>

  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
