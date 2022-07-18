<!-- SIDEBAR -->
<div class="col-md-4">
  <? if(isset($newest_article) && is_array($newest_article) && count($newest_article) > 0){ ?>
  <h2 class="block-title-small heading-black">
    <span>Fresh</span>
  </h2>
  <div class="row m-t-20">
    <? foreach($newest_article as $x => $article){ ?>
    <div class="col-12">
      <h2 class="post-title fs-16 heading-extrabold">
        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
          <?= $article->title; ?>
        </a>
      </h2>
    </div>
    <div class="col-12">
      <div class="separator-black m-t-5 m-b-10"></div>
    </div>
    <? } ?>
  </div>
  <? } ?>

  <? if(isset($commented_article) && is_array($commented_article) && count($commented_article) > 0){ ?>
  <h2 class="block-title-small heading-black m-t-30">
    <span>Diobrolin</span>
  </h2>
  <div class="row m-t-20">
    <? foreach($commented_article as $x => $article){ ?>
    <div class="col-12">
      <h2 class="post-title fs-16 heading-extrabold">
        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
          <?= $article->title; ?>
        </a>
      </h2>
    </div>
    <div class="col-12">
      <div class="separator-black m-t-5 m-b-10"></div>
    </div>
    <? } ?>
  </div>
  <? } ?>
  
  <!-- ADVERTISEMENT
  <div class="row m-t-20">
    <div class="col-12">
      <a href="#">
        <img class="img-fluid full-width" src="<?= base_url(); ?>files/frontend/images/banner-image/image2.png" alt="">
      </a>
    </div>
  </div>
  -->
  
  <? if(isset($featured_author) && is_array($featured_author) && count($featured_author) > 0){ ?>
  <h2 class="block-title-small heading-black m-t-30">
    <span>Penulis Pilihan</span>
  </h2>
  
  <div class="row m-t-20">
    <? foreach($featured_author as $x => $author){ ?>
      <div class="col-4">
        <p align="center" class="m-b-0 p-b-0">
          <center>
            <a href="<?= base_url(); ?>author/<?= $author->id_user; ?>/<?= strtolower(url_title($author->name)); ?>">
              <img src="<?= $this->frontend_lib->getUserPictureURL($author->picture, $author->picture_from); ?>" class="avatar-big lazy">
              <br/><p class="text-black fs-12 lh-14 m-t-10"><?= $author->name; ?></p>
            </a>
          </center>
        </p>
      </div>
      
      <? if(($x + 1) % 3 == 0){ ?>
        <div class="clearfix" style="clear:both;"></div>
      <? } ?>
    <? } ?>
  </div>
  <? } ?>
</div>
