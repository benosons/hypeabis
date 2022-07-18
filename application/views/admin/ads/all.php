<?php $this->load->view('admin/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            <?= (strtolower($this->uri->segment(2)) == 'search' ? 'Search Result :' : ''); ?>
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <?php $this->load->view('admin/ads/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap" colspan="2"><b>Ads</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap"><b>Shown</b></th>
                  <th class="text-nowrap"><b>Clicked</b></th>
                  <th class="text-nowrap"><b>CTR</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($ads as $item){ ?>
                  <?php $now = date('Y-m-d 00:00:00'); ?>
                  <tr>
                    <td class="text-nowrap align-middle">
                      <?php if (is_null($item->edit_id_admin) || $item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                        <?php if (is_null($item->id_user) || strtotime($now) < strtotime($item->start_date) || $item->status !== '1') { ?>
                          <a href="<?= base_url(); ?>admin_ads/edit/<?= $item->id_ads; ?>" title="Edit Ads" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                        <?php } ?>
                        <?php if (is_null($item->id_user)) { ?>
                          <a href="<?= base_url(); ?>admin_ads/delete/<?= $item->id_ads; ?>" title="Delete Ads" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                        <?php } ?>
                      <?php } ?>

                      <?php if (!is_null($item->edit_id_admin)) { ?>
                        <?php if ($item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                          <a href="<?php echo base_url("admin_ads/unlock_edit/{$item->id_ads}") ?>" class="btn btn-xs btn-info" title="Unlock Editor"><span class="fa fa-unlock"></span></a>
                          <?php } elseif ($this->session->userdata('admin_level') == '1') { ?>
                            <a href="<?php echo base_url("admin_ads/unlock_edit/{$item->id_ads}") ?>" class="btn btn-xs btn-complete" title="Sedang diedit oleh <?php echo $item->edit_admin_name ?>"><span class="fa fa-unlock"></span></a>
                        <?php } else { ?>
                          <?php $title = ($item->edit_id_admin !== $this->session->userdata('id_admin') ? "title='Sedang diedit oleh {$item->edit_admin_name}'" : '') ?>
                          <button class="btn btn-xs btn-danger" disabled <?php echo $title ?>><span class="fa fa-lock"></span></button>
                        <?php } ?>
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <img src="<?= base_url(); ?>assets/adv/<?= $item->ads_pic; ?>" class="img img-fluid img-thumbnail" style="max-width:300px;max-height:150px;" />
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <b><?= $item->ads_name; ?></b><br/>
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        (Built-in Ads)
                      <?php } else { ?>
                        (Google Ads)
                      <?php } ?>
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <br/><b>Mulai tayang: </b> <?= date('d-M-Y', strtotime($item->start_date)); ?>
                        <br/><b>Selesai: </b> <?= date('d-M-Y', strtotime($item->finish_date)); ?>
                        <?php if (!is_null($item->id_user)) { ?>
                          <br/><b>Member: </b> <?php echo $item->user_name ?>
                        <?php } ?>
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <?php if ($item->status === '1') { ?>
                          <?php $nowTime = strtotime($now) ?>
                          <?php $startDateTime = strtotime($item->start_date) ?>
                          <?php if($nowTime >= $startDateTime && $nowTime <= strtotime($item->finish_date)){ ?>
                            <label class="label label-info">Tayang</label>
                          <?php } elseif ($nowTime < $startDateTime) { ?>
                            <label class="label label-default">Akan Tayang</label>
                          <?php } else { ?>
                            <label class="label label-default">Tidak aktif</label>
                          <?php } ?>
                        <?php } elseif (in_array($item->status, ['0', '2'])) { ?>
                          <label class="label label-warning">Menunggu Approval</label>
                        <?php } elseif (in_array($item->status, ['-2', '-3'])) { ?>
                          <label class="label label-danger">Ditolak</label>
                        <?php } ?>
                      <?php } else { ?>
                        <label class="label label-info">Aktif</label>
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <?= rtrim(rtrim(number_format($item->view_count, 2, ',', '.'), '0'), ','); ?>
                      <?php } else { ?>
                        -
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <?= rtrim(rtrim(number_format($item->click_count, 2, ',', '.'), '0'), ','); ?>
                      <?php } else { ?>
                        -
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?php if(strtolower($item->ads_source) == 'builtin'){ ?>
                        <?php if($item->view_count > 0){ ?>
                          <?= rtrim(rtrim(number_format(($item->click_count / $item->view_count) * 100, 2, ',', '.'), '0'), ','); ?> %
                        <?php } else { ?>
                          0%
                        <?php } ?>
                      <?php } else { ?>
                        -
                      <?php } ?>
                    </td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in ads database.</p>
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
