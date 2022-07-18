<?php $this->load->view('admin/interest/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
  <!-- BEGIN PlACE PAGE CONTENT HERE -->

  <div class="row">
    <div class="col-md-12">

      <!-- START card -->
      <div class="card card-transparent">
        <div class="card-body">

          <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
            Edit Interest Option
          </h4>

          <?= $this->session->flashdata('message'); ?>

          <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

            <?= form_open("admin_interest/saveEdit/" . $interest[0]->id_interest, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'interest_form', 'autocomplete' => 'off')); ?>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Interest</label>
                <div class="col-md-8 col-xs-12">
                  <input class="form-control" type="text" name="interest" value="<?= $interest[0]->interest; ?>" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left">Urutan</label>
                <div class="col-md-2 col-xs-12">
                  <input class="form-control" type="number" name="order" value="<?= $interest[0]->order; ?>" />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                <div class="col-md-9">
                  <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                  <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i class="fa fa-chevron-circle-left"></i> Back</button>
                </div>
              </div>
            </div>
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
    $('#interest_form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        interest: {
          validators: {
            notEmpty: {
              message: 'Interest option is required and cannot be empty'
            }
          }
        }
      }
    });
  });
</script>