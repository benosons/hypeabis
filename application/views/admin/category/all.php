<?php $this->load->view('admin/category/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <?php if(strtolower($this->uri->segment(2)) == 'search'){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
          <?php } ?>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <?php $this->load->view('admin/category/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="15%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Nama kategori</b></th>
                  <th class="text-nowrap"><b>URL</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($categories as $item){ ?>
                <tr>
                  <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                  <td class="text-nowrap align-middle">
                    <?php if($item['category_parent'] == 0){ ?>
                      <a href="<?= base_url(); ?>admin_category/addSub/<?= $item['id_category']; ?>" title="Add Sub Category" class="btn btn-xs btn-info"><span class="fa fa-sort-amount-desc"></span> Add Sub</a>
                    <?php } ?>
                    <?php if($item['updatable'] == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                      <a href="<?= base_url(); ?>admin_category/edit/<?= $item['id_category']; ?>" title="Edit Category" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                    <?php } ?>
                    <?php if($item['deletable'] == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                      <a href="<?= base_url(); ?>admin_category/delete/<?= $item['id_category']; ?>" title="Delete Category" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are sure want to delete this category?"><span class="fa fa-times"></span></a>
                    <?php } ?>
                  </td>
                  <td class="text-nowrap align-middle"><?= $item['category_name']; ?></td>
                  <td class="text-nowrap align-middle">page/category/<?= $item['id_category']; ?>/<?= strtolower(url_title($item['category_name'])); ?></td>
                </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in category database.</p>
          <?php } ?>
        
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->