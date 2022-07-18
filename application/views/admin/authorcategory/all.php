<?php $this->load->view('admin/authorcategory/sub_header'); ?>

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
          <?php $this->load->view('admin/authorcategory/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Kategori</b></th>
                  <th class="text-nowrap"><b>User / Member</b></th>
                  <th class="text-nowrap"><b>urutan</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($users as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_authorcategory/edit/<?= $item->id_author_category; ?>" title="Edit Authorcategory" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_authorcategory/delete/<?= $item->id_author_category; ?>" title="Delete Authorcategory" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-middle"><?= $item->category_name; ?></td>
                    <td class="align-middle"><?= $item->name; ?></td>
                    <td class="align-middle"><?= $item->author_order; ?></td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in database.</p>
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