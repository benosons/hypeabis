<? foreach($articles as $x => $article){ ?>
  <div class="row m-b-20">
    <div class="col-md-3 col-12 align-self-center m-b-10">
      <? if(isset($article->content_pic_thumb) && strlen(trim($article->content_pic_thumb)) > 0){ ?>
      <div class="post-thumb">
        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
          <img class="img-fluid lazy" src="<?= base_url(); ?>assets/content/thumb/<?= $article->content_pic_thumb; ?>" alt="Bisnis Muda">
        </a>
      </div>
      <? } ?>
    </div>
    <div class="col-md-9 col-12 align-self-center">
      <h2 class="post-title fs-18 heading-extrabold">
        <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>">
          <?= $article->title; ?>
        </a>
      </h2>
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
    </div>
  </div>
<? } ?>

<? if(!(isset($articles) && is_array($articles) && count($articles) > 0)){ ?>
  <div class="row">
    <div class="col-md-12">
      <p>Belum ada artikel di kategori ini.</p>
    </div>
  </div>
<? } else { ?>
  <div class="row">
    <div class="col-md-12">
      <?= $this->pagination->create_links(); ?>
    </div>
  </div>
<? } ?>
