<?php $user_url =  base_url() . "author/{$user[0]['id_user']}/" . strtolower(url_title($user[0]['name'])) ?>
<section class="content-section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="author-box">
          <figure>
            <img alt="Image" src="<?= $this->frontend_lib->getUserPictureURL($user[0]['picture'], $user[0]['picture_from']); ?>">
          </figure>
          <div class="content">
            <h5>
              <?= $user[0]['name']; ?>
              <?php if ($user[0]['verified'] == 1) { ?>
                <img src="<?= base_url(); ?>files/frontend/images/verified.png" class="lazy ml-2" alt="Verified member" title="Verified member" height="20px" />
              <?php } ?>
            </h5>
            <h6 class="mt-1 mb-0">
              <?php if (isset($user[0]['level']) && strlen(trim($user[0]['level'])) > 0) { ?>
                <label class="badge" style="background:#<?= $user[0]['bg_color']; ?>;color:#<?= $user[0]['text_color']; ?>">
                  <?= $user[0]['level']; ?>
                </label>
              <?php } ?>
            </h6>
            <?php if (isset($user[0]['profile_desc']) && strlen(trim($user[0]['profile_desc'])) > 0) { ?>
              <p class="mb-3"><?= $user[0]['profile_desc']; ?></p>
            <?php } ?>
            <div class="mt-2">
              <?php if ($is_following === false) : ?>
                <a class="btn btn-default btn-sm" href="<?php echo "{$user_url}/follow" ?>">Follow</a>
              <?php elseif ($is_following === true) : ?>
                <a class="btn btn-default" href="<?php echo "{$user_url}/unfollow" ?>">Unfollow</a>
              <?php endif; ?>
            </div>
          </div><!-- end content -->
        </div>
      </div>
      <!-- end col-12 -->
      <hr>
    </div>
    <!-- end row -->

    <hr>

    <div class="row">
      <div class="col-md-3">
        <div class="ts-category">
          <ul class="ts-category-list">
            <li>
              <a href="<?php echo $user_url ?>">
                <span>Artikel </span>
                <span class="bar"></span>
                <span class="category-count"><?= number_format($author_post, 0, ',', '.'); ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $user_url ?>/hypephotos">
                <span>Hypephoto </span>
                <span class="bar"></span>
                <span class="category-count"><?= number_format($author_hypephotos, 0, ',', '.'); ?></span>
              </a>
            </li>
            <?php if ($user[0]['verified'] === '1') : ?>
              <li>
                <a href="<?php echo $user_url ?>/polls">
                  <span>Polling </span>
                  <span class="bar"></span>
                  <span class="category-count"><?= number_format($author_polls, 0, ',', '.'); ?></span>
                </a>
              </li>
              <li>
                <a href="<?php echo $user_url ?>/quizzes">
                  <span>Quiz</span>
                  <span class="bar"></span>
                  <span class="category-count"><?= number_format($author_quizzes, 0, ',', '.'); ?></span>
                </a>
              </li>
            <?php endif; ?>
            <li>
              <a href="<?php echo "{$user_url}/followers" ?>">
                <span>Follower </span>
                <span class="bar"></span>
                <span class="category-count"><?php echo number_format($author_followers, 0, ',', '.'); ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo "{$user_url}/followings" ?>">
                <span>Following </span>
                <span class="bar"></span>
                <span class="category-count"><?php echo number_format($author_followings, 0, ',', '.'); ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo "{$user_url}/followings-article" ?>">
                <span>Following Article</span>
                <span class="bar"></span>
                <span class="category-count"><?php echo number_format($author_following_articles, 0, ',', '.'); ?></span>
              </a>
            </li>
          </ul>

          <ul class="ts-category-list m-t-20">
            <li>
              <a class="no-hover">
                <span>Dibaca </span>
                <span class="bar"></span>
                <span class="category-count"><?= number_format($author_read, 0, ',', '.'); ?></span>
              </a>
            </li>
            <li>
              <a class="no-hover">
                <span>Komentar </span>
                <span class="bar"></span>
                <span class="category-count"><?= number_format($author_comment, 0, ',', '.'); ?></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-9">
        <?php if ($is_following_articles) : ?>
          <h3>Following Articles</h3>
        <?php endif; ?>
        <?php foreach ($articles as $x => $article) { ?>
          <div class="blog-post montana">
            <?php if ($article->type != 7) { ?>
              <figure class="post-image" style="width: 240px;">
                <?php if (isset($article->content_pic_thumb) && strlen(trim($article->content_pic_thumb)) > 0) { ?>
                  <?php $name = $article->type === '1' ? $article->name : $article->user_name ?>
                  <a href="<?php echo $article_base_url ?>/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                    <img class="img-fluid lazy" src="<?php echo $assets_base_url ?>/thumb/<?= $article->content_pic_thumb; ?>" width="240" height="160" alt="Image">
                  </a>
                <?php } ?>
              </figure>
              <div class="post-content">
                <ul class="post-categories">
                  <li><a href="<?php echo base_url() . "/category/{$article->id_category}/" . strtolower(url_title($article->category_name)); ?>"><?php echo $article->category_name ?></a></li>
                </ul>
                <h3 class="post-title">
                  <a href="<?php echo $article_base_url ?>/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
                    <?= $article->title; ?>
                  </a>
                </h3>
                <div class="metas">
                  <span class="date"><i class="far fa-clock"></i> <?= $this->global_lib->timeElapsedString($article->publish_date); ?></span>
                  <span class="views ml-1"><i class="far fa-eye mr-0"></i> Dibaca <?= number_format($article->read_count); ?> kali</span>
                  <span class="views ml-1"><i class="far fa-comment mr-0"></i> <?= number_format($article->comment_count); ?> komentar</span>
                </div>
              </div>
            <?php } else { ?>
              <figure class="post-image" style="width: 240px;">
                <div class="top_gallery_info_wrapper">
                  <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "="); ?>
                  <a class="<?php echo ($article->is_liked ? 'link-liked' : 'link-like') ?>" data-id="<?php echo $article->id_content ?>" title="<?php echo ($article->is_liked ? 'Liked' : 'Like') ?>" <?php echo ($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
                    <span class="like-counter align-middle" <?php echo ($article->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($article->like_count), 0, ',', '.'); ?></span>

                    <?php if ($article->is_liked) { ?>
                      <i class="fas fa-thumbs-up ml-1"></i>
                    <?php } else { ?>
                      <i class="far fa-thumbs-up ml-1"></i>
                    <?php } ?>
                  </a>

                  <?php if ($article->photo_counts > 1) { ?>
                    <i class="far fa-clone text-white ml-2" style="font-size: 15px;"></i>
                  <?php } ?>
                </div>
                <?php $name = $article->type === '1' ? $article->name : $article->user_name ?>
                <a href="<?php echo photo_url($article->id_content, $article->title) ?>">
                  <img class="img-fluid lazy" src="<?php echo $assets_base_url ?>/thumb/<?= $article->photos[0]->picture_thumb; ?>" width="240" height="160" alt="Image" style="min-height: 100px;">
                </a>
              </figure>
              <div class="post-content">
                <h3 class="post-title">
                  <a href="<?php echo photo_url($article->id_content, $article->title) ?>">
                    <?= strtoupper($article->title); ?>
                  </a>
                </h3>
                <div class="metas">
                  <span class="date"><i class="far fa-clock"></i> <?= $this->global_lib->timeElapsedString($article->publish_date); ?></span>
                  <span class="views ml-1"><i class="far fa-eye mr-0"></i> Dilihat <?= number_format($article->read_count); ?> kali</span>
                  <?php if ($article->like_count > 0) { ?>
                    <span class="views ml-1"><i class="far fa-thumbs-up mr-0"></i> Disukai <?= number_format($article->like_count); ?> kali</span>
                  <?php } ?>
                </div>
              </div>

            <?php } ?>
          </div>
        <?php } ?>

        <?php if (count($related_users) > 0) : ?>
          <h3><?php echo $related_text ?></h3>
          <div class="row">
            <?php foreach ($related_users as $related_user) : ?>
              <?php $related_user_url = base_url() . "author/{$related_user->id_user}/" . strtolower(url_title($related_user->name)) ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center mb-4">
                <div class="author-box">
                  <div class="d-block w-100 ">
                    <a href="<?php echo $related_user_url ?>">
                      <img src="<?php echo $this->frontend_lib->getUserPictureURL($related_user->picture, $related_user->picture_from) ?>" class="lazy img-fluid rounded-circle w-100" width="125" height="125" alt="<?php echo $related_user->name ?>">
                    </a>
                  </div>
                  <a href="<?php echo $related_user_url ?>" class="mx-auto mt-3">
                    <h6 class="text-black"><?php echo $related_user->name ?></h6>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="col-md-12">
          <?= $this->pagination->create_links(); ?>
        </div>

      </div>
    </div>
  </div>
  <!-- end container -->
