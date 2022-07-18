<? $this->load->view('admin/example/sub_header'); ?>

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
          <? $this->load->view('admin/example/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Example name</b></th>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($example as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_example/edit/<?= $item->id_example; ?>" title="Edit Example" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_example/delete/<?= $item->id_example; ?>" title="Delete Example" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-middle"><?= $item->example_text; ?></td>
                  </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in example database.</p>
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