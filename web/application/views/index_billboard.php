<?php

$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>

<body>
<!-- Include menu -->
<?php include 'menu.php';?>

<header id="header" >
	<div class="row">
    
    	<div class="large-6 columns">
			<div id="teaser-slider-2">
							<div class="flexslider">
								<ul class="slides">
									<li>
										<img src="<?php echo $assets_url; ?>/img/slides/iphoneshots-1.jpg" alt="Petrichor - slider">
									</li>
									<li>
										<img src="<?php echo $assets_url; ?>/img/slides/iphoneshots-2.jpg" alt="Petrichor - slider">
									</li>
									<li>
										<img src="<?php echo $assets_url; ?>/img/slides/iphoneshots.jpg" alt="Petrichor - slider">
									</li>
								</ul>
							</div> 
						</div>
		</div>
        
		<div class="large-6 columns">
			<h1>AdHunter aplikácia</h1>
			<span class="subheading">Bojuj proti vizuálnemu smogu, zlepši verejný priestor, skrášli svoje mesto</span>
	        <a class="download-btn" href="#" target="_blank"></a>
		</div>
		
	</div>
</header>

<div id="features" class="section features" data-magellan-destination="features">
	<div class="row hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3b">
		<div class="large-4 columns feature">
			<span class="icon icon-directions hi-icon"></span>
			<h3>Mapa  vizuálneho smogu</h3>
			<p>Prezri si podrobnú mapu svetelnej reklamy, billboardov a pútačov kdekoľvek na slovensku v našej interaktívnej mape s veľkým množstvom informácii.

</p>
		</div>
		<div class="large-4 columns feature">
			<span class="icon icon-mobile hi-icon"></span>
			<h3>Pridaj sa k nám</h3>
			<p>Potrebujeme tvoju pomoc pri mapovaní reklamy na mieste kde žiješ. Stiahni si mobilnú verziu aplikácie a začni fotiť loviť neestetickú reklamu hneď teraz. Aplikácie je momentálne dostupná pre OS Android.

</p>
		</div>
		<div class="large-4 columns feature">
			<span class="icon icon-info hi-icon"></span>
			<h3>Povedz nám čo vieš</h3>
			<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum.

</p>
		</div>
	</div>
</div>
<footer>
	<div class="row">
		<div class="large-6 columns">
			<ul class="inline-list">
			  <li class="copyright">2014 &copy; FIIT STU</a></li>
			 
  			</ul>
		</div>
		<div class="large-6 columns">
			<ul class="inline-list social-media right">
				<li><a href="http://www.facebook.com/EGrappler" class="icon icon-facebook"></a></li>
				<li><a href="htp://twitter.com/egrappler" class="icon icon-twitter"></a></li>
				<li><a href="https://plus.google.com/102572598506883739879/posts" class="icon icon-googleplus"></a></li>
			</ul>
		</div>
	</div>
</footer>			
			


</body>
</html>


