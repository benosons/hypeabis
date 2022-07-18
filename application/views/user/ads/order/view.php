<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <?php if ($ads_order->payment_status == 'pending') { ?>
          <div class="row mb-4">
            <div class="col text-right">
              <button id="btn-pay" class="btn btn-complete">Cara Pembayaran</button>
            </div>
          </div>
          <?php } ?>
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label sm-text-left">Tanggal Transaksi</label>
                    <div class="col-md-9">
                      <?php echo date('d M Y', strtotime($ads_order->checkout_date)) ?>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-3 control-label sm-text-left">Status Pembayaran</label>
                    <div class="col-md-9">
                      <?php if (in_array($ads_order->payment_status, ['settlement', 'capture'])) { ?>
                        <label class="label label-success">Terbayar</label>
                      <?php } elseif ($ads_order->payment_status == 'pending') { ?>
                        <label class="label label-warning">Menunggu Pembayaran</label><br>
                      <?php } elseif ($ads_order->payment_status == 'deny') { ?>
                        <label class="label label-danger">Ditolak</label>
                      <?php } elseif ($ads_order->payment_status == 'cancel') { ?>
                        <label class="label label-danger">Dibatalkan</label>
                      <?php } elseif ($ads_order->payment_status == 'expire') { ?>
                        <label class="label label-default">Expired</label>
                      <?php } ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <hr class="mt-0	d-block d-md-none">
                
                <div class="table-responsive border-0">
                  <table class="table table-borderless table-sm" style="font-size: 13px;">
                    <tr>
                      <th>Subtotal</th>
                      <th class="text-right font-weight-normal">Rp. <?php echo number_format($subtotal, 0, ',', '.') ?></th>
                    </tr>
                    <?php if ($ads_order->id_ads_voucher) { ?>
                      <tr>
                        <th>
                          Voucher <?php echo $ads_order->voucher_code ?>
                        </th>
                        <th class="text-right font-weight-normal">Rp. -<?php echo number_format($ads_order->voucher_value, 0, ',', '.') ?></th>
                      </tr>
                    <?php } ?>
                    <tr>
                      <th>Total</th>
                      <th class="text-right text-nowrap">Rp. <?php echo number_format($subtotal - ($ads_order->voucher_value ?? 0), 0, ',', '.') ?></th>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-transparent">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <?php if (in_array($ads_order->payment_status, ['settlement', 'capture'])) { ?>
                    <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <?php } ?>
                  <th class="text-nowrap" colspan="2"><b>Iklan</b></th>
                  <th class="text-nowrap text-right"><b>Harga</b></th>
                  <th class="text-nowrap text-right"><b>Subtotal</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $now = strtotime('now') ?>
                <?php foreach ($ads_order->items as $ad) { ?>
                  <tr>
                    <?php if (in_array($ads_order->payment_status, ['settlement', 'capture'])) { ?>
                      <td class="text-nowrap align-middle">
                        <?php if ($ad->id_ads) { ?>
                          <?php if ($now < strtotime($ad->start_date)) { ?>
                            <?php if ($ad->edit_id_admin && $ad->edit_id_admin != $this->session->userdata('id_admin')) { ?>
                              <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-pencil"></span></button>
                              <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-times"></span></button>
                            <?php } else { ?>
                              <a href="<?php echo base_url() ?>user_ads/edit/<?= $ad->id_ads; ?>" title="Edit Content" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                            <?php } ?>
                          <?php } ?>
                        <?php } ?>
                      </td>
                    <?php } ?>
                    <?php if (!is_null($ad->ads_pic)) { ?>
                      <td class="align-middle">
                        <img src="<?= base_url(); ?>assets/adv/<?= $ad->ads_pic; ?>" class="img img-fluid img-thumbnail" style="max-width:300px;max-height:150px;" />
                      </td>
                    <?php } ?>
                    <td class="align-middle" <?php echo (is_null($ad->ads_pic) ? 'colspan="2"' : '') ?>>
                      <b><?= $ad->ads_name; ?></b>
                      <br/><b>Mulai tayang: </b> <?= date('d-M-Y', strtotime($ad->start_date)); ?>
                      <br/><b>Selesai: </b> <?= date('d-M-Y', strtotime($ad->finish_date)); ?>
                    </td>
                    <td class="text-right align-middle text-nowrap">Rp. <?php echo number_format($ad->price_per_day, 0, ',', '.') ?></td>
                    <td class="text-right align-middle text-nowrap">Rp. <?php echo number_format($ad->subtotal, 0, ',', '.') ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
</div>

<?php echo $this->midtrans_lib->script() ?>
<script>
  $(document).ready(function() {
    $('#btn-pay').click(function () {
      snap.pay('<?php echo $ads_order->snap_token ?>', {
        onPending: result => {},
      });
    })
  });
</script>
