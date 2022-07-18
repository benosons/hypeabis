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
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Penulis : </label>
                <div class="col-md-6">
                  <input type="hidden" name="author" id="author" class="full-width" value="<?= $search_param['author'] ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Editor : </label>
                <div class="col-md-6">
                  <input type="hidden" name="admin" id="admin" class="full-width" value="<?= $search_param['admin'] ?>">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Kompetisi</label>
                <div class="col-md-3 m-b-5">
                  <select class="full-width select_withsearch" name="id_competition">
                    <option value="" <?= (is_null($search_param['id_competition']) ? "selected" : ""); ?>>Semua</option>
                    <?php foreach ($competitions as $competition) { ?>
                      <option value="<?= $competition->id_competition ?>" <?= ($search_param['id_competition'] == $competition->id_competition ? "selected" : ""); ?>>
                        <?= $competition->title ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Sort by</label>
                <div class="col-md-3 m-b-5">
                  <select class="full-width select_nosearch" name="sort_by">
                    <option value="default" <?= ($search_param['sort_by'] == "default" ? "selected" : ""); ?>>Default</option>
                    <option value="publish_date_asc" <?= ($search_param['sort_by'] == "publish_date_asc" ? "selected" : ""); ?>>Tanggal Publish Terawal</option>
                    <option value="publish_date_desc" <?= ($search_param['sort_by'] == "publish_date_desc" ? "selected" : ""); ?>>Tanggal Publish Terbaru</option>
                    <option value="read_count_asc" <?= ($search_param['sort_by'] == "read_count_asc" ? "selected" : ""); ?>>Jumlah Hits Terendah</option>
                    <option value="read_count_desc" <?= ($search_param['sort_by'] == "read_count_desc" ? "selected" : ""); ?>>Jumlah Hits Tertinggi</option>
                    <option value="like_count_asc" <?= ($search_param['sort_by'] == "like_count_asc" ? "selected" : ""); ?>>Jumlah Like Terendah</option>
                    <option value="like_count_desc" <?= ($search_param['sort_by'] == "like_count_desc" ? "selected" : ""); ?>>Jumlah Like Tertinggi</option>
                    <option value="comment_count_asc" <?= ($search_param['sort_by'] == "comment_count_asc" ? "selected" : ""); ?>>Jumlah Komentar Terendah</option>
                    <option value="comment_count_desc" <?= ($search_param['sort_by'] == "comment_count_desc" ? "selected" : ""); ?>>Jumlah Komentar Tertinggi</option>
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
                  <th class="text-nowrap"><b>Judul</b></th>
                  <th class="text-nowrap"><b>Penulis</b></th>
                  <th class="text-nowrap"><b>Editor</b></th>
                  <th class="text-nowrap"><b>Tanggal Publish</b></th>
                  <th class="text-nowrap"><b>Jumlah Kata</b></th>
                  <th class="text-nowrap"><b>Jumlah Karakter</b></th>
                  <th class="text-nowrap"><b><i class="fa fa-eye"></i></b></th>
                  <th class="text-nowrap"><b><i class="fa fa-thumbs-up"></i></b></th>
                  <th class="text-nowrap"><b><i class="fa fa-comment"></i></b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach ($items as $item) { ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="align-middle">
                      <b><?= $item->title; ?></b>
                      <br>
                      <a href="<?= base_url() . ($item->type === '1' ? 'read' : 'hypephoto'); ?>/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>" target="_blank">
                        <?= base_url() . ($item->type === '1' ? 'read' : 'hypephoto'); ?>/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>
                      </a>
                    </td>
                    <td class="text-nowrap align-middle"><?= $item->user_name; ?></td>
                    <td class="text-nowrap align-middle"><?= $item->admin_name; ?></td>
                    <td class="text-nowrap align-middle">
                      <? if (isset($item->publish_date) && strlen(trim($item->publish_date)) > 0) { ?>
                        <?= date('d-M-Y H:i', strtotime($item->publish_date)); ?>
                      <? } else { ?>
                        -
                      <? } ?>
                    </td>
                    <td class="align-middle"><?= ($item->type == 1 ? number_format(ceil($item->word_count), 0, ',', '.') : '-'); ?></td>
                    <td class="align-middle"><?= ($item->type == 1 ? number_format(ceil($item->char_count), 0, ',', '.') : '-'); ?></td>
                    <td class="align-middle"><?= number_format(ceil($item->read_count), 0, ',', '.'); ?></td>
                    <td class="align-middle"><?= number_format(ceil($item->like_count), 0, ',', '.'); ?></td>
                    <td class="align-middle"><?= number_format($item->comment_count, 0, ',', '.'); ?></td>
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

<script>
  $(document).ready(function() {
    $('#author').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: 'Semua',
      ajax: {
        dataType: 'json',
        url: '<?php echo base_url("admin_user/searchUserByKeyword"); ?>',
        delay: 800,
        quietMillis: 250,
        data: function(params, page) {
          return {
            q: params
          };
        },
        results: function(data, page) {
          return {
            results: data
          };
        }
      },
      initSelection: function(element, callback) {
        callback({
          id: '<?php echo ($search_param["author"] ?? '') ?>',
          text: '<?php echo ($author[0]->name ?? '') . " - (" . ($author[0]->email ?? '') . ")" ?>'
        })
      },
    });

    $('#admin').select2({
      minimumInputLength: 3,
      allowClear: true,
      placeholder: 'Semua',
      ajax: {
        dataType: 'json',
        url: '<?php echo base_url("admin_account/searchUserByKeyword"); ?>',
        delay: 800,
        quietMillis: 250,
        data: function(params, page) {
          return {
            q: params
          };
        },
        results: function(data, page) {
          return {
            results: data
          };
        }
      },
      initSelection: function(element, callback) {
        callback({
          id: '<?php echo ($search_param["admin"] ?? '') ?>',
          text: '<?php echo ($admin_name ?? '') ?>'
        })
      },
    });
  });
</script>