<?php $this->load->view('admin/content/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <div class="d-flex">
                        <h4 class="m-t-0 m-b-15 fw-600 text-heading-black mr-auto">
                            Edit Artikel <?= ($content[0]->paginated == 1 ? '(Paginated)' : '(Standar)'); ?>
                        </h4>

                        <div>
                            <?php if ($content[0]->edit_id_admin) { ?>
                                <a class="btn btn-complete <?php echo((!is_null($locked_content_id) && $content[0]->id_content !== $locked_content_id) ? 'disabled' : '') ?>"
                                    href="<?php echo base_url() ?>admin_content/unlock_edit/<?php echo $content[0]->id_content ?>">
                                    <i class="fa fa-unlock mr-1"></i> Unlock Editor
                                </a>
                            <?php } else { ?>
                                <a class="btn btn-danger <?php echo((!is_null($locked_content_id) && $content[0]->id_content !== $locked_content_id) ? 'disabled' : '') ?>"
                                    href="<?php echo base_url() ?>admin_content/lock_edit/<?php echo $content[0]->id_content ?>"
                                    style="height:">
                                    <i class="fa fa-lock mr-1"></i> Lock Editor
                                </a>
                            <?php } ?>

                            <?php if (!is_null($locked_content_id) && $content[0]->id_content !== $locked_content_id) { ?>
                                <a class="btn btn-complete"
                                    href="<?php echo base_url() ?>admin_content/edit/<?php echo $locked_content_id ?>">
                                    <i class="fa fa-pencil mr-1"></i> Edit Artikel Terkunci
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <?= $this->session->flashdata('message'); ?>

                    <div class="alert alert-warning mt-2">
                        Save artikel secara berkala
                    </div>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

                        <?= form_open_multipart("admin_content/saveEdit/" . $content[0]->id_content, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off')); ?>
                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Tipe artikel</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="0" name="paginated"
                                            id="paginated0" <?= ($content[0]->paginated == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="paginated0">Standar</label>
                                        <input type="radio" value="1" name="paginated"
                                            id="paginated1" <?= ($content[0]->paginated == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="paginated1">Paginated</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($content[0]->id_competition) { ?>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label text-right sm-text-left">Kompetisi</label>
                                    <div class="col-md-8 col-xs-12">
                                        <select name="id_competition" id="id_competition" class="full-width select_nosearch" disabled>
                                            <?php foreach ($competitions as $comp) { ?>
                                                <option value="<?= $comp->id_competition; ?>" <?= ($content[0]->id_competition == $comp->id_competition ? 'selected' : ''); ?>>
                                                    <?= $comp->title; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 control-label text-right sm-text-left">Kategori kompetisi</label>
                                    <div class="col-md-8 col-xs-12">
                                        <select name="id_competition_category" id="id_competition_category" class="full-width select_nosearch">
                                            <?php foreach ($competition_category as $cat) { ?>
                                                <option value="<?= $cat->id_competition_category; ?>" <?= ($content[0]->id_competition_category == $cat->id_competition_category ? 'selected' : ''); ?>>
                                                    <?= $cat->category_name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title"
                                        value="<?= $content[0]->title; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Deskripsi singkat</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="short_desc"
                                        value="<?= $content[0]->short_desc; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta title</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_title"
                                        value="<?= $content[0]->meta_title; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta description</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_desc"
                                        value="<?= $content[0]->meta_desc; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta keyword</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_keyword"
                                        value="<?= $content[0]->meta_keyword; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Category</label>
                                <div class="col-md-8 col-xs-12">
                                    <select class="full-width select_withsearch" name="category">
                                        <option value=""> -</option>
                                        <?php foreach ($categories as $x => $category) { ?>
                                            <option
                                                value="<?= $category['id_category']; ?>" <?= ($category['has_child'] == 1 && false ? 'disabled="disabled"' : ''); ?> <?= ($content[0]->id_category == $category['id_category'] ? 'selected' : ''); ?>>
                                                <?= $category['category_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Penulis</label>
                                <div class="col-md-8 col-xs-12">
                                    <select class="full-width select_withsearch" name="id_user">
                                        <option value=""> -</option>
                                        <?php foreach ($users as $x => $user) { ?>
                                            <option
                                                value="<?= $user->id_user; ?>" <?= ($content[0]->id_user == $user->id_user ? 'selected' : ''); ?>>
                                                <?= $user->name; ?> (<?= $user->email; ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Gambar artikel</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (strlen(trim($content[0]->content_pic)) > 0) { ?>
                                        <div class="file-preview">
                                            <!--<a href="<?= base_url(); ?>admin_content/deletePic/<?= $content[0]->id_content; ?>" class="close fileinput-remove text-right" title="remove / delete"><span class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>-->
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
                                            *Maksimal 5MB. Rekomendasi ukuran: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. Format file yang diperbolehkan: jpg / jpeg.<br/>
                                            *Untuk mengoptimalkan performance website, compress gambar sebelum di upload
                                            <a href="https://compressor.io/" target="_blank"><b><u>disini.</u></b></a>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Caption gambar</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="pic_caption"
                                        value="<?= $content[0]->pic_caption; ?>"/>
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
                                <label class="col-md-3 control-label text-right sm-text-left">Konten</label>
                                <div class="col-md-9 col-xs-12">
                                    <?php $content_file_path = base_url() . "assets/content/"; ?>
                                    <textarea id="content" class="form-control ckeditor" name="content"
                                        rows="20"><?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($content[0]->content)); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Tags</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php $tag_str = ""; ?>
                                    <?php foreach ($tags as $tag) { ?>
                                        <?php $tag_str .= $tag->tag_name . ","; ?>
                                    <?php } ?>
                                    <input type="text" class="tagsinput form-control" name="tags"
                                        value="<?= $tag_str; ?>"/>
                                    <p class="hint-text">
                                        <small>*Ketikkan label/tag artikel lalu tekan enter untuk menambahkan</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Status</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="-1" name="content_status"
                                            id="content_status-1" <?= ($content[0]->content_status == -1 ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status-1">Simpan sebagai draft</label>
                                        <input type="radio" value="0" name="content_status"
                                            id="content_status0" <?= ($content[0]->content_status == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status0">Waiting approval</label>
                                        <input type="radio" value="1" name="content_status"
                                            id="content_status1" <?= ($content[0]->content_status == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status1">Publish</label>
                                        <input type="radio" value="2" name="content_status"
                                            class=" <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>"
                                            id="content_status2" <?= ($content[0]->content_status == '2' ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status2"
                                            class="mb-0 <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">Publish Terjadwal</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left"></label>
                                <div class="col-md-8">
                                    <div class="collapse" id="publish-date-collapse">
                                        <div class="row">
                                            <div class="col-md-5 col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control datepicker-component"
                                                        name="publish_date" id="publish_date"
                                                        value="<?= date('d-m-Y', strtotime($content[0]->publish_date ?? 'now')); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-3">
                                                <div class="form-group">
                                                    <select id="publish_time" name="publish_time"
                                                        class="full-width select_withsearch">
                                                        <?php for ($i = 0; $i < 23; $i++) { ?>
                                                            <?php foreach (['00', '30'] as $minute) : ?>
                                                                <?php $time = str_pad($i, 2, 0, STR_PAD_LEFT) . ':' . $minute ?>
                                                                <option <?php echo($time == $content[0]->publish_time ? 'selected' : '') ?>><?php echo $time ?></option>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Slider Homepage</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="featured_on_homepage"
                                            id="featured_on_homepage1" <?= ($content[0]->featured_on_homepage == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_homepage1">Yes</label>
                                        <input type="radio" value="0" name="featured_on_homepage"
                                            id="featured_on_homepage0" <?= ($content[0]->featured_on_homepage == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_homepage0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Buah Bibir</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="recommended"
                                            id="recommended1" <?= ($content[0]->recommended == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended1">Yes</label>
                                        <input type="radio" value="0" name="recommended"
                                            id="recommended0" <?= ($content[0]->recommended == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Konten Atas Halaman Kategori</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="on_top_category"
                                            id="on_top_category1" <?= ($content[0]->on_top_category == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="on_top_category1">Yes</label>
                                        <input type="radio" value="0" name="on_top_category"
                                            id="on_top_category0" <?= ($content[0]->on_top_category == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="on_top_category0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group <?= (!is_null($content[0]->id_competition) ? 'd-none' : ''); ?>">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Konten Rekomendasi Halaman Kategori</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="featured_on_category"
                                            id="featured_on_category1" <?= ($content[0]->featured_on_category == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_category1">Yes</label>
                                        <input type="radio" value="0" name="featured_on_category"
                                            id="featured_on_category0" <?= ($content[0]->featured_on_category != '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_category0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:none">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Trending</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="trending"
                                            id="trending1" <?= ($content[0]->trending == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="trending1">Yes</label>
                                        <input type="radio" value="0" name="trending"
                                            id="trending0" <?= ($content[0]->trending == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="trending0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:none">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Recommend on category page</label>
                                <div class="col-md-8">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="recommended_category"
                                            id="recommended_category1" <?= ($content[0]->recommended_category == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended_category1">Yes</label>
                                        <input type="radio" value="0" name="recommended_category"
                                            id="recommended_category0" <?= ($content[0]->recommended_category == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended_category0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="form-group" <?= ($this->session->userdata('admin_level') != '1' || true ? "style='display:none'" : ""); ?>>
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Deletable</label>
                                <div class="col-md-8 col-xs-12">
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="deletable"
                                            id="deletable1" <?= ($content[0]->deletable == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="deletable1">Yes</label>
                                        <input type="radio" value="0" name="deletable"
                                            id="deletable0" <?= ($content[0]->deletable == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="deletable0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                <div class="col-md-8">
                                    <button class="btn btn-complete sm-m-b-10" type="submit">Submit</button>
                                    <!-- <input class="btn btn-info sm-m-b-10" type="submit" name="preview" value="Preview" onclick="this.form.target='_blank';return true;" /> -->
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?= form_close(); ?>

                    </div>

                    <?php if ($content[0]->paginated == 1) { ?>
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
                <?php if(is_null($content[0]->id_competition)){ ?>
                pic_caption: {
                    validators: {
                        notEmpty: {
                            message: 'Caption gambar harus diisi. '
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
                file_pic: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih gambar artikel. '
                        }
                    }
                },
                <?php } ?>
                content: {
                    excluded: false,
                    validators: {
                        notEmpty: {
                            message: 'Konten artikel harus diisi. '
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
                }
            }
        });

        $('input[type=radio][name=content_status]').change(function () {
            var value = $('input[type=radio][name=content_status]:checked').val()
            $('#publish-date-collapse').collapse(value == 2 ? 'show' : 'hide')

            updateValidator();
        }).trigger('change');

        contentSatusChanged();
    });

    function updateValidator() {
        var status = $('input[type=radio][name=content_status]:checked').val();
        if (status == '-1') {
            $("#short_desc").removeAttr("required");
            $("#pic_caption").removeAttr("required");
            $("#category").removeAttr("required");
            $("#content").removeAttr("required");
            $("#file_pic").removeAttr("data-required");
            $("#file_pic").attr("data-required", "false");
            $("#file_pic").removeAttr("required");
            $("#file_pic").prop('required', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'pic_caption', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'category', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'content', false);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', false);
        } else {
            $("#short_desc").attr("required", "yes");

            <?php if(is_null($content[0]->id_competition)){ ?>
            $("#pic_caption").attr("required", "yes");
            $("#category").attr("required", "yes");
            <?php } ?>

            <?php if (!(strlen(trim($content[0]->content_pic)) > 0)){ ?>
            $("#content").attr("required", "yes");
            <?php if(is_null($content[0]->id_competition)){ ?>
            $("#file_pic").attr("data-required", "true");
            $("#file_pic").prop('required', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', true);
            <?php } ?>
            <?php } else { ?>
            $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', false);
            <?php } ?>

            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', true);

            <?php if(is_null($content[0]->id_competition)){ ?>
            $('#content_form').bootstrapValidator('enableFieldValidators', 'pic_caption', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'category', true);
            <?php } ?>

            $('#content_form').bootstrapValidator('enableFieldValidators', 'content', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'publish_date', status == '2');
            $('#content_form').bootstrapValidator('enableFieldValidators', 'publish_time', status == '2');
        }
    }

    function contentSatusChanged() {
        var content_status = $('#content_status').val();
        if (content_status == '2') {
            $('#submit_date_wrapper').show();
        } else {
            $('#submit_date_wrapper').hide();
        }
    }
</script>
