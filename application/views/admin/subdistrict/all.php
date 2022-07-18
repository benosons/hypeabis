<? $this->load->view('admin/subdistrict/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <? if(strtolower($this->uri->segment(2)) == 'search'){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Search Result :</h4>
          <? } ?>
          
          <!-- Start Search Bar --> 
          <? $this->load->view('admin/subdistrict/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Subdistrict name</b></th>
                  <th class="text-nowrap"><b>City / District</b></th>
                  <th class="text-nowrap"><b>Province</b></th>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($subdistrict as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_subdistrict/edit/<?= $item->id_subdistrict; ?>" title="Edit Subdistrict" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_subdistrict/delete/<?= $item->id_subdistrict; ?>" title="Delete Subdistrict" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-middle"><?= $item->subdistrict_name; ?></td>
                    <td class="align-middle"><?= $item->city_type; ?> <?= $item->city_name; ?></td>
                    <td class="align-middle"><?= $item->province_name; ?></td>
                  </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in subdistrict database.</p>
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