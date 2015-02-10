<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>AdHunter Landing Page</title>

	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive.css">
	<!-- <link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'> -->
	<!-- <link href='http://fonts.googleapis.com/css?family=Cabin+Condensed:600' rel='stylesheet' type='text/css'> -->
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo assets_url(); ?>/img/favicon.ico'>

	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery-2.1.1.min.js"></script>
</head>
<body>
	<?php
	if (uri_string() == '') { echo '<div class="app"><div class="fixed">'; }
	?>
	<header>
		<div class="c">
			<a href="<?php echo base_url(); ?>" id="logo"></a>
			<a href="#" id="toggle">Menu</a>
			<ul>
				<li><a href="<?php echo base_url(); ?>">Domov</a></li>
				<li><a href="<?php echo base_url(); ?>billboards/show/">Mapa billboardov</a></li>
				
				<?php

				if ($this->session->userdata('email') != NULL)
				{
					echo '<li><a href="'. base_url(). 'auth/logout/">Odhlásiť</a></li>';
				}
				else
				{
					echo '<li><a href="'. base_url(). 'auth/login/">Prihlásenie</a></li>';
					echo '<li><a href="'. base_url(). 'auth/register/">Registrácia</a></li>';
				}

				?>
			</ul>
		</div>
	</header>