<?php $this->load->view('admin/ads_voucher/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <?php if (strtolower($this->uri->segment(2)) == 'search'): ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
          <?php endif; ?>

          <?php echo $this->session->flashdata('message'); ?>

          <!-- Start Search Bar -->
          <?php #$this->load->view('shared/photo/search'); ?>
          <!-- End Search Bar -->

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="100px"><b>Action</b></th>
                  <th class="text-nowrap"><b>Kode</b></th>
                  <th class="text-nowrap"><b>Nilai</b></th>
                  <th class="text-nowrap"><b>Tanggal Berlaku</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ads_vouchers as $key => $ads_voucher) { ?>
                  <tr>
                    <td class="text-nowrap"><?php echo $key + $offset + 1 ?></td>
                    <td class="text-nowrap">
                      <a class="btn btn-xs btn-info" href="<?php echo $base_url ?>/edit/<?php echo $ads_voucher->id_ads_voucher ?>" title="Edit">
                        <span class="fa fa-pencil"></span>
                      </a>
                      <a class="btn btn-xs btn-info btn-need-confirmation" href="<?php echo $base_url ?>/delete/<?php echo $ads_voucher->id_ads_voucher ?>" title="Delete" data-message="Are you sure want to delete this?">
                        <span class="fa fa-times"></span>
                      </a>
                    </td>
                    <td><?php echo $ads_voucher->code ?></td>
                    <td>Rp. <?php echo number_format($ads_voucher->value, 0, ',', '.') ?></td>
                    <td>
                      <span class="text-nowrap"><?php echo date('d M Y', strtotime($ads_voucher->start_date)) ?></span> - <span class="text-nowrap"><?php echo date('d M Y', strtotime($ads_voucher->end_date)) ?></span>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

        <?php echo $this->pagination->create_links(); ?>
      </div>
      <!-- END CARD -->
    </div>
  </div>

  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
