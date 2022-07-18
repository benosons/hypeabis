<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <?= $this->session->flashdata('message'); ?>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Tanggal</b></th>
                  <th class="text-nowrap"><b>Iklan</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap text-right"><b>Harga</b></th>
                  <th class="text-nowrap text-right"><b>Subtotal</b></th>
                  <th class="text-nowrap text-right"><b>Nominal Refund</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ads as $ad) { ?>
                  <?php $now = date('Y-m-d H:i:s'); ?>
                  <tr>
                    <td class="text-nowrap align-middle">
                      <?php if (strtotime($now) < strtotime($ad->start_date)) { ?>
                        <?php if (in_array($ad->status, ['-1', '0'])) { ?>
                          <a href="<?php echo $base_url ?>/edit/<?= $ad->id_ads_cancel; ?>" title="Ubah Pembatalan Iklan" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                          <a href="<?php echo $base_url ?>/delete/<?= $ad->id_ads_cancel; ?>" title="Batalkan Pembatalan Iklan" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete content?"><span class="fa fa-times"></span></a>
                        <?php } ?>
                      <?php } ?>
                    </td>
                    <td class="align-middle"><?= date('d-M-Y', strtotime($ad->request_date)); ?></td>
                    <td class="align-middle">
                      <b><?= $ad->ads_name; ?></b>
                      <br/><b>Mulai tayang: </b> <?= date('d-M-Y', strtotime($ad->start_date)); ?>
                      <br/><b>Selesai: </b> <?= date('d-M-Y', strtotime($ad->finish_date)); ?>
                    </td>
                    <td class="align-middle">
                      <?php if (in_array($ad->status, ['1', '2']) || strtotime($now) < strtotime($ad->start_date)) { ?>
                        <?php if ($ad->status === '2') { ?>
                          <label class="label label-success">Selesai</label>
                        <?php } elseif ($ad->status === '1') { ?>
                          <label class="label label-info">Diproses</label>
                        <?php } elseif ($ad->status === '0') { ?>
                          <label class="label label-warning">Menunggu Approval</label>
                        <?php } elseif ($ad->status === '-1') { ?>
                          <label class="label label-danger">Ditolak</label>
                        <?php } ?>
                      <?php } else { ?>
                        <label class="label label-default">Kadaluarsa</label>
                      <?php } ?>
                    </td>
                    <td class="text-right align-middle text-nowrap">Rp. <?php echo number_format($ad->price_per_day, 0, ',', '.') ?></td>
                    <td class="text-right align-middle text-nowrap">Rp. <?php echo number_format($ad->subtotal, 0, ',', '.') ?></td>
                    <td class="text-right align-middle text-nowrap">
                      <?php if ($ad->nominal) { ?>
                        Rp. <?php echo number_format($ad->nominal, 0, ',', '.') ?>
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <?php if (empty($ads)) { ?>
            <p class="m-t-40 text-center">Belum ada data pembatalan iklan.</p>
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
