<?php $this->load->view('admin/admin/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <?php if(strtolower($this->uri->segment(2)) == 'search'){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Search Result :</h4>
          <?php } ?>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <?php $this->load->view('admin/admin/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap"><b>Name</b></th>
                  <th class="text-nowrap"><b>Username</b></th>
                  <th class="text-nowrap"><b>Email</b></th>
                  <th class="text-nowrap"><b>Level</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($admin as $item){ ?>
                <tr>
                  <td class="align-middle text-nowrap"><?= ($x + 1); ?></td>
                  <td class="align-middle text-nowrap">
                    <?php if($item->status == 1){ ?>
                      <a href="<?= base_url(); ?>admin_account/banned/<?= $item->id_admin; ?>" title="Banned Admin" class="btn btn-xs btn-info"><span class="fa fa-minus-circle"></span></a>
                    <?php } else { ?>
                      <a href="<?= base_url(); ?>admin_account/activate/<?= $item->id_admin; ?>" title="Activate Admin" class="btn btn-xs btn-info"><span class="fa fa-plus-circle"></span></a>
                    <?php } ?>
                    <a href="<?= base_url(); ?>admin_account/edit/<?= $item->id_admin; ?>" title="Edit Admin" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                    <a href="<?= base_url(); ?>admin_account/delete/<?= $item->id_admin; ?>" title="Delete Admin" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure to delete this administrator data?">
                      <span class="fa fa-times"></span>
                    </a>
                  </td>
                  <td class="align-middle text-nowrap"><?= ($item->status == 1 ? "Active" : "Banned"); ?></td>
                  <td class="align-middle text-nowrap"><?= $item->name; ?></td>
                  <td class="align-middle text-nowrap"><?= $item->username; ?></td>
                  <td class="align-middle text-nowrap"><?= $item->email; ?></td>
                  <td class="align-middle text-nowrap"><?= ($item->level == 1 ? "Super Admin" : "Admin"); ?></td>
                </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in module database.</p>
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