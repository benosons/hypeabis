<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  <div class="row">
    <div class="col-12 mb-5 mb-lg-0">
      <!-- Toolbar -->
      <div class="d-flex justify-content-end d-print-none mb-4">
        <a class="btn btn-primary" href="javascript:;" onclick="window.print(); return false;">
          <i class="fa fa-print mr-1"></i> Print
        </a>
      </div>
      <!-- End Toolbar -->

      <!-- Card -->
      <div class="card card-lg mb-5">
        <div class="card-body">
          <div class="row justify-content-lg-between">
            <div class="col-sm order-2 order-sm-1 mb-3" style="padding-top: 10px;">
              <?php if(isset($global_data[0]->logo) && strlen(trim($global_data[0]->logo)) > 0){ ?>
                <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" height="69">
              <?php } else { ?>
                <img src="<?= base_url(); ?>files/backend/img/logo.png" alt="logo" class="avatar mb-2" data-src="<?= base_url(); ?>files/backend/img/logo.png" data-src-retina="<?= base_url(); ?>files/backend/img/logo_2x.png" height="20">
              <?php } ?>
            </div>

            <div class="col-sm-auto order-1 order-sm-2 text-sm-right mb-3">
              <div class="mb-3">
                <h2>Invoice #<?php echo $ads_order->id_ads_order ?></h2>
              </div>

              <address class="text-dark"><?php echo str_replace([',', '-'], '<br>', $global_data[0]->address ?? '' ) ?></address>

            </div>
          </div>
          <!-- End Row -->

          <!-- Bill To -->
          <div class="row justify-content-md-between mb-4">
            <div class="col-md-3">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <th class="align-middle p-1">Tanggal</th>
                    <td class="p-1"><?php echo date('d M Y', strtotime($ads_order->checkout_date)) ?></td>
                  </tr>
                  <tr>
                    <th class="align-middle p-1">Kepada</th>
                    <td class="p-1"><?php echo $ads_order->user_name ?></td>
                  </tr>
                  <tr>
                    <th class="align-middle p-1">Status</th>
                    <td class="p-1">
                      <?php if (in_array($ads_order->payment_status, ['settlement', 'capture'])) { ?>
                        Terbayar
                      <?php } elseif ($ads_order->payment_status == 'pending') { ?>
                        Menunggu Pembayaran
                      <?php } elseif ($ads_order->payment_status == 'deny') { ?>
                        Ditolak
                      <?php } elseif ($ads_order->payment_status == 'cancel') { ?>
                        Dibatalkan
                      <?php } elseif ($ads_order->payment_status == 'expire') { ?>
                        Expired
                      <?php } ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="col-md text-md-right">
            </div>
          </div>
          <!-- End Bill To -->

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
              <thead class="thead-light">
                <tr>
                  <th>Posisi Iklan</th>
                  <th>Tanggal Tayang</th>
                  <th class="text-right">Harga</th>
                  <th class="text-right">Subtotal</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($ads_order->items as $ad) { ?>
                  <tr>
                    <th><?php echo $ad->ads_name ?></th>
                    <td>
                      <?php echo date('d M Y', strtotime($ad->start_date)) ?></span> - <span class="text-nowrap"><?php echo date('d M Y', strtotime($ad->finish_date)) ?>
                    </td>
                    <td class="text-right">Rp. <?php echo number_format($ad->price_per_day, 0, ',', '.') ?></td>
                    <td class="text-right">Rp. <?php echo number_format($ad->subtotal, 0, ',', '.') ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <!-- End Table -->

          <hr class="my-4">

          <div class="row justify-content-md-end mb-3">
            <div class="col-md-5 col-lg-4">
              <dl class="row">
                <dt class="col-sm-6">Subtotal:</dt>
                <dd class="col-sm-6 text-right">Rp. <?php echo number_format($subtotal ?? 0, 0, ',', '.') ?></dd>

                <?php if ($ads_order->id_ads_voucher) { ?>
                  <dt class="col-sm-6">Voucher <?php echo $ads_order->voucher_code ?>:</dt>
                  <dd class="col-sm-6 text-right">Rp. -<?php echo number_format($ads_order->voucher_value, 0, ',', '.') ?></dd>
                <?php } ?>

                <dt class="col-sm-6">Total:</dt>
                <dd class="col-sm-6 text-right">Rp. <?php echo number_format($subtotal - ($ads_order->voucher_value ?? 0), 0, ',', '.') ?></dd>
              </dl>
              <!-- End Row -->
            </div>
          </div>
          <!-- End Row -->
        </div>
      </div>
      <!-- End Card -->
    </div>
  </div>
</div>
