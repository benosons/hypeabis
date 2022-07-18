<?php $this->load->view('admin/competition/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
                        Edit Kategori Kompetisi - <?= $competition->title; ?>
                    </h4>

                    <?php echo $this->session->flashdata('message') ?>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                        <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'add_category_form', 'autocomplete' => 'off']); ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-2 control-label text-right sm-text-left">Nama kategori</label>
                                    <div class="col-md-9 col-xs-12">
                                        <input id="title" class="form-control" type="text" name="category_name" value="<?= $competition_category->category_name; ?>" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row"><label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                    <div class="col-md-9">
                                        <button class="btn btn-complete sm-m-b-10" type="submit">Update</button>
                                        <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                                class="fa fa-chevron-circle-left"></i> Back
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
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
    $(document).ready(function () {
        $('#add_category_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: {
                category_name: {
                    validators: {
                        notEmpty: {
                            message: 'Nama kategori harus diisi. '
                        }
                    }
                }
            }
        });
    });
</script>