<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>AdHunter<?php if (isset($page_title)){ echo ' - '. $page_title; } ?></title>

	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive.css">
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo assets_url(); ?>/img/favicon.ico'>

	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery-2.1.1.min.js"></script>
</head>
<body class="<?php echo $layout_version; ?>">
	<?php
	if (uri_string() == '') { echo '<div class="app"><div class="fixed">'; }
	?>
	<header>
		<div class="c">
			<a href="<?php echo root_url(); ?>" id="logo"></a>
			<a href="#" id="toggle">Menu</a>
			<ul>
				<li><a href="<?php echo root_url(); ?>">Domov</a></li>
				<li><a href="<?php echo root_url(); ?>billboards/show/">Mapa billboardov</a></li>
				
				<?php
				if ($_user->logged)
				{
					echo '<li><a href="'. root_url(). 'profile/">Profil</a></li>';
					echo '<li><a href="'. root_url(). 'profile/badges/">Moje odznaky</a></li>';
					echo '<li class="logout"><a href="'. root_url(). 'auth/logout/">Odhlásiť</a></li>';
				}
				else
				{
					echo '<li><a href="'. root_url(). 'auth/login/">Prihlásenie</a></li>';
					echo '<li><a href="'. root_url(). 'auth/register/">Registrácia</a></li>';
				}

				?>
			</ul>
		</div>
	</header>

	<?php

	if ($layout_version == 'regular')
    {
        echo '<div class="content_regular">';

        if (isset($page_title))
        {
            echo '<h1><span>'. $page_title. '</span></h1>';
        }

        echo '<div class="content">';
    }

	?>