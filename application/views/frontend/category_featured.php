<?php if(isset($article_recommended[0]->id_content) && $article_recommended[0]->id_content > 0){ ?>
<!-- Section featured content category -->
<section class="block">
  <div class="container">
    <div class="row">
      <div class="col-md-6 m-b-20">
        <?php if(isset($article_recommended[0]->id_content) && $article_recommended[0]->id_content > 0){ ?>
          <div class="post-block-style">
            <?php if(isset($article_recommended[0]->content_pic_thumb) && strlen(trim($article_recommended[0]->content_pic_thumb)) > 0){ ?>
              <div class="post-thumb">
                <a href="<?= base_url(); ?>read/<?= $article_recommended[0]->id_content; ?>/<?= strtolower(url_title($article_recommended[0]->title)); ?>">
                  <img class="img-fluid lazy" src="<?= base_url(); ?>assets/content/thumb/<?= $article_recommended[0]->content_pic_thumb; ?>" alt="">
                </a>
              </div>
            <?php } ?>
            <div class="post-content">
              <div class="post-meta m-t-30">
                <ul>
                  <?php if(isset($article_recommended[0]->id_user) && $article_recommended[0]->id_user > 0){ ?>
                  <li>
                    <a href="<?= base_url(); ?>author/<?= $article_recommended[0]->id_user; ?>/<?= $article_recommended[0]->name; ?>">
                      <img src="<?= $this->frontend_lib->getUserPictureURL($article_recommended[0]->picture, $article_recommended[0]->picture_from); ?>" alt="Avatar" class="avatar lazy" align="left"> 
                      <?= $article_recommended[0]->name; ?>
                    </a>
                  </li>
                  <?php } ?>
                  <li>
                    <a href="javascript:;"><i class="fa fa-clock-o"></i> <?= date('d F Y', strtotime($article_recommended[0]->publish_date)); ?></a>
                  </li>
                </ul>
              </div>
              <h1 class="post-title title-xl heading-extrabold">
                <a href="<?= base_url(); ?>read/<?= $article_recommended[0]->id_content; ?>/<?= strtolower(url_title($article_recommended[0]->title)); ?>">
                  <?= $article_recommended[0]->title; ?>
                </a>
              </h1>
              <?php if(isset($article_recommended[0]->short_desc) && strlen(trim($article_recommended[0]->content_pic_thumb)) > 0){ ?>
              <p class="m-t-20">
                <?= $article_recommended[0]->short_desc; ?>
              </p>
              <?php } ?>
            </div><!-- Post content end -->
          </div><!-- post-block -->
        <?php } ?>
      </div>
      
      <div class="col-md-3">
        <?php for($x = 1; $x <= 2; $x++){ ?>
          <?php if(isset($article_recommended[$x]->id_content) && $article_recommended[$x]->id_content > 0){ ?>
            <div class="post-block-style m-b-10">
              <?php if(isset($article_recommended[$x]->content_pic_thumb) && strlen(trim($article_recommended[$x]->content_pic_thumb)) > 0){ ?>
                <div class="post-thumb">
                  <a href="<?= base_url(); ?>read/<?= $article_recommended[$x]->id_content; ?>/<?= strtolower(url_title($article_recommended[$x]->title)); ?>">
                    <img class="img-fluid lazy" src="<?= base_url(); ?>assets/content/thumb/<?= $article_recommended[$x]->content_pic_thumb; ?>" alt="Bisnis Muda">
                  </a>
                </div>
              <?php } ?>
              <div class="post-content">
                <div class="post-meta m-t-10">
                  <ul>
                    <?php if(isset($article_recommended[$x]->id_user) && $article_recommended[$x]->id_user > 0){ ?>
                    <li>
                      <a href="<?= base_url(); ?>author/<?= $article_recommended[$x]->id_user; ?>/<?= strtolower(url_title($article_recommended[$x]->name)); ?>">
                        <img src="<?= $this->frontend_lib->getUserPictureURL($article_recommended[$x]->picture, $article_recommended[$x]->picture_from); ?>" alt="Avatar" class="avatar lazy" align="left"> 
                        <?= $article_recommended[$x]->name; ?>
                      </a>
                    </li>
                    <?php } ?>
                    <li>
                      <a href="javascript:;"><i class="fa fa-clock-o"></i> <?= date('d F Y', strtotime($article_recommended[$x]->publish_date)); ?></a>
                    </li>
                  </ul>
                </div>
                <h1 class="post-title fs-16 heading-extrabold">
                  <a href="<?= base_url(); ?>read/<?= $article_recommended[$x]->id_content; ?>/<?= strtolower(url_title($article_recommended[$x]->title)); ?>">
                    <?= $article_recommended[$x]->title; ?>
                  </a>
                </h1>
              </div><!-- Post content end -->
            </div><!-- post-block -->
          <?php } ?>
        <?php } ?>
      </div>
      
      <div class="col-md-3">
        <?php for($x = 3; $x <= 4; $x++){ ?>
          <?php if(isset($article_recommended[$x]->id_content) && $article_recommended[$x]->id_content > 0){ ?>
            <div class="post-block-style m-b-10">
              <?php if(isset($article_recommended[$x]->content_pic_thumb) && strlen(trim($article_recommended[$x]->content_pic_thumb)) > 0){ ?>
                <div class="post-thumb">
                  <a href="<?= base_url(); ?>read/<?= $article_recommended[$x]->id_content; ?>/<?= strtolower(url_title($article_recommended[$x]->title)); ?>">
                    <img class="img-fluid lazy" src="<?= base_url(); ?>assets/content/thumb/<?= $article_recommended[$x]->content_pic_thumb; ?>" alt="">
                  </a>
                </div>
              <?php } ?>
              <div class="post-content">
                <div class="post-meta m-t-10">
                  <ul>
                    <?php if(isset($article_recommended[$x]->id_user) && $article_recommended[$x]->id_user > 0){ ?>
                    <li>
                      <a href="<?= base_url(); ?>author/<?= $article_recommended[$x]->id_user; ?>/<?= strtolower(url_title($article_recommended[$x]->name)); ?>">
                        <img src="<?= $this->frontend_lib->getUserPictureURL($article_recommended[$x]->picture, $article_recommended[$x]->picture_from); ?>" alt="Avatar" class="avatar lazy" align="left"> 
                        <?= $article_recommended[$x]->name; ?>
                      </a>
                    </li>
                    <?php } ?>
                    <li>
                      <a href="javascript:;"><i class="fa fa-clock-o"></i> <?= date('d F Y', strtotime($article_recommended[$x]->publish_date)); ?></a>
                    </li>
                  </ul>
                </div>
                <h1 class="post-title fs-16 heading-extrabold">
                  <a href="<?= base_url(); ?>read/<?= $article_recommended[$x]->id_content; ?>/<?= strtolower(url_title($article_recommended[$x]->title)); ?>">
                    <?= $article_recommended[$x]->title; ?>
                  </a>
                </h1>
              </div><!-- Post content end -->
            </div><!-- post-block -->
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</section>
<!-- section end -->
<?php } ?>
