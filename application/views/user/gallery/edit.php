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

                    <?= form_open_multipart("user_gallery/saveEdit/" . $content[0]->id_galeri, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'content_form', 'autocomplete' => 'off')); ?>
                    

                    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
                        Edit Virtual Gallery 
                    </h4>

                    <div class="alert alert-warning">
                        Save virtusl galeery secara berkala
                    </div>

                    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                       

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Judul</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="title"
                                        value="<?= $content[0]->judul_galeri ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Deskripsi</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="deskripsi"
                                        value="<?= $content[0]->deskripsi; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">Thumb Galeri</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (strlen(trim($content[0]->thumb_galeri)) > 0) { ?>
                                        <div class="file-preview">
                                            <a href="<?= base_url(); ?>user_gallery/deletePic/<?= $content[0]->id_galeri; ?>"
                                                class="close fileinput-remove text-right" title="remove / delete"><span
                                                    class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                                            <div class="file-preview-thumbnails">
                                                <div class="file-preview-frame">
                                                    <img
                                                        src="<?= base_url(); ?>assets/content/<?= $content[0]->thumb_galeri; ?>"
                                                        class="file-preview-image"
                                                        title="<?= $content[0]->thumb_galeri; ?>" width="auto"
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
                                <label class="col-md-2 control-label text-right sm-text-left">Link Gallery</label>
                                <div class="col-md-8 col-xs-12">
                                    <input class="form-control" type="text" name="link" id="link"
                                        value="<?= $content[0]->link_galeri; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label text-right sm-text-left">File Galeri</label>
                                <div class="col-md-8 col-xs-12">
                                    <?php if (strlen(trim($content[0]->file_galeri)) > 0) { ?>
                                        <div class="file-preview">
                                            <a href="<?= base_url(); ?>user_gallery/deletePic/<?= $content[0]->id_galeri; ?>"
                                                class="close fileinput-remove text-right" title="remove / delete"><span
                                                    class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                                                    <div class="file-preview-thumbnails">
                                                        <div class="file-preview-frame">
                                                            <span
                                                                src="<?= base_url(); ?>assets/content/<?= $content[0]->file_galeri; ?>"
                                                                class="file-preview-image"
                                                                title="<?= $content[0]->file_galeri; ?>" width="auto"
                                                                style="max-height:80px">
                                                            </span>
                                                            <span style="font-size:100px;" class="fa fa-file-archive-o m-r-20"></span>
                                                        </div>
                                                    </div>
                                            <div class="clearfix"></div>
                                            <div class="file-preview-status text-center text-complete"></div>
                                        </div>
                                    <?php } ?>

                                    <input type="file" class="file" name="file_galery" id="file_galery"
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
                                    
                                    <option value="Y" <?= $selected = $content[0]->statuss == 'Y' ? 'selected': '' ?> >Aktif</option>
                                    <option value="N" <?= $selected = $content[0]->statuss == 'N' ? 'selected': '' ?>>Tidak Aktif</option>
                                    </select>
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
                            message: 'Judul gallery harus diisi. '
                        }
                    }
                },
                deskripsi: {
                    validators: {
                        notEmpty: {
                            message: 'Deskripsi galeery harus diisi. '
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
