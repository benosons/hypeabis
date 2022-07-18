<?php
header('Content-Type: text/html; charset=UTF-8');
header('Content-Language: id');
//header('X-Clacks-Overhead: "GNU Terry Pratchett"');
//header('X-XSS-Protection: 1;mode=block');
//header('X-Content-Type-Options: nosniff');
//header('X-Frame-Options: SAMEORIGIN');
//header("Access-Control-Allow-Origin: *");
//header('Referrer-Policy: no-referrer');
//header("Strict-Transport-Security:max-age=31536000;includeSubDomains");
//header("Content-Security-Policy:default-src 'self' https: data:; img-src 'self' blob: data: https:; style-src 'self' 'unsafe-inline' https: https://fonts.gstatic.com https://fonts.googleapis.com; font-src 'self' data: https:;script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;");
//header("Feature-Policy:sync-script self; vertical-scroll *;");
?>

<!DOCTYPE html>
<html lang="id">

<?php
$default_meta = [
    'title' => 'Hypeabis',
    'desc' => 'Portal berita berbasis komunitas, bagian dari Bisnis Indonesia Group, yang menyajikan fashion, beauty, lifestyle, health, culinary, art, film, dan beragam cerita inspiratif untuk kaum milenial.',
    'keyword' => 'Hypeabis, news, lifestyle, travel, post, blog, ads, read, author, quote, newspaper, digital, video, comment'
];
?>

