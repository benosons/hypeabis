<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Tanggal Transaksi</b></th>
                  <th class="text-nowrap"><b>Jumlah Iklan</b></th>
                  <th class="text-nowrap"><b>Total Harga</b></th>
                  <th class="text-nowrap"><b>Status Pembayaran</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order) { ?>
                  <tr>
                    <td class="text-nowrap">
                      <a href="<?php echo $base_url ?>/view/<?php echo $order->id_ads_order ?>" title="Lihat Transaksi" class="btn btn-xs btn-info"><span class="fa fa-eye"></span></a>
                      <a href="<?php echo $base_url ?>/invoice/<?php echo $order->id_ads_order ?>" title="Lihat Invoice" class="btn btn-xs btn-info"><span class="fa fa-file-o"></span></a>
                    </td>
                    <td class="text-nowrap"><?php echo date('d M Y', strtotime($order->checkout_date)) ?></td>
                    <td class="text-nowrap"><?php echo $order->ads_count ?></td>
                    <td class="text-nowrap">Rp. <?php echo number_format($order->total, 0, ',', '.') ?></td>
                    <td class="text-nowrap">
                      <?php if (in_array($order->payment_status, ['settlement', 'capture'])) { ?>
                        <label class="label label-success">Terbayar</label>
                      <?php } elseif ($order->payment_status == 'pending') { ?>
                        <label class="label label-warning">Menunggu Pembayaran</label>
                      <?php } elseif ($order->payment_status == 'deny') { ?>
                        <label class="label label-danger">Ditolak</label>
                      <?php } elseif ($order->payment_status == 'cancel') { ?>
                        <label class="label label-danger">Dibatalkan</label>
                      <?php } elseif ($order->payment_status == 'expire') { ?>
                        <label class="label label-default">Expired</label>
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <?php if (empty($orders)) { ?>
            <p class="m-t-40 text-center">Belum ada data iklan, silahkan <u><a href="<?php echo $base_url ?>/add_position">memesan iklan</a></u> atau <u><a href="<?php echo $base_url ?>/cart">menyelesaikan transaksi iklan</a></u> anda.</p>
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
