<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>AdHunter Landing Page</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/style.css">
	<link rel="stylesheet" href="<?php echo assets_url(); ?>css/responsive.css">
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo assets_url(); ?>/img/favicon.ico'>

	<script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery-2.1.1.min.js"></script>
</head>
<body>
	<div class="app">
		<div class="fixed">
			<header>
				<div class="c">
					<a href="<?php echo base_url(); ?>" id="logo"></a>
					<a href="#" id="toggle">Menu</a>
					<ul>
						<li><a href="<?php echo base_url(); ?>">Domov</a></li>
						<li><a href="<?php echo base_url(); ?>billboards/show/">Mapa billboardov</a></li>
						<li><a href="<?php echo base_url(); ?>login_registration/login/">Prihlásenie</a></li>
						<li><a href="<?php echo base_url(); ?>login_registration/register/">Registrácia</a></li>
					</ul>
				</div>
			</header>

			<div class="c">
				<div class="text">
					<h1>AdHunter aplikácia</h1>
					<p>Bojuj proti vizuálnemu smogu, zlepši verejný priestor, skrášli svoje mesto.</p>
					<a href="#" class="download"></a>
				</div><!--
				--><div class="image"></div>
			</div>
		</div>
	</div>

	<div class="features">
		<div class="c">
			<span class="column">
				<a href="<?php echo base_url(); ?>billboards/show/">
					<img src="<?php echo assets_url(); ?>img/buttons/button1.png">
				</a>
				<h1>Mapa vizuálneho smogu</h1>
				<p>Prezri si podrobnú mapu svetelnej reklamy, billboardov a pútačov kdekoľvek na slovensku v našej interaktívnej mape s veľkým množstvom informácii.</p>
			</span>
			<span class="column">
				<a href="<?php echo base_url(); ?>login_registration/register/">
					<img src="<?php echo assets_url(); ?>img/buttons/button2.png">
				</a>
				<h1>Pridaj sa k nám</h1>
				<p>Potrebujeme tvoju pomoc pri mapovaní reklamy na mieste kde žiješ. Stiahni si mobilnú verziu aplikácie a začni fotiť loviť neestetickú reklamu hneď teraz. Aplikácie je momentálne dostupná pre OS Android.</p>
			</span>
			<span class="column">
				<a href="<?php echo base_url(); ?>">
					<img src="<?php echo assets_url(); ?>img/buttons/button3.png">
				</a>
				<h1>Povedz nám čo vieš</h1>
				<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum.</p>
			</span>
		</div>
	</div>

	<script src="<?php echo assets_url(); ?>js/main.js"></script>