<head>
    <meta charset="utf-8">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <title>Hypeabis - <?= $meta['title']; ?></title>
    <?php } else { ?>
        <title><?= (isset($global_data[0]->meta_title) && strlen(trim($global_data[0]->meta_title)) > 0 ? $global_data[0]->meta_title : $default_meta['title']); ?></title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#e90101"/>
    <meta name="author" content="Hypeabis">
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta name="description" content="<?= $meta['description']; ?>">
    <?php } else { ?>
        <meta name="description"
            content="<?= (isset($global_data[0]->meta_desc) && strlen(trim($global_data[0]->meta_desc)) > 0 ? $global_data[0]->meta_desc : $default_meta['desc']); ?>">
    <?php } ?>
    <?php if (isset($meta['keyword']) && strlen(trim($meta['keyword'])) > 0) { ?>
        <meta name="keywords" content="<?= $meta['keyword']; ?>">
    <?php } else { ?>
        <meta name="keywords"
            content="<?= (isset($global_data[0]->meta_keyword) && strlen(trim($global_data[0]->meta_keyword)) > 0 ? $global_data[0]->meta_keyword : $default_meta['desc']); ?>">
    <?php } ?>
    <?php if (isset($meta['canonical']) && strlen(trim($meta['canonical'])) > 0) { ?>
        <link rel="canonical" href="<?= $meta['canonical']; ?>"/>
    <?php } else { ?>
        <link rel="canonical" href="<?= base_url(uri_string()); ?>"/>
    <?php } ?>
    <meta name="format-detection" content="telephone=no">

    <!-- SOCIAL MEDIA META -->
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta property="og:description" content="<?= $meta['description']; ?>"/>
    <?php } else { ?>
        <meta property="og:description"
            content="<?= (isset($global_data[0]->meta_desc) && strlen(trim($global_data[0]->meta_desc)) > 0 ? $global_data[0]->meta_desc : $default_meta['desc']); ?>">
    <?php } ?>
    <?php if (isset($meta['picture']) && strlen(trim($meta['picture'])) > 0) { ?>
        <meta property="og:image" content="<?= $meta['picture']; ?>"/>
    <?php } else { ?>
        <meta property="og:image" content="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>"/>
    <?php } ?>
    <meta property="og:site_name" content="Hypeabis">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <meta property="og:title"
            content="<?= (isset($global_data[0]->meta_title) && strlen(trim($global_data[0]->meta_title)) > 0 ? $global_data[0]->meta_title : $default_meta['title']); ?> - <?= $meta['title']; ?>"/>
    <?php } else { ?>
        <meta property="og:title"
            content="<?= (isset($global_data[0]->meta_title) && strlen(trim($global_data[0]->meta_title)) > 0 ? $global_data[0]->meta_title : $default_meta['title']); ?>">
    <?php } ?>
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= base_url(); ?><?= $this->uri->uri_string(); ?>">

    <!-- TWITTER META -->
    <meta name="twitter:card" content="summary_large_images">
    <meta name="twitter:site" content="@Hypeabis">
    <meta name="twitter:creator" content="@Hypeabis">
    <?php if (isset($meta['title']) && strlen(trim($meta['title'])) > 0) { ?>
        <meta name="twitter:title"
            content="<?= (isset($global_data[0]->meta_title) && strlen(trim($global_data[0]->meta_title)) > 0 ? $global_data[0]->meta_title : $default_meta['title']); ?> - <?= $meta['title']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:title"
            content="<?= (isset($global_data[0]->meta_title) && strlen(trim($global_data[0]->meta_title)) > 0 ? $global_data[0]->meta_title : $default_meta['title']); ?>">
    <?php } ?>
    <?php if (isset($meta['description']) && strlen(trim($meta['description'])) > 0) { ?>
        <meta name="twitter:description" content="<?= $meta['description']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:description"
            content="<?= (isset($global_data[0]->meta_desc) && strlen(trim($global_data[0]->meta_desc)) > 0 ? $global_data[0]->meta_desc : $default_meta['desc']); ?>">
    <?php } ?>
    <?php if (isset($meta['picture']) && strlen(trim($meta['picture'])) > 0) { ?>
        <meta name="twitter:image" content="<?= $meta['picture']; ?>"/>
    <?php } else { ?>
        <meta name="twitter:image" content="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>"/>
    <?php } ?>

    <meta name="googlebot-news" content="index, follow"/>
    <meta name="googlebot" content="index, follow"/>
    <meta name="robots" content="index, follow"/>
    <meta name="language" content="id"/>
    <meta name="geo.country" content="phpp"/>

    <!-- FAVICON FILES -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?= base_url(); ?>files/frontend/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>files/frontend/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>files/frontend/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url(); ?>files/frontend/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= base_url(); ?>files/frontend/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <?php $uri = $this->uri->segment(1); ?>
    <!--<link rel="dns-prefetch" href="https://fonts.gstatic.com">-->
    <!--<link rel="dns-prefetch" href="https://fonts.googleapis.com">-->
    <!--<link rel="dns-prefetch" href="https://pagead2.googlesyndication.com">-->
    <!--<link rel="dns-prefetch" href="https://www.googletagmanager.com">-->
    <?php if (isset($uri) && strlen(trim($uri)) > 0){ ?>
        <!--<link rel="dns-prefetch" href="https://platform-api.sharethis.com">-->
    <?php } ?>

    <!-- FONT FILES-->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;800&display=swap">
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;800&display=swap">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;800&display=swap">
    </noscript>

    <!-- CSS FILES -->
    <link rel="stylesheet" media="screen" href="<?= base_url(); ?>files/frontend/css/app.css">

    <!-- Google Ads -->
    <!--<script async="async" data-ad-client="ca-pub-1420952494424052"-->
    <!--    src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
    <script src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <?php if (isset($uri) && strlen(trim($uri)) > 0){ ?>
        <script type='text/javascript'
            src='https://platform-api.sharethis.com/js/sharethis.js#property=60af1cc806a044001202bbf7&product=sop'
            async='async'></script>
    <?php } ?>

    <?php if (isset($breadcrumb) && strlen(trim($breadcrumb)) > 0) { ?>
        <?= $breadcrumb; ?>
    <?php } ?>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PBRX7Q4');</script>
    <!-- End Google Tag Manager -->
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBRX7Q4"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!--start sticky-navbar-->
<nav class="sticky-navbar">
    <div class="logo">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="Hypeabis" width="179" height="50"
                class="img-fluid logo-dark">
            <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="Hypeabis" width="179" height="50"
                class="img-fluid logo-light">
        </a>
    </div>
    <!-- end logo -->
    <div class="site-menu">
        <ul>
            <?php $category_counts = count($categories) ?>
            <?php foreach ($categories as $x => $category) : ?>
                <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                    <li>
                        <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
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

            <li>
                <a href="<?= base_url(); ?>hypevent">
                    HYPEVENT
                    <span class="dot <?= (isset($is_competition_exist) && $is_competition_exist > 0 ? '' : 'd-none'); ?>"></span>
                </a>
            </li>
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

<!--start side-widget-->
<aside class="side-widget">
    <div class="close-button"><i class="fal fa-times"></i></div>
    <!-- end close-button -->
    <a href="<?= base_url(); ?>">
        <figure class="logo"><img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="Hypeabis" width="214" height="60"
                class="img-fluid" style="height:60px;"></figure>
    </a>
    <div class="inner">
        <div class="widget">
            <?php
            $name = $this->session->userdata('user_name');
            $picture = $this->session->userdata('user_picture');
            $picture_from = $this->session->userdata('user_picture_from');
            ?>
            <!--
            <div class="account">
              <?php if ($this->session->userdata('user_logged_in')) { ?>
                <div class="icon-wrapper">
                  <i class="fal fa-user-circle"></i>
                </div>
                <a href="<?= base_url('user_dashboard') ?>"><?php echo strtoupper($name) ?></a>
              <?php } else { ?>
                <div class="icon-wrapper">
                  <i class="fal fa-user-circle"></i>
                </div>
                <a href="<?= base_url('page/login') ?>">LOGIN</a>
              <?php } ?>
            </div>
            -->
            <!-- end account -->
            <div class="site-menu">
                <ul>
                    <?php foreach ($categories as $x => $category) : ?>
                        <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                            <li>
                                <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>" class="has-child">
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

                    <li>
                        <a href="<?= base_url(); ?>hypevent">
                            HYPEVENT
                            <span class="dot <?= (isset($is_competition_exist) && $is_competition_exist > 0 ? '' : 'd-none'); ?>"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- end site-menu -->
        </div>
        <!-- end widget -->
    </div>
    <!-- end inner -->
