<?php $this->load->view('admin/page/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg page-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            <?= (strtolower($this->uri->segment(2)) == 'search' ? 'Search Result :' : 'All Page'); ?>
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <?php $this->load->view('admin/page/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="15%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Page title</b></th>
                  <th class="text-nowrap" style="display:none;"><b>Status</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($page as $item){ ?>
                  <tr>
                    <td class="text-nowrap"><?= ($x + 1); ?></td>
                    <td class="text-nowrap">
                      <?php if($item->updatable == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                        <a href="<?= base_url(); ?>admin_page/edit/<?= $item->id_page; ?>" title="Edit Page" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <?php } ?>
                      <?php if($item->deletable == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                        <a href="<?= base_url(); ?>admin_page/delete/<?= $item->id_page; ?>" title="Delete Page" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete page?"><span class="fa fa-times"></span></a>
                      <?php } ?>
                    </td>
                    <td>
                      <b><?= $item->page_title; ?></b><br/>
                      <?= base_url(); ?>page/content/<?= $item->id_page; ?>/<?= strtolower(url_title($item->page_title)); ?>
                    </td>
                    <td style="display:none;">
                      <?php if($item->page_status == '1'){ ?>
                        <a href="<?= base_url(); ?>admin_page/unpublish/<?= $item->id_page; ?>" title="Click to unpublish page"><span class="fa fa-check-square"></span> Yes</a>
                      <?php } else {?>
                        <a href="<?= base_url(); ?>admin_page/publish/<?= $item->id_page; ?>" title="Click to publish page"><span class="fa fa-minus-square"></span> No</a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in page database.</p>
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