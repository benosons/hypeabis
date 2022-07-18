<?php $this->load->view('admin/adstype/sub_header'); ?>

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
                  <th class="text-nowrap"><b>Nama</b></th>
                  <th class="text-nowrap"><b>Harga</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($adstypes as $key => $adstype) { ?>
                  <tr>
                    <td class="text-nowrap"><?php echo $key + $offset + 1 ?></td>
                    <td class="text-nowrap">
                      <a class="btn btn-xs btn-info" href="<?php echo $base_url ?>/edit/<?php echo $adstype->id_adstype ?>" title="Edit">
                        <span class="fa fa-pencil"></span>
                      </a>
                    </td>
                    <td><?php echo $adstype->ads_name ?></td>
                    <td>Rp. <?php echo number_format($adstype->price_per_day, 0, ',', '.') ?></td>
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
