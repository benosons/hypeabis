<!-- breadcrumb -->
<section class="block bg-light pt-2 pb-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <span class="mr-2">
                    <a href="<?= base_url(); ?>">Home</a>
                </span>
                <span class="mr-2" style="font-size:12px;">
                    <i class="fa fa-chevron-right"></i>
                </span>
                <span>
                    <a href="<?= base_url(); ?>category/<?= $article[0]->id_category; ?>/<?= strtolower(url_title($article[0]->category_name)); ?>">
                        <?= $article[0]->category_name ?>
                    </a>
                </span>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb end -->

<!-- end single-header -->
<?php if(isset($article[0]->content_pic) && (!empty(trim($article[0]->content_pic)))){ ?>
<section class="single-header">
    <div class="image-wrapper full-width">
        <img src="<?= base_url() ?>assets/content/<?= $article[0]->content_pic ?>" class="full-width">
        <ul class="post-categories detail-categories-wrapper">
            <li>
                <a href="<?= base_url(); ?>category/<?= $article[0]->id_category; ?>/<?= strtolower(url_title($article[0]->category_name)); ?>">
                    <?= $article[0]->category_name ?>
                </a>
            </li>
        </ul>
    </div>
</section>
<div class="pic-caption text-grey">
    <center>
        <p><?php echo $article[0]->pic_caption ?></p>
    </center>
</div>
<?php } ?>
<!-- end single-header -->


