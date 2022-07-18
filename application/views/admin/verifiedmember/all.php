<?php $this->load->view('admin/verifiedmember/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          <?= $this->session->flashdata('message'); ?>
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr class="">
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="100px"><b>Action</b></th>
                  <th class="text-nowrap"><b>User</b></th>
                  <th class="text-nowrap" width="175px"><b>Status</b></th>
                  <th class="text-nowrap"><b>Tanggal Pengajuan</b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($submissions as $key => $submission): ?>
                  <tr>
                    <td class="text-nowrap"><?php echo $key + $offset + 1 ?></td>
                    <td class="text-nowrap">
                      <a class="btn btn-xs btn-info" href="<?php echo base_url() ?>admin_verifiedmember/edit/<?php echo $submission->id ?>" title="Edit">
                        <span class="fa fa-pencil"></span>
                      </a>
                      <a class="btn btn-xs btn-info btn-need-confirmation" href="<?php echo base_url() ?>admin_verifiedmember/delete/<?php echo $submission->id ?>" title="Delete" data-message="Are you sure want to delete this?">
                        <span class="fa fa-times"></span>
                      </a>
                    </td>
                    <td><?php echo $submission->name ?> (<?php echo $submission->email ?>)</td>
                    <td>
                      <?php if (is_null($submission->is_accepted)): ?>
                        <label class="label label-warning">Menunggu</label>
                      <?php elseif ($submission->is_accepted === '1'): ?>
                        <label class="label label-info">Accepted</label>
                      <?php else: ?>
                        <label class="label label-danger">Rejected</label>
                      <?php endif; ?>
                    </td>
                    <td><?php echo date('d-M-Y, H:i:s',strtotime($submission->created_at)) ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (count($submissions) === 0): ?>
                  <tr>
                    <td class="text-center" colspan="5">Belum ada data pengajuan verified member.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php echo $this->pagination->create_links() ?>
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
