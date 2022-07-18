<?php $this->load->view('admin/level/sub_header'); ?>

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
                  <th class="text-nowrap"><b>Level / label name</b></th>
                  <th class="text-nowrap"><b>Minimum point</b></th>
                  <th class="text-nowrap"><b>Label color</b></th>
                </tr>
              </thead>
              <tbody>
                <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <?php foreach($level as $item){ ?>
                  <tr>
                    <td class="text-nowrap align-middle">
                      <a href="<?= base_url(); ?>admin_level/edit/<?= $item->id_level; ?>" title="Edit Level" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_level/delete/<?= $item->id_level; ?>" title="Delete Level" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this data?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="align-middle"><?= $item->level_name; ?></td>
                    <td class="align-middle"><?= $item->level_point; ?></td>
                    <td class="align-middle">
                      <label class="label" style="background:#<?= $item->bg_color; ?>;color:#<?= $item->text_color; ?>">
                        <?= $item->level_name; ?>
                      </label>
                    </td>
                  </tr>
                <?php $x++; ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <?php if($x == 0){ ?>
            <p class="m-t-40">There's no data in database.</p>
          <?php } ?>
          
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->