<!-- Article Detail -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="blog-post california">
                    <div class="row">
                        <div class="col">
                            <div class="post-content detail-content">
                                <center>
                                    <h1 class="post-title"><?= $article[0]->title ?></h1>

                                    <div class="row justify-content-center mb-2 mt-4">
                                        <div class="col-auto px-3">
                                            <small class="text-muted">
                                                <?php echo strftime('%d %B %Y', date_create($article[0]->publish_date)->getTimestamp()) ?>
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <?php echo date_create($article[0]->publish_date)->format('H:i') ?> WIB
                                            </small>
                                        </div>

                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-auto mx-auto">
                                            <?php if (isset($article[0]->id_user) && $article[0]->id_user > 0) { ?>
                                                <div class="author-info-box">
                                                    <figure class="mb-0">
                                                        <a href="<?php echo base_url(); ?>author/<?php echo $article[0]->id_user; ?>/<?php echo strtolower(url_title($article[0]->name)); ?>">
                                                            <img
                                                                src="<?= $this->frontend_lib->getUserPictureURL($article[0]->picture, $article[0]->picture_from); ?>"
                                                                alt="Image">
                                                        </a>
                                                    </figure>
                                                    <div class="content px-2">
                                                        <div class="d-flex flex-column h-100 justify-content-center">
                                                            <span class="mb-0">
                                                              <a href="<?php echo base_url(); ?>author/<?php echo $article[0]->id_user; ?>/<?php echo strtolower(url_title($article[0]->name)); ?>">
                                                                <?= $article[0]->name; ?>
                                                              </a>
                                                            </span>
                                                            <?php if (!empty($article[0]->profile_desc)) { ?>
                                                                <small class="d-block"
                                                                    style="max-width: 500px"><?php echo $article[0]->profile_desc ?></small>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <hr class="mt-20 mb-40">
                                    <div class="row align-items-center mb-4">
                                        <div class="col-sm-7">
                                            <div class="sharethis-inline-share-buttons text-sm-left"></div>
                                        </div>
                                        <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
                                            <?php if ($this->session->userdata('user_logged_in') !== true) { ?>
                                            <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                            <?php if ($article[0]->like_count > 0) { ?>
                                                <div class="like_counter_wrapper text-center mr-1">
                                                    <span
                                                        id="like_counter"><?= number_format(ceil($article[0]->like_count), 0, ',', '.'); ?></span><br>
                                                    <span><?= ($article[0]->like_count > 1 ? 'Likes' : 'Like'); ?></span><br>
                                                </div>
                                            <?php } ?>
                                            <?php if ($is_preview) : ?>
                                            <a class="btn btn-outline-dark" href="" onclick="return false;">
                                                <?php else : ?>
                                                <a class="btn btn-outline-dark"
                                                    href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>">
                                                    <?php endif; ?>
                                                    <i class="fa fa-thumbs-up"></i> Like
                                                </a>

                                                <?php } else { ?>

                                                    <!-- Like counter & text -->
                                                    <div
                                                        class="like_counter_wrapper text-center mr-1" <?= ($article[0]->like_count > 0 ? '' : 'style="display:none;"'); ?>>
                                                        <span
                                                            id="like_counter"><?= number_format(ceil($article[0]->like_count), 0, ',', '.'); ?></span><br>
                                                        <span><?= ($article[0]->like_count > 1 ? 'Likes' : 'Like'); ?></span>
                                                    </div>

                                                    <!-- Button like -->
                                                    <!--<button class="btn btn-outline-dark" disabled>-->
                                                    <!--    <i class="fa fa-thumbs-up"></i> Liked-->
                                                    <!--</button>-->
                                                    <a id="unlike-button" class="btn btn-outline-dark"
                                                        href="javascript:;" <?php echo(!$is_preview ? 'onclick="likeArticle();"' : '') ?>
                                                        style="<?= ($liked ? '' : 'display:none;'); ?>">
                                                        <i class="fa fa-thumbs-up"></i> Liked
                                                    </a>
                                                    <a id="like-button" class="btn btn-outline-dark"
                                                        href="javascript:;" <?php echo(!$is_preview ? 'onclick="likeArticle();"' : '') ?>
                                                        style="<?= (! $liked ? '' : 'display:none;'); ?>">
                                                        <i class="fa fa-thumbs-up"></i> Like
                                                    </a>

                                                <?php } ?>
                                        </div>
                                    </div>

                                </center>
                                <!-- end metas -->
                                <?php $content_file_path = base_url() . "assets/content/"; ?>
                                <div class="<?= ($page_no === '1' ? 'has-dropcap' : ''); ?>"
                                    style="overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
                                    <?php echo str_replace("##BASE_URL##", $content_file_path, html_entity_decode($page_no === '1' ? $article[0]->content : $page->content)); ?>
                                </div>
                            </div>
                            <!-- end post-content -->
                        </div>
                    </div>

                    <div class="row w-100 mt-3">
                        <div class="col">
                            <ul class="post-categories">
                                <?php foreach ($tags as $tag) { ?>
                                    <li class="my-2">
                                        <?php $tag_slug = strtolower(url_title($tag->tag_name)) ?>
                                        <a href='<?php echo base_url("content/tag/{$tag->id_content_tag}/{$tag_slug}") ?>'
                                            class="font-weight-bold"
                                            style="font-size: 14px; padding: 2.5px 10px; height: auto;">
                                            <?php echo $tag->tag_name ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end blog-post -->

                <!-- end author-info-box -->
                <?php if ($article[0]->paginated === '1' && intval($max_page_no) > 1) : ?>
                    <?php $article_url = base_url() . "read/{$article[0]->id_content}" . '/' . strtolower(url_title($article[0]->title)) ?>
                    <nav class="clearfix row my-3 align-items-center">
                        <div class="col-md-4">
                            <?php if ($page_no > '1') : ?>
                                <?php $page_url = $page_no !== '2' ? '/' . ($page_no - 1) : '' ?>
                                <a href="<?php echo $article_url . $page_url ?>"
                                    class="btn btn-sm btn-block btn-primary">
                                    SEBELUMNYA
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-center align-middle" style="line-height: 3;">
                            <span>Halaman <?php echo $page_no ?> dari <?php echo $max_page_no ?></span></div>
                        <div class="col-md-4 text-right">
                            <?php if ($page_no < $max_page_no) : ?>
                                <a href="<?php echo $article_url ?>/<?php echo($page_no + 1) ?>"
                                    class="btn btn-sm btn-block btn-primary">
                                    SELANJUTNYA
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>


                <div class="post-navigation">
                    <?php if (!empty($article_prev)) { ?>
                        <div class="post-prev"><span>SEBELUMNYA</span>
                            <h2>
                                <?php if (isset($article_prev[0]->id_content) && $article_prev[0]->id_content > 0) { ?>
                                    <a href="<?= base_url(); ?>read/<?= $article_prev[0]->id_content; ?>/<?= strtolower(url_title($article_prev[0]->title)); ?>">
                                        <?= $article_prev[0]->title; ?>
                                    </a>
                                <?php } ?>
                            </h2>
                        </div>
                    <?php } ?>
                    <!-- end post-prev -->
                    <?php if (!empty($article_next)) { ?>
                        <div class="post-next"><span>BERIKUTNYA</span>
                            <h2>
                                <?php if (isset($article_next[0]->id_content) && $article_next[0]->id_content > 0) { ?>
                                    <a href="<?= base_url(); ?>read/<?= $article_next[0]->id_content; ?>/<?= strtolower(url_title($article_next[0]->title)); ?>">
                                        <?= $article_next[0]->title; ?>
                                    </a>
                                <?php } ?>
                            </h2>
                        </div>
                    <?php } ?>
                    <!-- end post-next -->
                </div>
                <!-- end post-navigation -->

            </div>
        </div>
    </div>
</section>
<!-- end content-section -->

<!-- Adv -->
<?php
if ((isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0)
) {
    ?>
    <center>
        <div class="adv-home-super-leaderboard mt-4 m-b-0">
            <?php if (isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) { ?>
                <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['sup_leaderboard_ar']['builtin'][0]['id_ads']; ?>"
                    target="_blank">
                    <img src="<?= base_url(); ?>assets/adv/<?= $ads['sup_leaderboard_ar']['builtin'][0]['ads_pic']; ?>"
                        alt="Bisnis Muda" class="img img-fluid"/>
                </a>
            <?php } else {
                if (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0) { ?>
                    <?= html_entity_decode($ads['sup_leaderboard_ar']['googleads'][0]['googleads_code']); ?>
                <?php }
                else {
                }
            } ?>
        </div>
    </center>
<?php } ?>
<!-- end Adv -->

<div class="comments container pb-5">
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-lg-10">
            <h3 class="section-title">Komentar</h3>
            <div class="comments-list">
                <?php foreach ($comments as $comment) { ?>
                    <div class="blog-post kentucky">
                        <figure class="post-image align-self-start">
                            <a href="<?php echo base_url(); ?>author/<?php echo $comment->id_user; ?>/<?php echo strtolower(url_title($comment->name)); ?>">
                                <img
                                    src="<?php echo $this->frontend_lib->getUserPictureURL($comment->picture, $comment->picture_from) ?>"
                                    alt="<?php echo $comment->name ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <div class="metas">
                                <h3 class="post-title mb-0"><?php echo $comment->name ?></h3>
                                <span
                                    class="date"><?php echo date('d M Y - H:i', strtotime($comment->comment_date)) ?></span>
                            </div>
                            <p><?php echo nl2br($comment->comment) ?></p>
                        </div>
                        <!-- end post-content -->
                    </div>
                    <!-- end blog-post -->
                <?php } ?>
            </div>

            <div class="text-center">
                <input type="hidden" class="id_content" value="<?= $article[0]->id_content; ?>"/>
                <input type="hidden" class="comment_offset" value="<?= $this->per_page_comment; ?>"/>

                <div class="comment_loader"
                    style="background:rgba(255, 255, 255, 0.75);width:100%;height:100%;position:absolute;z-index:20;display:none;">
                    <center>
                        <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"/><br/>
                        <p class="small hint-text text-black">Loading...</p>
                    </center>
                </div>

                <?php if ($total_comments > $this->per_page_comment) { ?>
                    <button class="btn btn-sm btn-default mb-4 mt-4 load_comment_btn"
                        onclick="loadComments()">Lihat komentar sebelumnya
                    </button>
                <?php } ?>
            </div>

            <hr class="mt-20 mb-20">

            <div class="add-comment blog-post kentucky">
                <?php if ($this->session->userdata('user_logged_in') !== true) { ?>
                    <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string() . '#add-comment')), "="); ?>
                    <p class="text-center py-2 w-100" style="font-size: 24px;">
                        Silahkan
                        <a href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>"><b><u>Login</u></b></a>
                        terlebih dahulu untuk meninggalkan komentar.
                    </p>
                <?php } else { ?>
                    <div class="addcomment_wrapper w-100">
                        <div class="add_comment_loader"
                            style="background:rgba(255, 255, 255, 0.75);width:100%;height:100%;position:absolute;z-index:20;display:none;">
                            <center>
                                <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"/><br/>
                                <p class="small hint-text text-black">Loading...</p>
                            </center>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="comment_message"></div>
                                <div class="form-group">
                                    <textarea class="form-control input-msg required-field comment_content"
                                        placeholder="Komentar Anda" rows="5" required="yes"></textarea>
                                </div>
                            </div><!-- Col end -->
                        </div><!-- Form row end -->

                        <?= $this->recaptcha->render(); ?>

                        <button class="mt-3 btn btn-default"
                            type="button" <?php echo(!$is_preview ? 'onclick="submitComment(event)"' : '') ?>>Submit komentar
                        </button>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<!-- Baca Juga -->
<?php if (!empty($read_too) || !empty($content_by_category)) { ?>
    <section class="content-section">
        <div class="container">
            <div class="row" style="margin-bottom:20px">
                <div class="col-12">
                    <h2 class="section-title">Baca Juga:</h2>
                </div>
            </div>
            <!-- end row -->

            <?php $index = 0; ?>
            <div class="row">
                <?php
                if ((isset($ads['md_rec1_ar']['builtin'][0]['id_ads']) && $ads['md_rec1_ar']['builtin'][0]['id_ads'] > 0) ||
                    (isset($ads['md_rec1_ar']['googleads'][0]['id_ads']) && $ads['md_rec1_ar']['googleads'][0]['id_ads'] > 0)
                ) {
                    ?>
                    <?php foreach ($read_too as $x => $read_too_article) { ?>
                        <?php if ($index == 2) { ?>
                            <div class="col-lg-4 col-md-6">
                                <center>
                                    <div class="ads-wrapper gres-ads-wrapper">
                                        <?php if (isset($ads['md_rec1_ar']['builtin'][0]['id_ads']) && $ads['md_rec1_ar']['builtin'][0]['id_ads'] > 0) { ?>
                                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['md_rec1_ar']['builtin'][0]['id_ads']; ?>"
                                                target="_blank">
                                                <img
                                                    src="<?= base_url(); ?>assets/adv/<?= $ads['md_rec1_ar']['builtin'][0]['ads_pic']; ?>"
                                                    alt="Hypeabis" class="img img-fluid"/>
                                            </a>
                                        <?php } else {
                                            if (isset($ads['md_rec1_ar']['googleads'][0]['id_ads']) && $ads['md_rec1_ar']['googleads'][0]['id_ads'] > 0) { ?>
                                                <?= html_entity_decode($ads['md_rec1_ar']['googleads'][0]['googleads_code']); ?>
                                            <?php }
                                            else {
                                            }
                                        } ?>
                                    </div>
                                </center>
                            </div>

                        <?php } else { ?>

                            <div class="col-lg-4 col-md-6">
                                <div class="blog-post texas">
                                    <figure class="post-image"><img
                                            src="<?= base_url(); ?>assets/content/thumb/<?= $read_too_article->content_pic_thumb; ?>"
                                            alt="Image">
                                        <ul class="post-categories">
                                            <li>
                                                <a href="<?= base_url(); ?>category/<?= $read_too_article->id_category; ?>/<?= strtolower(url_title($read_too_article->category_name)); ?>"><?= $read_too_article->category_name ?></a>
                                            </li>
                                        </ul>
                                    </figure>
                                    <div class="post-content">
                                        <h3 class="post-title">
                                            <a href="<?= base_url(); ?>read/<?= $read_too_article->id_content; ?>/<?= strtolower(url_title($read_too_article->title)); ?>">
                                                <?= $read_too_article->title; ?>
                                            </a>
                                        </h3>
                                        <div class="metas">
                                            <div class="author">
                                                <figure>
                                                    <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article->id_user; ?>/<?php echo strtolower(url_title($read_too_article->name)); ?>">
                                                        <img
                                                            src="<?= $this->frontend_lib->getUserPictureURL($read_too_article->picture, $read_too_article->picture_from); ?>"
                                                            alt="Image">
                                                    </a>
                                                </figure>
                                                <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article->id_user; ?>/<?php echo strtolower(url_title($read_too_article->name)); ?>"><?= $read_too_article->name; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                        <?php $index++; ?>
                    <?php } ?>
                <?php } else { ?>
                    <?php foreach ($read_too as $x => $read_too_article) : ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-post texas">
                                <figure class="post-image"><img
                                        src="<?= base_url(); ?>assets/content/thumb/<?= $read_too_article->content_pic_thumb; ?>"
                                        alt="Image">
                                    <ul class="post-categories">
                                        <li>
                                            <a href="<?= base_url(); ?>category/<?= $read_too_article->id_category; ?>/<?= strtolower(url_title($read_too_article->category_name)); ?>"><?= $read_too_article->category_name ?></a>
                                        </li>
                                    </ul>
                                </figure>
                                <div class="post-content">
                                    <h3 class="post-title">
                                        <a href="<?= base_url(); ?>read/<?= $read_too_article->id_content; ?>/<?= strtolower(url_title($read_too_article->title)); ?>">
                                            <?= $read_too_article->title; ?>
                                        </a>
                                    </h3>
                                    <div class="metas">
                                        <div class="author">
                                            <figure>
                                                <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article->id_user; ?>/<?php echo strtolower(url_title($read_too_article->name)); ?>">
                                                    <img
                                                        src="<?= $this->frontend_lib->getUserPictureURL($read_too_article->picture, $read_too_article->picture_from); ?>"
                                                        alt="Image">
                                                </a>
                                            </figure>
                                            <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article->id_user; ?>/<?php echo strtolower(url_title($read_too_article->name)); ?>"><?= $read_too_article->name; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php } ?>

                <?php if ($total_content_by_tag < 6) { ?>
                    <?php foreach ($content_by_category as $x => $read_too_article) { ?>
                        <?php if ($index == 2 && (
                                (isset($ads['md_rec1_ar']['builtin'][0]['id_ads']) && $ads['md_rec1_ar']['builtin'][0]['id_ads'] > 0) ||
                                (isset($ads['md_rec1_ar']['googleads'][0]['id_ads']) && $ads['md_rec1_ar']['googleads'][0]['id_ads'] > 0))) { ?>
                            <div class="col-lg-4 col-md-6">
                                <center>
                                    <div class="ads-wrapper gres-ads-wrapper">
                                        <?php if (isset($ads['md_rec1_ar']['builtin'][0]['id_ads']) && $ads['md_rec1_ar']['builtin'][0]['id_ads'] > 0) { ?>
                                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['md_rec1_ar']['builtin'][0]['id_ads']; ?>"
                                                target="_blank">
                                                <img
                                                    src="<?= base_url(); ?>assets/adv/<?= $ads['md_rec1_ar']['builtin'][0]['ads_pic']; ?>"
                                                    alt="Hypeabis" class="img img-fluid"/>
                                            </a>
                                        <?php } else {
                                            if (isset($ads['md_rec1_ar']['googleads'][0]['id_ads']) && $ads['md_rec1_ar']['googleads'][0]['id_ads'] > 0) { ?>
                                                <?= html_entity_decode($ads['md_rec1_ar']['googleads'][0]['googleads_code']); ?>
                                            <?php }
                                            else {
                                            }
                                        } ?>
                                    </div>
                                </center>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="blog-post texas">
                                    <figure class="post-image"><img
                                            src="<?= base_url(); ?>assets/content/thumb/<?= $read_too_article['content_pic_thumb']; ?>"
                                            alt="Image">
                                        <ul class="post-categories">
                                            <li>
                                                <a href="<?= base_url(); ?>category/<?= $read_too_article['id_category']; ?>/<?= strtolower(url_title($read_too_article['category_name'])); ?>"><?= $read_too_article['category_name'] ?></a>
                                            </li>
                                        </ul>
                                    </figure>
                                    <div class="post-content">
                                        <h3 class="post-title">
                                            <a href="<?= base_url(); ?>read/<?= $read_too_article['id_content']; ?>/<?= strtolower(url_title($read_too_article['title'])); ?>">
                                                <?= $read_too_article['title']; ?>
                                            </a>
                                        </h3>
                                        <div class="metas">
                                            <div class="author">
                                                <figure>
                                                    <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article['id_user']; ?>/<?php echo strtolower(url_title($read_too_article['name'])); ?>">
                                                        <img
                                                            src="<?= $this->frontend_lib->getUserPictureURL($read_too_article['picture'], $read_too_article['picture_from']); ?>"
                                                            alt="Image">
                                                    </a>
                                                </figure>
                                                <a href="<?php echo base_url(); ?>author/<?php echo $read_too_article['id_user']; ?>/<?php echo strtolower(url_title($read_too_article['name'])); ?>"><?= $read_too_article['name']; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php $index++; ?>
                    <?php } ?>
                <?php } ?>
                <!-- end col-4 -->

            </div>
        </div>
        </div>
    </section>
<?php } ?>

<input type="hidden" id="id_content" value="<?= $article[0]->id_content; ?>"/>

<script>
    function likeArticle() {
        var post_data = {};
        post_data['id_content'] = $('#id_content').val();

        $.ajax({
            'url': '<?= base_url(); ?>' + 'content2/likeContent',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                // //if status not success, show message..
                // if (obj.status == 'success') {
                //     Swal.fire('Terima kasih', 'telah menyukai artikel ini.', 'success');
                //     $('#like_counter').html(obj.like_count);
                //     $('.like_counter_wrapper').show();
                //     $('#like-button').html('<i class="fa fa-thumbs-up"></i> Liked').prop('onclick', null).off('click').addClass('disabled');
                // } else if (obj.status == 'nologin') {
                //     Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk menyukai artikel ini.', 'error');
                // } else if (obj.status == 'already_liked') {
                //     Swal.fire('Terima kasih', 'anda sudah meyukai artikel ini.', 'warning');
                // } else {
                //     Swal.fire('Maaf', obj.message, 'error');
                // }

                //if status not success, show message..
                if(obj.status == 'success'){
                    $('#like_counter').html(obj.like_count);
                    $('.like_counter_wrapper').show();
                    Swal.fire('Terima kasih', obj.message, 'success');
                }
                else if(obj.status == 'nologin'){
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu.', 'error');
                }
                else if(obj.status == 'already_liked'){
                    Swal.fire('Terima kasih', obj.message, 'warning');
                }
                else{
                    Swal.fire('Maaf', obj.message, 'error');
                }
                updateLikeButton(obj.action);
                refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
            },
            'complete': function () {
            }
        });
    }

    function updateLikeButton(action){
        if (action == 'unlike'){
            $('#like-button').show();
            $('#unlike-button').hide();
        }
        else{
            $('#like-button').hide();
            $('#unlike-button').show();
        }
    }
</script>
