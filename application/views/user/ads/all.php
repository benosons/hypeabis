<?php $this->load->view('user/ads/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <?= $this->session->flashdata('message'); ?>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap" colspan="2"><b>Iklan</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap"><b><i class="fa fa-eye"></i></b></th>
                  <th class="text-nowrap"><b><i class="fa fa-mouse-pointer"></i></b></th>
                  <th class="text-nowrap"><b>CTR</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ads as $ad) { ?>
                  <?php $now = date('Y-m-d 00:00:00'); ?>
                  <tr>
                    <td class="text-nowrap">
                      <?php if (strtotime($now) < strtotime($ad->start_date)) { ?>
                        <?php if ($ad->edit_id_admin && $ad->edit_id_admin != $this->session->userdata('id_admin')) { ?>
                          <?php if (in_array($ad->status, [ '-2','-1', '0'])) { ?>
                            <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-pencil"></span></button>
                          <?php } elseif (in_array($ad->status, ['-3', '1', '2'])) { ?>
                            <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-pencil-square-o"></span></button>
                          <?php } ?>

                          <?php if (is_null($ad->id_ads_cancel)) { ?>
                            <button class="btn btn-xs btn-info" disabled title="Admin is editing"><span class="fa fa-times"></span></button>
                          <?php } ?>
                        <?php } else { ?>
                          <?php if (in_array($ad->status, [ '-2','-1', '0'])) { ?>
                            <a href="<?php echo $base_url ?>/edit/<?= $ad->id_ads; ?>" title="Ubah Iklan" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                          <?php } elseif (in_array($ad->status, ['-3', '1', '2'])) { ?>
                            <a href="<?php echo $base_url ?>/revise/<?= $ad->id_ads; ?>" title="Revisi Iklan" class="btn btn-xs btn-info"><span class="fa fa-pencil-square-o"></span></a>
                          <?php } ?>

                          <?php if (is_null($ad->id_ads_cancel)) { ?>
                            <a href="<?php echo base_url() ?>user_ads_cancel/add/<?= $ad->id_ads; ?>" title="Batalkan Iklan" class="btn btn-xs btn-info"><span class="fa fa-times"></span></a>
                          <?php } ?>
                        <?php } ?>
                      <?php } elseif (strtotime($now) <= strtotime($ad->finish_date) && $ad->status === '-1') { ?>
                            <a href="<?php echo $base_url ?>/edit/<?= $ad->id_ads; ?>" title="Ubah Iklan" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <?php } ?>
                    </td>
                    <?php if (!is_null($ad->ads_pic)) { ?>
                      <td class="align-middle">
                        <?php $redirect_url = (substr($ad->redirect_url, 0, 7) !== "http://" && substr($ad->redirect_url, 0, 8) !== "https://") ? "http://{$ad->redirect_url}" : $ad->redirect_url ?>
                        <a href="<?php echo $redirect_url ?? '' ?>">
                          <img src="<?= base_url(); ?>assets/adv/<?= $ad->ads_pic; ?>" class="img img-fluid img-thumbnail" style="max-width:300px;max-height:150px;" />
                        </a>
                      </td>
                    <?php } ?>
                    <td class="align-middle" <?php echo (is_null($ad->ads_pic) ? 'colspan="2"' : '') ?>>
                      <b><?= $ad->ads_name; ?></b>
                      <br/><b>Mulai tayang: </b> <?= date('d-M-Y', strtotime($ad->start_date)); ?>
                      <br/><b>Selesai: </b> <?= date('d-M-Y', strtotime($ad->finish_date)); ?>
                    </td>
                    <td class="align-middle">
                      <?php if ($ad->status === '1') { ?>
                        <?php $nowTime = strtotime($now) ?>
                        <?php $startDateTime = strtotime($ad->start_date) ?>
                        <?php if($nowTime >= $startDateTime && $nowTime <= strtotime($ad->finish_date)){ ?>
                          <label class="label label-info">Tayang</label>
                        <?php } elseif ($nowTime < $startDateTime) { ?>
                          <label class="label label-default">Akan Tayang</label>
                        <?php } else { ?>
                          <label class="label label-default">Tidak aktif</label>
                        <?php } ?>
                      <?php } elseif (in_array($ad->status, ['0', '2'])) { ?>
                        <label class="label label-warning">Menunggu Approval</label>
                      <?php } elseif (in_array($ad->status, ['-2', '-3'])) { ?>
                        <label class="label label-danger">Ditolak</label>
                      <?php } else { ?>
                        <label class="label label-default">Draft</label>
                      <?php } ?>
                    </td>
                    <td class="align-middle">
                      <?= rtrim(rtrim(number_format($ad->view_count, 2, ',', '.'), '0'), ','); ?>
                    </td>
                    <td class="align-middle">
                      <?= rtrim(rtrim(number_format($ad->click_count, 2, ',', '.'), '0'), ','); ?>
                    </td>
                    <td class="align-middle">
                      <?php if($ad->view_count > 0){ ?>
                        <?= rtrim(rtrim(number_format(($ad->click_count / $ad->view_count) * 100, 2, ',', '.'), '0'), ','); ?> %
                      <?php } else { ?>
                        0 %
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <?php if (empty($ads)) { ?>
            <p class="m-t-40 text-center">Belum ada data iklan, silahkan <u><a href="<?php echo base_url() ?>user_ads_order/add_position">memesan iklan</a></u> atau <u><a href="<?php echo $base_url ?>_order">menyelesaikan transaksi iklan</a></u> anda.</p>
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
