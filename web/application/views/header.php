<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->
<!--[if IE 8]><html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html xmlns="http://www.w3.org/1999/xhtml"><!--<![endif]-->
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>AdHunter Landing Page</title>

	<link rel="stylesheet" href="<?php echo assets_url(); ?>/css/normalize.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>/css/foundation.min.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>/css/ie.css">
	<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Cabin+Condensed:600' rel='stylesheet' type='text/css'>

	<script src="<?php echo assets_url(); ?>/js/vendor/custom.modernizr.js"></script>
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo assets_url(); ?>/img/favicon.ico'>

	<script type="text/javascript" src="<?php echo assets_url(); ?>/js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>/js/foundation.min.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>/js/functions.js"></script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>/js/jquery.nicescroll.js"></script>
	<script src="<?php echo assets_url(); ?>/js/jquery.localscroll-1.2.7.js" type="text/javascript"></script>
	<script src="<?php echo assets_url(); ?>/js/jquery.scrollTo-1.4.3.1.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo assets_url(); ?>/css/flexslider.css"> <!-- Flex slider -->
	<script src="<?php echo assets_url(); ?>/js/jquery.flexslider.js" type="text/javascript"></script><!-- Flex slider -->
	<script type="text/javascript" src="<?php echo assets_url(); ?>/js/custom.js"></script>
</head>
<body>
	<div id="top" data-magellan-expedition="fixed">
		<div class="row">
			<div class="large-12 columns">
				<nav class="top-bar">
					<ul class="title-area">
						<li class="name logo">
							<a href="<?php echo base_url(); ?>"><img src="<?php echo assets_url(); ?>/img/adhunter.png"  alt=""></a>
						</li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
					</ul>
					
					<section class="top-bar-section">
						<ul class="right" id="menu">
							<li data-magellan-arrival="home">
								<a href="<?php echo base_url(); ?>">Domov</a>
							</li>
							<li data-magellan-arrival="view">
								<a href="<?php echo base_url(); ?>billboards/show/">Mapa Billboardov</a>
							</li>
							<li data-magellan-arrival="login">
								<a href="<?php echo base_url(); ?>login_registration/login/">Prihlásenie</a>
							</li>
							<li data-magellan-arrival="login">
								<a href="<?php echo base_url(); ?>login_registration/register/">Registrácia</a>
							</li>
						</ul>
					</section>
				</nav>
			</div>
		</div>
	</div>