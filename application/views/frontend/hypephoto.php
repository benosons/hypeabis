<!-- Top -->
<section class="content-section title-gallery">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <center>
          <h2 class="section-title text-uppercase mb-10">HYPEPHOTO</h2>
        </center>
      </div>
    </div>
  </div>
  <!-- end container -->
</section>
<!-- end Top -->

<style>
    .link-unlike{
        cursor: pointer;
        color:#ffffff;
    }
</style>
<!-- Hypephoto -->
<section class="content-section pt-0">
  <div class="container-fluid">
    <div class="col-12 mt-40">
      <div id="photo-masonry" class="masonry">
        <?php foreach ($contents as $key => $content) { ?>
            <div class="grid <?php echo ($key >= $contents_see_more_limit ? 'd-none' : '') ?>">
              <div class="top_gallery_info_wrapper">
                <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())) , "="); ?>
                <a class="link-like"
                    href="javascript:;"
                    data-id="<?php echo $content->id_content ?>"
                    title="Like / Unlike"
                    <?php echo ($this->session->userdata('user_logged_in') ? '' : 'data-redirect="' . base_url("page/login/{$redirect_url}") . '"') ?>>
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
              <a href="<?php echo photo_url($content->id_content, $content->title) ?>">
                <?php if ($key < $contents_see_more_limit) { ?>
                  <img src="<?php echo base_url() ?>assets/photo/thumb/<?php echo $content->photos[0]->picture_thumb ?>" data-target="#carouselExample" data-slide-to="0" style="margin-bottom: 0; min-height: 158px;">
                <?php } else { ?>
                  <img data-src="<?php echo base_url() ?>assets/photo/thumb/<?php echo $content->photos[0]->picture_thumb ?>" data-target="#carouselExample" data-slide-to="0" style="margin-bottom: 0; min-height: 158px;">
                <?php } ?>
              </a>
              <div class="grid__body">
                <div class="gallery_info_wrapper">
                  <div class="d-flex">
                    <a href="<?php echo photo_url($content->id_content, $content->title) ?>" class="full-width">
                      <h1 class="grid__title text-white">
                        <?php echo strtoupper($content->title) ?>
                      </h1>
                    </a>
                  </div>
                  <div class="grid__author d-inline-block">
                    <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->user_name)); ?>">
                      <img data-src="<?php echo $this->frontend_lib->getUserPictureURL($content->user_picture, $content->user_picture_from); ?>" class="lazy rounded-circle" width="30" height="30" alt="Avatar">

                      <span class="text-white"><?php echo $content->user_name ?></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
        <?php } ?>
      </div>

      <?php if (count($contents) > $contents_see_more_limit) { ?>
        <div class="photo-more-btn-wrapper">
          <div class="container">
            <div class="row">
              <div class="col-12 mt-20">
                <center>
                  <a class="btn btn-default btn-see-more-masonry" role="button" data-masonry-element="#photo-masonry" data-href="<?php echo base_url('page/hypephoto/' . count($contents)) ?>">
                    LIHAT LEBIH BANYAK
                  </a>
                </center>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <!-- end row -->
  <!-- <div class="row"> -->
  <!--   <div class="col-12 mt-40"> -->
  <!--     <center> -->
  <!--       <a href="#" class="btn btn-default">Lihat Lebih Banyak</a> -->
  <!--     </center> -->
  <!--   </div> -->
  <!-- </div> -->
  <!-- end container -->
</section>
