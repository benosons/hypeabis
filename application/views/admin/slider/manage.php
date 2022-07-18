<? $this->load->view('admin/slider/sub_header_manage'); ?>

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
                <tr>
                  <th class="text-nowrap"><b>Action</b></th>
                  <th class="text-nowrap" width="20%"><b>Slider picture</b></th>
                  <th class="text-nowrap">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <? foreach($slider_content as $x => $item){ ?>
                  <tr>
                    <td class="text-nowrap align-top">
                      <a href="<?= base_url(); ?>admin_slider/editContent/<?= $item->id_slider; ?>/<?= $item->id_slider_content; ?>" title="Edit Slide" class="btn btn-xs btn-info"><span class="fa fa-pencil"></span></a>
                      <a href="<?= base_url(); ?>admin_slider/deleteContent/<?= $item->id_slider; ?>/<?= $item->id_slider_content; ?>" title="Delete Slide" class="btn btn-xs btn-info btn-need-confirmation" data-message="Are you sure want to delete this slide?"><span class="fa fa-times"></span></a>
                    </td>
                    <td class="text-nowrap align-top">
                      <img src="<?= base_url(); ?>assets/slider/thumb/<?= $item->slider_pic_thumb; ?>" class="img img-fluid img-thumbnail" style="width:auto;max-height:60px;">
                    </td>
                    <td class="align-top">
                      <? if(isset($item->text1) && strlen(trim($item->text1)) > 0){ ?>
                        <b><?= $item->text1; ?></b><br/>
                      <? } ?>
                      <? if(isset($item->text2) && strlen(trim($item->text2)) > 0){ ?>
                        <?= $item->text2; ?><br/>
                      <? } ?>
                      <? if(isset($item->text3) && strlen(trim($item->text3)) > 0){ ?>
                        <?= $item->text3; ?><br/>
                      <? } ?>
                      <? if(isset($item->button_text) && strlen(trim($item->button_text)) > 0){ ?>
                        <?= $item->button_text; ?><br/>
                      <? } ?>
                      <? if(isset($item->redirect_url) && strlen(trim($item->redirect_url)) > 0){ ?>
                        <?= $item->redirect_url; ?>
                      <? } ?>
                    </td>
                  </tr>
                <? } ?>
              </tbody>
            </table>
          </div>
          
          <? if(count($slider_content) == 0){ ?>
            <p class="m-t-40">There's no data in slider database.</p>
          <? } ?>
          
        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->