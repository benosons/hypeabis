<?php
$author_slug = strtolower(url_title($content->user_name));
$title_slug = strtolower(url_title($content->title));
$content_url = base_url("hypephoto/{$content->id_content}-{$author_slug}/$title_slug");
$author_picture_url = $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from);
$author_page_url = base_url("author/{$content->id_user}/{$author_slug}");
?>

<div class="blog-post montana">
  <figure class="post-image">
    <div class="top_gallery_info_wrapper">
      <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "="); ?>
      <a class="<?php echo ($content->is_liked ? 'link-liked' : 'link-like') ?>" data-id="<?php echo $content->id_content ?>" title="<?php echo ($content->is_liked ? 'Liked' : 'Like') ?>" <?php echo ($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
        <span class="like-counter align-middle" <?php echo ($content->like_count > 0 ? '' : 'style="display: none;"') ?>><?php echo number_format(ceil($content->like_count), 0, ',', '.'); ?></span>

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

    <a href="<?php echo $content_url ?>">
      <img data-src='<?php echo base_url("assets/photo/thumb/{$content->first_photo_thumb }") ?>' class="lazy img-fluid" width="320" height="213" alt="<?php echo $content->title ?>" style="margin-bottom: 0; min-height: 3.5rem;">
    </a>
  </figure>
  <div class="post-content">
    <ul class="post-categories">
      <li>
        <a href="<?php echo base_url('hypephoto') ?>">
          Hypephoto
        </a>
      </li>
    </ul>
    <h3 class="post-title">
      <a href="<?php echo $content_url ?>">
        <?php echo $content->title ?>
      </a>
    </h3>
    <div class="metas d-flex">
      <?php if ($content->id_user) { ?>
        <div class="author mr-3">
          <figure>
            <a href="<?php echo $author_page_url ?>">
              <img data-src="<?php echo $author_picture_url ?>" class="lazy" width="30" height="30" alt="<?php echo $content->user_name ?>">
            </a>
          </figure>
          By <a href="<?php echo $author_page_url ?>"><?php echo $content->user_name ?></a>
        </div>
      <?php } ?>
      <span class="date">
        <i class="far fa-clock"></i> <?php echo $this->global_lib->timeElapsedString($content->publish_date); ?>
      </span>
    </div>
  </div>
</div>

