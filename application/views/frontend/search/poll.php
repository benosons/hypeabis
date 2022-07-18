<?php
$author_slug = strtolower(url_title($content->user_name));
$title_slug = strtolower(url_title($content->title));
$content_url = base_url("poll/{$content->id_content}-{$author_slug}/$title_slug");
$author_picture_url = $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from);
$author_page_url = base_url("author/{$content->id_user}/{$author_slug}");
?>

<div class="blog-post montana">
  <figure class="post-image">
    <a href="<?php echo $content_url ?>">
      <img data-src='<?php echo base_url("assets/poll/thumb/{$content->content_pic_thumb}") ?>' class="lazy img-fluid" width="320" height="213" alt="<?php echo $content->title ?>">
    </a>
  </figure>
  <div class="post-content">
    <ul class="post-categories">
      <li>
        <a>Polling</a>
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
