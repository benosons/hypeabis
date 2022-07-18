<?php $this->load->view('admin/comment/sub_header'); ?>

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
          <?php $this->load->view('admin/comment/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="10%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Member</b></th>
                  <th class="text-nowrap"><b>Komentar</b></th>
                  <th class="text-nowrap" width="175px"><b>Status</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($comment as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_comment/delete/<?= $item->id_content_comment; ?>" title="Delete Comment" class="btn btn-xs btn-info btn-need-confirmation" data-message="Anda yakin ingin menghapus komentar ini?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-middle"><?= $item->name; ?></td>
                    <td class="align-middle">
                      <b><?= (strlen(trim($item->title)) > 70 ? substr($item->title, 0, 67) . '...' : $item->title); ?></b><br/>
                      "<?= nl2br($item->comment); ?>"<br/>
                      <?= date('d-M-Y H:i', strtotime($item->comment_date)); ?>
                    </td>
                    <td class="align-middle">
                      <?php if($item->comment_status == 1){ ?>
                        <label class="label label-info">Published</label>
                      <?php } else { ?>
                        <a href="<?= base_url(); ?>admin_comment/approve/<?= $item->id_content_comment; ?>" title="Klik untuk approve komentar" class="btn-need-confirmation" data-message="Anda yakin ingin mempublish komentar ini?">
                          <label class="label label-warning link">Menunggu approval</label>
                        </a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in comment database.</p>
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