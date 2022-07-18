<!-- Top -->
<section class="content-section title-gallery">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <center>
          <h2 class="section-title text-uppercase mb-10">HYPESHOP</h2>
        </center>
      </div>
    </div>
  </div>
  <!-- end container -->
</section>
<!-- end Top -->

<!-- Hypeshop -->
<section class="content-section pt-0">
  <div class="container-fluid">
    <div class="col-12 mt-40">
      <div id="shop-masonry" class="masonry">
        <?php foreach ($contents as $key => $content) { ?>
          <a href="<?php echo base_url() . 'hypeshop/' . $content->id_content . '/' . strtolower(url_title($content->title)) ?>">
            <div class="grid <?php echo ($key >= $contents_see_more_limit ? 'd-none' : '') ?>">
              <?php if ($key < $contents_see_more_limit) { ?>
                <img src="<?php echo $assets_url ?>/thumb/<?php echo $content->content_pic_thumb ?>">
              <?php } else { ?>
                <img data-src="<?php echo $assets_url ?>/thumb/<?php echo $content->content_pic_thumb ?>">
              <?php } ?>
              <div class="grid__body">
                <div class="gallery_info_wrapper">
                  <div class="d-flex">
                    <h1 class="grid__title">
                      <?php echo strtoupper($content->title) ?>
                    </h1>
                  </div>
                </div>
              </div>
              <div class="overlay"></div>
            </div>
          </a>
        <?php } ?>
      </div>

      <?php if (count($contents) > $contents_see_more_limit) { ?>
        <div class="row">
          <div class="col-12 mt-20">
            <center>
              <a class="btn btn-default btn-see-more-masonry" role="button" data-masonry-element="#shop-masonry" data-href="<?php echo base_url() . 'page/hypeshop' ?>">Lihat Lebih Banyak</a>
            </center>
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
  </div>
  <!-- end container -->
</section>
