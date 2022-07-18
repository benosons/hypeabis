<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-header">
          <a class="btn btn-primary float-right mb-4" href="<?php echo $base_url ?>/add_position">
            Tambah Posisi Iklan
          </a>
        </div>
        <div class="card-body">
          <?php echo $this->session->flashdata('message'); ?>

          <?php if ($ad_cart->has_booked_item) { ?>
            <?php echo $this->global_lib->generateMessage('Posisi iklan telah terpesan, silahkan hapus terlebih dahulu posisi iklan yang telah terpesan.', "danger") ?>
          <?php } ?>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Posisi</b></th>
                  <th class="text-nowrap"><b>Tanggal Tayang</b></th>
                  <th class="text-nowrap text-right"><b>Harga</b></th>
                  <th class="text-nowrap text-right"><b>Subtotal</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $subtotal = 0; ?>
                <?php foreach ($ad_cart->items as $key => $item) { ?>
                  <tr id="ads-order-item-<?php echo $item->id_ads_order_item ?>" class="<?php echo $item->is_booked ? 'table-danger' : '' ?>">
                    <td class="text-nowrap"><?php echo $key + 1 ?></td>
                    <td class="text-nowrap">
                      <?php if (!$item->is_booked) { ?>
                        <a class="btn btn-xs btn-info" href="<?php echo $base_url ?>/edit_position/<?php echo $item->id_ads_order_item ?>" title="Edit">
                          <span class="fa fa-pencil"></span>
                        </a>
                        <a class="btn btn-xs btn-info btn-need-confirmation" href="<?php echo $base_url ?>/delete_position/<?php echo $item->id_ads_order_item ?>" title="Delete" data-message="Are you sure want to delete this?">
                          <span class="fa fa-times"></span>
                        </a>
                      <?php } else { ?>
                        <a class="btn btn-xs btn-info" href="<?php echo $base_url ?>/delete_position/<?php echo $item->id_ads_order_item ?>" title="Delete">
                          <span class="fa fa-times"></span>
                        </a>
                      <?php } ?>
                    </td>
                    <td class="text-nowrap"><?php echo $item->ads_name ?></td>
                    <td class="text-nowrap">
                      <span class="text-nowrap"><?php echo date('d M Y', strtotime($item->start_date)) ?></span> - <span class="text-nowrap"><?php echo date('d M Y', strtotime($item->finish_date)) ?></span>
                    </td>
                    <td class="text-right text-nowrap">Rp. <?php echo number_format($item->price_per_day, 0, ',', '.') ?></td>
                    <td class="text-right text-nowrap">Rp. <?php echo number_format($item->subtotal, 0, ',', '.') ?></td>
                  </tr>

                  <?php $subtotal += $item->subtotal ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card card-transparent">
        <div class="card-body pt-0">
          <div id="voucher" class="row">
            <div class="col-md-4 offset-md-8">
              <?php echo form_open("{$base_url}/apply_voucher", ['role' => 'form', 'id' => 'voucher-form', 'autocomplete' => 'off']); ?>
                <?php echo $this->session->flashdata('voucher_message') ?>

                <div class="form-group form-group-code position-relative">
                  <div class="input-group">
                    <input id="voucher-code" type="text" class="form-control" name="code" placeholder="Kode Voucher" aria-label="Kode Voucher" aria-describedby="button-voucher" autocomplete="off">
                    <div class="input-group-append">
                      <button class="btn btn-success" id="button-voucher" type="submit">Terapkan</button>
                    </div>
                  </div>
                </div>
              <?php echo form_close() ?>
              <div class="table-responsive border-top py-2 mt-3">
                <table class="table table-borderless table-sm">
                  <tr>
                    <th>Subtotal</th>
                    <th class="text-right font-weight-normal">Rp. <?php echo number_format($subtotal, 0, ',', '.') ?></th>
                  </tr>
                  <?php if ($ad_cart->id_ads_voucher) { ?>
                    <tr>
                      <th>
                        Voucher <?php echo $ad_cart->voucher_code ?>
                        <a href="<?php echo $base_url ?>/remove_voucher" class="text-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Batalkan Voucher">
                          <i class="fa fa-minus-circle"></i>
                        </a>
                      </th>
                      <th class="text-right font-weight-normal">Rp. -<?php echo number_format($ad_cart->voucher_value, 0, ',', '.') ?></th>
                    </tr>
                  <?php } ?>
                  <tr>
                    <th>Total</th>
                    <th class="text-right text-nowrap">Rp. <?php echo number_format(max(0, $subtotal - ($ad_cart->voucher_value ?? 0)), 0, ',', '.') ?></th>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <input type="hidden" id="csrf-token-name" value="<?= $this->security->get_csrf_token_name(); ?>" />
              <input type="hidden" id="csrf-token-hash" value="<?= $this->security->get_csrf_hash(); ?>" />

              <button id="pay-button" class="btn btn-complete float-right my-4" <?php echo ($ad_cart->has_booked_item ? 'disabled' : '') ?>>
                Bayar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- END CARD -->
    </div>
  </div>

  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<div id="pay-loader" class="loader loader-default"></div>

<?php echo $this->midtrans_lib->script() ?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#voucher-form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        code: {
          validators: {
            notEmpty: {
              message: 'Kode Voucher harus diisi.'
            }
          }
        },
      }
    });

    $('#pay-button').on('click', function () {
      var $loader = $('#pay-loader');

      $loader.addClass('is-active');

      $.ajax({
        url: '<?php echo $base_url ?>/payment_token',
        type: 'post',
        data: getCSRFToken(),
        success: function (response) {
          $loader.removeClass('is-active');
          refreshCSRFToken(response.csrf_token_name, response.csrf_token_hash);

          if (response.status === 'success') {
            snap.pay(response.token);
          } else if (response.status === 'booked'){
            Swal.fire('Maaf', response.message, 'error');

            for (var item of response.booked_items) {
              var $items = $('#ads-order-item-' + item);
              var $actionColumn = $items.find('td:nth-child(2)');
              var $deleteButton = $actionColumn.find('.btn').last().clone().removeClass('btn-need-confirmation');

              $items.addClass('table-danger');
              $actionColumn.find('.btn').remove();
              $actionColumn.append($deleteButton);
            }

            $('html, body').animate({
              scrollTop: $('.table-danger').first().offset().top - 68 - 45 - 10
            }, 1000);
          } else {
            Swal.fire('Maaf', response.message, 'error');
          }
        },
        complete: function (jqXHR) {
          $loader.removeClass('is-active');
        }
      })
    })

    function updatePayment(result)
    {
      var $loader = $('#pay-loader');

      $loader.addClass('is-active');

      $.ajax({
        url: '<?php echo $base_url ?>/update_cart_payment',
        type: 'post',
        data: getCSRFToken({
          status: result.transaction_status,
          payment_date: result.transaction_time,
        }),
        success: function (response) {
          window.location.href = '<?php echo base_url() ?>user/ads?tran';
        },
        error: function () {
          $loader.removeClass('is-active');
          Swal.fire('Maaf', 'Update Payment Error', 'error');
        }
      })
    }

    function getCSRFToken(current_data = {}) {
      current_data[$('#csrf-token-name').val()] = $('#csrf-token-hash').val();
      return current_data;
    }

    function refreshCSRFToken(name, hash) {
      $('#csrf-token-name').val(name);
      $('#csrf-token-hash').val(hash);
      $('input[name ="' + name + '"]').val(hash);
    }
  });
</script>
