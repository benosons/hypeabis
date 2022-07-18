<!-- Main Slider -->
<section class="content-section no-spacing">
    <div class="highlight-slider-wrapper">
        <div class="highlight-slider-top">
            <div class="swiper-wrapper">
                <?php foreach ($featured_article as $x => $article) { ?>
                    <?php if ($x === 3 && $featured_sponsored) { ?>
                        <div class="swiper-slide">
                            <div class="blog-post indiana">
                                <div class="post-image"
                                    data-background="<?= base_url() ?>assets/sponsored-content/<?= $featured_sponsored->content_pic ?>">
                                    <div class="overlay"></div>
                                </div>
                                <div class="container">
                                    <div class="post-content slider-content">
                                        <div class="post-title-wrapper">
                                            <h3 class="post-title d-flex align-items-center justify-content-center">
                                                <a href="<?= base_url(); ?>read-sponsored/<?= $featured_sponsored->id_content; ?>/<?= strtolower(url_title($featured_sponsored->title)); ?>">
                                                    <?= $featured_sponsored->title ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="swiper-slide">
                        <div class="blog-post indiana">
                            <div class="post-image"
                                data-background="<?= base_url() ?>assets/content/<?= $article->content_pic ?>">
                                <div class="overlay"></div>
                            </div>
                            <div class="container">
                                <div class="post-content slider-content">
                                    <!-- <ul class="post-categories">
                                      <li><a href="#">TRAVEL</a></li>
                                      <li><a href="#">LIFESTYLE</a></li>
                                    </ul> -->
                                    <div class="post-title-wrapper">
                                        <h3 class="post-title d-flex align-items-center justify-content-center">
                                            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                <?= $article->title ?>
                                            </a>
                                        </h3>
                                        <div class="author">
                                            <figure><img
                                                    width="30" height="30"
                                                    src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                    alt="<?= $article->name; ?>"></figure>
                                            <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- end swiper-slide -->
            </div>
            <!-- end swiper-wrapper -->
        </div>
        <!-- end highlight-slider-top -->
        <div class="highlight-slider-bottom">
            <div class="container">
                <div class="highlight-slider-thumbs">
                    <div class="swiper-wrapper">

                        <?php $no = 1 ?>
                        <?php foreach ($featured_article as $x => $article) { ?>
                            <?php if ($x === 3 && $featured_sponsored) { ?>
                                <div class="swiper-slide">
                                    <figure><img
                                            width="282" height="188"
                                            src="<?= base_url() ?>assets/sponsored-content/thumb/<?= $featured_sponsored->content_pic_thumb ?>"
                                            alt="<?= $featured_sponsored->title ?>">
                                        <figcaption>
                                            <h3 class="post-title title text-capitalize mb-10"><?= $featured_sponsored->title ?></h3>
                                        </figcaption>
                                    </figure>
                                </div>
                            <?php } ?>
                            <div class="swiper-slide">
                                <figure><img
                                        width="282" height="188"
                                        src="<?= base_url() ?>assets/content/thumb/<?= $article->content_pic_thumb ?>"
                                        alt="<?= $article->title ?>">
                                    <figcaption>
                                        <h3 class="post-title title text-capitalize mb-10"><?= $article->title ?></h3>
                                    </figcaption>
                                </figure>
                            </div>
                            <?php $no++;
                        } ?>
                        <!-- end swiper-slide -->
                    </div>
                    <!-- end swiper-wrapper -->
                </div>
                <!-- end highlight-slider-thumbs -->
            </div>
            <!-- end container -->
        </div>
        <!-- end highlight-slider-bottom -->
    </div>
    <!-- end highlight-slider-wrapper -->
</section>
<!-- end Main Slider -->

<!-- Adv -->
<?php
if ((isset($ads['sup_leaderboard_hm']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_hm']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['sup_leaderboard_hm']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_hm']['googleads'][0]['id_ads'] > 0)
) {
    ?>
    <div class="section-ad mt-40" style="padding-top:50px">
        <div class="container">
            <div class="col-md-12 m-b-75" style="margin-top:-30px;">
                <center>
                    <div class="ads-wrapper gres-ads-wrapper">
                        <?php if (isset($ads['sup_leaderboard_hm']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_hm']['builtin'][0]['id_ads'] > 0) { ?>
                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['sup_leaderboard_hm']['builtin'][0]['id_ads']; ?>"
                                target="_blank">
                                <img
                                    src="<?= base_url(); ?>assets/adv/<?= $ads['sup_leaderboard_hm']['builtin'][0]['ads_pic']; ?>"
                                    alt="Hypeabis" class="img img-fluid"/>
                            </a>
                        <?php } else {
                            if (isset($ads['sup_leaderboard_hm']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_hm']['googleads'][0]['id_ads'] > 0) { ?>
                                <?= html_entity_decode($ads['sup_leaderboard_hm']['googleads'][0]['googleads_code']); ?>
                            <?php }
                            else {
                            }
                        } ?>
                    </div>
                </center>
            </div>
        </div>
    </div>
<?php } ?>
<!-- end Adv -->

<!-- Gres (Terbaru) -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <center>
                    <h2 class="section-title">Gres</h2>
                </center>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-lg-8">
                <?php if (isset($newest_article[0])) { ?>
                    <div class="blog-post colorado">
                        <figure class="post-image">
                            <div class="overlay"></div>
                            <a href="<?= base_url(); ?>read/<?= $newest_article[0]->id_content; ?>/<?= strtolower(url_title($newest_article[0]->title)); ?>">
                                <img data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_big', $newest_article[0]->content_pic_thumb); ?>"
                                    width="810" height="425" alt="<?= $newest_article[0]->title; ?>" class="lazy">
                            </a>
                        </figure>
                        <div class="post-content">
                            <ul class="post-categories">
                                <li>
                                    <a href="<?= base_url(); ?>category/<?= $newest_article[0]->id_category; ?>/<?= strtolower(url_title($newest_article[0]->category_name)); ?>"><?= $newest_article[0]->category_name ?></a>
                                </li>
                            </ul>
                            <h3 class="post-title">
                                <a href="<?= base_url(); ?>read/<?= $newest_article[0]->id_content; ?>/<?= strtolower(url_title($newest_article[0]->title)); ?>">
                                    <?= $newest_article[0]->title ?>
                                </a>
                            </h3>
                            <div class="metas">
                                <div class="author">
                                    <figure><img
                                            data-src="<?= $this->frontend_lib->getUserPictureURL($newest_article[0]->picture, $newest_article[0]->picture_from); ?>"
                                            class="lazy" width="30" height="30" alt="<?= $newest_article[0]->title; ?>"></figure>
                                    <a href="<?php echo base_url(); ?>author/<?php echo $newest_article[0]->id_user; ?>/<?php echo strtolower(url_title($newest_article[0]->name)); ?>"><?= $newest_article[0]->name ?></a>
                                </div>
                                <!-- <div class="dot"></div>
                                <span class="date">March 5th 2020</span> <span class="comments">12</span> -->
                            </div>
                            <!-- end metas -->
                        </div>
                        <!-- end post-content -->
                    </div>
                <?php } ?>
            </div>
            <!-- end col-8 -->

            <div class="col-lg-4">
                <?php if($newest_sponsored){ ?>
                    <div class="blog-post utah">
                        <figure class="post-image">
                            <div class="overlay"></div>
                            <a href="<?= base_url(); ?>read-sponsored/<?= $newest_sponsored->id_content; ?>/<?= strtolower(url_title($newest_sponsored->title)); ?>">
                                <img data-src="<?= base_url() ?>assets/sponsored-content/thumb/<?= $newest_sponsored->content_pic_thumb; ?>"
                                    width="390" height="260" class="lazy" alt="<?= $newest_sponsored->title; ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <ul class="post-categories">
                            </ul>
                            <h3 class="post-title text-capitalize">
                                <a href="<?= base_url(); ?>read-sponsored/<?= $newest_sponsored->id_content; ?>/<?= strtolower(url_title($newest_sponsored->title)); ?>">
                                    <?= $newest_sponsored->title ?>
                                </a>
                            </h3>
                        </div>
                        <!-- end post-content -->
                    </div>
                <?php } else if (isset($newest_article[1])) { ?>
                    <div class="blog-post utah">
                        <figure class="post-image">
                            <div class="overlay"></div>
                            <a href="<?= base_url(); ?>read/<?= $newest_article[1]->id_content; ?>/<?= strtolower(url_title($newest_article[1]->title)); ?>">
                                <img data-src="<?= base_url() ?>assets/content/thumb/<?= $newest_article[1]->content_pic_thumb; ?>"
                                    width="390" height="260" class="lazy" alt="<?= $newest_article[1]->title; ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <ul class="post-categories">
                                <!-- <li><a href="#">ADVERTISEMENT</a></li> -->
                            </ul>
                            <h3 class="post-title text-capitalize">
                                <a href="<?= base_url(); ?>read/<?= $newest_article[1]->id_content; ?>/<?= strtolower(url_title($newest_article[1]->title)); ?>">
                                    <?= $newest_article[1]->title ?>
                                </a>
                            </h3>
                            <div class="metas">
                                <div class="author">
                                    <figure><img
                                            data-src="<?= $this->frontend_lib->getUserPictureURL($newest_article[1]->picture, $newest_article[1]->picture_from); ?>"
                                            class="lazy" width="30" height="30" alt="<?= $newest_article[1]->title; ?>"></figure>
                                    <a href="<?php echo base_url(); ?>author/<?php echo $newest_article[1]->id_user; ?>/<?php echo strtolower(url_title($newest_article[1]->name)); ?>"><?= $newest_article[1]->name ?></a>
                                </div>
                                <!-- <span class="date">March 5th 2020</span>
                                <div class="read-time"><i class="far fa-clock"></i> <span>2 mins</span></div>
                                <span class="comments">12</span> -->
                            </div>
                        </div>
                        <!-- end post-content -->
                    </div>
                <?php } ?>
            </div>
            <!-- end col-4 -->
            <div class="clearfix spacing-50"></div>
        </div>
        <!-- end row -->

        <div class="row">
            <?php
            if ((isset($ads['md_rec1_hm']['builtin'][0]['id_ads']) && $ads['md_rec1_hm']['builtin'][0]['id_ads'] > 0) ||
                (isset($ads['md_rec1_hm']['googleads'][0]['id_ads']) && $ads['md_rec1_hm']['googleads'][0]['id_ads'] > 0)
            ) {
            ?>
            <div class="col-lg-8">
                <?php $index = 0; ?>
                <?php foreach (array_chunk(array_slice($newest_article, 2, $newest_article_see_more_limit), 2) as $x => $chunk) { ?>
                    <div class="row">
                        <?php foreach ($chunk as $article) { ?>
                            <div class="col-lg-6 col-md-6 mb-4 pb-0">
                                <div class="blog-post miami mb-0 pb-3 h-100 border-bottom">
                                    <figure class="post-image">
                                        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                            <img
                                                data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_small', $article->content_pic_thumb); ?>"
                                                class="lazy" width="120" height="80" alt="<?= $article->title; ?>">
                                        </a>
                                    </figure>

                                    <div class="post-content mb-auto">
                                        <h3 class="post-title text-capitalize mb-10">
                                            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                <?= $article->title ?>
                                            </a>
                                        </h3>
                                        <div class="metas">
                                            <div class="author">
                                                <figure><img
                                                        data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                        class="lazy" width="30" height="30" alt="<?= $article->title; ?>"></figure>
                                                <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                            </div>
                                        </div>
                                        <!-- end metas -->
                                    </div>
                                    <!-- <hr> -->
                                </div>
                                <!-- end blog-post -->
                            </div>
                            <?php $index++; ?>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div id="collapse-newest-article" class="collapse collapse-see-more">
                    <?php foreach (array_chunk(array_slice($newest_article, $newest_article_see_more_limit + 2), 2) as $x => $chunk) { ?>
                        <div class="row">
                            <?php foreach ($chunk as $article) { ?>
                                <div class="col-lg-6 col-md-6 mb-4 pb-0">
                                    <div class="blog-post miami mb-0 pb-3 h-100 border-bottom">
                                        <figure class="post-image">
                                            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                <img
                                                    data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_small', $article->content_pic_thumb); ?>"
                                                    class="lazy" width="120" height="80" alt="<?= $article->title; ?>">
                                            </a>
                                        </figure>
                                        <div class="post-content mb-auto">
                                            <h3 class="post-title text-capitalize mb-10">
                                                <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                    <?= $article->title ?>
                                                </a>
                                            </h3>
                                            <div class="metas">
                                                <div class="author">
                                                    <figure><img
                                                            data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                            class="lazy" width="30" height="30" alt="<?= $article->title; ?>"></figure>
                                                    <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                                </div>
                                            </div>
                                            <!-- end metas -->
                                        </div>
                                        <!-- <hr> -->
                                    </div>
                                    <!-- end blog-post -->
                                </div>
                                <?php $index++; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <!-- end col-4 -->
            </div>
            <div class="col-lg-4">
                <center>
                    <div class="ads-wrapper gres-ads-wrapper">
                        <?php if (isset($ads['md_rec1_hm']['builtin'][0]['id_ads']) && $ads['md_rec1_hm']['builtin'][0]['id_ads'] > 0) { ?>
                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['md_rec1_hm']['builtin'][0]['id_ads']; ?>"
                                target="_blank">
                                <img
                                    src="<?= base_url(); ?>assets/adv/<?= $ads['md_rec1_hm']['builtin'][0]['ads_pic']; ?>"
                                    alt="Hypeabis" class="img img-fluid"/>
                            </a>
                        <?php } else {
                            if (isset($ads['md_rec1_hm']['googleads'][0]['id_ads']) && $ads['md_rec1_hm']['googleads'][0]['id_ads'] > 0) { ?>
                                <?= html_entity_decode($ads['md_rec1_hm']['googleads'][0]['googleads_code']); ?>
                            <?php }
                            else {
                            }
                        } ?>
                    </div>
                </center>
            </div>
        </div>
        <?php } else { ?>
            <div class="col-lg-12 col-md-12">
                <?php $index = 0; ?>
                <?php foreach (array_chunk(array_slice($newest_article, 2, $newest_article_see_more_limit), 3) as $x => $chunk) { ?>
                    <div class="row">
                        <?php foreach ($chunk as $article) { ?>
                            <div class="col-lg-4 mb-4 pb-0">
                                <div class="blog-post miami mb-0 pb-3 h-100 border-bottom">
                                    <figure class="post-image">
                                        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                            <img
                                                data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_small', $article->content_pic_thumb); ?>"
                                                class="lazy" width="120" height="80" alt="<?= $article->title; ?>">
                                        </a>
                                    </figure>
                                    <div class="post-content">
                                        <h3 class="post-title text-capitalize mb-10">
                                            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                <?= $article->title ?>
                                            </a>
                                        </h3>
                                        <div class="metas mb-auto">
                                            <div class="author">
                                                <figure><img
                                                        data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                        class="lazy" width=30"" height="30" alt="<?= $article->title; ?>"></figure>
                                                <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                            </div>
                                        </div>
                                        <!-- end metas -->
                                    </div>
                                    <!-- <hr> -->
                                </div>
                                <!-- end blog-post -->
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <!-- end col-4 -->

                <div id="collapse-newest-article" class="collapse collapse-see-more">
                    <?php foreach (array_chunk(array_slice($newest_article, $newest_article_see_more_limit + 2), 3) as $x => $chunk) { ?>
                        <div class="row">
                            <?php foreach ($chunk as $article) { ?>
                                <div class="col-lg-4 mb-4 pb-0">
                                    <div class="blog-post miami mb-0 pb-3 h-100 border-bottom">
                                        <figure class="post-image">
                                            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                <img
                                                    data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_small', $article->content_pic_thumb); ?>"
                                                    class="lazy" width="120" height="80" alt="<?= $article->title; ?>">
                                            </a>
                                        </figure>
                                        <div class="post-content mb-auto">
                                            <h3 class="post-title text-capitalize mb-10">
                                                <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                                    <?= $article->title ?>
                                                </a>
                                            </h3>
                                            <div class="metas">
                                                <div class="author">
                                                    <figure><img
                                                            data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                            class="lazy" width="30" height="30" alt="<?= $article->name; ?>"></figure>
                                                    <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                                </div>
                                            </div>
                                            <!-- end metas -->
                                        </div>
                                        <!-- <hr> -->
                                    </div>
                                    <!-- end blog-post -->
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- end row -->

    <?php if (count($newest_article) - 2 > $newest_article_see_more_limit) { ?>
        <div class="row">
            <div class="col-12 mt-40">
                <center>
                    <a href="#collapse-newest-article" class="btn btn-default" data-toggle="collapse" role="button"
                        data-href="<?php echo base_url() . 'page/articles' ?>">Lihat Lebih Banyak
                    </a>
                </center>
            </div>
        </div>
    <?php } ?>
    </div>
    <!-- end container -->

    <?php
    if ((isset($ads['skyscrapper_left_hm']['builtin'][0]['id_ads']) && $ads['skyscrapper_left_hm']['builtin'][0]['id_ads'] > 0) ||
        (isset($ads['skyscrapper_left_hm']['googleads'][0]['id_ads']) && $ads['skyscrapper_left_hm']['googleads'][0]['id_ads'] > 0)
    ) {
        ?>
        <div class="adv-home-skyscrapper adv-home-skyscrapper-left">
            <?php if (isset($ads['skyscrapper_left_hm']['builtin'][0]['id_ads']) && $ads['skyscrapper_left_hm']['builtin'][0]['id_ads'] > 0) { ?>
                <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['skyscrapper_left_hm']['builtin'][0]['id_ads']; ?>"
                    target="_blank">
                    <img src="<?= base_url(); ?>assets/adv/<?= $ads['skyscrapper_left_hm']['builtin'][0]['ads_pic']; ?>"
                        alt="Bisnis Muda" class="img img-fluid"/>
                </a>
            <?php } else {
                if (isset($ads['skyscrapper_left_hm']['googleads'][0]['id_ads']) && $ads['skyscrapper_left_hm']['googleads'][0]['id_ads'] > 0) { ?>
                    <?= html_entity_decode($ads['skyscrapper_left_hm']['googleads'][0]['googleads_code']); ?>
                <?php }
                else {
                }
            } ?>
        </div>
    <?php } ?>

    <?php
    if ((isset($ads['skyscrapper_right_hm']['builtin'][0]['id_ads']) && $ads['skyscrapper_right_hm']['builtin'][0]['id_ads'] > 0) ||
        (isset($ads['skyscrapper_right_hm']['googleads'][0]['id_ads']) && $ads['skyscrapper_right_hm']['googleads'][0]['id_ads'] > 0)
    ) {
        ?>
        <div class="adv-home-skyscrapper adv-home-skyscrapper-right">
            <?php if (isset($ads['skyscrapper_right_hm']['builtin'][0]['id_ads']) && $ads['skyscrapper_right_hm']['builtin'][0]['id_ads'] > 0) { ?>
                <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['skyscrapper_right_hm']['builtin'][0]['id_ads']; ?>"
                    target="_blank">
                    <img
                        src="<?= base_url(); ?>assets/adv/<?= $ads['skyscrapper_right_hm']['builtin'][0]['ads_pic']; ?>"
                        alt="Bisnis Muda" class="img img-fluid"/>
                </a>
            <?php } else {
                if (isset($ads['skyscrapper_right_hm']['googleads'][0]['id_ads']) && $ads['skyscrapper_right_hm']['googleads'][0]['id_ads'] > 0) { ?>
                    <?= html_entity_decode($ads['skyscrapper_right_hm']['googleads'][0]['googleads_code']); ?>
                <?php }
                else {
                }
            } ?>
        </div>
    <?php } ?>
</section>
<!-- end Gres (Terbaru) -->
<hr>

<!-- Buah Bibir -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h2 class="section-title text-left">Buah Bibir</h2>
                <?php $index = 0 ?>
                <?php foreach ($recommended_article as $x => $article) { ?>
                    <?php if ($index > 0) { ?>
                        <?php if ($x == 1 && isset($recommended_small_sponsored)){ ?>
                            <div class="blog-post miami">
                                <figure class="post-image">
                                    <a href="<?= base_url(); ?>read-sponsored/<?= $recommended_small_sponsored->id_content; ?>/<?= strtolower(url_title($recommended_small_sponsored->title)); ?>">
                                        <img width="120" height="80"
                                            data-src="<?= base_url() ?>assets/sponsored-content/thumb/<?= $recommended_small_sponsored->content_pic_thumb; ?>"
                                            class="lazy" alt="<?= $recommended_small_sponsored->title; ?>">
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <h3 class="post-title text-capitalize">
                                        <a href="<?= base_url(); ?>read-sponsored/<?= $recommended_small_sponsored->id_content; ?>/<?= strtolower(url_title($recommended_small_sponsored->title)); ?>">
                                            <?= $recommended_small_sponsored->title ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="blog-post miami">
                                <figure class="post-image">
                                    <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                        <img width="120" height="80"
                                            data-src="<?= base_url() ?>assets/content/thumb/<?= $article->content_pic_thumb; ?>"
                                            class="lazy" alt="<?= $article->title; ?>">
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <h3 class="post-title text-capitalize">
                                        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                            <?= $article->title ?>
                                        </a>
                                    </h3>
                                    <div class="metas">
                                        <div class="author">
                                            <figure><img
                                                    data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                    class="lazy" width="30" height="30" alt="<?= $article->name; ?>"></figure>
                                            <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php $index++;
                } ?>
                <!-- end blog-post -->
            </div>
            <!-- end col-4 -->

            <div class="col-lg-8">
                <?php if($recommended_big_sponsored){ ?>
                    <div class="blog-post nevada mt-20">
                        <figure class="post-image">
                            <a href="<?= base_url(); ?>read/<?= $recommended_big_sponsored->id_content; ?>/<?= strtolower(url_title($recommended_big_sponsored->title)); ?>">
                                <img width="810" height="425"
                                    data-src="<?= base_url() ?>assets/sponsored-content/<?= $recommended_big_sponsored->content_pic; ?>"
                                    class="lazy" alt="<?= $recommended_big_sponsored->title; ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <h3 class="post-title text-capitalize">
                                <a href="<?= base_url(); ?>read-sponsored/<?= $recommended_big_sponsored->id_content; ?>/<?= strtolower(url_title($recommended_big_sponsored->title)); ?>">
                                    <?= $recommended_big_sponsored->title ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                <?php } else if (isset($recommended_article[0])) { ?>
                    <div class="blog-post nevada mt-20">
                        <figure class="post-image">
                            <a href="<?= base_url(); ?>read/<?= $recommended_article[0]->id_content; ?>/<?= strtolower(url_title($recommended_article[0]->title)); ?>">
                                <img width="810" height="425"
                                    data-src="<?= base_url() ?>assets/content/thumb/<?= str_replace('_thumb', '_thumb_big', $recommended_article[0]->content_pic_thumb); ?>"
                                    class="lazy" alt="<?= $recommended_article[0]->title; ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <ul class="post-categories">
                                <li>
                                    <a href="<?= base_url(); ?>category/<?= $recommended_article[0]->id_category; ?>/<?= strtolower(url_title($recommended_article[0]->category_name)); ?>"><?= $recommended_article[0]->category_name ?></a>
                                </li>
                            </ul>
                            <h3 class="post-title text-capitalize">
                                <a href="<?= base_url(); ?>read/<?= $recommended_article[0]->id_content; ?>/<?= strtolower(url_title($recommended_article[0]->title)); ?>">
                                    <?= $recommended_article[0]->title ?>
                                </a>
                            </h3>
                            <div class="metas">
                                <div class="author">
                                    <figure><img
                                            data-src="<?= $this->frontend_lib->getUserPictureURL($recommended_article[0]->picture, $recommended_article[0]->picture_from); ?>"
                                            class="lazy" width="30" height="30" alt="<?= $recommended_article[0]->name; ?>"></figure>
                                    <a href="<?php echo base_url(); ?>author/<?php echo $recommended_article[0]->id_user; ?>/<?php echo strtolower(url_title($recommended_article[0]->name)); ?>"><?= $recommended_article[0]->name ?></a>
                                </div>
                            </div>
                            <!-- end metas -->
                        </div>
                        <!-- end post-content -->
                    </div>
                <?php } ?>

                <!-- end blog-post -->
            </div>
            <!-- end col-8 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- end Buah Bibir -->

<!-- Adv -->
<?php
if ((isset($ads['billboard_hm']['builtin'][0]['id_ads']) && $ads['billboard_hm']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['billboard_hm']['googleads'][0]['id_ads']) && $ads['billboard_hm']['googleads'][0]['id_ads'] > 0)
) {
    ?>
    <div class="section-ad mt-40">
        <div class="container">
            <div class="col-md-12 m-b-75" style="margin-top:-30px;">
                <center>
                    <div class="ads-wrapper gres-ads-wrapper">
                        <?php if (isset($ads['billboard_hm']['builtin'][0]['id_ads']) && $ads['billboard_hm']['builtin'][0]['id_ads'] > 0) { ?>
                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['billboard_hm']['builtin'][0]['id_ads']; ?>"
                                target="_blank">
                                <img
                                    src="<?= base_url(); ?>assets/adv/<?= $ads['billboard_hm']['builtin'][0]['ads_pic']; ?>"
                                    alt="Hypeabis" class="img img-fluid"/>
                            </a>
                        <?php } else {
                            if (isset($ads['billboard_hm']['googleads'][0]['id_ads']) && $ads['billboard_hm']['googleads'][0]['id_ads'] > 0) { ?>
                                <?= html_entity_decode($ads['billboard_hm']['googleads'][0]['googleads_code']); ?>
                            <?php }
                            else {
                            }
                        } ?>
                    </div>
                </center>
            </div>
        </div>
    </div>
<?php } ?>
<!-- end Adv -->

<?php if (!empty($shoppables)) { ?>
    <!-- Hypeshop -->
    <section class="content-section">
        <div class="row">
            <div class="col-12">
                <center>
                    <h2 class="section-title">
                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                        Hypeshop
                    </h2>
                </center>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col">
                <div class="owl-carousel owl-theme">
                    <?php $index = 0; ?>
                    <?php foreach ($shoppables as $shoppable) : ?>
                        <?php if ($index == 3) { ?>
                            <div class="item">
                                <center>
                                    <div class="ads-wrapper gres-ads-wrapper">
                                        <?php if (isset($ads['md_rec2_hm']['builtin'][0]['id_ads']) && $ads['md_rec2_hm']['builtin'][0]['id_ads'] > 0) { ?>
                                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['md_rec2_hm']['builtin'][0]['id_ads']; ?>"
                                                target="_blank">
                                                <img
                                                    src="<?= base_url(); ?>assets/adv/<?= $ads['md_rec2_hm']['builtin'][0]['ads_pic']; ?>"
                                                    alt="Hypeabis" class="img img-fluid"/>
                                            </a>
                                        <?php } else {
                                            if (isset($ads['md_rec2_hm']['googleads'][0]['id_ads']) && $ads['md_rec2_hm']['googleads'][0]['id_ads'] > 0) { ?>
                                                <?= html_entity_decode($ads['md_rec2_hm']['googleads'][0]['googleads_code']); ?>
                                            <?php }
                                            else {
                                            }
                                        } ?>
                                    </div>
                                </center>
                            </div>
                        <?php } else { ?>
                            <div class="item">
                                <a href="<?php echo base_url() . 'hypeshop/' . $shoppable->id_content . '/' . strtolower(url_title($shoppable->title)) ?>"
                                    class="d-block w-100">
                                    <img
                                        src="<?= base_url(); ?>assets/shoppable/thumb/<?php echo $shoppable->content_pic_thumb; ?>"
                                        width="250" height="250" alt="Shoppable hypeabis">
                                </a>
                                <div class="metas-citra-wrapper">
                                    <div class="metas">
                                        <a href="<?php echo base_url() . 'hypeshop/' . $shoppable->id_content . '/' . strtolower(url_title($shoppable->title)) ?>"
                                            class="d-block w-100">
                                            <h3 class="post-title title text-capitalize w-100"><?= $shoppable->title ?></h3>
                                        </a>
                                    </div>
                                </div>
                                <!-- end metas -->
                            </div>
                        <?php } ?>
                        <?php $index++;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hypeshop -->
<?php } ?>

<hr>

<!-- Populer -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">Populer</h2>
            </div>
            <!-- end col-12 -->
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <?php foreach ($popular_article as $x => $article) { ?>
            <?php if ($x == $popular_article_see_more_limit) { ?>
        </div>
        <div id="collapse-popular-article" class="collapse collapse-see-more">
            <div class="row justify-content-center">
                <?php } ?>

                <?php if($x == 0 && isset($popular_sponsored)){ ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-post texas">
                            <figure class="post-image">
                                <a href="<?= base_url(); ?>read-sponsored/<?= $popular_sponsored->id_content; ?>/<?= strtolower(url_title($popular_sponsored->title)); ?>">
                                    <img data-src="<?= base_url() ?>assets/sponsored-content/thumb/<?= $popular_sponsored->content_pic_thumb ?>"
                                        width="390" height="260" class="lazy" alt="<?= $popular_sponsored->title; ?>">
                                </a>
                            </figure>
                            <div class="post-content">
                                <h3 class="post-title text-capitalize">
                                    <a href="<?= base_url(); ?>read-sponsored/<?= $popular_sponsored->id_content; ?>/<?= strtolower(url_title($popular_sponsored->title)); ?>">
                                        <?= $popular_sponsored->title ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-post texas">
                            <figure class="post-image">
                                <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                    <img data-src="<?= base_url() ?>assets/content/thumb/<?= $article->content_pic_thumb ?>"
                                        width="390" height="260" class="lazy" alt="<?= $article->title; ?>">
                                </a>
                                <ul class="post-categories">
                                    <li>
                                        <a href="<?= base_url(); ?>category/<?= $article->id_category; ?>/<?= strtolower(url_title($article->category_name)); ?>"><?= $article->category_name ?></a>
                                    </li>
                                </ul>
                            </figure>
                            <div class="post-content">
                                <h3 class="post-title text-capitalize">
                                    <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                                        <?= $article->title ?>
                                    </a>
                                </h3>
                                <div class="metas">
                                    <div class="author">
                                        <figure><img
                                                data-src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>"
                                                class="lazy" width="30" height="30" alt="<?= $article->name; ?>"></figure>
                                        <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($x == count($popular_article) - 1) { ?>
            </div>
            <?php } ?>
            <?php } ?>
            <!-- end col-4 -->

        </div>
        <!-- end row -->
        <?php if (count($popular_article) > $popular_article_see_more_limit) { ?>
            <div class="row">
                <div class="col-12 mt-20">
                    <center>
                        <a href="#collapse-popular-article" class="btn btn-default" data-toggle="collapse" role="button"
                            data-href="<?php echo base_url() . 'page/popular' ?>">Lihat Lebih Banyak
                        </a>
                    </center>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- end container -->
</section>
<!-- end Populer -->

<!-- Hypephoto -->
<?php if (!empty($photo_contents)) { ?>
    <section class="content-section">
        <div class="row">
            <div class="col-12">
                <center>
                    <h2 class="section-title">Hypephoto</h2>
                </center>
            </div>
        </div>
        <!-- end row -->
        <div class="owl-carousel no-lazy owl-theme">
            <?php foreach ($photo_contents as $content): ?>
                <div class="item">
                    <a href="<?php echo photo_url($content->id_content, $content->title) ?>" style="overflow:hidden">
                        <img src="<?= base_url() ?>assets/photo/thumb/<?php echo str_replace('_thumb', '_thumb_homepage', $content->photos[0]->picture_thumb); ?>"
                            width="300" height="300" alt="<?= $content->title; ?>" class="homepage_hypephoto">
                    </a>

                    <div class="top_gallery_info_wrapper">
                        <a href="javascript:;" class="<?php echo($content->is_liked ? 'link-liked' : 'link-like') ?>"
                            data-id="<?php echo $content->id_content ?>"
                            title="<?php echo($content->is_liked ? 'Liked' : 'Like') ?>">
                            <span
                                class="like-counter align-middle" <?php echo($content->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>

                            <?php if ($content->is_liked) { ?>
                                <i class="fas fa-thumbs-up ml-1"></i>
                            <?php } else { ?>
                                <i class="far fa-thumbs-up ml-1"></i>
                            <?php } ?>
                        </a>

                        <?php if ($content->photo_counts > 1) { ?>
                            <i class="far fa-clone text-white ml-2" style="font-size: 15px;"></i>
                        <?php } ?>
                    </div>

                    <div class="metas-citra-wrapper">
                        <div class="metas">
                            <a href="<?php echo photo_url($content->id_content, $content->title) ?>"
                                class="d-block w-100">
                                <h3 class="post-title title text-capitalize w-100"><?php echo strtoupper($content->title) ?></h3>
                            </a>
                            <div class="author">
                                <figure><img width="30" height="30"
                                        src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>"
                                        alt="<?= $content->user_name; ?>"></figure>
                                <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>">
                                    <?php echo $content->user_name ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end metas -->
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php } ?>
<!-- End Hypephoto -->

<!-- Adv -->
<div class="section-ad mt-40">
    <div class="container">
        <?php
        if ((isset($ads['billboard2_hm']['builtin'][0]['id_ads']) && $ads['billboard2_hm']['builtin'][0]['id_ads'] > 0) ||
            (isset($ads['billboard2_hm']['googleads'][0]['id_ads']) && $ads['billboard2_hm']['googleads'][0]['id_ads'] > 0)
        ) {
            ?>
            <div class="col-md-12 m-b-75" style="margin-top:-30px;">
                <center>
                    <div class="ads-wrapper gres-ads-wrapper">
                        <?php if (isset($ads['billboard2_hm']['builtin'][0]['id_ads']) && $ads['billboard2_hm']['builtin'][0]['id_ads'] > 0) { ?>
                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['billboard2_hm']['builtin'][0]['id_ads']; ?>"
                                target="_blank">
                                <img
                                    src="<?= base_url(); ?>assets/adv/<?= $ads['billboard2_hm']['builtin'][0]['ads_pic']; ?>"
                                    alt="Hypeabis" class="img img-fluid"/>
                            </a>
                        <?php } else {
                            if (isset($ads['billboard2_hm']['googleads'][0]['id_ads']) && $ads['billboard2_hm']['googleads'][0]['id_ads'] > 0) { ?>
                                <?= html_entity_decode($ads['billboard2_hm']['googleads'][0]['googleads_code']); ?>
                            <?php }
                            else {
                            }
                        } ?>
                    </div>
                </center>
            </div>
        <?php } ?>

    </div>
</div>
<!-- end Adv -->

<?php if (!empty($featured_author)) { ?>
    <!-- Penulis Pilihan -->
    <section class="content-section featured_writer_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <center>
                        <h2 class="section-title">Penulis Pilihan</h2>
                    </center>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <ul class="authors">
                        <?php foreach ($featured_author as $x => $author) { ?>
                            <li>
                                <a href="<?php echo base_url(); ?>author/<?php echo $author->id_user; ?>/<?php echo strtolower(url_title($author->name)); ?>">
                                    <figure>
                                        <img
                                            data-src="<?= $this->frontend_lib->getUserPictureURL($author->picture, $author->picture_from); ?>"
                                            class="lazy" width="110" height="110" alt="<?= $author->name; ?>">
                                        <figcaption>
                                            <small><?php echo strtoupper($author->name); ?></small>
                                            <?php echo $author->profile_desc ?? '' ?>
                                        </figcaption>
                                    </figure>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- end col-12 -->
            </div>
        </div>
    </section>
    <!-- end Penulis Pilihan -->
<?php } ?>

<?php if ((!empty($quizzes)) && false) { ?>
    <hr>
    <!-- quiz -->
    <section class="content-section quiz_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <center>
                        <h2 class="section-title mb-40">Quiz</h2>
                    </center>
                </div>
                <?php foreach ($quizzes

                as $key => $quiz) { ?>
                <?php if ($key == $quizzes_see_more_limit) { ?>
            </div>
            <div id="collapse-quizzes-article" class="collapse collapse-see-more">
                <div class="row">
                    <?php } ?>
                    <div class="col-lg-6 mb-20">
                        <div class="blog-post miami">
                            <figure class="post-image"><img
                                    data-src="<?= base_url() ?>assets/quiz/thumb/<?= $quiz->content_pic_thumb ?>"
                                    class="lazy" width="120" height="80" alt="<?= $quiz->title; ?>"></figure>
                            <div class="post-content">
                                <h3 class="post-title text-capitalize">
                                    <a href="<?= base_url(); ?>quiz/<?php echo $quiz->id_content . ($quiz->id_user ? '-' . strtolower(url_title($quiz->user_name)) : '') ?>/<?= strtolower(url_title($quiz->title)); ?>"><?= $quiz->title ?></a>
                                </h3>
                                <div class="metas">
                                    <?php if ($quiz->id_user) { ?>
                                        <div class="author">
                                            <figure><img
                                                    data-src="<?= $this->frontend_lib->getUserPictureURL($quiz->user_picture, $quiz->user_picture_from); ?>"
                                                    class="lazy" width="30" height="30" alt="<?= $quiz->user_name; ?>"></figure>
                                            <a href="<?php echo base_url(); ?>author/<?php echo $quiz->id_user; ?>/<?php echo strtolower(url_title($quiz->user_name)); ?>"><?= $quiz->user_name ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($key == count($quizzes) - 1) { ?>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
            <!-- end blog-post -->
            <div class="clear-fix"></div>
            <?php if (count($quizzes) > $quizzes_see_more_limit) { ?>
                <div class="row">
                    <div class="col-12 mt-10">
                        <center>
                            <a href="#collapse-quizzes-article" class="btn btn-default" data-toggle="collapse"
                                role="button" data-href="<?php echo base_url() . 'page/quizzes' ?>">Lihat Lebih Banyak
                            </a>
                        </center>
                    </div>
                </div>
            <?php } ?>
            <!-- end col-12 -->

            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- end Quiz -->
<?php } ?>

<?php if ((!empty($polls)) && false) { ?>
    <hr>
    <!-- Polling -->
    <section class="content-section poll_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <center>
                        <h2 class="section-title mb-40">Polling</h2>
                    </center>
                </div>
                <?php foreach ($polls

                as $key => $poll) { ?>
                <?php if ($key == $polls_see_more_limit) { ?>
            </div>
            <div id="collapse-polls-article" class="collapse collapse-see-more">
                <div class="row">
                    <?php } ?>
                    <div class="col-lg-6 mb-20">
                        <div class="blog-post miami">
                            <figure class="post-image"><img
                                    data-src="<?= base_url() ?>assets/poll/thumb/<?= $poll->content_pic_thumb ?>"
                                    class="lazy" width="120" height="80" alt="<?= $poll->title; ?>"></figure>
                            <div class="post-content">
                                <h3 class="post-title text-capitalize">
                                    <a href="<?php echo base_url() ?>poll/<?php echo $poll->id_content . ($poll->id_user ? '-' . strtolower(url_title($poll->user_name)) : '') ?>/<?php echo strtolower(url_title($poll->title)) ?>"><?= $poll->title ?></a>
                                </h3>
                                <div class="metas">
                                    <?php if ($poll->id_user) { ?>
                                        <div class="author">
                                            <figure><img
                                                    data-src="<?= $this->frontend_lib->getUserPictureURL($poll->user_picture, $poll->user_picture_from); ?>"
                                                    class="lazy" width="30" height="30" alt="<?= $poll->user_name; ?>"></figure>
                                            <a href="<?php echo base_url(); ?>author/<?php echo $poll->id_user; ?>/<?php echo strtolower(url_title($poll->user_name)); ?>"><?= $poll->user_name ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($key == count($polls) - 1) { ?>
                </div>
                <?php } ?>
                <?php } ?>
                <!-- end blog-post -->
            </div>

            <?php if (count($polls) > $polls_see_more_limit) { ?>
                <div class="row">
                    <div class="col-12 mt-10">
                        <center>
                            <a href="#collapse-polls-article" class="btn btn-default" data-toggle="collapse"
                                role="button" data-href="<?php echo base_url() . 'page/polls' ?>">Lihat Lebih Banyak
                            </a>
                        </center>
                    </div>
                </div>
            <?php } ?>
            <!-- end col-12 -->

            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- end Polling -->
<?php } ?>