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
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Sort by</label>
                <div class="col-md-3 m-b-5">
                  <select class="full-width select_nosearch" name="sort_by">
                    <option value="default" <?= ($search_param['sort_by'] == "default" ? "selected" : ""); ?>>Default</option>
                    <option value="article_count_asc" <?= ($search_param['sort_by'] == "article_count_asc" ? "selected" : ""); ?>>Jumlah Artikel Terendah</option>
                    <option value="article_count_desc" <?= ($search_param['sort_by'] == "article_count_desc" ? "selected" : ""); ?>>Jumlah Artikel Tertinggi</option>
                    <option value="photo_count_asc" <?= ($search_param['sort_by'] == "photo_count_asc" ? "selected" : ""); ?>>Jumlah Hypephoto Terendah</option>
                    <option value="photo_count_desc" <?= ($search_param['sort_by'] == "photo_count_desc" ? "selected" : ""); ?>>Jumlah Hypephoto Tertinggi</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Publish Date : </label>
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
                  <th class="text-nowrap"><b>Terakhir Publish</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach ($items as $item) { ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-middle">
                      <b><?= $item->name; ?></b>

                      <div class="mt-1 text-muted"><?= $item->email; ?></div>
                    </td>
                    <td class="align-middle"><?= $item->article_count; ?></td>
                    <td class="align-middle"><?= $item->photo_count; ?></td>
                    <td class="text-nowrap align-middle">
                      <?php if ($item->last_publish_date) { ?>
                        <?php echo date('d-M-Y H:i:s', strtotime($item->last_publish_date)); ?>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php $x++; ?>
                  <?php if ($x == 0) { ?>
                    <tr>
                      <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                  <?php } ?>
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