    <?php $is_top_article_exist = (isset($article_on_top) && is_array($article_on_top) && count($article_on_top) > 0 ? true : false); ?>

    <!-- Top Featured Article -->
    <section class="content-section space-50 <?= (!(isset($article_on_top) && is_array($article_on_top) && count($article_on_top) > 0) ? 'pb-0' : ''); ?>">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <center>
              <h2 class="section-title text-uppercase <?= (!$is_top_article_exist ? 'mb-0' : ''); ?>">
                <?= $category[0]->category_name; ?>
              </h2>
            </center>
          </div>
        </div>

        <?php
        if ((isset($ads['skyscrapper_left_ct']['builtin'][0]['id_ads']) && $ads['skyscrapper_left_ct']['builtin'][0]['id_ads'] > 0) ||
          (isset($ads['skyscrapper_left_ct']['googleads'][0]['id_ads']) && $ads['skyscrapper_left_ct']['googleads'][0]['id_ads'] > 0)
        ) {
        ?>
          <div class="adv-ct-skyscrapper adv-ct-skyscrapper-left">
            <?php if (isset($ads['skyscrapper_left_ct']['builtin'][0]['id_ads']) && $ads['skyscrapper_left_ct']['builtin'][0]['id_ads'] > 0) { ?>
              <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['skyscrapper_left_ct']['builtin'][0]['id_ads']; ?>" target="_blank">
                <img src="<?= base_url(); ?>assets/adv/<?= $ads['skyscrapper_left_ct']['builtin'][0]['ads_pic']; ?>" alt="Bisnis Muda" class="img img-fluid" />
              </a>
            <?php } else if (isset($ads['skyscrapper_left_ct']['googleads'][0]['id_ads']) && $ads['skyscrapper_left_ct']['googleads'][0]['id_ads'] > 0) { ?>
              <?= html_entity_decode($ads['skyscrapper_left_ct']['googleads'][0]['googleads_code']); ?>
            <?php } else {
            } ?>
          </div>
        <?php } ?>

        <?php
        if ((isset($ads['skyscrapper_right_ct']['builtin'][0]['id_ads']) && $ads['skyscrapper_right_ct']['builtin'][0]['id_ads'] > 0) ||
          (isset($ads['skyscrapper_right_ct']['googleads'][0]['id_ads']) && $ads['skyscrapper_right_ct']['googleads'][0]['id_ads'] > 0)
        ) {
        ?>
          <div class="adv-ct-skyscrapper adv-ct-skyscrapper-right">
            <?php if (isset($ads['skyscrapper_right_ct']['builtin'][0]['id_ads']) && $ads['skyscrapper_right_ct']['builtin'][0]['id_ads'] > 0) { ?>
              <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['skyscrapper_right_ct']['builtin'][0]['id_ads']; ?>" target="_blank">
                <img src="<?= base_url(); ?>assets/adv/<?= $ads['skyscrapper_right_ct']['builtin'][0]['ads_pic']; ?>" alt="Bisnis Muda" class="img img-fluid" />
              </a>
            <?php } else if (isset($ads['skyscrapper_right_ct']['googleads'][0]['id_ads']) && $ads['skyscrapper_right_ct']['googleads'][0]['id_ads'] > 0) { ?>
              <?= html_entity_decode($ads['skyscrapper_right_ct']['googleads'][0]['googleads_code']); ?>
            <?php } else {
            } ?>
          </div>
        <?php } ?>
        <?php if ($is_top_article_exist) { ?>
          <!-- end row -->
          <div class="row mt-20">
            <?php foreach ($article_on_top as $x => $article) { ?>
              <div class="col-lg-3 col-md-6">
                <div class="blog-post new-york">
                  <div class="post-content">
                    <h3 class="post-title">
                      <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                        <?= $article->title ?>
                      </a>
                    </h3>
                    <div class="metas">
                      <div class="author">
                        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                          <figure><img src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>" alt="Image"></figure>
                        </a>
                        <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <!-- end row -->
        <?php } ?>
      </div>
      <!-- end container -->
    </section>
    <!-- end Top Featured Article -->

    <?php if ($is_top_article_exist) { ?>
      <hr />
    <?php } ?>

    <!-- featured on category -->
    <?php if (isset($featured_on_category) && is_array($featured_on_category) && count($featured_on_category) > 0) { ?>
      <section class="content-section">
        <div class="container">
          <div class="row">
            <div class="col-lg-8">
              <?php if (isset($featured_on_category[0])) { ?>
                <div class="blog-post nevada mt-20">
                  <figure class="post-image">
                    <a href="<?= base_url(); ?>read/<?= $featured_on_category[0]->id_content; ?>/<?= strtolower(url_title($featured_on_category[0]->title)); ?>">
                      <img src="<?= base_url() ?>assets/content/<?= $featured_on_category[0]->content_pic ?>" alt="Image">
                    </a>
                  </figure>
                  <div class="post-content">
                    <ul class="post-categories">
                      <li><a href="<?= base_url(); ?>category/<?= $featured_on_category[0]->id_category; ?>/<?= strtolower(url_title($featured_on_category[0]->category_name)); ?>"><?= $featured_on_category[0]->category_name ?></a></li>
                    </ul>
                    <h3 class="post-title text-capitalize">
                      <a href="<?= base_url(); ?>read/<?= $featured_on_category[0]->id_content; ?>/<?= strtolower(url_title($featured_on_category[0]->title)); ?>">
                        <?= $featured_on_category[0]->title ?>
                      </a>
                    </h3>
                    <div class="metas">
                      <div class="author">
                        <figure><img src="<?= $this->frontend_lib->getUserPictureURL($featured_on_category[0]->picture, $featured_on_category[0]->picture_from); ?>" alt="Image"></figure>
                        By <a href="<?php echo base_url(); ?>author/<?php echo $featured_on_category[0]->id_user; ?>/<?php echo strtolower(url_title($featured_on_category[0]->name)); ?>"><?= $featured_on_category[0]->name ?></a>
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

            <div class="col-lg-4">
              <h2 class="section-title text-left"></h2>
              <?php $index = 0 ?>
              <?php foreach ($featured_on_category as $x => $article) { ?>
                <?php if ($index > 0) { ?>
                  <div class="blog-post miami">
                    <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                      <figure class="post-image"> <img src="<?= base_url() ?>assets/content/thumb/<?= $article->content_pic_thumb; ?>" alt="Image"> </figure>
                    </a>
                    <div class="post-content">
                      <h3 class="post-title text-capitalize">
                        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>"><?= $article->title ?></a>
                      </h3>
                      <div class="metas">
                        <div class="author">
                          <figure><img src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>" alt="Image"></figure>
                          <a href="<?php echo base_url(); ?>author/<?php echo $article->id_user; ?>/<?php echo strtolower(url_title($article->name)); ?>"><?= $article->name ?></a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              <?php $index++;
              } ?>
              <!-- end blog-post -->
            </div>
            <!-- end col-4 -->
          </div>
          <!-- end row -->
        </div>
        <!-- end container -->
      </section>
    <?php } ?>
    <!-- end featured on category -->

    <!-- Adv -->
    <?php
    if ((isset($ads['sup_leaderboard_ct']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ct']['builtin'][0]['id_ads'] > 0) ||
      (isset($ads['sup_leaderboard_ct']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ct']['googleads'][0]['id_ads'] > 0)
    ) {
    ?>
      <div class="section-ad mt-40" style="padding-top:50px">
        <div class="container">
          <div class="col-md-12 m-b-75" style="margin-top:-30px;">
            <center>
              <div class="ads-wrapper gres-ads-wrapper">
                <?php if (isset($ads['sup_leaderboard_ct']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ct']['builtin'][0]['id_ads'] > 0) { ?>
                  <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['sup_leaderboard_ct']['builtin'][0]['id_ads']; ?>" target="_blank">
                    <img src="<?= base_url(); ?>assets/adv/<?= $ads['sup_leaderboard_ct']['builtin'][0]['ads_pic']; ?>" alt="Hypeabis" class="img img-fluid" />
                  </a>
                <?php } else if (isset($ads['sup_leaderboard_ct']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ct']['googleads'][0]['id_ads'] > 0) { ?>
                  <?= html_entity_decode($ads['sup_leaderboard_ct']['googleads'][0]['googleads_code']); ?>
                <?php } else {
                } ?>
              </div>
            </center>
          </div>
        </div>
      </div>
    <?php } ?>
    <!-- end Adv -->

    <!-- Artikel Terbaru -->
    <section class="content-section mt-0">
      <div class="container">
        <?php if (isset($category[0]->show_section_title) && $category[0]->show_section_title == 1) { ?>
          <div class="row">
            <div class="col-12">
              <h2 class="section-title">Gres</h2>
            </div>
          </div>
        <?php } ?>
        <!-- end row -->
        <div class="row justify-content-center">
          <?php foreach ($newest_articles as $x => $new_article) { ?>
            <?php if ($x == $newest_articles_see_more_limit) { ?>
        </div>
        <div id="collapse-newest-article" class="collapse collapse-see-more">
          <div class="row justify-content-center">
          <?php } ?>
          <div class="col-lg-4 col-md-6">
            <div class="blog-post texas">
              <figure class="post-image">
                <a href="<?= base_url(); ?>read/<?= $new_article->id_content; ?>/<?= strtolower(url_title($new_article->title)); ?>">
                <img data-src="<?= base_url(); ?>assets/content/thumb/<?php echo $new_article->content_pic_thumb; ?>" class="lazy" alt="Image">
                </a>
                <ul class="post-categories">
                  <li><a href="<?= base_url(); ?>category/<?= $new_article->id_category; ?>/<?= strtolower(url_title($new_article->category_name)); ?>"><?= $new_article->category_name ?></a></li>
                </ul>
              </figure>
              <div class="post-content">
                <h3 class="post-title">
                  <a href="<?= base_url(); ?>read/<?= $new_article->id_content; ?>/<?= strtolower(url_title($new_article->title)); ?>"><?= $new_article->title ?> </a>
                </h3>
                <div class="metas">
                  <div class="author">
                    <figure><img data-src="<?= $this->frontend_lib->getUserPictureURL($new_article->picture, $new_article->picture_from); ?>" class="lazy" width="30" height="30" alt="Image"></figure>
                    <a href="<?php echo base_url(); ?>author/<?php echo $new_article->id_user; ?>/<?php echo strtolower(url_title($new_article->name)); ?>"><?= $new_article->name ?> </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end col-4 -->
          <?php if ($x == count($newest_articles) - 1) { ?>
          </div>
        <?php } ?>
      <?php } ?>

        </div>
        <!-- end row -->
        <?php if (count($newest_articles) > $newest_articles_see_more_limit) { ?>
          <div class="row">
            <div class="col-12 mt-40">
              <center>
                <a href="#collapse-newest-article" class="btn btn-default" data-toggle="collapse" role="button" data-href="<?php echo base_url() . 'page/articles?category=' . $category[0]->id_category ?>">Lihat Lebih Banyak</a>
              </center>
            </div>
          </div>
        <?php } ?>
      </div>
      <!-- end container -->
    </section>
    <!-- end Artikel Terbaru -->

    <!-- Adv -->
    <?php
    if ((isset($ads['billboard_ct']['builtin'][0]['id_ads']) && $ads['billboard_ct']['builtin'][0]['id_ads'] > 0) ||
      (isset($ads['billboard_ct']['googleads'][0]['id_ads']) && $ads['billboard_ct']['googleads'][0]['id_ads'] > 0)
    ) {
    ?>
      <div class="section-ad mt-40">
        <div class="container">
          <div class="col-md-12 m-b-75" style="margin-top:-30px;">
            <center>
              <div class="ads-wrapper gres-ads-wrapper">
                <?php if (isset($ads['billboard_ct']['builtin'][0]['id_ads']) && $ads['billboard_ct']['builtin'][0]['id_ads'] > 0) { ?>
                  <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['billboard_ct']['builtin'][0]['id_ads']; ?>" target="_blank">
                    <img src="<?= base_url(); ?>assets/adv/<?= $ads['billboard_ct']['builtin'][0]['ads_pic']; ?>" alt="Hypeabis" class="img img-fluid" />
                  </a>
                <?php } else if (isset($ads['billboard_ct']['googleads'][0]['id_ads']) && $ads['billboard_ct']['googleads'][0]['id_ads'] > 0) { ?>
                  <?= html_entity_decode($ads['billboard_ct']['googleads'][0]['googleads_code']); ?>
                <?php } else {
                } ?>
              </div>
            </center>
          </div>
        </div>
      </div>
    <?php } ?>
    <!-- end Adv -->
