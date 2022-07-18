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
        href="<?= base_url(); ?>files/frontend/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?= base_url(); ?>files/frontend/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?= base_url(); ?>files/frontend/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url(); ?>files/frontend/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= base_url(); ?>files/frontend/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#3f3f3f">
    <meta name="theme-color" content="#ffffff">

    <!-- CSS -->
    <!-- Bootstrap -->
    <!--<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/iconfonts.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/owl.theme.default.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/colorbox.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/mmenu.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.css" media="screen" />
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/backend/plugins/bootstrap-datepicker/css/datepicker3.css" media="screen" />
  -->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/frontend/css/responsive.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- Javascript Files -->
    <script src="<?= base_url(); ?>files/frontend/js/jquery.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/owl.carousel.min.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/jquery.colorbox.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/custom.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/mmenu.polyfills.js"></script>
    <script src="<?= base_url(); ?>files/frontend/js/mmenu.js"></script>
    <script type="text/javascript"
        src="<?= base_url(); ?>files/backend/plugins/bootstrap-select2/select2.min.js"></script>
    <script type="text/javascript"
        src="<?= base_url(); ?>files/backend/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript"
        src="<?= base_url(); ?>files/backend/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            new Mmenu(document.querySelector('#menu'));
        });
    </script>
    <!--
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-166637266-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-166637266-1');
    </script>

    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5eb9f6077d4ec20012dc041b&product=inline-share-buttons&cms=sop' async='async'></script> -->
</head>

