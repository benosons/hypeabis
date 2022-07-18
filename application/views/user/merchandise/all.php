<?php $this->load->view('user/merchandise/sub_header'); ?>
<?php $tab_id = $this->uri->segment(3); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="row">
            <?php foreach($merchandises as $x => $merch){ ?>
              <div class="col-6 col-md-3">
                <?php if(isset($merch->merch_pic_thumb) && strlen(trim($merch->merch_pic_thumb)) > 0){ ?>
                  <a href="<?= base_url(); ?>user_merch/redeemPoint/<?= $merch->id_merchandise; ?>/<?= strtolower(url_title($merch->merch_name)); ?>" class="btn-need-confirmation" data-message="Anda yakin ingin menukarkan poin?">
                    <img class="img img-responsive img-fluid img-thumbnail" src="<?= base_url(); ?>assets/merchandise/thumb/<?= $merch->merch_pic_thumb; ?>" alt="Merchandise Bisnis Muda">
                  </a>
                <?php } ?>
                <p class="m-t-10">
                  <a href="<?= base_url(); ?>user_merch/redeemPoint/<?= $merch->id_merchandise; ?>/<?= strtolower(url_title($merch->merch_name)); ?>" class="btn-need-confirmation" data-message="Anda yakin ingin menukarkan poin?">
                    <b><?= $merch->merch_name; ?></b><br/>
                    <i class="fa fa-star"></i> <?= number_format($merch->merch_point, 0, ',', '.') ?> poin<br/>
                    <i class="fa fa-shopping-cart"></i> Kuota: <?= number_format($merch->merch_quota, 0, ',', '.') ?>
                  </a>
                </p>
              </div>
              
              <?php
                if(($x + 1) % 4 == 0){
                  echo '<div class="clearfix d-none d-md-block" style="clear:both;"></div>';
                }
                if(($x + 1) % 2 == 0){
                  echo '<div class="clearfix d-none d-sm-block d-md-none" style="clear:both;"></div>';
                }
              ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
