<!-- Top -->
<section class="content-section title-gallery">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <center>
                    <h1 class="post-title text-uppercase mb-1">
                      HYPEVENT
                    </h1>
                </center>

                <div class="row d-flex justify-content-center">
                    <div class="col-md-10 text-center mt-3 mb-3">
                        <form action="<?= base_url('hypevent') ?>" class="form-inline justify-content-center">
                            <label class="my-1 mr-2" for="inlineFormInputGroup">Filter</label>
                            <select id="competition-type" class="custom-select" name="type" onchange="changeCompetitionType(event)">
                                <option value="" <?= (is_null($type) ? 'selected' : '') ?>>Semua Kompetisi</option>
                                <option value="article" <?= ($type == 'article' ? 'selected' : '') ?>>Kompetisi Artikel</option>
                                <option value="photo" <?= ($type == 'photo' ? 'selected' : '') ?>>Kompetisi Foto</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container -->
</section>
<!-- end Top -->

<!-- Hypephoto -->
<section class="content-section pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                <?php foreach ($competitions as $competition) { ?>
                    <?php $urlTitle = strtolower(url_title($competition->title)) ?>
                    <?php $url = base_url("kompetisi/{$competition->id_competition}/{$urlTitle}/" . ($competition->contents_count < 1 ? 'syarat-dan-ketentuan' : '')) ?>

                    <div class="blog-post utah border mb-5 rounded">
                        <?php if(!empty(trim($competition->pic))){ ?>
                            <a class="w-100" href="<?= $url ?>">
                                <figure class="post-image">
                                    <img src="<?= base_url(); ?>assets/competition/<?= $competition->pic; ?>" class="card-img-top" alt="<?= $competition->title; ?>" style="<?= $competition->cover_status ? '' : 'filter: grayscale(100%);'; ?>">
                                </figure>
                            </a>
                        <?php } ?>
                        <div class="post-content text-center p-3">
                            <h3 class="post-title text-uppercase" style="font-weight: 400;">
                                <a href="<?= $url ?>">
                                    <?= $competition->title; ?>
                                </a>
                            </h3>

                            <?php if (count($competition->categories) > 0) { ?>
                                <ul class="post-categories justify-content-center mb-3">
                                    <?php foreach ($competition->categories as $category) { ?>
                                        <?php $url = base_url("kompetisi/{$competition->id_competition}/{$urlTitle}?id_competition_category={$category->id_competition_category}") ?>

                                        <li>
                                            <a href="<?= $url ?>" style="font-size: 12px;">
                                                <?= $category->category_name ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                            <p class="mb-0 text-center text-muted"><?= strftime('%d %B %Y', date_create($competition->start_date)->getTimestamp()) ?> - <?= strftime('%d %B %Y', date_create($competition->finish_date)->getTimestamp()) ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="mt-3">
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</section>

<style>
    .utah .post-content .post-title a {
        /* display: inline; */
        background-image: -moz-linear-gradient(rgba(0,0,0,0) calc(99% - 1px),#0c0c0c 1px);
        background-image: -webkit-linear-gradient(rgba(0,0,0,0) calc(99% - 1px),#0c0c0c 1px);
        background-image: linear-gradient(rgba(0,0,0,0) calc(99% - 1px),#0c0c0c 1px);
        background-repeat: no-repeat;
        background-size: 0 100%;
        transition: background-size .5s;
    }

    .utah .post-content .post-title a:hover {
        text-decoration: none;
        background-size: 100% 100%;
    }
</style>

<script>
    function changeCompetitionType(ev) {
        $this = $(ev.currentTarget);

        $this.parent('form').submit()
    };
</script>
