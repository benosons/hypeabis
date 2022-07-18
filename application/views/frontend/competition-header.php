<?php $title_slug = strtolower(url_title($competition->title)); ?>

<!-- Top -->
<section class="content-section title-gallery">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <center>
                    <h1 class="post-title text-uppercase mb-1">
                        <?php echo $competition->title ?>
                    </h1>
                    <p class="text-muted">
                        <?php echo strftime('%d %B %Y', date_create($competition->start_date)->getTimestamp()) ?>
                        &nbsp;&nbsp;-&nbsp;&nbsp;
                        <?php echo strftime('%d %B %Y', date_create($competition->finish_date)->getTimestamp()) ?>
                    </p>
                    <?php if (count($categories) > 0) { ?>
                        <div class="row d-flex justify-content-center my-3">
                            <div class="col text-center">
                                <?php foreach ($categories as $categoryOption) { ?>
                                    <a href='<?php echo $base_url ?>?id_competition_category=<?= $categoryOption->id_competition_category ?>' class="mb-2 btn btn-sm btn-default <?php echo($categoryOption->id_competition_category == $category ? 'bg-dark border-dark text-white disabled' : '') ?>">
                                        <?= $categoryOption->category_name ?>
                                    </a>
                                <?php } ?>
                                <a href='<?php echo $base_url; ?>' class="mb-2 btn btn-sm btn-default <?php echo(!$category ? 'bg-dark border-dark text-white disabled' : '') ?>">
                                    Semua Kategori
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                            <img src="<?= base_url(); ?>assets/competition/<?= $competition->pic; ?>" class="card-img-top mt-3" alt="<?= $competition->title; ?>" style="<?= $competition->cover_status ? '' : 'filter: grayscale(100%);'; ?>">
                        </div>
                    </div>
                </center>


                <?php $nowTime = strtotime(date('Y-m-d H:i:s')) ?>
                <?php $startDateTime = strtotime($competition->start_date) ?>
                <?php $finishDateTime = strtotime($competition->finish_date) ?>

                <?php if ($nowTime >= $startDateTime && $nowTime <= $finishDateTime) { ?>
                    <div class="row justify-content-center my-3">
                        <div class="col-auto px-3">
                            <a href="<?= base_url($competition->competition_type == 'article' ? 'user_content/add' : 'user_photo/add') ?>?join_competition=1" class="btn btn-lg btn-default text-uppercase font-weight-bolder" style="border:3px solid;padding:10px 40px;">
                                Ikuti Lomba
                            </a>
                        </div>
                    </div>
                <?php } ?>

                <div class="row d-flex justify-content-center my-4">
                    <div class="col-md-10 text-center">
                        <a href='<?php echo base_url("kompetisi/{$competition->id_competition}/{$title_slug}/syarat-dan-ketentuan") ?>' class="mb-2 btn btn-sm btn-default <?php echo($this->uri->segment(4) == 'syarat-dan-ketentuan' ? 'bg-dark border-dark text-white disabled' : '') ?>">
                            Syarat dan Ketentuan Kompetisi
                        </a>
                        <a href='<?php echo base_url("kompetisi/{$competition->id_competition}/{$title_slug}" . ($category ? "?id_competition_category={$category}" : '')) ?>'
                            class="mb-2 btn btn-sm btn-default <?php echo($this->uri->segment(4) === null ? 'bg-dark border-dark text-white disabled' : '') ?>">Like Terbanyak
                        </a>
                        <a href='<?php echo base_url("kompetisi/{$competition->id_competition}/{$title_slug}/terbaru" . ($category ? "?id_competition_category={$category}" : '')) ?>'
                            class="mb-2 btn btn-sm btn-default <?php echo($this->uri->segment(4) == 'terbaru' ? 'bg-dark border-dark text-white disabled' : '') ?>">Terbaru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container -->
</section>
<!-- end Top -->

