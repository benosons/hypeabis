<?php $this->load->view('admin/homepage/sub_header'); ?>
<?php $tab_id = $this->uri->segment(3); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <?= $this->session->flashdata('message'); ?>

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <div class="card-group card-accordion horizontal" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="card card-default m-b-0">
              <div class="card-header " role="tab" id="heading0">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="<?= ($tab_id == 0 ? 'true' : 'false'); ?>" aria-controls="collapse0" <?= ($tab_id == 0 ? '' : 'class="collapsed"'); ?>>
                    <strong class="fs-16 text-heading-black">Featured Konten</strong>
                  </a>
                </h4>
              </div>
              <div id="collapse0" class="collapse <?= ($tab_id == 0 ? 'show' : ''); ?>" role="tabcard" aria-labelledby="heading0">
                <div class="card-body">
                  <?php $this->load->view('admin/homepage/edit_sec0'); ?>
                </div>
              </div>
            </div>

            <div class="card card-default m-b-0" style="display:none">
              <div class="card-header " role="tab" id="heading1">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="<?= ($tab_id == 1 ? 'true' : 'false'); ?>" aria-controls="collapse1" <?= ($tab_id == 1 ? '' : 'class="collapsed"'); ?>>
                    <strong class="fs-16 text-heading-black">Trending / Nge-Hits</strong>
                  </a>
                </h4>
              </div>
              <div id="collapse1" class="collapse <?= ($tab_id == 1 ? 'show' : ''); ?>" role="tabcard" aria-labelledby="heading1">
                <div class="card-body">
                  <?php $this->load->view('admin/homepage/edit_sec1'); ?>
                </div>
              </div>
            </div>

            <div class="card card-default m-b-0" style="display:none">
              <div class="card-header " role="tab" id="heading2">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="<?= ($tab_id == 2 ? 'true' : 'false'); ?>" aria-controls="collapse2" <?= ($tab_id == 2 ? '' : 'class="collapsed"'); ?>>
                    <strong class="fs-16 text-heading-black">Rekomendasi</strong>
                  </a>
                </h4>
              </div>
              <div id="collapse2" class="collapse <?= ($tab_id == 2 ? 'show' : ''); ?>" role="tabcard" aria-labelledby="heading2">
                <div class="card-body">
                  <?php $this->load->view('admin/homepage/edit_sec2'); ?>
                </div>
              </div>
            </div>

            <div class="card card-default m-b-0" style="display:none">
              <div class="card-header " role="tab" id="heading3">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="<?= ($tab_id == 3 ? 'true' : 'false'); ?>" aria-controls="collapse3" <?= ($tab_id == 3 ? '' : 'class="collapsed"'); ?>>
                    <strong class="fs-16 text-heading-black">Penulis Pilihan</strong>
                  </a>
                </h4>
              </div>
              <div id="collapse3" class="collapse <?= ($tab_id == 3 ? 'show' : ''); ?>" role="tabcard" aria-labelledby="heading3">
                <div class="card-body">
                  <?php $this->load->view('admin/homepage/edit_sec3'); ?>
                </div>
              </div>
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