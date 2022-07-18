<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Under Maintenance</title>
  <meta name="description" content="Kopi Senayan">
  <meta name="author" content="Binary">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google-site-verification" content="7gHcVzJ_av6LXA24c4u6oVU1CTiC71hMTxwe49wjmyI" />
	
	<!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,800%7CRanga:700" rel="stylesheet">  
    
  <!-- All CSS -->
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/css/animate.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/style.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>files/comingsoon/css/responsive.css">
	
	<!-- Modernizr js - required -->
	<script src="<?= base_url(); ?>files/comingsoon/js/modernizr.custom.js"></script>
  

</head>
<body class="mrs-v9">
	<!-- Page Loader Start -->
	<div class="marshall-loading-screen">
	    <div class="marshall-loading-icon">
	        <div class="marshall-loading-inner">
	        	<div class="marshall-load" data-name=""></div>
	        </div>
	    </div>
	</div><!-- End .loading-screen -->

	<div class="marshall-container">
		<div class="marshall-col-6 marshall-col-content">
			<div class="marshall-logo">
        <? if(isset($global_data[0]->logo) && strlen(trim($global_data[0]->logo)) > 0){ ?>
          <img src="<?= base_url(); ?>assets/logo/<?= $global_data[0]->logo; ?>">
        <? } else { ?>
          <img src="<?= base_url(); ?>files/frontend/images/logo.png">
        <? } ?>
			</div>
			<div class="marshall-content jquery-center">
				<h2 class="fadeIn fast">Our site is under maintenance</h2>
				<section class="cd-intro">
					<h1 class="cd-headline clip is-full-width">
						<span class="cd-words-wrapper" style="color:#0080ff;">
							<b class="is-visible">Almost There</b>
							<b>Coming Soon</b>
							<b>Stay Tuned</b>
						</span>
					</h1>
				</section> <!-- cd-intro -->
			</div>
			<div class="marshall-social-column">
				<p class="fadeIn fast">Stay in touch :</p>
				<ul class="marshall-social-links">
					<li><a class="fadeIn fast-child-1" target="_blank" href="<?= $global_data[0]->facebook; ?>" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
					<li><a class="fadeIn fast-child-1" target="_blank" href="<?= $global_data[0]->twitter; ?>" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
					<li><a class="fadeIn fast-child-2" target="_blank" href="<?= $global_data[0]->instagram; ?>" title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
					<li><a class="fadeIn fast-child-2" target="_blank" href="<?= $global_data[0]->youtube; ?>" title="Youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
				</ul>
			</div>
		</div>
		<div class="marshall-col-6 marshall-col-screen display_in_mobile">
			<div id="marshall-animate-area" data-hide="mrs-scaleDown" style="background-image:url('<?= base_url(); ?>assets/cover/<?= $global_data[0]->cover; ?>')" class="marshall-single-fit-thumb marshall-animate-content marshall-animate-content mrs-active marshall-fit-column">
				<div class="marshall-simple-content css-center marshall-content">
					<h2>Contact us at<br/><a href="mailto:<?= $global_data[0]->email; ?>"><b><?= $global_data[0]->email; ?></b></a></h2>
				</div>
				<div class="content_overley"></div>
			</div>
		</div>
	</div>
	

	<!-- All marshall js files -->
	<script type="text/javascript" src="<?= base_url(); ?>files/comingsoon/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>files/comingsoon/js/classie.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>files/comingsoon/js/uiMorphingButton_fixed.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>files/comingsoon/js/v9/textanime.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>files/comingsoon/js/main.js"></script>
	
</body>
</html>