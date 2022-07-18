<?php $this->load->view('user/point/sub_header'); ?>
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
                      <th class="text-nowrap"><b>Tanggal</b></th>
                      <th class="text-nowrap"><b>Aktivitas</b></th>
                      <th class="text-nowrap"><b>Poin</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                    <?php foreach($histories as $item){ ?>
                      <tr>
                        <td><?= date('d-M-Y, H:i',strtotime($item->submit_date)); ?></td>
                        <td>
                          <?= $item->description; ?>
                        </td>
                        <td>
                          <?php if($item->id_reaction > 0){ ?>
                            <?php if($item->reaction_point > 0){ ?>
                              <b class="text-complete">+ <?= $item->reaction_point; ?></b>
                            <?php } else { ?>
                              <b class="text-danger"><?= $item->reaction_point; ?></b>
                            <?php } ?>
                          <?php } else { ?>
                            <?php if($item->point > 0){ ?>
                              <b class="text-complete">+ <?= $item->point; ?></b>
                            <?php } else { ?>
                              <b class="text-danger"><?= $item->point; ?></b>
                            <?php } ?>
                          <?php } ?>
                          </b>
                        </td>
                      </tr>
                    <?php $x++; ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              
              <?php if($x == 0){ ?>
                <p class="m-t-40">Belum ada data data.</p>
              <?php } ?>
              
              <?= $this->pagination->create_links(); ?>
            </div>
            
            <div class="col-md-4 m-b-30">
              <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                <p>Anda akan mendapatkan poin untuk setiap aktivitas anda di Hypeabis. Poin yang terkumpul dapat anda tukarkan dengan merchandise menarik dari Tim Hypeabis.</p>
                <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <?php foreach($points as $point){ ?>
                      <tr>
                        <td style="background:none;"><?= $point->trigger_str; ?></td>
                        <td class="text-nowrap" style="background:none;">
                          <b>
                             + <?= $point->point; ?>
                          </b>
                        </td>
                      </tr>
                    <?php } ?>
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