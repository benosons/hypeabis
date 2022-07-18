<? $this->load->view('admin/slider/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <? if(strtolower($this->uri->segment(2)) == 'search'){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Search Result :</h4>
          <? } ?>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <? $this->load->view('admin/slider/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="20%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Slider name</b></th>
                  <th class="text-nowrap"><b>Width</b></th>
                  <th class="text-nowrap"><b>Height</b></th>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($slider as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_slider/manage/<?= $item->id_slider; ?>" title="Manage Slider" class="btn btn-xs btn-info"><span class="fa fa-share"></span> Manage</a>
                      <? if($item->updatable == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                        <a href="<?= base_url(); ?>admin_slider/edit/<?= $item->id_slider; ?>" title="Edit Slider" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <? } ?>
                      <? if($item->deletable == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                        <a href="<?= base_url(); ?>admin_slider/delete/<?= $item->id_slider; ?>" title="Delete Slider" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                      <? } ?>
                    </td>
                    <td class="text-nowrap align-middle"><?= $item->slider_name; ?></td>
                    <td class="text-nowrap align-middle"><?= $item->width; ?> px</td>
                    <td class="text-nowrap align-middle"><?= $item->height; ?> px</td>
                  </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in slider database.</p>
          <? } ?>
          
          <?= $this->pagination->create_links(); ?>
        
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->