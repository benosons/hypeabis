<?php $this->load->view('admin/content/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
                        Tambah artikel
                    </h4>

                    <?= $this->session->flashdata('message'); ?>
                    <?php $content_value = $this->session->flashdata('content_value'); ?>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

                        <?= form_open_multipart("admin_content/saveAdd", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off')); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Tipe artikel</label>
                                <div class="col-md-8">
                                    <?php
                                    $paginated = 0;
                                    if (isset($content_value['paginated']) && $content_value['paginated'] == 1) {
                                        $paginated = 1;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="0" name="paginated"
                                            id="paginated0" <?= ($paginated == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="paginated0">Standar</label>
                                        <input type="radio" value="1" name="paginated"
                                            id="paginated1" <?= ($paginated == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="paginated1">Paginated</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title"
                                        value="<?= (isset($content_value['title']) ? $content_value['title'] : ''); ?>"
                                        required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Deskripsi singkat</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="short_desc"
                                        value="<?= (isset($content_value['short_desc']) ? $content_value['short_desc'] : ''); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta title</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_title"
                                        value="<?= (isset($content_value['meta_title']) ? $content_value['meta_title'] : ''); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta description</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_desc"
                                        value="<?= (isset($content_value['meta_desc']) ? $content_value['meta_desc'] : ''); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Meta keyword</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="meta_keyword"
                                        value="<?= (isset($content_value['meta_keyword']) ? $content_value['meta_keyword'] : ''); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Kategori</label>
                                <div class="col-md-8 col-xs-12">
                                    <select class="full-width select_withsearch" name="category">
                                        <option value=""> -</option>
                                        <?php foreach ($categories as $x => $category) { ?>
                                            <option
                                                value="<?= $category['id_category']; ?>" <?= ($category['has_child'] == 1 && false ? 'disabled="disabled"' : ''); ?> <?= (isset($content_value['id_category']) && $category['id_category'] == $content_value['id_category'] ? 'selected' : ''); ?>>
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
                                                value="<?= $user->id_user; ?>" <?= (isset($content_value['id_user']) && $user->id_user == $content_value['id_user'] ? 'selected' : ''); ?>>
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
                                    <input type="file" class="file" name="file_pic" id="file_pic"
                                        data-show-upload="false" data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
                                    <p class="hint-text">
                                        <small>
                                            *Maksimal 5MB. Rekomendasi ukuran: <?= $this->pic_width; ?>px x <?= $this->pic_height; ?>px. Format file yang diperbolehkan: jpg, png &amp; gif.<br/>
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
                                        value="<?= (isset($content_value['pic_caption']) ? $content_value['pic_caption'] : ''); ?>"/>
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
                                    <?php
                                    $content_text = "";
                                    if (isset($content_value['content']) && strlen(trim($content_value['content'])) > 0) {
                                        $content_text = str_replace("##BASE_URL##", $content_file_path, html_entity_decode($content_value['content']));
                                    }
                                    ?>
                                    <textarea id="content" class="form-control" name="content" rows="15"
                                        style="height:300px;"><?= ($content_text); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Tags</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="tagsinput form-control" name="tags" value=""/>
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
                                    <?php
                                    $status = -1;
                                    if (isset($content_value['content_status']) && $content_value['content_status'] == 0) {
                                        $status = 0;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="-1" name="content_status"
                                            id="content_status-1" <?= ($status == -1 ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status-1">Simpan sebagai draft</label>
                                        <input type="radio" value="0" name="content_status"
                                            id="content_status0" <?= ($status == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status0">Waiting approval</label>
                                        <input type="radio" value="1" name="content_status"
                                            id="content_status1" <?= ($status == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status1">Publish</label>
                                        <input type="radio" value="2" name="content_status"
                                            id="content_status2" <?= ($status == 2 ? 'checked="checked"' : ''); ?>>
                                        <label for="content_status2" class="mb-0">Publish Terjadwal</label>
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
                                                                <option><?php echo $time ?></option>
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

                        <div class="form-group">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di slider homepage</label>
                                <div class="col-md-8">
                                    <?php
                                    $featured_on_homepage = 0;
                                    if (isset($content_value['featured_on_homepage']) && $content_value['featured_on_homepage'] == 1) {
                                        $featured_on_homepage = 1;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="featured_on_homepage"
                                            id="featured_on_homepage1" <?= ($featured_on_homepage == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_homepage1">Yes</label>
                                        <input type="radio" value="0" name="featured_on_homepage"
                                            id="featured_on_homepage0" <?= ($featured_on_homepage == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_homepage0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Buah Bibir</label>
                                <div class="col-md-8">
                                    <?php
                                    $recommended = 0;
                                    if (isset($content_value['recommended']) && $content_value['recommended'] == 1) {
                                        $recommended = 1;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="recommended"
                                            id="recommended1" <?= ($recommended == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended1">Yes</label>
                                        <input type="radio" value="0" name="recommended"
                                            id="recommended0" <?= ($recommended == 0 ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Konten Atas Halaman Kategori</label>
                                <div class="col-md-8">
                                    <?php
                                    $featured_on_category = 1;
                                    if (isset($content_value['on_top_category']) && $content_value['on_top_category'] == 0) {
                                        $featured_on_category = 0;
                                    }
                                    ?>
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

                        <div class="form-group">
                            <div class="row">
                                <label
                                    class="col-md-3 control-label text-right sm-text-left">Tampilkan di Konten Rekomendasi Halaman Kategori</label>
                                <div class="col-md-8">
                                    <?php
                                    $featured_on_category = 1;
                                    if (isset($content_value['featured_on_category']) && $content_value['featured_on_category'] == 0) {
                                        $featured_on_category = 0;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="featured_on_category"
                                            id="featured_on_category1" <?= ($content[0]->featured_on_category == '1' ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_category1">Yes</label>
                                        <input type="radio" value="0" name="featured_on_category"
                                            id="featured_on_category0" <?= ($content[0]->featured_on_category == '0' ? 'checked="checked"' : ''); ?>>
                                        <label for="featured_on_category0">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:none">
                            <div class="row">
                                <label class="col-md-3 control-label text-right sm-text-left">Trending</label>
                                <div class="col-md-8">
                                    <?php
                                    $trending = 0;
                                    if (isset($content_value['trending']) && $content_value['trending'] == 1) {
                                        $trending = 1;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="trending"
                                            id="trending1" <?= ($trending == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="trending1">Yes</label>
                                        <input type="radio" value="0" name="trending"
                                            id="trending0" <?= ($trending == 0 ? 'checked="checked"' : ''); ?>>
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
                                    <?php
                                    $recommended_category = 0;
                                    if (isset($content_value['recommended_category']) && $content_value['recommended_category'] == 1) {
                                        $recommended_category = 1;
                                    }
                                    ?>
                                    <div class="radio radio-complete">
                                        <input type="radio" value="1" name="recommended_category"
                                            id="recommended_category1" <?= ($trending == 1 ? 'checked="checked"' : ''); ?>>
                                        <label for="recommended_category1">Yes</label>
                                        <input type="radio" value="0" name="recommended_category"
                                            id="recommended_category0" <?= ($trending == 0 ? 'checked="checked"' : ''); ?>>
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
                                        <input type="radio" value="1" name="deletable" id="deletable1"
                                            checked="checked">
                                        <label for="deletable1">Yes</label>
                                        <input type="radio" value="0" name="deletable" id="deletable0">
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
                                    <input class="btn btn-complete sm-m-b-10 d-none" type="submit"
                                        name="submit_and_add_next_page" value="Submit & Add Next Page"/>
                                    <!-- <input class="btn btn-info sm-m-b-10" type="submit" name="preview" value="Preview" onclick="this.form.target='_blank';return true;" /> -->
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
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
    $(document).ready(function () {
        $('#content_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                id_user: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih penulis. '
                        }
                    }
                },
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
                pic_caption: {
                    validators: {
                        notEmpty: {
                            message: 'Caption gambar harus diisi. '
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
                file_pic: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih gambar artikel. '
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

        $('#content').ckeditor().editor.on('change', function () {
            $('#content_form').bootstrapValidator('revalidateField', 'content');
        });

        $('input[type=radio][name=paginated]').change(function () {
            $('input[name=submit_and_add_next_page]').toggleClass(
                'd-none',
                $('input[type=radio][name=paginated]:checked').val() === '0'
            );
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
            $("#pic_caption").attr("required", "yes");
            $("#category").attr("required", "yes");
            $("#content").attr("required", "yes");
            $("#file_pic").attr("data-required", "true");
            $("#file_pic").prop('required', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'pic_caption', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'category', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'content', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'file_pic', true);
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