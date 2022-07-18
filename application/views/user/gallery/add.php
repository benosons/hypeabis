<?php $this->load->view('user/gallery/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?= $this->session->flashdata('message'); ?>

                    <?= form_open_multipart("user_gallery/saveAdd", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off')); ?>


                    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
                        Tambah virtual gallery
                    </h4>

                    <div class="alert alert-warning">
                        Save virtual gallery secara berkala
                    </div>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Judul Gallery</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title" id="title"
                                        value="<?= (isset($content_value['title']) ? $content_value['title'] : ''); ?>"
                                        required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Deskripsi</label>
                                <div class="col-md-8 col-xs-12">
                                    <textarea class="form-control" type="text" name="deskripsi" id="deskripsi"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Thumb Gallery</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="file" class="file" name="file_pic" id="file_pic"
                                        data-show-upload="false" data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["jpg", "jpeg", "png"]' required/>
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
                                <label class="col-md-2 control-label text-right sm-text-left">Link Gallery</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="link" id="link"
                                        value="<?= (isset($content_value['link']) ? $content_value['link'] : ''); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">File Gallery</label>
                                <div class="col-md-8 col-xs-12">
                                    <input type="file" class="file" name="file_gallery" id="file_gallery"
                                        data-show-upload="false" data-show-close="false" data-show-preview="false"
                                        data-allowed-file-extensions='["zip", "rar"]'/>
                                    <p class="hint-text">
                                        <small>
                                            *Maksimal 100MB. Format file yang diperbolehkan: zip / rar.<br/>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Status</label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="statuss" id="statuss" class="full-width select_nosearch" required>
                                    <option value="Y">Aktif</option>
                                    <option value="N">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                <div class="col-md-9">
                                    <input class="btn btn-complete sm-m-b-10" type="submit" value="Submit"/>
                                    <input class="btn btn-complete sm-m-b-10 d-none" type="submit"
                                        name="submit_and_add_next_page" id="submit_and_add_next_page" value="Submit & Add Next Page"/>
                                    <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                            class="fa fa-chevron-circle-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?= form_close(); ?>

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
            }
        });

        updateValidator();
   
    });

    function updateValidator() {
       
            $("#title").attr("required", "yes");
            $("#short_desc").attr("required", "yes");
            $('#content_form').bootstrapValidator('enableFieldValidators', 'title', true);
            $('#content_form').bootstrapValidator('enableFieldValidators', 'short_desc', true);
    
    }


</script>
