<!-- START card -->
<div class="card card-transparent">
  <div class="card-body">
    <?= $this->session->flashdata('message'); ?>

    <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
      <h4 class="m-t-0 m-b-0 fw-600 text-heading-black">
        Terpopuler 1x24 Jam:
      </h4>
      <ol style="padding-inline-start: 20px;">
        <?php foreach ($popular_today_articles_alltime as $article) { ?>
          <li class="mb-1">
            <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>" title="Read" target="_blank">
              <?php echo $article->title ?>
            </a>
            – Oleh <?php echo $article->user_name ?>
            – <strong><?php echo $article->read_count ?></strong> hits
          </li>
        <?php } ?>
        </ol>
      
      <hr>

      <h4 class="m-t-20 m-b-0 fw-600 text-heading-black">
        Terpopuler Bulan Ini:
      </h4>

      <ol style="padding-inline-start: 20px;">
      <?php foreach ($popular_this_month_articles_alltime as $article) { ?>
        <li class="mb-1">
          <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>" title="Read" target="_blank">
            <?php echo $article->title ?>
          </a>
          – Oleh <?php echo $article->user_name ?>
          – <strong><?php echo $article->read_count ?></strong> hits
        </li>
      <?php } ?>
      </ol>
    </div>

    <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
      Statistik
    </h4>

    <?php $this->load->view('admin/dashboard/search'); ?>

    <?php $search_param = $this->session->userdata('search_dashboard'); ?>
    <? if ((isset($search_param['start_date']) && strlen(trim($search_param['start_date'])) > 0) || (isset($search_param['finish_date']) && strlen(trim($search_param['finish_date'])) > 0)) { ?>
      <p>
        Menampilkan hasil pencarian untuk artikel yang di publish&nbsp;
        <? if (isset($search_param['start_date']) && strlen(trim($search_param['start_date'])) > 0) { ?>
          dari tanggal <b><?= ($search_param['start_date']); ?></b>&nbsp;
        <? } ?>
        <? if (isset($search_param['finish_date']) && strlen(trim($search_param['finish_date'])) > 0) { ?>
          sampai tanggal <b><?= ($search_param['finish_date']); ?></b>&nbsp;
        <? } ?>
      </p>
      <br />
    <? } ?>

    <div class="table-responsive">
      <table class="table table-bordered">
        <tbody>
          <tr>
            <th class="text-nowrap">Total Artikel Terplubikasi</th>
            <td style="border-top:1px solid rgba(230, 230, 230, 0.7);"><?php echo number_format($published_articles_count, 0, ',', '.') ?></td>
          </tr>
          <tr>
            <th class="text-nowrap">Total Artikel Draft</th>
            <td><?php echo number_format($draft_articles_count, 0, ',', '.') ?></td>
          </tr>
          <tr>
            <th class="text-nowrap">Komentar terbaru</th>
            <td>
              <a href="<?php echo base_url('admin_comment') ?>" style="text-decoration: underline;">Lihat Semua</a>
            </td>
          </tr>
          <tr>
            <th class="text-nowrap">Terpopuler 1x24 Jam (berdasarkan tgl publish)</th>
            <td>
              <ol style="padding-inline-start: 20px;">
                <?php foreach ($popular_today_articles as $article) { ?>
                  <li class="mb-1">
                    <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>" title="Read" target="_blank">
                      <?php echo $article->title ?>
                    </a>
                    – Oleh <?php echo $article->user_name ?>
                    – <strong><?php echo $article->read_count ?></strong> hits
                  </li>
                <?php } ?>
                <ol>
            </td>
          </tr>
          <tr>
            <th class="text-nowrap">Terpopuler Bulan Ini (berdasarkan tgl publish)</th>
            <td>
              <ol style="padding-inline-start: 20px;">
                <?php foreach ($popular_this_month_articles as $article) { ?>
                  <li class="mb-1">
                    <a href="<?= base_url(); ?>read/<?= $article->id_content; ?>/<?= strtolower(url_title($article->title)); ?>" title="Read" target="_blank">
                      <?php echo $article->title ?>
                    </a>
                    – Oleh <?php echo $article->user_name ?>
                    – <strong><?php echo $article->read_count ?></strong> hits
                  </li>
                <?php } ?>
                <ol>
            </td>
          </tr>
          <tr>
            <th class="text-nowrap">Penulis Terproduktif</th>
            <td>
              <ol style="padding-inline-start: 20px;">
                <?php foreach ($most_productive_author as $author) { ?>
                  <li class="mb-1">
                    <?php echo $author->name ?>
                    – <strong><?php echo $author->article_count ?></strong> artikel
                    – <strong><?php echo $author->photo_count ?></strong> hypephoto
                  </li>
                <?php } ?>
                <ol>
            </td>
          </tr>
          <tr>
            <th class="text-nowrap">Editor Terproduktif</th>
            <td>
              <ol style="padding-inline-start: 20px;">
                <?php foreach ($most_productive_editor as $editor) { ?>
                  <li class="mb-1">
                    <?php echo $editor->name ?>
                    – <strong><?php echo $editor->article_count ?></strong> artikel
                    – <strong><?php echo $editor->photo_count ?></strong> hypephoto
                  </li>
                <?php } ?>
                <ol>
            </td>
          </tr>
          <tr>
            <th class="text-nowrap">Produktifitas Kanal</th>
            <td>
              <ul class="list-unstyled">
                <?php $categories_count = count($productivity_category) ?>
                <?php foreach ($productivity_category as $key => $category) { ?>
                  <li class="mb-1">
                    <?php echo $category['category_name'] ?>
                    – <strong><?php echo $category['article_count'] ?></strong> artikel
                  </li>

                  <?php if ($key == $categories_count - 2) { ?>
                    <li class="mb-1">
                      Hypeshop
                      – <strong><?php echo $productivity_hypeshop ?></strong> shoppable
                    </li>
                    <li class="mb-1">
                      Hypephoto
                      – <strong><?php echo $productivity_hypephoto ?></strong> hypephoto
                    </li>
                  <?php } ?>
                <?php } ?>
                <ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>