<body>
<div id="page">
    <!-- Header start -->
    <header id="header" class="header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-between">
                <div class="col-3 d-lg-none">
                    <a class="mobile-menu" href="#menu"><span></span></a>
                </div>

                <div class="col-lg-auto col-6">
                    <div class="logo">
                        <a href="<?= base_url(); ?>">
                            <? if (isset($global_data[0]->logo) && strlen(trim($global_data[0]->logo)) > 0) { ?>
                                <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>" alt="logo"
                                    data-src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>"
                                    data-src-retina="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>"
                                    class="img img-responsive img-fluid" style="max-height:80px">
                            <? } ?>
                        </a>
                    </div>
                </div><!-- logo col end -->

                <div class="col">
                    <div class="main-nav is-ts-sticky">
                        <div class="row justify-content-between">
                            <nav class="navbar navbar-expand-lg col  d-none d-lg-block">
                                <div class="site-nav-inner float-left">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="true" aria-label="Toggle navigation">
                                        <span class="fa fa-bars"></span>
                                    </button>
                                    <!-- End of Navbar toggler -->
                                    <div id="navbarSupportedContent"
                                        class="collapse navbar-collapse navbar-responsive-collapse">
                                        <ul class="nav navbar-nav heading-bold">

                                            <li>
                                                <a href="<?= base_url(); ?>">HOME</a>
                                            </li>
                                            <? foreach ($categories as $x => $category) { ?>
                                                <? if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>

                                                    <li class="nav-item dropdown active">
                                                        <a href="javascript:;" class="menu-dropdown"
                                                            data-toggle="dropdown">
                                                            <?= strtoupper($category['category_name']); ?> <i
                                                                class="fa fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu sub-menu" role="menu">
                                                            <? foreach ($category['child'] as $y => $child) { ?>
                                                                <li>
                                                                    <a href="<?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?>">
                                                                        <?= strtoupper($child['category_name']); ?>
                                                                    </a>
                                                                </li>
                                                            <? } ?>
                                                        </ul>
                                                    </li>

                                                <? } else { ?>

                                                    <li>
                                                        <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
                                                            <?= strtoupper($category['category_name']); ?>
                                                        </a>
                                                    </li>

                                                <? } ?>
                                            <? } ?>

                                        </ul>
                                        <!--/ Nav ul end -->
                                    </div>
                                    <!--/ Collapse end -->
                                </div><!-- Site Navbar inner end -->
                            </nav>
                            <!--/ Navigation end -->
                            <div class="col text-right nav-social-wrap">
                                <div class="nav-search">
                                    <a href="#search-popup" class="xs-modal-popup">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </div><!-- Search end -->
                                <div class="zoom-anim-dialog mfp-hide modal-searchPanel ts-search-form"
                                    id="search-popup">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="xs-search-panel">
                                                <?= form_open('page/submitSearch', array('class' => 'ts-search-group')); ?>
                                                <div class="input-group">
                                                    <input type="search" class="form-control" name="keyword"
                                                        placeholder="Search" value="" minlength="3">
                                                    <button class="input-group-btn search-button">
                                                        <i class="icon icon-search1"></i>
                                                    </button>
                                                </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End xs modal -->
                            </div>
                        </div>
                        <!--/ Row end -->
                    </div>
                    <!--/ Row end -->
                </div><!-- header right end -->

                <div class="col-lg-auto header-right-wrapper heading-bold d-none d-lg-block">
                    <!-- d-none d-lg-block -->
                    <a href="<?= base_url(); ?>page/login" class="text-white">
                        <? if ($this->session->userdata('user_logged_in')) { ?>
                            <?
                            $name = $this->session->userdata('user_name');
                            $picture = $this->session->userdata('user_picture');
                            $picture_from = $this->session->userdata('user_picture_from');
                            ?>
                            <img src="<?= $this->frontend_lib->getUserPictureURL($picture, $picture_from); ?>"
                                alt="Avatar" class="avatar" align="left"
                                style="margin-right:10px;width:25px;height:25px;">
                            <?= (strlen($name) > 10 ? substr($name, 0, 10) . '...' : $name); ?>
                        <? } else { ?>
                            <i class="fa fa-chevron-circle-right"></i> &nbsp; Mulai Menulis
                        <? } ?>
                    </a>
                </div>
            </div><!-- Row end -->
        </div><!-- Logo and banner area end -->
    </header>
    <!--/ Header end -->

    <!-- BEGIN MAIN CONTENT -->
    <?= $content; ?>
    <!-- END MAIN CONTENT -->

    <!-- Section Subscribe -->
    <section class="block footer-fv-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7 align-self-center">
                    <h2 class="block-title-secondary heading-black m-t-30">
                        <span class="title-angle-shap"> Gabung Kuy!</span>
                    </h2>
                    <div class="row">
                        <div class="col-md-12 m-b-30">
                            <p class="subscribe-text">
                                Yuk bergabung dengan Bisnismuda.id untuk sharing dan belajar investasi, pengelolaan keuangan, dan wirausaha.
                            </p>
                            <div class="newsletter-area">
                                <div class="email-form-group">
                                    <a href="<?= base_url(); ?>page/signup" class="newsletter-submit">Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 align-self-center" style="min-height:320px;">
                    <center>
                        <img src="<?= base_url(); ?>files/frontend/images/kv.png" style="max-width:400px;"/>
                    </center>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer start -->
    <div class="ts-footer">
        <div class="container">
            <div class="row ts-gutter-30 justify-content-lg-between justify-content-center fs-14">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widtet">
                        <? if (isset($global_data[0]->logo_white) && strlen(trim($global_data[0]->logo_white)) > 0) { ?>
                            <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo_white; ?>"
                                class="img img-responsive img-fluid m-b-30" style="max-height:70px">
                        <? } ?>
                        <div class="widget-content">
                            <ul class="ts-footer-info">
                                <li><i class="fa fa-home"></i> <?= nl2br($global_data[0]->address); ?></li>
                                <li><i class="icon icon-phone2"></i> <?= $global_data[0]->phone1; ?></li>
                                <li><i class="fa fa-envelope"></i> <?= $global_data[0]->email; ?></li>
                            </ul>
                            <ul class="ts-social">
                                <? if (isset($global_data[0]->facebook) && strlen(trim($global_data[0]->facebook)) > 0) { ?>
                                    <li>
                                        <a href="<?= $global_data[0]->facebook; ?>" target="_blank"><i
                                                class="fa fa-facebook"></i></a>
                                    </li>
                                <? } ?>
                                <? if (isset($global_data[0]->twitter) && strlen(trim($global_data[0]->twitter)) > 0) { ?>
                                    <li>
                                        <a href="<?= $global_data[0]->twitter ?>" target="_blank"><i
                                                class="fa fa-twitter"></i></a>
                                    </li>
                                <? } ?>
                                <? if (isset($global_data[0]->instagram) && strlen(trim($global_data[0]->instagram)) > 0) { ?>
                                    <li>
                                        <a href="<?= $global_data[0]->instagram ?>" target="_blank"><i
                                                class="fa fa-instagram"></i></a>
                                    </li>
                                <? } ?>
                                <? if (isset($global_data[0]->youtube) && strlen(trim($global_data[0]->youtube)) > 0) { ?>
                                    <li>
                                        <a href="<?= $global_data[0]->youtube ?>" target="_blank"><i
                                                class="fa fa-youtube"></i></a>
                                    </li>
                                <? } ?>
                                <? if (isset($global_data[0]->linkedin) && strlen(trim($global_data[0]->linkedin)) > 0) { ?>
                                    <li>
                                        <a href="<?= $global_data[0]->linkedin ?>" target="_blank"><i
                                                class="fa fa-linkedin"></i></a>
                                    </li>
                                <? } ?>
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
                                    <a href="<?= base_url(); ?>page/terms_service"
                                        class="text-white">Ketentuan Layanan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/ketentuan-konten"
                                        class="text-white">Ketentuan Konten
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/penggunaan-hak-cipta"
                                        class="text-white">Penggunaan &amp; Hak Cipta
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/sanggahan-pelaporan"
                                        class="text-white">Sanggahan &amp; Pelaporan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/ketentuan-perubahan"
                                        class="text-white">Ketentuan Perubahan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/undang-undang-ite"
                                        class="text-white">Undang - Undang ITE
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/privacy_policy" class="text-white">Privacy Policy
                                    </a>
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
                                    <a href="<?= base_url(); ?>page/profil" class="text-white">Profil</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/tim-bisnismudaid"
                                        class="text-white">Tim Bisnismuda.id
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/tips-tricks" class="text-white">Tips &amp; Tricks
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/contact" class="text-white">Lapor Gangguan</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/contact" class="text-white">Kolaborasi Bisnis</a>
                                </li>
                                <li>
                                    <a href="<?= base_url(); ?>page/contact" class="text-white">Hubungi Kami</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- col end -->
                <!--
          <div class="col-lg-3 col-md-6">
            <div class="footer-widtet post-widget">
              <div class="widget-content">
                <img class="img-fluid" src="<?= base_url(); ?>files/frontend/images/banner-image/image6.jpg" alt="">
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

