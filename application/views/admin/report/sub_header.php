<?php $module = $this->global_lib->getModuleDetail($this->module_name); ?>

<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">

      <div class="row d-flex align-items-center m-t-20 p-t-10 p-b-0">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <?php if(isset($module[0]->module_icon_big) && strlen(trim($module[0]->module_icon_big)) > 0){ ?>
                <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>assets/icon/<?= $module[0]->module_icon_big; ?>" alt="">
              <?php } ?>
            </div>
          </div>
          <!-- END card -->
        </div>

        <div class="col-xl-6 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black"><?= $module[0]->module_name; ?></h3>
              <select id="report" class="full-width select_withsearch">
                <option value="<?php echo base_url('admin_report_author') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_author' ? 'selected' : '') ?>>
                  Data Penulis/Fotografer
                </option>
                <option value="<?php echo base_url('admin_report_author_active') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_author_active' ? 'selected' : '') ?>>
                  Penulis/Fotografer aktif &amp; tidak aktif
                </option>
                <option value="<?php echo base_url('admin_report_author_productivity') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_author_productivity' ? 'selected' : '') ?>>
                  Produktivitas Penulis/Fotografer
                </option>
                <option value="<?php echo base_url('admin_report_article') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_article' ? 'selected' : '') ?>>
                  Data Artikel
                </option>
                <option value="<?php echo base_url('admin_report_photo') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_photo' ? 'selected' : '') ?>>
                  Data Hypephoto
                </option>
                <option value="<?php echo base_url('admin_report_editor_productivity') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_editor_productivity' ? 'selected' : '') ?>>
                  Produktivitas Editor/Admin
                </option>
                <option value="<?php echo base_url('admin_report_competition') ?>" <?php echo ($this->uri->segment(1) === 'admin_report_competition' ? 'selected' : '') ?>>
                  Kompetisi
                </option>
              </select>
            </div>
          </div>
          <!-- END card -->
        </div>
      </div>

    </div>
  </div>
</div>
<!-- END JUMBOTRON -->

<script>
  $(document).ready(function () {
    $('#report').on('change', function () {
      var value = $(this).val();

      if (value !== '') {
        window.location = value;
      }
    })
  });
</script>
