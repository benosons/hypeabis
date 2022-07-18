<? $module = $this->global_lib->getModuleDetail(); ?>

<!-- START JUMBOTRON -->
<div class="jumbotron m-b-0">
  <div class="container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
    <div class="inner">
      <!-- START BREADCRUMB -->
      <? $breadcrumb = $this->session->userdata('breadcrumb'); ?>
      <ol class="breadcrumb hidden-sm hidden-xs">
        <li class="breadcrumb-item">
          <a href="<?= base_url(); ?>admin_slider">All Slider</a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?= base_url(); ?>admin_slider/manage/<?= $slider[0]->id_slider; ?>"><?= $slider[0]->slider_name; ?></a>
        </li>
        <? foreach($breadcrumb as $crumb){ ?>
          <li class="breadcrumb-item <?= (strlen(trim($crumb['href'])) > 0 && $crumb['href'] != '#' ? '' : 'active'); ?>">
            <? if(strlen(trim($crumb['href'])) > 0 && $crumb['href'] != '#'){ ?>
              <a href="<?= $crumb['href']; ?>"><?= $crumb['text']; ?></a>
            <? } else { ?>
              <?= $crumb['text']; ?>
            <? } ?>
          </li>
        <? } ?>
      </ol>
      <!-- END BREADCRUMB -->
      
      <div class="row d-flex align-items-center m-t-20 p-t-10 p-b-0">
        <div class="col-xl-2 col-lg-2 col-md-2 m-b-10">
          <!-- START card -->
          <div class="full-height">
            <div class="card-body p-t-0 p-b-0 text-center">
              <? if(isset($module[0]->module_icon_big) && strlen(trim($module[0]->module_icon_big)) > 0){ ?>
                <img class="demo-mw-600 mw-100" src="<?= base_url(); ?>assets/icon/<?= $module[0]->module_icon_big; ?>" alt="">
              <? } ?>
            </div>
          </div>
          <!-- END card -->
        </div>
        
        <div class="col-xl-6 col-lg-6 col-md-10">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 sm-text-center">
              <h3 class="m-t-0 fw-700 text-heading-black"><?= $slider[0]->slider_name; ?></h3>
              <p class="m-b-10">Manage slider <?= $slider[0]->slider_name; ?></p>
            </div>
          </div>
          <!-- END card -->
        </div>
        
        <div class="col-xl-3 col-lg-4 ">
          <!-- START card -->
          <div class="card card-transparent m-b-0">
            <div class="card-body p-t-0 p-b-0 text-right sm-text-center">
              <a href="<?= base_url(); ?>admin_slider/addContent/<?= $slider[0]->id_slider; ?>" class="btn btn-lg btn-perfect-rounded btn-complete tip m-b-5" data-placement="bottom" data-toggle="tooltip" data-original-title="Add Slider Content">
                <i class="fa fa-plus"></i>
              </a>
            </div>
          </div>
          <!-- END card -->
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- END JUMBOTRON -->

<? $function_url = strtolower(trim($this->uri->segment(2))); ?>
<!-- Submenu Nav tabs -->
<div class="submenu-wrapper m-b-10" id="submenu-wrapper">
  <ul class="submenu nav binari-nav nav-tabs no-border" role="tablist">
    <li class="<?= ($function_url == '' || $function_url == 'manage' ? 'active' : ''); ?>">
      <a href="<?= base_url(); ?>admin_slider/manage/<?= $slider[0]->id_slider; ?>">
        <i class="fa fa-list"></i> All Slides
      </a>
    </li>
    <li class="<?= ($function_url == 'addcontent' ? 'active' : '') ?>">
      <a href="<?= base_url(); ?>admin_slider/addContent/<?= $slider[0]->id_slider; ?>">
        <i class="fa fa-plus"></i> Add Slide
      </a>
    </li>
  </ul>
</div>
<!-- End Submenu Nav tabs -->
