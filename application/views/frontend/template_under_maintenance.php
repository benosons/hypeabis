<? $base_url = 'http://localhost:8080/bisnismuda/'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <title>Hypeabis - <?= $meta['title']; ?></title>
    <?php } else { ?>
        <title>Hypeabis</title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#e90101"/>
    <meta name="author" content="Hypeabis">
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta name="description" content="<?= $meta['description']; ?>">
    <?php } else { ?>
        <meta name="description"
            content="Portal berita berbasis komunitas, bagian dari Bisnis Indonesia Group, yang menyajikan fashion, beauty, lifestyle, health, culinary, art, film, dan beragam cerita inspiratif untuk kaum milenial.">
    <?php } ?>
    <?php if (isset($meta['keyword']) && strlen(trim($meta['keyword'])) > 0) { ?>
        <meta name="keywords" content="<?= $meta['keyword']; ?>">
    <?php } else { ?>
        <meta name="keywords"
            content="Hypeabis, news, lifestyle, travel, post, blog, ads, read, author, quote, newspaper, digital, video, comment">
    <?php } ?>

    <!-- SOCIAL MEDIA META -->
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta property="og:description" content="<?= $meta['description']; ?>"/>
    <?php } else { ?>
        <meta property="og:description"
            content="Portal berita berbasis komunitas, bagian dari Bisnis Indonesia Group, yang menyajikan fashion, beauty, lifestyle, health, culinary, art, film, dan beragam cerita inspiratif untuk kaum milenial.">
    <?php } ?>
    <?php if (isset($meta['picture']) && strlen(trim($meta['picture'])) > 0) { ?>
        <meta property="og:image" content="<?= $meta['picture']; ?>"/>
    <?php } else { ?>
        <meta property="og:image" content="<?= base_url(); ?>files/frontend/images/meta-picture-default.jpg"/>
    <?php } ?>
    <meta property="og:site_name" content="Hypeabis">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <meta property="og:title" content="Hypeabis - <?= $meta['title']; ?>"/>
    <?php } else { ?>
        <meta property="og:title" content="Hypeabis">
    <?php } ?>
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= base_url(); ?><?= $this->uri->uri_string(); ?>">

    <!-- TWITTER META -->
    <meta name="twitter:card" content="summary_large_images">
    <meta name="twitter:site" content="@Hypeabis">
    <meta name="twitter:creator" content="@Hypeabis">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <meta name="twitter:title" content="Hypeabis - <?= $meta['title']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:title" content="Hypeabis">
    <?php } ?>
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta name="twitter:description" content="<?= $meta['description']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:description"
            content="Portal berita berbasis komunitas, bagian dari Bisnis Indonesia Group, yang menyajikan fashion, beauty, lifestyle, health, culinary, art, film, dan beragam cerita inspiratif untuk kaum milenial.">
    <?php } ?>
    <?php if (isset($meta['picture']) && strlen(trim($meta['picture'])) > 0) { ?>
        <meta name="twitter:image" content="<?= $meta['picture']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:image" content="<?= base_url(); ?>files/frontend/images/meta-picture-default.jpg"/>
    <?php } ?>

    <meta name="googlebot-news" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="id" />
    <meta name="geo.country" content="id" />

    <!-- FAVICON FILES -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url(); ?>files/frontend/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>files/frontend/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>files/frontend/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url(); ?>files/frontend/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= base_url(); ?>files/frontend/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?= $base_url; ?>files/frontend/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?= $base_url; ?>files/frontend/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?= $base_url; ?>files/frontend/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= $base_url; ?>files/frontend/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= $base_url; ?>files/frontend/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#3f3f3f">
    <meta name="theme-color" content="#ffffff">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>files/frontend/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>files/frontend/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>files/frontend/css/responsive.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="<?= $base_url; ?>files/frontend/js/darkmode-js.min.js"></script>
    <script src="<?= $base_url; ?>files/frontend/js/init.min.js"></script>
</head>

