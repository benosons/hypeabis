<?php $this->load->view('admin/report/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <?= $this->session->flashdata('message'); ?>

          <?php if (strtolower($this->uri->segment(2)) == 'search') { ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
          <?php } ?>

          <!-- Start Search Bar -->
          <?php $search_param = $this->session->userdata('search_report'); ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
            <?= form_open("{$base_url}/submitSearch", ['role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off']); ?>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Tanggal Daftar : </label>
                <div class="col-md-3">
                  <div class="input-group tranparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">Mulai</span>
                    </div>
                    <input type="text" class="form-control datepicker-component" name="start_date" value="<?= $search_param['start_date']; ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="input-group transparent">
                    <div class="input-group-prepend">
                      <span class="input-group-text transparent">Sampai</span>
                    </div>
                    <input type="text" class="form-control datepicker-component" name="finish_date" value="<?= $search_param['finish_date']; ?>" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                <div class="col-md-9">
                  <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-search"></i> Pencarian</button>
                  <a href="<?php echo $base_url ?>" class="btn btn-white sm-m-b-10"><i class="fa fa-times"></i> Reset</a>
                </div>
              </div>
            </div>
            <?= form_close(); ?>
          </div>
          <!-- End Search Bar -->

          <? if ((isset($search_param['start_date']) && strlen(trim($search_param['start_date'])) > 0) || (isset($search_param['finish_date']) && strlen(trim($search_param['finish_date'])) > 0)) { ?>
            <p>
              Menampilkan hasil pencarian untuk artikel yang di publish&nbsp;
              <? if (isset($search_param['start_date']) && strlen(trim($search_param['start_date'])) > 0) { ?>
                dari tanggal <b><?= ($search_param['start_date']); ?></b>&nbsp;
              <? } ?>
              <? if (isset($search_param['finish_date']) && strlen(trim($search_param['finish_date'])) > 0) { ?>
                sampai tanggal <b><?= ($search_param['finish_date']); ?></b>&nbsp;
              <? } ?>
            </p>
            <br />
          <? } ?>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap"><b>Name</b></th>
                  <th class="text-nowrap"><b>Artikel</b></th>
                  <th class="text-nowrap"><b>Hypephoto</b></th>
                  <th class="text-nowrap"><b>Point</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap"><b>Tanggal Daftar</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach ($items as $item) { ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-middle" <?php echo ($item->is_internal === '1' ? 'title="Member internal hypeabis.id"' : '') ?>>
                      <b><?= $item->name; ?></b>

                      <?php if ($item->is_internal == 1) { ?>
                        <i class="fa fa-check-circle text-success"></i>&nbsp;
                      <?php } ?>

                      <div class="mt-1 text-muted"><?= $item->email; ?></div>
                    </td>
                    <td class="align-middle"><?= $item->article_count; ?></td>
                    <td class="align-middle"><?= $item->photo_count; ?></td>
                    <td class="align-middle"><?= $item->point; ?></td>
                    <td class="align-middle text-nowrap">
                      <?php if ($item->status == '1') { ?>
                        <a href="<?= base_url(); ?>admin_user/ban/<?= $item->id_user; ?>" class="btn-need-confirmation" data-message="Are you sure want to ban this account?" title="Click to ban this account">
                          <span class="fa fa-check-square"></span> Active
                        </a>
                      <?php } else { ?>
                        <a href="<?= base_url(); ?>admin_user/activate/<?= $item->id_user; ?>" class="btn-need-confirmation" data-message="Are you sure want to activate this account?" title="Click to activate this account">
                          <span class="fa fa-minus-square"></span> Banned
                        </a>
                      <?php } ?>
                    </td>
                    <td class="text-nowrap align-middle">
                      <?php if ($item->created && $item->created != '0000-00-00 00:00:00') { ?>
                        <?php echo date('d-M-Y H:i:s', strtotime($item->created)); ?>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php $x++; ?>
                <?php } ?>
                <?php if ($x == 0) { ?>
                  <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="row">
            <div class="col-md-9">
              <?= $this->pagination->create_links(); ?>
            </div>
            <div class="col-md-3">
              <a href="<?= $base_url; ?>/export" class="btn btn-default pull-right m-t-20"><i class="fa fa-download"></i> Export Excel</a>
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