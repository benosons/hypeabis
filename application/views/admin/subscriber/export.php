<?php $this->load->view('admin/subscriber/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <?= $this->session->flashdata('message'); ?>
      
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Export Subscriber</h4>
          
          <!-- Start Search Bar --> 
          <?php $this->load->view('admin/subscriber/search_export'); ?>
          <!-- End Search Bar -->
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->