<nav id="menu">

    <ul>
        <li>
            <a href="<?= base_url(); ?>">HOME</a>
        </li>
        <? foreach ($categories as $x => $category) { ?>
            <? if (isset($category['child']) && is_array($category['child']) && count($category['child']) > 0) { ?>
                <li>
                    <span><?= strtoupper($category['category_name']); ?></span>
                    <ul>
                        <? foreach ($category['child'] as $y => $child) { ?>
                            <li>
                                <a href="<?= base_url(); ?>category/<?= $child['id_category']; ?>/<?= strtolower(url_title($child['category_name'])); ?>">
                                    <?= strtoupper($child['category_name']); ?>
                                </a>
                            </li>
                        <? } ?>
                    </ul>
                </li>
            <? } else { ?>
                <li>
                    <a href="<?= base_url(); ?>category/<?= $category['id_category']; ?>/<?= strtolower(url_title($category['category_name'])); ?>">
                        <?= strtoupper($category['category_name']); ?>
                    </a>
                </li>
            <? } ?>
        <? } ?>
        <!--
        <li><span>About us</span>
          <ul>
            <li><a href="#about/history">History</a></li>
            <li><span>The team</span>
              <ul>
                <li><a href="#about/team/management">Management</a></li>
                <li><a href="#about/team/sales">Sales</a></li>
                <li><a href="#about/team/development">Development</a></li>
              </ul>
            </li>
            <li><a href="#about/address">Our address</a></li>
          </ul>
        </li>
        <li><a href="#contact">Contact</a></li>
        <li class="Divider">Other demos</li>
        <li><a href="advanced.html">Advanced demo</a></li>
        <li><a href="onepage.html">One page demo</a></li>
        -->
    </ul>
    <? if ($this->session->userdata('user_logged_in')) { ?>
        <a href="<?= base_url(); ?>page/login" class="btn-logged-in">
            <?
            $name = $this->session->userdata('user_name');
            $picture = $this->session->userdata('user_picture');
            $picture_from = $this->session->userdata('user_picture_from');
            ?>
            <img src="<?= $this->frontend_lib->getUserPictureURL($picture, $picture_from); ?>" alt="Avatar"
                class="avatar" align="left" style="margin-right:10px;width:25px;height:25px;">
            <?= (strlen($name) > 10 ? substr($name, 0, 10) . '...' : $name); ?>
        </a>
    <? } else { ?>
        <a href="<?= base_url(); ?>page/login" class="btn-login">
            <i class="fa fa-chevron-circle-right"></i> &nbsp; Mulai Menulis
        </a>
    <? } ?>
</nav>

<input type="hidden" id="csrf-token-name" value="<?= $this->security->get_csrf_token_name(); ?>"/>
<input type="hidden" id="csrf-token-hash" value="<?= $this->security->get_csrf_hash(); ?>"/>
</body>

</html>