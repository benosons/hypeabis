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
        <meta name="description" content="Hypeabis For all kind of news and magazine website">
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
        <meta property="og:description" content="Hypeabis">
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
        <meta name="twitter:description" content="Hypeabis">
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

    <!-- FONT FILES-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;700;800&display=swap" rel="stylesheet">

    <!-- CSS FILES -->
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/css/swiper.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/css/style.css">
    <!-- CSS PLUGIN FILES -->
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/plugins/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/plugins/owlcarousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/plugins/lightbox/css/lightbox.css"/>
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/plugins/masonry/masonry.css">
    <link rel="stylesheet" href="<?= base_url(); ?>files/frontend/plugins/lightbox/css/modal-gallery.css">

    <!-- Google Ads -->
    <script data-ad-client="ca-pub-6553864806182734" async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>


<body>
<nav class="sticky-navbar">
    <div class="logo">
        <a href="<?= base_url() ?>"><img src="<?= base_url() ?>assets/logo/logo-dark.png" alt="Image" class="img-fluid">
        </a>
    </div>
    <!-- end logo -->
    <div class="site-menu">
        <ul>
            <?php $category_counts = count($categories) ?>
            <?php foreach ($categories as $x => $category) : ?>
                <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                    <li>
                        <a href="#">
                            <?= strtoupper($category['category_name']); ?>
                        </a>
                        <i></i>
                        <ul>
                            <?php foreach ($category['child'] as $y => $child) { ?>
                                <li>
                                    <a href="<?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?>">
                                        <?= strtoupper($child['category_name']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } else { ?>
                    <?php if ($x == $category_counts - 1) { ?>
                        <li>
                            <a href="<?= base_url(); ?>hypeshop">HYPESHOP</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>hypevirtual">HYPEVIRTUAL</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>hypephoto">HYPEPHOTO</a>
                        </li>
                    <?php } ?>

                    <li>
                        <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
                            <?= strtoupper($category['category_name']); ?>
                        </a>
                    </li>
                <?php } ?>
            <?php endforeach ?>
        </ul>
    </div>
    <!-- end site-menu -->
    <div class="search-button"><i class="fal fa-search"></i></div>
    <!--end search-button -->
</nav>
<!--end sticky-navbar -->

<div class="scrollup">
    <svg class="progress-circle" width="100%" height="100%" viewBox="-2 -2 104 104">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
</div>

<aside class="side-widget">
    <div class="close-button"><i class="fal fa-times"></i></div>
    <!-- end close-button -->
    <figure class="logo"><img src="<?= base_url() ?>assets/logo/logo-light.png" alt="Image" class="img-fluid"></figure>
    <div class="inner">
        <div class="widget">
            <div class="account">
                <?php if ($this->session->userdata('user_logged_in')) { ?>
                    <?php
                    $name = $this->session->userdata('user_name');
                    $picture = $this->session->userdata('user_picture');
                    $picture_from = $this->session->userdata('user_picture_from');
                    ?>
                    <div class="icon-wrapper">
                        <i class="fal fa-user-circle"></i>
                        <!-- <i class="fa fa-check red-dot"></i> -->
                    </div>
                    <a href="<?= base_url('user_dashboard') ?>">AKUN</a>
                <?php } else { ?>
                    <div class="icon-wrapper">
                        <i class="fal fa-user-circle"></i>
                        <!-- <i class="fa fa-check red-dot"></i> -->
                    </div>
                    <a href="<?= base_url('page/login') ?>">LOGIN</a>
                <?php } ?>
            </div>
            <!-- end account -->
            <div class="site-menu">
                <ul>
                    <?php foreach ($categories as $x => $category) : ?>
                        <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                            <li>
                                <a href="#">
                                    <?= strtoupper($category['category_name']); ?>
                                </a>
                                <i></i>
                                <ul>
                                    <?php foreach ($category['child'] as $y => $child) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?>">
                                                <?= strtoupper($child['category_name']); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <?php if ($x == $category_counts - 1) { ?>
                                <li>
                                    <a href="<?= base_url(); ?>hypeshop">HYPESHOP</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>hypevirtual">HYPEVIRTUAL</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>hypephoto">HYPEPHOTO</a>
                                </li>
                            <?php } ?>

                            <li>
                                <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
                                    <?= strtoupper($category['category_name']); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php endforeach ?>
                </ul>
            </div>
            <!-- end site-menu -->
        </div>
        <!-- end widget -->
    </div>
    <!-- end inner -->
</aside>
<!-- end side-widget -->

<div class="search-box">
    <div class="close-button"><i class="fal fa-times"></i></div>
    <!-- end close-button -->
    <div class="container">
        <form>
            <input type="search" placeholder="Type here to find">
        </form>
        <h6>POPULAR SEARCHS</h6>
        <ul>
            <li>
                <a href="#">cooking</a>
            </li>
            <li>
                <a href="#">make up</a>
            </li>
            <li>
                <a href="#">vacation</a>
            </li>
        </ul>
    </div>
    <!-- end container -->
</div>
<!-- end search-box -->

<!-- Adv -->
<?php if ($this->uri->segment(1) == "read") { ?>
    <div class="section-ad mt-20 mb-20">
        <div class="container">
            <center>
                <div class="ads-wrapper gres-ads-wrapper">
                    <a target="_blank" href="https://youtube.com">
                        <img src="<?= base_url() ?>assets/adv/20200607232750000000aboutbg2.jpg" alt="">
                    </a>
                </div>
            </center>
        </div>
    </div>
<?php } ?>
<!-- end Adv -->

<header class="header">
    <div class="container">
        <div class="left-side">
            <div class="social-media">
                <ul>
                    <? if (isset($global_data[0]->facebook) && strlen(trim($global_data[0]->facebook)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->facebook; ?>" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->twitter) && strlen(trim($global_data[0]->twitter)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->twitter; ?>" target="_blank"><i class="fab fa-twitter"></i>
                            </a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->youtube) && strlen(trim($global_data[0]->youtube)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->youtube; ?>" target="_blank"><i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->instagram) && strlen(trim($global_data[0]->instagram)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->instagram; ?>" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->linkedin) && strlen(trim($global_data[0]->linkedin)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->linkedin; ?>" target="_blank"><i
                                    class="fab fa-linkedin-in"></i></a>
                        </li>
                    <? } ?>
                </ul>
            </div>
            <!-- end social-connected -->
        </div>
        <!-- end left-side -->
        <div class="logo">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url() ?>assets/logo/logo-light.png" alt="Image" class="logo-light">
                <img src="<?= base_url() ?>assets/logo/logo-dark.png" alt="Image" class="logo-dark"></a>
        </div>
        <!-- end logo -->
        <div class="right-side">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="darkSwitch">
                <label class="custom-control-label" for="darkSwitch">Mode Gelap</label>
            </div>
            <!-- end custom-control -->
            <div class="account">
                <?php if ($this->session->userdata('user_logged_in')) { ?>
                    <?php
                    $name = $this->session->userdata('user_name');
                    $picture = $this->session->userdata('user_picture');
                    $picture_from = $this->session->userdata('user_picture_from');
                    ?>
                    <div class="icon-wrapper">
                        <i class="fal fa-user-circle"></i>
                        <i class="fa fa-check red-dot"></i>
                    </div>
                    <a href="<?= base_url('user_dashboard') ?>">AKUN</a>
                <?php } else { ?>
                    <div class="icon-wrapper">
                        <i class="fal fa-user-circle"></i>
                        <i class="fa fa-check red-dot"></i>
                    </div>
                    <a href="<?= base_url('page/login') ?>">LOGIN</a>
                <?php } ?>
            </div>
            <!-- end account -->
        </div>
        <!-- end right-side -->
    </div>
    <!-- end container -->
</header>
<!-- end header -->

<nav class="navbar">
    <div class="container">
        <div class="hamburger-menu"><i class="fal fa-bars"></i></div>
        <!-- end hamburger-menu -->
        <div class="logo">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url() ?>assets/logo/logo-light.png" alt="Image" class="logo-light">
                <img src="<?= base_url() ?>assets/logo/logo-dark.png" alt="Image" class="logo-dark"></a>
        </div>
        <!-- end logo -->
        <div class="site-menu">
            <ul>
                <?php foreach ($categories as $x => $category) : ?>
                    <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                        <li>
                            <a href="#">
                                <?= strtoupper($category['category_name']); ?>
                            </a>
                            <i></i>
                            <ul>
                                <?php foreach ($category['child'] as $y => $child) { ?>
                                    <li>
                                        <a href="<?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?>">
                                            <?= strtoupper($child['category_name']); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <?php if ($x == $category_counts - 1) { ?>
                            <li>
                                <a href="<?= base_url(); ?>hypeshop">HYPESHOP</a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>hypevirtual">HYPEVIRTUAL</a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>hypephoto">HYPEPHOTO</a>
                            </li>
                        <?php } ?>

                        <li>
                            <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
                                <?= strtoupper($category['category_name']); ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php endforeach ?>
            </ul>
        </div>
        <!-- end site-menu -->
        <div class="search-button"><i class="fal fa-search"></i></div>
        <!-- end search-button -->
    </div>
    <!-- end container -->
</nav>
<!-- end navbar -->
<!--/ Header end -->

<!-- BEGIN MAIN CONTENT -->
<section class="block footer-fv-bg">
    <div class="container">
        <div class="row" style="min-height: 320px;">
            <div class="col-lg-12 col-md-12 align-self-center mx-auto">
                <h1 class="block-title heading-black m-t-30">
                    <span class="title-angle-shap"> Oops!</span>
                </h1>
                <div class="row">
                    <div class="col-md-12 m-b-30">
                        <p class="subscribe-text">
                            Something went wrong.
                        </p>
                        <div class="newsletter-area">
                            <div class="email-form-group">
                                <a href="<?= base_url(); ?>" class="btn btn-default">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END MAIN CONTENT -->

<!-- Section Subscribe -->

<!-- Footer start -->
<!-- Berlangganan -->
<footer class="footer" style="margin-top: 5rem;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="newsletter-box">
                    <!-- <i class="fal fa-envelope-open"></i> -->
                    <p class="text-center">
                        <img src="<?= base_url() ?>assets/content/subscribe-icon.png" width="70px"/>
                    </p>
                    <!-- <h3>BERLANGGANAN</h3> -->
                    <p>Daftarkan email Anda untuk mendapatkan informasi terbaru dari kami</p>
                    <form method="post">
                        <div id="subscribe_loader"
                            style="background:rgba(255, 255, 255, 0.75);width:540px;padding-top:10px;padding-bottom:10px;position:absolute;z-index:20;display:none;">
                            <center>
                                <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"/><br/>
                            </center>
                        </div>
                        <input id="subscribe_email" type="email" placeholder="Masukkan alamat email anda...">
                        <input type="submit" value="KIRIM" onclick="submitSubscriber(event)">
                    </form>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-md-4">
                <img src="<?= base_url() ?>assets/logo/logo-light.png" alt="Image"
                    style="max-width:180px;margin-top:20px;"/>
                <div class="footer-list mt-10">
                    <div class="footer-list-icon">
                        <i class="fa fa-building color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p>
                            <? if (isset($global_data[0]->address) && strlen(trim($global_data[0]->address)) > 0) { ?>
                                <?= nl2br($global_data[0]->address); ?>
                            <? } ?>
                        </p>
                    </div>
                </div>

                <div class="footer-list">
                    <div class="footer-list-icon">
                        <i class="fa fa-phone-square color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p>
                            <? if (isset($global_data[0]->phone1) && strlen(trim($global_data[0]->phone1)) > 0) { ?>
                                <?= $global_data[0]->phone1; ?>
                            <? } ?>
                            <? if (isset($global_data[0]->phone2) && strlen(trim($global_data[0]->phone2)) > 0) { ?>
                                <br/><?= $global_data[0]->phone2; ?>
                            <? } ?>
                        </p>
                    </div>
                </div>

                <div class="footer-list">
                    <div class="footer-list-icon">
                        <i class="fa fa-envelope color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p>
                            <? if (isset($global_data[0]->email) && strlen(trim($global_data[0]->email)) > 0) { ?>
                                <?= $global_data[0]->email; ?>
                            <? } ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h4 class="footer-subtitle mt-40">Tentang Hypeabis.id</h4>
                <ul class="footer-links">
                    <li>
                        <a href="<?php echo base_url() . 'page/tentang-kami' ?>">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'page/info-iklan' ?>">Info Iklan</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'contact/us' ?>">Hubungi Kami</a>
                    </li>
                </ul>
                <ul class="footer-social mt-40">
                    <? if (isset($global_data[0]->facebook) && strlen(trim($global_data[0]->facebook)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->facebook; ?>" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->twitter) && strlen(trim($global_data[0]->twitter)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->twitter; ?>" target="_blank"><i class="fab fa-twitter"></i>
                            </a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->youtube) && strlen(trim($global_data[0]->youtube)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->youtube; ?>" target="_blank"><i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->instagram) && strlen(trim($global_data[0]->instagram)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->instagram; ?>" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        </li>
                    <? } ?>
                    <? if (isset($global_data[0]->linkedin) && strlen(trim($global_data[0]->linkedin)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->linkedin; ?>" target="_blank"><i
                                    class="fab fa-linkedin-in"></i></a>
                        </li>
                    <? } ?>
                </ul>
            </div>

            <div class="col-md-4">
                <h4 class="footer-subtitle mt-40">Syarat &amp; Ketentuan</h4>
                <ul class="footer-links">
                    <li>
                        <a href="<?php echo base_url() . 'page/kebijakan-privasi' ?>">Kebijakan Privasi</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'page/terms-condition' ?>">Syarat &amp; Ketentuan</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'page/panduan-media-siber' ?>">Panduan Media Siber</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'page/panduan-foto' ?>">Panduan Foto</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url() . 'page/panduan-komunitas' ?>">Panduan Komunitas</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-12"><span
                    class="copyright">&copy; 2021 Hypeabis - Hak Cipta Dilindungi Undang - Undang.</span></div>
        </div>
    </div>
</footer>
<!-- Footer End-->

<input type="hidden" id="csrf-token-name" value="<?= $this->security->get_csrf_token_name(); ?>"/>
<input type="hidden" id="csrf-token-hash" value="<?= $this->security->get_csrf_hash(); ?>"/>

<!-- JS FILES -->
<!-- <script src="<?= base_url(); ?>files/frontend/js/jquery.min.js"></script> -->
<script src="<?= base_url(); ?>files/frontend/js/swiper.min.js"></script>
<script src="<?= base_url(); ?>files/frontend/js/sweetalert2.min.js"></script>
<script src="<?= base_url(); ?>files/frontend/js/dark-mode-switch.min.js"></script>
<script src="<?= base_url(); ?>files/frontend/js/scripts.js"></script>
<script src="<?= base_url(); ?>files/frontend/js/adv.js"></script>
<!-- JS PLUGINS FILES -->
<script src="<?= base_url(); ?>files/frontend/plugins/owlcarousel/owl.carousel.js"></script>
<script src="<?= base_url(); ?>files/frontend/plugins/lightbox/js/lightbox.js"></script>
<script src="<?= base_url(); ?>files/frontend/plugins/masonry/masonry.pkgd.min.js"></script>
<script src="<?= base_url(); ?>files/frontend/plugins/lightbox/js/modal_gallery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    function submitSubscriber(event) {
        event.preventDefault();

        var $loader = $('#subscribe_loader');
        $loader.show();

        var post_data = {};
        post_data['email'] = $('#subscribe_email').val();
        if (post_data['email'].length > 0) {
            $.ajax({
                'url': '<?= base_url(); ?>' + 'page/subscribe',
                'type': 'POST', //the way you want to send data to your URL
                'data': getCSRFToken(post_data),
                'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                    //if the request success..
                    var obj = JSON.parse(data); // parse data from json to object..

                    //if status not success, show message..
                    if (obj.status == 'success') {
                        $('#subscribe_email').val('');
                        refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
                        Swal.fire(
                            'Selamat',
                            'Email anda telah didaftarkan.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Gagal',
                            obj.message,
                            'error'
                        );
                        $('#subscribe_email').focus();
                        refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
                    }

                    $loader.hide();
                },
                'complete': function () {
                    $loader.hide();
                }
            });
        } else {
            Swal.fire(
                'Gagal',
                'Mohon isi alamat email',
                'warning'
            );
            $loader.hide();
        }
    }

    function getCSRFToken(current_data) {
        current_data[$('#csrf-token-name').val()] = $('#csrf-token-hash').val();
        return current_data;
    }

    function refreshCSRFToken(name, hash) {
        $('#csrf-token-name').val(name);
        $('#csrf-token-hash').val(hash);
        $('input[name ="' + name + '"]').val(hash);
    }
</script>
</body>

</html>