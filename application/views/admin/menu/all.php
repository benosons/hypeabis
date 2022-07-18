<? $this->load->view('admin/menu/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->
  
  <div class="row">
    <div class="col-md-12">
    
      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">
          
          <? if(strtolower($this->uri->segment(2)) == 'search'){ ?>
            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Search Result :</h4>
          <? } ?>
          
          <?= $this->session->flashdata('message'); ?>
          
          <!-- Start Search Bar --> 
          <? $this->load->view('admin/menu/search'); ?>
          <!-- End Search Bar -->
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-nowrap" width="5%"><b>No</b></th>
                  <th class="text-nowrap" width="15%"><b>Action</b></th>
                  <th class="text-nowrap"><b>Type</b></th>
                  <th class="text-nowrap"><b>Menu Name</b></th>
                  <th class="text-nowrap"><b>URL</b></th>
                  <th class="text-nowrap"><b>Updatable</b></th>
                  <? if($this->session->userdata('admin_level') == '1'){ ?>
                    <th class="text-nowrap"><b>Developer ID</b></th>
                  <? } ?>
                </tr>
              </thead>
              <tbody>
                <? $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                <? foreach($menus as $item){ ?>
                <tr>
                  <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                  <td class="text-nowrap align-middle">
                    <? if($item['menu_type'] != 'builtin' || $this->session->userdata('admin_level') == '1'){ ?>
                      <a href="<?= base_url(); ?>admin_menu/addSub/<?= $item['id_menu']; ?>" title="Add Sub Menu" class="btn btn-xs btn-info"><span class="fa fa-sort-amount-desc"></span> Add Sub</a>
                    <? } ?>
                    <a href="<?= base_url(); ?>admin_menu/edit/<?= $item['id_menu']; ?>" title="Edit Menu" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                    <? if($item['deletable'] == '1' || $this->session->userdata('admin_level') == '1'){ ?>
                      <a href="<?= base_url(); ?>admin_menu/delete/<?= $item['id_menu']; ?>" title="Delete Menu" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are sure want to delete this menu?"><span class="fa fa-times"></span></a>
                    <? } ?>
                  </td>
                  <td class="text-nowrap align-middle">
                    <? if($item['menu_type'] == 'multicolumn'){ ?>
                      Multi-column
                    <? } else if($item['menu_type'] == 'builtin') { ?>
                      Built-in menu
                    <? } else { ?>
                      Default
                    <? } ?>
                  </td>
                  <td class="text-nowrap align-middle"><?= $item['menu_name']; ?></td>
                  <td class="text-nowrap align-middle"><?= $item['redirect_url']; ?></td>
                  <td class="text-nowrap align-middle"><?= ($item['updatable'] == '1' ? 'Yes' : 'No'); ?></td>
                  <? if($this->session->userdata('admin_level') == '1'){ ?>
                    <td class="text-nowrap"><?= (strlen(trim($item['dev_code'])) > 0 ? $item['dev_code'] : '-'); ?></td>
                  <? } ?>
                </tr>
                <? $x++; ?>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if($x == 0){ ?>
            <p class="m-t-40">There's no data in menu database.</p>
          <? } ?>
        
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->