<body>
<div id="page">
    <!-- Section Subscribe -->
    <section class="block footer-fv-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7 align-self-center">
                    <h2 class="block-title heading-black m-t-30">
                        <span class="title-angle-shap" style="line-height:60px;"> Hellooo Beemers!</span>
                    </h2>
                    <div class="row">
                        <div class="col-md-12 m-b-30">
                            <p class="subscribe-text">
                                Mohon maaf ya website kita tercinta ini sedang dalam proses pemeliharaan. <br/>
                                Mau tetap dapet informasi kece soal investasi dan entrepreneurship? <br/>
                                Pantengin dan follow IG kita ya ❤️
                                <br/><br/>
                                <a href="https://www.instagram.com/bisnis.muda.id/" target="_blank">
                                    <img src="<?= $base_url; ?>files/frontend/images/ig_button.png"
                                        class="img img-fluid img-responsive">
                                </a>
                                <br/><br/>
                                Salam Cuan & Sayang,<br/>
                                Beemin
                            </p>
                            <div class="newsletter-area">
                                <div class="email-form-group">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 align-self-center" style="min-height:320px;">
                    <center>
                        <img src="<?= $base_url; ?>files/frontend/images/kv.png" style="max-width:400px;"/>
                    </center>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer start -->
    <div class="ts-footer">
        <div class="container">
            <div class="row ts-gutter-30 justify-content-lg-between justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widtet">
                        <img src="<?= $base_url; ?>assets/logo/Original20200423170226000000logo1.png"
                            class="img img-responsive img-fluid m-b-30" style="max-height:70px">
                        <div class="widget-content">
                            <ul class="ts-footer-info">
                                <li>
                                    <i class="fa fa-home"></i> Jalan K.H. Mas Mansyur No. 12A, Karet Tengsin - Jakarta Pusat
                                </li>
                                <li><i class="icon icon-phone2"></i> +62 811-9141-240 (SMS/WhatsApp)</li>
                                <li><i class="fa fa-envelope"></i> untukmimin@bisnismuda.id</li>
                            </ul>
                            <ul class="ts-social">
                                <li>
                                    <a href="https://www.facebook.com/bisnismudaid" target="_blank"><i
                                            class="fa fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/Bisnismudaid" target="_blank"><i
                                            class="fa fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/bisnis.muda.id/" target="_blank"><i
                                            class="fa fa-instagram"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- col end -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widtet post-widget">
                        <h3 class="widget-title heading-extrabold"><span>Syarat &amp; Ketentuan</span></h3>
                        <div class="widget-content">
                            <ul class="ts-footer-info">
                                <li>
                                    <a href="<?= $base_url; ?>page/terms_service" class="text-white">Ketentuan Layanan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/ketentuan-konten"
                                        class="text-white">Ketentuan Konten
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/penggunaan-hak-cipta"
                                        class="text-white">Penggunaan &amp; Hak Cipta
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/sanggahan-pelaporan"
                                        class="text-white">Sanggahan &amp; Pelaporan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/ketentuan-perubahan"
                                        class="text-white">Ketentuan Perubahan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/undang-undang-ite"
                                        class="text-white">Undang - Undang ITE
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/privacy_policy" class="text-white">Privacy Policy</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- col end -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widtet post-widget">
                        <h3 class="widget-title heading-extrabold"><span>Tentang Kami</span></h3>
                        <div class="widget-content">
                            <ul class="ts-footer-info">
                                <li>
                                    <a href="<?= $base_url; ?>page/profil" class="text-white">Profil</a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/tim-bisnismudaid"
                                        class="text-white">Tim Bisnismuda.id
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>page/tips-tricks" class="text-white">Tips &amp; Tricks</a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>contact/lapor-gangguan"
                                        class="text-white">Lapor Gangguan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>contact/kolaborasi-bisnis"
                                        class="text-white">Kolaborasi Bisnis
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $base_url; ?>contact/us" class="text-white">Hubungi Kami</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- col end -->
                <!--
          <div class="col-lg-3 col-md-6">
            <div class="footer-widtet post-widget">
              <div class="widget-content">
                <img class="img-fluid" src="<?= $base_url; ?>files/frontend/images/banner-image/image6.jpg" alt="">
              </div>
            </div>
          </div><!-- col end -->
            </div><!-- row end -->
        </div><!-- container end -->
    </div>
    <!-- Footer End-->

    <!-- ts-copyright start -->
    <div class="ts-copyright">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12 text-center">
                    <div class="copyright-content text-light">
                        <p>&copy; 2020, Bisnis Indonesia Group - Hak Cipta Dilindungi Undang - Undang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ts-copyright end-->

    <!-- backto -->
    <div class="top-up-btn">
        <div class="backto" style="display: block;">
            <a href="#" class="icon icon-arrow-up" aria-hidden="true"></a>
        </div>
    </div>
    <!-- backto end-->
</div>

<!-- <input type="hidden" id="csrf-token-name" value="<?= $this->security->get_csrf_token_name(); ?>"/>
  <input type="hidden" id="csrf-token-hash" value="<?= $this->security->get_csrf_hash(); ?>"/> -->

<script src="<?= $base_url; ?>files/frontend/js/components.min.js"></script>
<script src="<?= $base_url; ?>files/frontend/js/custom.js"></script>
<script type="text/javascript">
    $(function () {
        new Darkmode({
            bottom: '15px',
            right: '15px',
            label: '🌓',
        }).showWidget();

        $("img.lazy").lazyload({
            effect: "fadeIn"
        });
    });

    $(document).ready(function () {
        new Mmenu(document.querySelector('#menu'));
    });
</script>
</body>

</html>