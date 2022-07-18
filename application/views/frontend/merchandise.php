<!-- Section category title -->
<section class="block bg-lighter p-b-0 p-t-30">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="block-title block-title-secondary heading-black m-b-0 m-t-20">
          <span class="title-angle-shap"> Merchandises</span>
        </h2>
      </div>
    </div><!-- row end -->
  </div>
</section>
<!-- section end -->

<!-- Section category article -->
<section class="block">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <p class="subscribe-text">
          Yuk kumpulkan poin sebanyak - banyaknya dengan berkontribusi di komunitas Bisnismuda.id dan tukarkan dengan hadiah - hadiah menarik!
        </p>
      </div>
    </div>
    <div class="row m-t-40">
      <? foreach($merchandises as $x => $merch){ ?>
        <div class="col-6 col-md-3">
          <div class="post-block-style">
            <? if(isset($merch->merch_pic_thumb) && strlen(trim($merch->merch_pic_thumb)) > 0){ ?>
              <div class="post-thumb">
                <a href="<?= base_url(); ?>user_merch/redeem/<?= $merch->id_merchandise; ?>/<?= strtolower(url_title($merch->merch_name)); ?>">
                  <img class="img-fluid lazy" src="<?= base_url(); ?>assets/merchandise/thumb/<?= $merch->merch_pic_thumb; ?>" alt="Merchandise Bisnis Muda" class="img img-fluid img-thumbnail">
                </a>
              </div>
            <? } ?>
            <div class="post-content">
              <div class="post-meta m-t-10">
                <ul>
                  <li>
                    <a href="javascript:;"><i class="fa fa-star"></i> <?= number_format($merch->merch_point, 0, ',', '.') ?> poin</a>
                  </li>
                  <li>
                    <a href="javascript:;"><i class="fa fa-shopping-cart"></i> Kuota: <?= number_format($merch->merch_quota, 0, ',', '.') ?></a>
                  </li>
                </ul>
              </div>
              <h1 class="post-title fs-16 heading-extrabold">
                <a href="<?= base_url(); ?>user_merch/redeem/<?= $merch->id_merchandise; ?>/<?= strtolower(url_title($merch->merch_name)); ?>">
                  <?= $merch->merch_name; ?>
                </a>
              </h1>
            </div><!-- Post content end -->
          </div><!-- post-block -->
        </div>
        
        <?
          if(($x + 1) % 4 == 0){
            echo '<div class="clearfix d-none d-md-block" style="clear:both;"></div>';
          }
          if(($x + 1) % 2 == 0){
            echo '<div class="clearfix d-none d-sm-block d-md-none" style="clear:both;"></div>';
          }
        ?>
      <? } ?>
    </div>
  </div>
</section>
<!-- section end -->