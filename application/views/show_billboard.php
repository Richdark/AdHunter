<?php

$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Zobrazenie nájdených bilboardov</title>
		<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
		<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
		<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
	</head>
	<body class="show-billboards">
		<div id="panel">
			<a class="back" href="<?php echo $base_url; ?>billboards/">Späť</a>
		</div>

		<div id="map"></div>

		<script src="<?php echo $assets_url; ?>js/main.js"></script>
	</body>
</html>