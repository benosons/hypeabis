<? $this->load->view('admin/merchandise/sub_header'); ?>

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
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap" colspan="2"><b>Merchandise</b></th>
                  <th class="text-nowrap"><b>Quota / Qty</b></th>
                  <th class="text-nowrap"><b>Point</b></th>
                  <th class="text-nowrap"><b>Publish</b></th>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($merchandise as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-top">
                      <a href="<?= base_url(); ?>admin_merchandise/edit/<?= $item->id_merchandise; ?>" title="Edit Merchandise" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_merchandise/delete/<?= $item->id_merchandise; ?>" title="Delete Merchandise" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-top" width="100px">
                      <img src="<?= base_url(); ?>assets/merchandise/thumb/<?= $item->merch_pic_thumb; ?>" class="img img-thumbnail img-fluid"/>
                    </td>
                    <td class="align-top"><?= $item->merch_name; ?></td>
                    <td class="align-top"><?= $item->merch_quota; ?></td>
                    <td class="align-top"><?= $item->merch_point; ?></td>
                    <td class="align-top">
                      <? if($item->merch_publish == 1){ ?>
                        <label class="label label-info">Published</label>
                      <? } else { ?>
                        <label class="label label-warning">Not active</label>
                      <? } ?>
                    </td>
                  </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in merchandise database.</p>
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