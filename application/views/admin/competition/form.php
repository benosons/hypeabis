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
                        <?php echo $heading_text ?> Kompetisi
                    </h4>

                    <?php echo $this->session->flashdata('message') ?>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                        <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'ad_position_form', 'autocomplete' => 'off']); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-9 col-xs-12">
                                    <input id="title" class="form-control" type="text" name="title"
                                        value="<?php echo $form_value['title'] ?>" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Tipe kompetisi</label>
                                <div class="col-md-6 col-xs-12">
                                    <select name="competition_type" class="full-width select_nosearch">
                                        <option
                                            value="photo" <?= ($form_value['competition_type'] == 'photo' ? 'selected' : ''); ?>>Hype Photo
                                        </option>
                                        <option
                                            value="article" <?= ($form_value['competition_type'] == 'article' ? 'selected' : ''); ?>>Writing Contest
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group builtin_wrapper">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Tanggal</label>
                                <div class="col-md-3">
                                    <div class="input-daterange datepicker-range">
                                        <input type="text" class="form-control datepicker-range-start" name="start_date"
                                            value="<?php echo $form_value['start_date'] ?>" id="start_date"
                                            data-date-start-date="<?php echo date('d-m-Y', strtotime('tomorrow')) ?>"
                                            onkeydown="return false" required/>
                                    </div>
                                </div>
                                <label class="col-md-1 control-label text-right sm-text-left">Sampai</label>
                                <div class="col-md-3">
                                    <div class="input-daterange input-group datepicker-range">
                                        <input type="text" class="form-control datepicker-range-finish"
                                            name="finish_date" value="<?php echo $form_value['finish_date'] ?>"
                                            id="finish_date" onkeydown="return false" required/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Cover Status</label>
                                <div class="col-md-9">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="cover_status" id="cover_status1" <?= ($form_value['cover_status'] == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="cover_status1">Active</label>
                                        <input type="radio" value="0" name="cover_status" id="cover_status0" <?= ($form_value['cover_status'] == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="cover_status0">Non-Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Maksimal Submit</label>
                                <div class="col-md-2 col-xs-12">
                                    <input id="max_content" class="form-control" type="number" name="max_content"
                                        value="<?php echo $form_value['max_content'] ?>" min="1" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Deskripsi</label>
                                <div class="col-md-10 col-xs-12">
                                    <?php $content_file_path = base_url() . "assets/content/"; ?>
                                    <?php $description = str_replace("##BASE_URL##", $content_file_path, html_entity_decode($form_value['description'])); ?>
                                    <textarea class="ckeditor form-control" name="description" rows="10"><?= $description; ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Banner</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (isset($form_value['pic']) && strlen(trim($form_value['pic'])) > 0) { ?>
                                        <div class="file-preview">
                                            <a href="<?= base_url(); ?>admin_competition/deletePic/<?= $id; ?>"
                                                class="close fileinput-remove text-right btn-need-confirmation"
                                                data-message="Are you sure want to remove this picture?"
                                                title="remove / delete"><span
                                                    class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                                            <div class="file-preview-thumbnails">
                                                <div class="file-preview-frame">
                                                    <img
                                                        src="<?= base_url(); ?>assets/competition/<?= $form_value['pic']; ?>"
                                                        class="file-preview-image img-thumbnail img-fluid"
                                                        title="<?= $form_value['pic']; ?>" width="auto"
                                                        style="max-height:100px">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="file-preview-status text-center text-complete"></div>
                                        </div>
                                    <?php } ?>

                                    <input type="file" class="file" name="pic" id="pic" data-show-upload="false"
                                        data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["jpg", "png"]'/>
                                    <p class="hint-text">
                                        <small>*(Leave this field blank if you don't want to upload picture)</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Gambar default untuk artikel</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (isset($form_value['default_pic']) && strlen(trim($form_value['default_pic'])) > 0) { ?>
                                        <div class="file-preview">
                                            <div class="file-preview-thumbnails">
                                                <div class="file-preview-frame">
                                                    <img
                                                        src="<?= base_url(); ?>assets/competition/<?= $form_value['default_pic']; ?>"
                                                        class="file-preview-image img-thumbnail img-fluid"
                                                        title="<?= $form_value['default_pic']; ?>" width="auto"
                                                        style="max-height:100px">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="file-preview-status text-center text-complete"></div>
                                        </div>
                                    <?php } ?>

                                    <input type="file" class="file" name="default_pic" id="default_pic" data-show-upload="false"
                                        data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["jpg", "png"]'/>
                                    <p class="hint-text">
                                        <small>*(Size recommendation: <?= $this->default_pic_width; ?>px x <?= $this->default_pic_height; ?>px)</small></p>
                                </div>
                            </div>
                        </div>

                        <?php if(isset($mode) && $mode == 'add'){ ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-2 control-label text-right sm-text-left">Kategori Kompetisi</label>
                                    <div class="col-md-8 col-xs-12">
                                        <input type="text" class="tagsinput form-control" name="categories" value="" />
                                        <p class="hint-text"><small>*Ketikkan kategori kompetisi lalu tekan enter untuk menambahkan</small></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div class="row"><label
                                    class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                <div class="col-md-9">
                                    <button class="btn btn-complete sm-m-b-10" type="submit"><?= (isset($mode) && $mode == 'add' ? 'Submit' : 'Update'); ?></button>
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                    <?php if(! (isset($mode) && $mode == 'add')){ ?>
                        <div class="row">
                            <div class="col-md-9 m-b-10">
                                <h4 class="m-t-30 m-b-15 fw-600 text-heading-black mr-auto">
                                    Kategori Kompetisi
                                </h4>
                            </div>
                            <div class="col-md-3 pull-right m-b-10 m-t-30">
                                <a href="<?= $base_url; ?>/addCategory/<?= $id; ?>" class="btn btn-complete pull-right">Add Category</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="">
                                        <th class="text-nowrap"><b>Action</b></th>
                                        <th class="text-nowrap"><b>Nama kategori</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $category){ ?>
                                        <tr>
                                            <td style="width:150px">
                                                <a href="<?php echo $base_url ?>/editCategory/<?= $id; ?>/<?= $category->id_competition_category; ?>"
                                                    title="Edit" class="btn btn-xs btn-info"><span
                                                        class="fa fa-pencil"></span></a>
                                                <a href="<?php echo $base_url ?>/deleteCategory/<?= $id; ?>/<?= $category->id_competition_category; ?>"
                                                    title="Delete" class="btn btn-xs btn-info btn-need-confirmation"
                                                    data-message="Are you sure want to delete this competition category?"><span
                                                        class="fa fa-times"></span></a>
                                            </td>
                                            <td>
                                                <?= $category->category_name; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

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
        $('#ad_position_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: {
                title: {
                    validators: {
                        notEmpty: {
                            message: 'Judul kompetisi harus diisi. '
                        }
                    }
                },
                start_date: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih Tanggal Mulai.'
                        },
                        date: {
                            format: 'DD-MM-YYYY',
                            message: 'Format tanggal harus dd-mm-yyyy'
                        }
                    }
                },
                finish_date: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih Tanggal Akhir.'
                        },
                        date: {
                            format: 'DD-MM-YYYY',
                            message: 'Format tanggal harus dd-mm-yyyy'
                        }
                    }
                },
                max_content: {
                    validators: {
                        notEmpty: {
                            message: 'Maksimal submit harus diisi. '
                        },
                        greaterThan: {
                            message: 'Maksimal submit harus lebih atau sama dengan 1.',
                            value: 1,
                        }
                    }
                },
            }
        });

        setTimeout(function () {
            $('#start_date').on('changeDate', function (selected) {
                $('#ad_position_form').bootstrapValidator('revalidateField', 'start_date');
                $('#id_adstype').select2('val', '');
            });

            $('#finish_date').on('changeDate', function () {
                $('#ad_position_form').bootstrapValidator('revalidateField', 'finish_date');
                $('#id_adstype').select2('val', '');
            });
        }, 100);
    });
</script>