</aside>
<!-- end side-widget -->

<!--start search-box-->
<div class="search-box">
    <div class="close-button"><i class="fal fa-times"></i></div>
    <!-- end close-button -->
    <div class="container">
        <form action="<?php echo base_url() ?>search">
            <input class="text-center" type="search" name="title" placeholder="Judul" class="mb-3"
                value="<?php echo $search_param['title'] ?? '' ?>">
            <select id="category-global-filter" class="w-100" name="category">
                <option value=""></option>
                <?php if(isset($categories_filter) && is_array($categories_filter)){ ?>
                    <?php $categories_count = count($categories_filter); ?>
                    <?php foreach ($categories_filter as $key => $item) { ?>
                        <option
                            value="<?php echo $item['id_category']; ?>" <?php echo(isset($search_param['category']) && $search_param['category'] == $item['id_category'] ? "selected" : ""); ?>><?php echo $item['category_name']; ?></option>

                        <?php if ($key == $categories_count - 2) { ?>
                            <option
                                value="hypeshop" <?php echo(isset($search_param['hypeshop']) && $search_param['hypeshop'] ? "selected" : ""); ?>>Hypeshop
                            </option>
                            <option
                                value="hypevirtual" <?php echo(isset($search_param['hypevirtual']) && $search_param['hypevirtual'] ? "selected" : ""); ?>>Hypevirtual
                            </option>
                            <option
                                value="hypephoto" <?php echo(isset($search_param['hypephoto']) && $search_param['hypephoto'] ? "selected" : ""); ?>>Hypephoto
                            </option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
            <div class="d-flex mx-auto">
                <input id="start-date" type="search" class="datepicker-range-start text-center" name="start_date"
                    onkeydown="return false" value="<?php echo $search_param['start_date'] ?? ''; ?>"
                    placeholder="Tanggal Mulai" autocomplete="off"/>
                <div class="display-4 mb-0 px-2" style="line-height: 1; font-weight: 100;"> -</div>
                <input id="finish-date" type="search" class="datepicker-range-finish text-center" name="finish_date"
                    onkeydown="return false" value="<?php echo $search_param['finish_date'] ?? ''; ?>"
                    placeholder="Tanggal Akhir" autocomplete="off"/>
            </div>

            <button class="btn btn-default mt-5">Cari Artikel</button>
        </form>
    </div>
    <!-- end container -->
</div>
<!-- end search-box -->

<!--start header-->
<header class="header">
    <div class="container">
        <div class="left-side">
            <div class="social-media">
                <ul>
                    <?php if (isset($global_data[0]->facebook) && strlen(trim($global_data[0]->facebook)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->facebook; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->twitter) && strlen(trim($global_data[0]->twitter)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->twitter; ?>" rel="noreferrer" target="_blank"><i class="fab fa-twitter"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->youtube) && strlen(trim($global_data[0]->youtube)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->youtube; ?>" rel="noreferrer" target="_blank"><i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->instagram) && strlen(trim($global_data[0]->instagram)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->instagram; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->linkedin) && strlen(trim($global_data[0]->linkedin)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->linkedin; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-linkedin-in"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- end social-connected -->
        </div>
        <!-- end left-side -->
        <div class="logo">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="Hypeabis"  width="357" height="100" class="logo-light">
                <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="Hypeabis"  width="357" height="100" class="logo-dark">
            </a>
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
                    <a href="<?= base_url('user_dashboard') ?>">
                        <div class="icon-wrapper">
                            <i class="fal fa-user-circle"></i>
                            <i class="fa fa-check red-dot"></i>
                        </div>
                        <span class="account-text"
                            style="width:80px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;vertical-align:middle;">
                <?php echo strtoupper($name) ?>
              </span>
                    </a>
                <?php } else { ?>
                    <a href="<?= base_url('page/login') ?>">
                        <div class="icon-wrapper">
                            <i class="fal fa-user-circle"></i>
                            <i class="fa fa-check red-dot"></i>
                        </div>
                        <span class="account-text">
                LOGIN
              </span>
                    </a>
                <?php } ?>
            </div>
            <!-- end account -->
        </div>
        <!-- end right-side -->
    </div>
    <!-- end container -->
</header>
<!-- end header -->

<!--start navbar-->
<nav class="navbar">
    <div class="container">
        <div class="hamburger-menu"><i class="fal fa-bars"></i></div>
        <!-- end hamburger-menu -->
        <div class="logo">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo_white; ?>" alt="Image" width="179" height="50"
                    class="logo-light">
                <img src="<?= base_url() ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="Image" width="179" height="50" class="logo-dark">
            </a>
        </div>
        <!-- end logo -->
        <div class="site-menu">
            <ul>
                <?php foreach ($categories as $x => $category) : ?>
                    <?php if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                        <li>
                            <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
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

                <li>
                    <a href="<?= base_url(); ?>hypevent">
                        HYPEVENT
                        <span class="dot <?= (isset($is_competition_exist) && $is_competition_exist > 0 ? '' : 'd-none'); ?>"></span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- end site-menu -->
        <div class="search-button"><i class="fal fa-search"></i></div>
        <!-- end search-button -->
    </div>
    <!-- end container -->
</nav>
<!-- end navbar -->

<!-- BEGIN MAIN CONTENT -->
<?= $content ?>
<!-- END MAIN CONTENT -->

<!-- Footer start -->
<footer class="footer" style="margin-top: 5rem;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="newsletter-box">
                    <!-- <i class="fal fa-envelope-open"></i> -->
                    <p class="text-center">
                        <img src="<?= base_url() ?>assets/content/subscribe-icon.png" width="70" height="58" alt="subscribe"/>
                    </p>
                    <!-- <h3>BERLANGGANAN</h3> -->
                    <p>Daftarkan email Anda untuk mendapatkan informasi terbaru dari kami</p>
                    <form method="post">
                        <div id="subscribe_loader"
                            style="background:rgba(255, 255, 255, 0.75);width:540px;padding-top:10px;padding-bottom:10px;position:absolute;z-index:20;display:none;">
                            <center>
                                <img data-src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"
                                    width="40" height="40"/><br/>
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
                <img src="<?= base_url() ?>assets/logo/logo-light.png" alt="Image" width="156" height="60"
                    style="margin-top:20px;"/>
                <div class="footer-list mt-10">
                    <div class="footer-list-icon">
                        <i class="fa fa-building color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p>
                            <?php if (isset($global_data[0]->address) && strlen(trim($global_data[0]->address)) > 0) { ?>
                                <?= nl2br($global_data[0]->address); ?>
                            <?php } ?>
                        </p>
                    </div>
                </div>

                <div class="footer-list">
                    <div class="footer-list-icon">
                        <i class="fa fa-phone-square color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p class="footer-telp" style="text-decoration:inherit;" x-ms-format-detection="none">
                            <?php if (isset($global_data[0]->phone1) && strlen(trim($global_data[0]->phone1)) > 0) { ?>
                                <?= $global_data[0]->phone1; ?>
                            <?php } ?>
                            <?php if (isset($global_data[0]->phone2) && strlen(trim($global_data[0]->phone2)) > 0) { ?>
                                <br/><?= $global_data[0]->phone2; ?>
                            <?php } ?>
                        </p>
                    </div>
                </div>

                <div class="footer-list">
                    <div class="footer-list-icon">
                        <i class="fa fa-envelope color-gold-flat"></i>
                    </div>
                    <div class="footer-list-info">
                        <p>
                            <?php if (isset($global_data[0]->email) && strlen(trim($global_data[0]->email)) > 0) { ?>
                                <?= $global_data[0]->email; ?>
                            <?php } ?>
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
                    <?php if (isset($global_data[0]->facebook) && strlen(trim($global_data[0]->facebook)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->facebook; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->twitter) && strlen(trim($global_data[0]->twitter)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->twitter; ?>" rel="noreferrer" target="_blank"><i class="fab fa-twitter"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->youtube) && strlen(trim($global_data[0]->youtube)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->youtube; ?>" rel="noreferrer" target="_blank"><i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->instagram) && strlen(trim($global_data[0]->instagram)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->instagram; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        </li>
                    <?php } ?>
                    <?php if (isset($global_data[0]->linkedin) && strlen(trim($global_data[0]->linkedin)) > 0) { ?>
                        <li>
                            <a href="<?= $global_data[0]->linkedin; ?>" rel="noreferrer" target="_blank"><i
                                    class="fab fa-linkedin-in"></i></a>
                        </li>
                    <?php } ?>
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
<input type="hidden" id="base_url" value="<?= base_url(); ?>"/>

<script src="<?= base_url(); ?>files/frontend/js/app.js"></script>
<?php foreach ((is_array($js_files) ? $js_files : array()) as $js) { ?>
    <?= $js; ?>
<?php } ?>

</body>
</html>
