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
            <div class="col-md-8 m-b-30">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr class="">
                      <th><b>Tanggal penukaran</b></th>
                      <th><b>Merchandise</b></th>
                      <th width="200px"><b>Status</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                    <?php foreach($redeem_requests as $item){ ?>
                      <tr>
                        <td><?= date('d-M-Y, H:i',strtotime($item->request_date)); ?></td>
                        <td>
                          <b><?= $item->merch_name; ?></b></br>
                          (<?= number_format($item->point, 0, ',', '.'); ?> poin)
                        </td>
                        <td>
                          <?php if($item->redeem_status == 0){ ?>
                            <label class="label label-warning">Menunggu konfirmasi</label>
                          <?php } else if($item->redeem_status == 1){ ?>
                            <label class="label label-info">Confirmed</label>
                          <?php } else if($item->redeem_status == 2){ ?>
                            <label class="label label-info">Done</label>
                          <?php } else if($item->redeem_status == 3){ ?>
                            <label class="label label-danger">Batal</label>
                          <?php } else {}?>
                        </td>
                      </tr>
                    <?php $x++; ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              
              <?php if(count($redeem_requests) == 0){ ?>
                <p class="m-t-40" align="center">
                  Belum ada data penukaran poin. Klik pada daftar merchandise untuk menukarkan poin anda.
                  <br/><br/>
                  <a href="<?= base_url(); ?>user_merch" class="btn btn-default">
                    <i class="fa fa-gift"></i> Daftar merchandise
                  </a>
                </p>
              <?php } ?>
            </div>
            
            <div class="col-md-4 m-b-30">
              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                <p><b>KETERANGAN STATUS PENUKARAN:</b></p>
                <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr>
                      <td style="background:none;">
                        <label class="label label-warning">Menunggu konfirmasi</label><br/>
                        Menunggu konfirmasi tim bisnismuda.id
                      </td>
                    </tr>
                    <tr>
                      <td style="background:none;">
                        <label class="label label-info">Confirmed</label><br/>
                        Penukaran poin anda telah dikonfirmasi. Tim bisnismuda.id akan menghubungi anda untuk informasi lebih detail.
                      </td>
                    </tr>
                    <tr>
                      <td style="background:none;">
                        <label class="label label-info">Done</label><br/>
                        Penukaran poin anda telah selesai di proses.
                      </td>
                    </tr>
                    <tr>
                      <td style="background:none;">
                        <label class="label label-danger">Batal</label><br/>
                        Penukaran poin anda tidak dapat diproses. Poin yang digunakan untuk penukaran akan ditambahkan kembali ke akun anda.
                      </td>
                    </tr>
                  </tbody>
                </table>
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