<? $this->load->view('dev/module/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="20%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Module Name</b></th>
                  <th class="text-nowrap"><b>Module URL</b></th>
                  <th class="text-nowrap"><b>Status</b></th>
                  <th class="text-nowrap"><b>Order</b></th>
                </tr>
              </thead>
              <tbody>
                <? $counter = 0; ?>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($module as $item){ ?>
                <tr>
                  <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                  <td class="text-nowrap align-middle">
                    <? if($item->updatable == '1'){ ?>
                      <a href="<?= base_url(); ?>dev_module/addSub/<?= $item->id_module; ?>" title="Add Sub Module" class="btn btn-xs btn-info"><span class="fa fa-sort-amount-desc"></span> Add Sub</a>
                      <a href="<?= base_url(); ?>dev_module/edit/<?= $item->id_module; ?>" title="Edit Module" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                    <? } ?>
                    <? if($item->deletable == '1'){ ?>
                      <a href="<?= base_url(); ?>dev_module/delete/<?= $item->id_module; ?>" title="Delete Module" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure to delete this data?">
                        <span class="fa fa-times"></span>
                      </a>
                    <? } ?>
                    <? if($item->updatable != '1' && $item->deletable != '1'){ ?>
                      <strong>Built-in module</strong>
                    <? } ?>
                  </td>
                  <td class="align-middle"><?= $item->module_name; ?></td>
                  <td class="align-middle"><?= $item->module_redirect; ?></td>
                  <td>
                    <? if($item->module_status == '1'){ ?>
                      <span class="fa fa-check-square text-complete"></span> Show
                    <? } else {?>
                      <span class="fa fa-minus-square text-danger"></span> Hidden
                    <? } ?>
                  </td>
                  <td class="align-middle"><?= $item->module_order; ?></td>
                </tr>
                <? $x++; ?>
                <? $counter++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in module database.</p>
          <? } ?>
        
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->