</section>

<script>
$(document).ready(function () {
  $('.link-like').on('click', function (event) {
    event.preventDefault();

    var $this = $(this)
    var post_data = {id_content: $(this).data('id')};
    var redirect = $this.data('redirect');

    if (redirect) {
      window.location = redirect;
      return;
    }

    $.ajax({
      'url' : '<?= base_url(); ?>' + 'content2/likeContent',
      'type' : 'POST', //the way you want to send data to your URL
      'data' : getCSRFToken(post_data),
      'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
        //if the request success..
        var obj = JSON.parse(data); // parse data from json to object..

        //if status not success, show message..
        if(obj.status == 'success'){
          Swal.fire('Terima kasih', 'telah menyukai hypephoto ini.', 'success');
          $this.find('.like-counter').show().html(obj.like_count);
          $this.off('click').removeClass('link-like').addClass('link-liked').attr('title', 'Liked');
          $this.find('.far').removeClass('far').addClass('fas')
        }
        else if(obj.status == 'nologin'){
          Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk menyukai hypephoto ini.', 'error');
        }
        else if(obj.status == 'already_liked'){
          Swal.fire('Terima kasih', 'anda sudah meyukai hypephoto ini.', 'warning');
        }
        else{
          Swal.fire('Maaf', obj.message, 'error');
        }
        refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
      },
      'complete' : function(){}
    });
  });
})
</script>
