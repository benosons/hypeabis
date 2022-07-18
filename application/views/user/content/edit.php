<?php $this->load->view('user/content/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?= $this->session->flashdata('message'); ?>

                    <?= form_open_multipart("user_content/saveEdit/" . $content[0]->id_content, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off')); ?>
                    <div
                        class="competition_wrapper <?= (isset($competitions) && count($competitions) > 0 && $content[0]->paginated == '0' ? '' : 'd-none'); ?>">
                        <div class="d-flex">
                            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
                                <i class="fa fa-trophy text-warning" style="font-size:40px;"></i>
                                Kompetisi<?= (count($competitions) > 1 ? ':' : ' ' . $competitions[0]->title); ?>
                            </h4>
                        </div>

                        <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                            <div class="form-group">
                                <div class="row">
                                    <label
                                        class="col-md-2 control-label text-right sm-text-left text-black">Ikutkan artikel ini dalam kompetisi:</label>
                                    <div class="col-md-8">
                                        <?php if(isset($content[0]->id_competition) && $content[0]->id_competition > 0){ ?>
                                            <div class="alert alert-warning join_message">
                                                Foto ini telah diikutkan pada kompetisi <?php echo $content[0]->competition_title ?>
                                                (<?php echo date('d M Y', strtotime($content[0]->start_date)) ?> - <?php echo date('d M Y', strtotime($content[0]->finish_date)) ?>).
                                            </div>
                                        <?php } ?>

                                        <div class="radio radio-complete" <?= ($allow_competition_edit ? '' : 'style="display:none"'); ?>>
                                            <input type="radio" value="1" name="join_competition"
                                                id="join_competition1" <?= (isset($content[0]->id_competition) && $content[0]->id_competition > 0 ? 'checked="checked"' : ''); ?>>
                                            <label for="join_competition1" class="text-black">Ya</label>
                                            <input type="radio" value="0" name="join_competition"
                                                id="join_competition0" <?= (!(isset($content[0]->id_competition) && $content[0]->id_competition > 0) ? 'checked="checked"' : ''); ?>>
                                            <label for="join_competition0" class="text-black">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                id="competition_option_wrapper" <?= (count($competitions) > 0 && $allow_competition_edit ? '' : 'style="display:none"'); ?>>
                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-2 control-label text-right sm-text-left text-black">Pilih kompetisi:</label>
                                        <div class="col-md-8">
                                            <select name="id_competition" id="id_competition"
                                                class="full-width select_nosearch"
                                                onchange="updateCompetitionCategory()">
                                                <?php foreach ($competitions as $comp) { ?>
                                                    <option
                                                        value="<?= $comp->id_competition; ?>" <?= ($content[0]->id_competition == $comp->id_competition ? 'selected' : ''); ?>>
                                                        <?= $comp->title; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="form-group" id="competition_category_wrapper" <?= (isset($competition_category) && count($competition_category) > 0 ? '' : 'style="display:none"'); ?>>
                                    <div class="row">
                                        <label
                                            class="col-md-2 control-label text-right sm-text-left text-black">Pilih kategori:</label>
                                        <div class="col-md-8">
                                            <select name="id_competition_category" id="id_competition_category"
                                                class="full-width select_nosearch">
                                                <?php foreach ($competition_category as $cat) { ?>
                                                    <option
                                                        value="<?= $cat->id_competition_category; ?>" <?= ($content[0]->id_competition_category == $cat->id_competition_category ? 'selected' : ''); ?>>
                                                        <?= $cat->category_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 control-label text-right sm-text-left text-black"></label>
                                        <div class="col-md-8">
                                            <div class="checkbox check-complete">
                                                <input type="checkbox" 
                                                    value="1" 
                                                    id="agree_competition_terms" 
                                                    name="agree_competition_terms" 
                                                    <?= ($content_value['agree_competition_terms'] == '1' ? 'checked="checked"' : ''); ?>
                                                />
                                                <label for="agree_competition_terms" class="text-black">Menyetujui Syarat &amp; Ketentuan Kompetisi</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-2 control-label text-right sm-text-left">&nbsp;</label>
                                        <div class="col-md-8">
                                            <a href="javascript:;" onclick="showModal()" class="btn btn-light"><i
                                                    class="fa fa-question-circle"></i> Syarat &amp; Ketentuan Kompetisi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
                        Edit Artikel <?php if ($is_verified_member): ?><?php echo($content_value['paginated'] == 1 ? '(Paginated)' : '(Standar)'); ?><?php endif; ?>
                    </h4>

                    <div class="alert alert-warning">
                        Save artikel secara berkala
                    </div>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                        <?php if ($is_verified_member): ?>
                            <div class="form-group" id="article_type_wrapper">
                                <div class="row">
                                    <label class="col-md-2 control-label text-right sm-text-left">Tipe artikel</label>
                                    <div class="col-md-8">
                                        <div class="radio radio-complete">
                                            <input type="radio" value="0" name="paginated"
                                                id="paginated0" <?= ($content_value['paginated'] == 0 ? 'checked="checked"' : ''); ?>>
                                            <label for="paginated0">Standar</label>
                                            <input type="radio" value="1" name="paginated"
                                                id="paginated1" <?= ($content_value['paginated'] == 1 ? 'checked="checked"' : ''); ?>>
                                            <label for="paginated1">Paginated</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title"
                                        value="<?= $content_value['title'] ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Deskripsi singkat</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="short_desc"
                                        value="<?= $content_value['short_desc']; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="content_category_wrapper">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Kategori</label>
                                <div class="col-md-8 col-xs-12">
                                    <select class="full-width select_withsearch" name="category">
                                        <option value=""> -</option>
                                        <?php foreach ($categories as $x => $category) { ?>
                                            <option
                                                value="<?= $category['id_category']; ?>" <?= ($content_value['id_category'] == $category['id_category'] ? 'selected' : ''); ?>><?= $category['category_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Gambar artikel</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (strlen(trim($content[0]->content_pic)) > 0) { ?>
                                        <div class="file-preview">
                                            <a href="<?= base_url(); ?>user_content/deletePic/<?= $content[0]->id_content; ?>"
                                                class="close fileinput-remove text-right" title="remove / delete"><span
                                                    class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                                            <div class="file-preview-thumbnails">
                                                <div class="file-preview-frame">
                                                    <img
                                                        src="<?= base_url(); ?>assets/content/<?= $content[0]->content_pic; ?>"
                                                        class="file-preview-image"
                                                        title="<?= $content[0]->content_pic; ?>" width="auto"
                                                        style="max-height:100px">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="file-preview-status text-center text-complete"></div>
                                        </div>
                                    <?php } ?>

                                    <input type="file" class="file" name="file_pic" id="file_pic"
                                        data-show-upload="false" data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
                                    <p class="hint-text">
                                        <small>
                                            *Maksimal 5MB. Rekomendasi ukuran: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. Format file yang diperbolehkan: jpg / jpeg. <br/>
                                            *Untuk mengoptimalkan performance website, compress gambar sebelum di upload
                                            <a href="https://compressor.io/" target="_blank"><b><u>disini.</u></b></a>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Caption gambar</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="pic_caption"
                                        value="<?= $content_value['pic_caption']; ?>"/>
                                    <p class="hint-text">
                                        <small>
                                            *Caption gambar wajib diisi dengan deskripsi gambar dan sumber gambar. <br/>
                                            *Contoh: Berinvestasi sejak dini akan bermanfaat bagi masa depan (Sumber gambar: ABCD)
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Konten</label>
                                <div class="col-md-10 col-xs-12">
                                    <?php $content_file_path = base_url() . "assets/content/"; ?>
                                    <textarea id="content" class="form-control" name="content"
                                        rows="20"><?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($content_value['content'])); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Tags</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php $tag_str = ""; ?>
                                    <?php foreach ($tags as $tag) { ?>
                                        <?php $tag_str .= $tag->tag_name . ","; ?>
                                    <?php } ?>
                                    <input type="text" class="tagsinput form-control" name="tags"
                                        value="<?= $tag_str; ?>"/>
                                    <p class="hint-text">
                                        <small>* Ketikkan label/tag artikel lalu tekan enter untuk menambahkan</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                                <div class="col-md-8 col-xs-12">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="-1" name="status"
                                            id="status-1" <?= ($content_value['content_status'] != 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="status-1">Simpan sebagai draft</label>
                                        <input type="radio" value="0" name="status"
                                            id="status0" <?= ($content_value['content_status'] == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="status0">Kirim Tulisan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                <div class="col-md-8">
                                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?= form_close(); ?>

                    <?php if ($is_verified_member && $content[0]->paginated == 1) { ?>
                        <?php $this->load->view('shared/content/nextpage'); ?>
                    <?php } ?>

                </div>
            </div>
            <!-- END CARD -->
        </div>
    </div>

    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<?php foreach ($competitions as $comp) { ?>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#content_form').bootstrapValidator({
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
                            message: 'Judul artikel harus diisi. '
                        }
                    }
                },
                short_desc: {
                    validators: {
                        notEmpty: {
                            message: 'Deskripsi singkat artikel harus diisi. '
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih kategori artikel. '
                        }
                    }
                },
                content: {
                    excluded: false,
                    validators: {
                        notEmpty: {
                            message: 'Konten artikel harus diisi. '
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
            }
        });

        updateValidator();
        $('input[type=radio][name=status]').change(function () {
            updateValidator();
        });

        $('#content').ckeditor().editor.on('change', function () {
            $('#content_form').bootstrapValidator('revalidateField', 'content');
        });

        $('[name=join_competition]').change(competitionChanged);
        competitionChanged();
    });

    function updateValidator() {
        var status = $('input[type=radio][name=status]:checked').val();
        if (status == '-1') {
            $("#short_desc").removeAttr("required");
            $("#category").removeAttr("required");
            $("#content").removeAttr("required");
            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'category', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'content', false);
        } else {
            $("#short_desc").attr("required", "yes");
            $("#category").attr("required", "yes");
            $("#content").attr("required", "yes");
            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'category', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'content', true);
        }
    }

    function showModal() {
        let id_competition = $('#id_competition').val();
        if(id_competition > 0) {
            $('#modal_competition_' + id_competition).modal('show');
        }
    }

    function competitionChanged() {
        let value = $('[name=join_competition]:checked').val();
        if(value === '1') {
            $('#competition_option_wrapper').show();
            $('#article_type_wrapper').hide();
            $('#content_category_wrapper').hide();
            $("#paginated0").prop("checked", true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'agree_competition_terms', true);
            contentTypeChanged();
        }
        else{
            $('#competition_option_wrapper').hide();
            $('#article_type_wrapper').show();
            $('#content_category_wrapper').show();
            $('#content_form').bootstrapValidator('enableFieldValidators', 'agree_competition_terms', false);
            contentTypeChanged();
        }
    }

    function contentTypeChanged(){
        $('input[name=submit_and_add_next_page]').toggleClass(
            'd-none',
            $('input[type=radio][name=paginated]:checked').val() === '0'
        );
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
                'success': function (data) {
                    var obj = JSON.parse(data);
                    if (obj.status == 'success') {
                        $('#id_competition_category').empty();
                        $.each(obj.categories, function (key, category) {
                            $('#id_competition_category').append("<option value='" + category.id_competition_category + "'>" + category.category_name + "</option>").trigger('change');
                        });

                        if(obj.categories.length > 0){
                            $('#competition_category_wrapper').show();
                        }
                        else{
                            $('#competition_category_wrapper').hide();
                        }
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
