<?php $this->load->view('admin/contact/sub_header'); ?>

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
          <?php $this->load->view('admin/contact/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Subject</b></th>
                  <th class="text-nowrap"><b>Sender</b></th>
                  <th class="text-nowrap"><b>Message</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($contact as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-top"><?= ($x + 1); ?></td>
                    <td class="text-nowrap align-top">
                      <a href="<?= base_url(); ?>admin_contact/detail/<?= $item->id_contact; ?>" title="Read" class="btn btn-xs btn-info"><span class="fa fa-eye"></span> Read</a>
                      <a href="<?= base_url(); ?>admin_contact/delete/<?= $item->id_contact; ?>" title="Delete Message" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this message?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-top">
                      <?= $item->contact_title; ?>
                    </td>
                    <td class="align-top">
                      <?= ($item->read_status == 0 ? '<b>' . $item->name . '</b>' : $item->name); ?><br/>
                      <?= $item->email; ?><br/>
                      <?= $item->phone; ?>
                    </td>
                    <td class="align-top">
                      <?= ($item->read_status == 0 ? '<b>' . date('d-M-Y, H:i:s',strtotime($item->submit_date)) . '</b>' : date('d-M-Y, H:i:s',strtotime($item->submit_date))); ?>
                      <br/>
                      <?= substr(strip_tags(nl2br($item->message)), 0, 200); ?>
                      <?= (strlen(strip_tags(nl2br($item->message))) > 200 ? '...[read more]' : ''); ?>
                    </td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in contact database.</p>
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