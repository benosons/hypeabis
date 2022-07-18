<?php $this->load->view('user/gallery/sub_header'); ?>
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
          <?php $this->load->view('user/gallery/search'); ?>
          <!-- End Search Bar -->

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Judul Gallery</b></th>
                  <th class="text-nowrap"><b>Tanggal Submit</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap" width="175px;"><b>Shown</b></th>
                  <th class="text-nowrap" width="175px;"><b>Clicked</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach ($gallery as $item) { ?>
                  <tr>
                    <td class="text-nowrap">
                        <a href="<?= base_url(); ?>user_gallery/edit/<?= $item->id_galeri; ?>" title="Edit Virtual Gallery" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                        <a href="<?= base_url(); ?>user_gallery/delete/<?= $item->id_galeri; ?>" title="Delete Virtual Gallery" class="btn btn-xs btn-info btn-need-confirmation" data-message="Anda yakin ingin menghapus virtual gallery ini?"><span class="fa fa-times"></span></a>
                    
                      <!-- <a href="<?= base_url(); ?>read/<?= $item->id_galeri; ?>/<?= strtolower(url_title($item->judul_galeri)); ?>" title="Preview" class="btn btn-xs btn-info" target="_blank"><span class="fa fa-eye"></span></a> -->
                    </td>
                    <td>
                      <b><?= $item->judul_galeri; ?></b><br />
                      <?= base_url(); ?>read/<?= $item->id_galeri; ?>/<?= strtolower(url_title($item->judul_galeri)); ?>
                    </td>
                    <td><?= date('d-M-Y, H:i', strtotime($item->tgl_submit)); ?></td>
                    <td>
                      <?php if ($item->statuss == 'Y') { ?>
                        <label class="label label-success">Aktif</label>
                      <?php } else { ?>
                        <label class="label label-default">Tidak Aktif</label>
                      <?php } ?>
                    </td>
                    <td><?= number_format(ceil($item->read_count), 0, ',', '.'); ?></td>
                    <td><?= number_format($item->clicked_count, 0, ',', '.'); ?></td>
                  </tr>
                  <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <?php if ($x == 0) { ?>
            <p class="m-t-40">Belum ada data virtual gallery.</p>
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
