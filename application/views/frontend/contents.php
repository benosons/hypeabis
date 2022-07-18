<?php if (!$disable_search) { ?>
  <div class="contents-filter bg-light">
    <div class="container py-4">
      <?php echo form_open('page/' . $this->uri->segment(2), array('id' => 'filter-form', 'method' => 'get', 'role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label" for="title">Judul</label>
            <input class="form-control no-dark" type="text" name="title" value="<?php echo $search_param['title'] ?>">
          </div>
        </div>
        <?php if ($is_articles) : ?>
          <div class="col">
            <div class="form-group">
              <label class="control-label" for="category">Kategori</label>
              <select id="category" class="w-100" name="category">
                <option value=""></option>
                <?php foreach ($categories as $item) : ?>
                  <option value="<?php echo $item['id_category']; ?>" <?php echo ($search_param['category'] == $item['id_category'] ? "selected" : ""); ?>><?php echo $item['category_name']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        <?php endif; ?>
        <div class="col">
          <div class="form-group">
            <label class="control-label" for="author">Penulis</label>
            <select id="author" name="author">
              <option value=""></option>
              <?php if (!empty($search_param['author'])) { ?>
                <option value="<?php echo $search_param['author'] ?>" selected><?php echo $search_param['author_name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="form-group">
            <label class="control-label" for="start-date">Tanggal</label>
            <div class="row">
              <div class="col-md-6" style="margin-bottom: 1rem;">
                <div class="input-group tranparent">
                  <div class="input-group-prepend">
                    <span class="input-group-text transparent">Mulai</span>
                  </div>
                  <input id="start-date" type="text" class="form-control datepicker-range-start" name="start_date" value="<?php echo $search_param['start_date']; ?>" style="background-color: #fff; color: #444" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group transparent">
                  <div class="input-group-prepend">
                    <span class="input-group-text transparent">Akhir</span>
                  </div>
                  <input id="finish-date" type="text" class="form-control datepicker-range-finish" name="finish_date" value="<?php echo $search_param['finish_date']; ?>" style="background-color: #fff; color: #444" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- row end -->

      <div class="row">
        <div class="col-12">
          <button class="btn btn-default"><i class="fa fa-search"></i> Filter</button>
          <button id="btn-reset" class="btn btn-default" type="reset"><i class="fa fa-times"></i> Reset</button>
        </div>
      </div>
      <?php echo form_close() ?>
    </div><!-- container end -->
  </div>
<?php } ?>

<!-- Section category article -->
<section class="content-section mt-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <?php foreach ($contents as $content) : ?>
          <div class="blog-post montana">
            <?php if (isset($content->content_pic_thumb) && strlen(trim($content->content_pic_thumb)) > 0) { ?>
              <figure class="post-image">
                <a href="<?php echo $content_base_url ?>/<?php echo $content->id_content; ?><?php echo ($content->id_user ? '-' . strtolower(url_title($content->user_name)) : ''); ?>/<?php echo strtolower(url_title($content->title)); ?>">
                  <img data-src="<?php echo $assets_url ?>/thumb/<?php echo $content->content_pic_thumb; ?>" class="lazy img-fluid" width="320" height="213" alt="Image">
                </a>
              </figure>
            <?php } ?>
            <div class="post-content">
              <?php if ($content->type == 1) { ?>
                <ul class="post-categories">
                  <li><a href="<?php echo base_url() . "/category/{$content->id_category}/" . strtolower(url_title($content->category_name)); ?>"><?php echo $content->category_name ?></a></li>
                </ul>
              <?php } ?>
              <h3 class="post-title">
                <a href="<?php echo $content_base_url ?>/<?php echo $content->id_content; ?><?php echo ($content->id_user ? '-' . strtolower(url_title($content->user_name)) : ''); ?>/<?php echo strtolower(url_title($content->title)); ?>">
                  <?php echo $content->title; ?>
                </a>
                </h2>
                <div class="metas d-flex">
                  <?php if ($content->id_user) { ?>
                    <div class="author mr-3">
                      <figure>
                        <img data-src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>" class="lazy" width="30" height="30" alt="Image">
                      </figure>
                      By <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>"><?php echo $content->user_name ?></a>
                    </div>
                  <?php } ?>
                  <span class="date"><i class="far fa-clock"></i> <?= $this->global_lib->timeElapsedString($content->publish_date); ?></span>
                </div>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if (!(isset($contents) && is_array($contents) && count($contents) > 0)) : ?>
          <div class="row">
            <div class="col-md-12">
              <p>Data tidak ditemukan.</p>
            </div>
          </div>
        <?php else : ?>
          <div class="row">
            <div class="col-md-12">
              <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- section end -->

<script>
  $(document).ready(function() {
    $('#btn-reset').on('click', function() {
      $('[name=title]').val('')
    })
  })
</script>
