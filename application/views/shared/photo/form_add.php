<?php $this->load->view('shared/photo/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?php echo $this->session->flashdata('message') ?>

                    <?php echo form_open_multipart($submit_url, ['class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off']); ?>
                    <?php if (isset($competition) && count($competition) > 0) { ?>
                        <div class="d-flex">
                            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
                                <i class="fa fa-trophy text-warning" style="font-size:40px;"></i>
                                Kompetisi<?= (count($competition) > 1 ? ':' : ' ' . $competition[0]->title); ?>
                            </h4>
                        </div>

                        <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                            <div class="form-group">
                                <div class="row">
                                    <label
                                        class="col-md-3 control-label text-right sm-text-left text-black">Ikutkan foto ini dalam kompetisi:</label>
                                    <div class="col-md-8">
                                        <div class="alert alert-warning" <?= ($has_reach_submit_limit ? '' : 'style="display:none"'); ?>>
                                            Anda telah mengikutkan <?= $competition[0]->max_content; ?> foto pada kompetisi <?php echo $competition[0]->title; ?>.
                                            <br/>
                                            Anda hanya dapat mendaftarkan maksimal <?= $competition[0]->max_content; ?> foto untuk kompetisi ini.
                                        </div>

                                        <div class="radio radio-complete" <?= ($has_reach_submit_limit ? 'style="display:none"' : ''); ?>>
                                            <input type="radio" value="1" name="join_competition"
                                                id="join_competition1" <?= ($form_value['join_competition'] == '1' && (! $has_reach_submit_limit) ? 'checked="checked"' : ''); ?>>
                                            <label for="join_competition1" calss="text-black">Ya</label>
                                            <input type="radio" value="0" name="join_competition"
                                                id="join_competition0" <?= ($form_value['join_competition'] == '0' || $has_reach_submit_limit ? 'checked="checked"' : ''); ?>>
                                            <label for="join_competition0" class="text-black">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="competition_option_wrapper" <?= (count($competition) > 0 ? '' : 'style="display:none"'); ?>>
                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left text-black">Pilih kompetisi:</label>
                                        <div class="col-md-8">
                                            <select name="id_competition" id="id_competition" class="full-width select_nosearch" onchange="updateCompetitionCategory()">
                                                <?php foreach($competition as $comp){ ?>
                                                    <option value="<?= $comp->id_competition; ?>" <?= (($form_value['id_competition'] ?? false) == $comp->id_competition ? 'selected' : ''); ?>>
                                                        <?= $comp->title; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" <?= (isset($competition_category) && count($competition_category) > 0 ? '' : 'style="display:none"'); ?>>
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left text-black">Pilih kategori:</label>
                                        <div class="col-md-8">
                                            <select name="id_competition_category" id="id_competition_category" class="full-width select_nosearch">
                                                <?php foreach($competition_category as $cat){ ?>
                                                    <option value="<?= $cat->id_competition_category; ?>" <?= (($form_value['id_competition_category'] ?? false) == $cat->id_competition_category ? 'selected' : ''); ?>>
                                                        <?= $cat->category_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left text-black"></label>
                                        <div class="col-md-8">
                                            <div class="checkbox check-complete">
                                                <input type="checkbox" 
                                                    value="1" 
                                                    id="agree_competition_terms" 
                                                    name="agree_competition_terms" 
                                                    <?= ($form_value['agree_competition_terms'] == '1' ? 'checked="checked"' : ''); ?>
                                                />
                                                <label for="agree_competition_terms" class="text-black">Menyetujui Syarat &amp; Ketentuan Kompetisi</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">&nbsp;</label>
                                        <div class="col-md-8">
                                            <a href="javascript:;" onclick="showModal()" class="btn btn-light"><i
                                                    class="fa fa-question-circle"></i> Syarat &amp; Ketentuan Kompetisi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($competition as $comp) { ?>
                            <div class="modal fade slide-down disable-scroll stick-up" id="modal_competition_<?= $comp->id_competition; ?>" tabindex="-1"
                                role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content-wrapper">
                                        <div class="modal-content">
                                            <div class="modal-header clearfix text-left b-grey p-t-20 p-b-10 m-b-20">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    <i class="pg-close fs-14"></i>
                                                </button>
                                                <h5><span class="bold"><?php echo $comp->title ?></span></h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <?php if (isset($comp->pic) && strlen(trim($comp->pic)) > 0) { ?>
                                                            <img
                                                                src="<?= base_url(); ?>assets/competition/<?= $comp->pic; ?>"
                                                                class="img img-fluid"/>
                                                        <?php } ?>
                                                        <p class="m-t-20">
                                                            <?php $content_file_path = base_url() . "assets/content/"; ?>
                                                            <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($comp->description)); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <div class="d-flex">
                        <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
                            <?php echo $heading_text ?> Photo
                        </h4>
                        <div>
                            <?php if (isset($content) && $is_admin) { ?>
                                <?php if ($content->edit_id_admin) { ?>
                                    <a class="btn btn-complete <?php echo((!is_null($locked_content_id) && $content->id_content !== $locked_content_id) ? 'disabled' : '') ?>"
                                        href="<?php echo $base_url ?>/unlock_edit/<?php echo $content->id_content ?>">
                                        <i class="fa fa-unlock mr-1"></i> Unlock Editor
                                    </a>
                                <?php } else { ?>
                                    <a class="btn btn-danger <?php echo((!is_null($locked_content_id) && $content->id_content !== $locked_content_id) ? 'disabled' : '') ?>"
                                        href="<?php echo $base_url ?>/lock_edit/<?php echo $content->id_content ?>"
                                        style="height:">
                                        <i class="fa fa-lock mr-1"></i> Lock Editor
                                    </a>

                                    <?php if (!is_null($locked_content_id) && $content->id_content !== $locked_content_id) { ?>
                                        <a class="btn btn-complete"
                                            href="<?php echo $base_url ?>/edit/<?php echo $locked_content_id ?>">
                                            <i class="fa fa-pencil mr-1"></i> Edit Hypephoto Terkunci
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title"
                                        value="<?php echo $form_value['title'] ?>" required/>
                                </div>
                            </div>
                        </div>

                        <?php if ($is_admin) { ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label text-right sm-text-left">Penulis</label>
                                    <div class="col-md-8 col-xs-12">
                                        <select class="full-width"
                                            name="id_user" <?php echo isset($content) ? 'readonly' : '' ?>>
                                            <option value=""> -</option>
                                            <?php foreach ($users as $x => $user) { ?>
                                                <option
                                                    value="<?= $user->id_user; ?>" <?= (isset($content) && $content->id_user == $user->id_user ? 'selected' : ''); ?>>
                                                    <?= $user->name; ?> (<?= $user->email; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php $photo_counts = count($photos) ?>
                        <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <?php $index = $i - 1 ?>

                            <?php if (($is_admin && isset($content) && isset($photos[$index])) || !$is_admin) { ?>
                                <div id="file-pic-<?php echo $i ?>-form-group" class="<?= ($i > 1 ? 'picture-input-collapse' : ''); ?> form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Gambar <?php echo $i ?></label>
                                        <div class="col-md-8 col-xs-12">
                                            <?php if (isset($photos[$index])) { ?>
                                                <div class="file-preview">
                                                    <?php if ($photo_counts > $min_total_photos) : ?>
                                                        <a href="<?php echo $base_url ?>/delete_photo/<?php echo $photos[$index]->id ?>"
                                                            class="close fileinput&#45;remove text&#45;right"
                                                            title="remove / delete"><span
                                                                class="fs&#45;16 fa fa&#45;times m&#45;l&#45;5 m&#45;r&#45;5"></span>
                                                        </a>
                                                    <?php endif; ?>
                                                    <div class="file-preview-thumbnails">
                                                        <div class="file-preview-frame">
                                                            <a href="<?php echo base_url(); ?>assets/photo/<?php echo $photos[$index]->picture ?>"
                                                                target="_blank">
                                                                <img
                                                                    src="<?php echo base_url(); ?>assets/photo/thumb/<?php echo $photos[$index]->picture_thumb ?>"
                                                                    class="file-preview-image"
                                                                    title="<?php echo $content->content_pic ?>"
                                                                    width="auto" style="max-height:100px">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="file-preview-status text-center text-complete"></div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!$is_admin || ($is_admin && !isset($content)) || true) { ?>
                                                <input type="file" class="file" name="file_pic_<?php echo $i ?>"
                                                    id="file_pic_<?php echo $i ?>" data-show-upload="false"
                                                    data-show-close="false" data-show-preview="false"
                                                    data-allowed-file-extensions='["jpg", "jpeg", "png", "gif"]'/>
                                                <p class="hint-text mb-0 float-left w-100">
                                                    <small>*(Maksimal 5MB. Rekomendasi ukuran: <?php echo $this->pic_width; ?>px x <?php echo $this->pic_height; ?>px. Format file yang diperbolehkan: jpg, png &amp; gif.)</small>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="<?= ($i > 1 ? 'picture-input-collapse' : ''); ?> form-group mb-4">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Deskripsi <?php echo $i ?></label>
                                        <div class="col-md-8 col-xs-12">
                                            <textarea class="form-control" type="text"
                                                name="short_desc_<?php echo $i ?>"
                                                id="short_desc_<?php echo $i ?>"><?php echo(isset($form_value["short_desc_{$i}"]) ? $form_value["short_desc_{$i}"] : (isset($photos[$index]) ? $photos[$index]->short_desc : '')) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Tags</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="tagsinput form-control" name="tags"
                                        value="<?php echo isset($form_value['tags']) ? $form_value['tags'] : '' ?>"/>
                                    <p class="hint-text">
                                        <small>*Ketikkan label/tag konten lalu tekan enter untuk menambahkan</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Status</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="-1" name="content_status"
                                            id="content_status-1" <?php echo($form_value['content_status'] === '-1' ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status-1">Simpan sebagai draft</label>

                                        <input type="radio" value="0" name="content_status"
                                            id="content_status0" <?php echo($form_value['content_status'] === '0' || (!$is_admin && $form_value['content_status'] !== '0') ? 'checked="checked"' : ''); ?>>
                                        <label
                                            for="content_status0"><?php echo($is_admin ? 'Waiting approval' : 'Kirim Hypephoto') ?></label>

                                        <?php if ($is_admin) { ?>
                                            <input type="radio" value="1" name="content_status"
                                                id="content_status1" <?php echo($form_value['content_status'] === '1' ? 'checked="checked"' : ''); ?>>
                                            <label for="content_status1">Publish</label>

                                            <input type="radio" value="2" name="content_status"
                                                id="content_status2" <?php echo($form_value['content_status'] === '2' ? 'checked="checked"' : ''); ?>>
                                            <label for="content_status2" class="mb-0">Publish Terjadwal</label>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($is_admin) { ?>
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left"></label>
                                <div class="col-md-8">
                                    <div class="collapse" id="publish-date-collapse">
                                        <div class="row">
                                            <div class="col-md-5 col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control datepicker-component"
                                                        name="publish_date" id="publish_date"
                                                        value="<?= date('d-m-Y', strtotime($form_value['publish_date'] ?? 'now')); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-3">
                                                <div class="form-group">
                                                    <select id="publish_time" name="publish_time"
                                                        class="full-width select_withsearch">
                                                        <?php for ($i = 0; $i < 23; $i++) { ?>
                                                            <?php foreach (['00', '30'] as $minute) : ?>
                                                                <?php $time = str_pad($i, 2, 0, STR_PAD_LEFT) . ':' . $minute ?>
                                                                <option <?php echo($time == $form_value['publish_time'] ? 'selected' : '') ?>><?php echo $time ?></option>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label
                                        class="col-md-3 control-label text-right sm-text-left">Tampilkan di Homepage</label>
                                    <div class="col-md-8">
                                        <div class="radio radio-complete">
                                            <input type="radio" value="1" name="featured_on_homepage"
                                                id="featured_on_homepage1" <?= ($form_value['featured_on_homepage'] == 1 ? 'checked="checked"' : ''); ?>>
                                            <label for="featured_on_homepage1">Yes</label>
                                            <input type="radio" value="0" name="featured_on_homepage"
                                                id="featured_on_homepage0" <?= ($form_value['featured_on_homepage'] == 0 ? 'checked="checked"' : ''); ?>>
                                            <label for="featured_on_homepage0">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label
                                        class="col-md-3 control-label text-right sm-text-left">Pin di Homepage</label>
                                    <div class="col-md-8">
                                        <div class="radio radio-complete">
                                            <input type="radio" value="1" name="pin_on_homepage"
                                                id="pin_on_homepage1" <?= ($form_value['pin_on_homepage'] == 1 ? 'checked="checked"' : ''); ?>>
                                            <label for="pin_on_homepage1">Yes</label>
                                            <input type="radio" value="0" name="pin_on_homepage"
                                                id="pin_on_homepage0" <?= ($form_value['pin_on_homepage'] == 0 ? 'checked="checked"' : ''); ?>>
                                            <label for="pin_on_homepage0">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div class="row"><label
                                    class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                <div class="col-md-8">
                                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                                    <!-- <input class="btn btn&#45;info sm&#45;m&#45;b&#45;10" type="submit" name="preview" value="Preview"/> -->
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
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
        $('[name=id_user]').select2();
        $('[name=id_user]').select2('readonly', 'true');

        $('textarea').ckeditor({
            height: 150,
            removeButtons: 'imagebinary',
        });

        var fields = {
            title: {
                validators: {
                    notEmpty: {
                        message: 'Judul konten harus diisi. '
                    }
                }
            },
            id_user: {
                validators: {
                    notEmpty: {
                        message: 'Pilih Penulis. '
                    }
                }
            },
            publish_date: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Tanggal Publish harus diisi. '
                    }
                }
            },
            publish_time: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Waktu Publish harus diisi. '
                    }
                }
            },
            agree_competition_terms: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Persetujuan Syaran & Ketentuan Kompetisi harus dipilih.'
                    }
                }
            },
        };

        var minTotalPhotos = '<?php echo $min_total_photos ?>';
        var validateShortDesc = function (index) {
            return function (value, validator, $field) {
                var pictureValue = validator.getFieldElements('file_pic_' + index).val();
                var hasFilePreview = $('#file-pic-' + index + '-form-group .file-preview').length > 0;
                if (pictureValue || hasFilePreview) {
                    return value ? true : false;
                }
                return true;
            };
        };
        var validateFilePic = function (index) {
            return function (value, validator, $field) {
                var totalPhotos = 0;
                var currentFilePreview = $('#file-pic-' + index + '-form-group .file-preview').length > 0;

                for (var i = 1; i <= 6; i++) {
                    var pictureValue = validator.getFieldElements('file_pic_' + i).val();
                    var hasFilePreview = $('#file-pic-' + i + '-form-group .file-preview').length > 0;

                    if (pictureValue || hasFilePreview) {
                        totalPhotos++;
                    }
                }

                if (value || currentFilePreview) {
                    validator.revalidateField('short_desc_' + index);

                    if (totalPhotos >= minTotalPhotos) {
                        for (var i = 1; i <= 6; i++) {
                            if (i != index) {
                                validator.updateStatus('file_pic_' + i, validator.STATUS_VALID);
                            }
                        }
                    }
                    return true;
                }

                return totalPhotos >= minTotalPhotos;
            }
        };
        var onFileCleared = function (index) {
            return function (event) {
                $('#content_form').bootstrapValidator('revalidateField', 'file_pic_' + index)
            }
        }

        for (var index = 1; index <= 6; index++) {
            fields['file_pic_' + index] = {
                enabled: false,
                validators: {
                    callback: {
                        message: 'Pilih gambar minimal ' + minTotalPhotos + ' foto. ',
                        callback: validateFilePic(index),
                    }
                }
            };

            fields['short_desc_' + index] = {
                enabled: false,
                excluded: false,
                validators: {
                    callback: {
                        message: 'Deskripsi gambar ' + index + ' harus diisi. ',
                        callback: validateShortDesc(index),
                    }
                }
            };

            $('#file_pic_' + index).on('filecleared', onFileCleared(index));
        }

        $('#content_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'fa fa-ok',
                invalid: 'fa fa-remove',
                validating: 'fa fa-refresh'
            },
            fields: fields,
        });
        $('input[type=radio][name=content_status]').change(statusChanged);
        statusChanged();

        $('[name=join_competition]').change(competitionChanged);
        competitionChanged();

        for (var i = 1; i <= 6; i++) {
            $('#file_pic_' + i).on('fileselect', function () {
                $('#content_form').bootstrapValidator('revalidateField', this.name);
            });

            $('#short_desc_' + i).ckeditor().editor.on('change', function () {
                $('#content_form').bootstrapValidator('revalidateField', this.name);
            });
        }
    });

    function competitionChanged() {
        let value = $('[name=join_competition]:checked').val();

        if(value === '1') {
            $('.picture-input-collapse').hide();
            $('#competition_option_wrapper').show();
            $('#content_form').bootstrapValidator('enableFieldValidators', 'agree_competition_terms', true);
        }
        else{
            $('.picture-input-collapse').show();
            $('#competition_option_wrapper').hide();
            $('#content_form').bootstrapValidator('enableFieldValidators', 'agree_competition_terms', false);
        }
    }

    function statusChanged() {
        var value = $('input[type=radio][name=content_status]:checked').val()
        $('#publish-date-collapse').collapse(value == 2 ? 'show' : 'hide')

        updateValidator();
    }

    function updateValidator() {
        var status = $('input[type=radio][name=content_status]:checked').val();

        if (status == '-1') {
            $("#pic_caption").removeAttr("required");

            for (i = 1; i <= 6; i++) {
                $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc_' + i, false);
                $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic_' + i, false);
            }
        } else {
            for (i = 1; i <= 6; i++) {
                var hasFilePreview = $('#file-pic-1-form-group .file-preview').length > 0;

                $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic_' + i, true);
                $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc_' + i, true);
            }
        }
    }

    function showModal() {
        let id_competition = $('#id_competition').val();
        if(id_competition > 0) {
            $('#modal_competition_' + id_competition).modal('show');
        }
    }

    function updateCompetitionCategory(){
        let id_competition = $('#id_competition').val();

        if (id_competition > 0) {
            $.ajax({
                'url': '<?= base_url(); ?>' + 'competition/getCategory',
                'type': 'POST', //the way you want to send data to your URL
                'data': {
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>',
                    'id_competition': id_competition
                },
                'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                    // if the request success..
                    var obj = JSON.parse(data); // parse data from json to object..

                    // if status not success, show message..
                    if (obj.status == 'success') {
                        $('#id_competition_category').empty();
                        $.each(obj.categories, function (key, category) {
                            $('#id_competition_category').append("<option value='" + category.id_competition_category + "'>" + category.category_name + "</option>").trigger('change');
                        });
                    }
                    else {
                        $('#id_competition_category').empty();
                    }
                },
                'complete': function () {

                }
            });
        }
        else {
            $('#id_competition_category').empty();
        }
    }
</script>
