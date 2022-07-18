<? $this->load->view('admin/redeem/sub_header'); ?>

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
          <? $this->load->view('admin/redeem/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>User</b></th>
                  <th class="text-nowrap"><b>Redeem information</b></th>
                  <th class="text-nowrap"><b>Request date</b></th>
                  <th class="text-nowrap" width="175px"><b>Status</b></th>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($redeem as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-top"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-top">
                      <a href="<?= base_url(); ?>admin_redeem/edit/<?= $item->id_merchandise_redeem; ?>" title="Edit" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span> Approval</a>
                      <!--<a href="<?= base_url(); ?>admin_redeem/delete/<?= $item->id_merchandise_redeem; ?>" title="Delete" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>-->
                    </td>
                    <td class="align-top">
                      <b><?= $item->name; ?></b><br/>
                      email: <?= $item->email; ?><br/>
                      nomor kontak: <?= $item->contact_number; ?>
                    </td>
                    <td class="align-top">
                      <b><?= $item->merch_name; ?></b><br/>
                      (<?= $item->point; ?> poin)
                    </td>
                    <td class="align-top">
                      <?= date('d-M-Y H:i', strtotime($item->request_date)); ?>
                    </td>
                    <td class="align-top">
                      <? if($item->redeem_status == 0){ ?>
                        <label class="label label-warning">Menunggu konfirmasi</label>
                      <? } else if($item->redeem_status == 1){ ?>
                        <label class="label label-info">Confirmed</label>
                      <? } else if($item->redeem_status == 2){ ?>
                        <label class="label label-info">Done</label>
                      <? } else if($item->redeem_status == 3){ ?>
                        <label class="label label-danger">Batal</label>
                      <? } else {}?>
                    </td>
                  </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in redeem database.</p>
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