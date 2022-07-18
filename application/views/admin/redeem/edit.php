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
            Edit Redeemtion Status
          </h4>
          
          <?= $this->session->flashdata('message'); ?>
          
          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
          
            <?= form_open_multipart("admin_redeem/saveEdit/" . $redeem[0]->id_merchandise_redeem ,array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'redeem_form', 'autocomplete' => 'off')); ?>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">User</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="name" value="<?= $redeem[0]->name; ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Merchandise</label>
                  <div class="col-md-8 col-xs-12">
                    <input class="form-control" type="text" name="merch_name" value="<?= $redeem[0]->merch_name; ?> (<?= $redeem[0]->point ?> poin)" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Request date</label>
                  <div class="col-md-4 col-xs-12">
                    <input class="form-control" type="text" name="request_date" value="<?= date('d-M-Y H:i'); ?>" readonly="yes" disabled/>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                  <div class="col-md-4 col-xs-12">
                    <select name="redeem_status" class="full-width select_nosearch" <?= ($redeem[0]->redeem_status == 2 || $redeem[0]->redeem_status == 3 ? ' readonle="yes" disabled' : ''); ?>>
                      <option value="0" <?= ($redeem[0]->redeem_status == '0' ? 'selected' : ''); ?>>Menunggu konfirmasi</option>
                      <option value="1" <?= ($redeem[0]->redeem_status == '1' ? 'selected' : ''); ?>>Confirmed</option>
                      <option value="2" <?= ($redeem[0]->redeem_status == '2' ? 'selected' : ''); ?>>Done</option>
                      <option value="3" <?= ($redeem[0]->redeem_status == '3' ? 'selected' : ''); ?>>Batal</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <? if($redeem[0]->redeem_status != 2 && $redeem[0]->redeem_status != 3){ ?>
                <div class="form-group">
                  <div class="row">
                    <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                    <div class="col-md-9">
                      <button class="btn btn-complete bnt-need-confirmation sm-m-b-10" data-message="Data ini tidak dapat diubah kembali setelah status done atau batal. Lanjutkan proses?" type="submit">Submit</button>
                      <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                    </div>
                  </div>
                </div>
              <? } ?>
            <?= form_close(); ?>
            
          </div>

        </div>
      </div>
      <!-- END CARD -->
    </div>
  </div>
  
  <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<script type="text/javascript">
	$(document).ready(function() {
		$('#redeem_form').bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				title: {
					group: '.col-md-8',
					validators: {
						notEmpty: {
							message: 'Title is required and cannot be empty'
						}
					}
				},
				redeem: {
					group: '.col-md-10',
					validators: {
						notEmpty: {
							message: 'Redeem is required and cannot be empty'
						}
					}
				}
			}
		});
	});
</script>