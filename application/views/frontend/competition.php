<?php $this->load->view('frontend/competition-header') ?>

<!-- Hypephoto -->
<section class="content-section pt-0">
    <div class="container-fluid">
        <div class="row">
            <?php foreach ($contents as $key => $content) { ?>
                <?php if ($competition->competition_type == 'article') { ?>
                    <div class="col-sm-6 col-md-6 col-lg-3 pb-3">
                        <div class="position-relative border">
                            <a href="<?= base_url(); ?>read/<?= $content->id_content; ?>/<?= strtolower(url_title($content->title)); ?>"
                                style="overflow:hidden">
                                <?php
                                $image = base_url() . 'assets/competition/' . $competition->default_pic;
                                if (!empty(trim($content->content_pic_thumb))) {
                                    $image = base_url() . 'assets/content/thumb/' . $content->content_pic_thumb;
                                    }
                                ?>
                                <img
                                    data-src="<?= $image; ?>"
                                    class="w-100 lazy" style="height:250px;object-fit:cover;" width="343" height="250">
                            </a>

                            <div class="top_gallery_info_wrapper">
                                <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                <!--
                                <a class="link-like"
                                    href="javascript:;"
                                    data-id="<?php echo $content->id_content ?>"
                                    title="Like / Unlike"
                                    <?php echo($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
                                    <span
                                        class="like-counter align-middle" <?php echo($content->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>

                                    <?php if ($content->is_liked) { ?>
                                        <i class="fas fa-thumbs-up ml-1"></i>
                                    <?php } else { ?>
                                        <i class="far fa-thumbs-up ml-1"></i>
                                    <?php } ?>
                                </a>
                                -->

                                <span
                                    class="like-counter align-middle"><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>
                                <i class="fas fa-thumbs-up ml-1"></i>
                            </div>

                            <div class="metas-citra-wrapper position-static" style="background: none;">
                                <div class="metas">
                                    <a href="<?= base_url(); ?>read/<?= $content->id_content; ?>/<?= strtolower(url_title($content->title)); ?>"
                                        class="d-block w-100">
                                        <h3 class="post-title title-wraptext text-dark" style="line-height:normal;">
                                            <?php echo ucwords($content->title); ?>
                                        </h3>
                                    </a>
                                    <div class="author">
                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>"
                                            class="mt-0">
                                            <figure>
                                                <img
                                                    src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>"
                                                    alt="Image">
                                            </figure>
                                        </a>
                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>">
                        <span class="text-muted">
                          <?php echo $content->user_name ?>
                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- end metas -->
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-6 col-md-6 col-lg-3 pb-3">
                        <div class="position-relative border">
                            <a href="<?php echo photo_url($content->id_content, $content->title) ?>"
                                style="overflow:hidden">
                                <?php if ($key < 4) { ?>
                                    <img
                                        src="<?= base_url() ?>assets/photo/thumb/<?php echo $content->photos[0]->picture_thumb ?>"
                                        class="w-100" style="height:250px;object-fit:cover;">
                                <?php } else { ?>
                                    <img
                                        data-src="<?= base_url() ?>assets/photo/thumb/<?php echo $content->photos[0]->picture_thumb ?>"
                                        class="w-100 lazy" style="height:250px;object-fit:cover;" width="343"
                                        height="250">
                                <?php } ?>
                            </a>

                            <div class="top_gallery_info_wrapper">
                                <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                <a class="link-like"
                                    href="javascript:;"
                                    data-id="<?php echo $content->id_content ?>"
                                    title="Like / Unlike"
                                    <?php echo($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
                                    <span
                                        class="like-counter align-middle" <?php echo($content->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>

                                    <?php if ($content->is_liked) { ?>
                                        <i class="fas fa-thumbs-up ml-1"></i>
                                    <?php } else { ?>
                                        <i class="far fa-thumbs-up ml-1"></i>
                                    <?php } ?>
                                </a>

                                <?php if ($content->photo_counts > 1) { ?>
                                    <i class="far fa-clone text-white ml-2" style="font-size:15px;"></i>
                                <?php } ?>
                            </div>

                            <div class="metas-citra-wrapper position-static" style="background: none;">
                                <div class="metas">
                                    <a href="<?php echo photo_url($content->id_content, $content->title) ?>"
                                        class="d-block w-100">
                                        <h3 class="post-title title-wraptext text-dark" style="line-height:normal;">
                                            <?php echo strtoupper($content->title); ?>
                                        </h3>
                                    </a>
                                    <div class="author">
                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>"
                                            class="mt-0">
                                            <figure>
                                                <img
                                                    src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>"
                                                    alt="Image">
                                            </figure>
                                        </a>
                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>">
                        <span class="text-muted">
                          <?php echo $content->user_name ?>
                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- end metas -->
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="mt-3">
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</section>
