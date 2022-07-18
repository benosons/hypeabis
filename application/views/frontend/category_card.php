<div class="row m-b-20">

  <? foreach($articles as $x => $article){ ?>
    <div class="col-md-<?= ($category[0]->show_sidebar == 1 ? '6' : '4'); ?> col-12 align-self-top m-b-20">
      <div class="post-block-style">
        <? if(isset($article->content_pic_thumb) && strlen(trim($article->content_pic_thumb)) > 0){ ?>
          <div class="post-thumb">
            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
              <img class="img-fluid lazy" src="<?= base_url(); ?>assets/content/thumb/<?= $article->content_pic_thumb; ?>" alt="Bisnis Muda">
            </a>
          </div>
        <? } ?>
        <div class="post-content">
          <div class="post-meta m-t-10">
            <ul>
              <? if(isset($article->id_user) && $article->id_user > 0){ ?>
              <li>
                <a href="<?= base_url(); ?>author/<?= $article->id_user; ?>/<?= strtolower(url_title($article->name)); ?>">
                  <img src="<?= $this->frontend_lib->getUserPictureURL($article->picture, $article->picture_from); ?>" alt="Avatar" class="avatar lazy" align="left"> 
                  <?= $article->name; ?>
                </a>
              </li>
              <? } ?>
              <li>
                <a href="javascript:;"><i class="fa fa-clock-o"></i> <?= date('d F Y', strtotime($article->publish_date)); ?></a>
              </li>
            </ul>
          </div>
          <h1 class="post-title fs-16 heading-extrabold">
            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
              <?= $article->title; ?>
            </a>
          </h1>
        </div><!-- Post content end -->
      </div><!-- post-block -->
    </div>
    
    <?
      $divider = ($category[0]->show_sidebar == 1 ? 2 : 3);
      if(($x + 1) % $divider == 0){
        echo '<div class="clearfix d-none d-md-block" style="clear:both;"></div>';
      }
    ?>
  <? } ?>
  
  <? if(!(isset($articles) && is_array($articles) && count($articles) > 0)){ ?>
    <div class="col-md-12">
      <p>Belum ada artikel di kategori ini.</p>
    </div>
  <? } else { ?>
    <div class="col-md-12">
      <?= $this->pagination->create_links(); ?>
    </div>
  <? } ?>
</div>
