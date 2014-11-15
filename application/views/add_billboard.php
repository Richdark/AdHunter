<?php

$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pridanie nového billboardu</title>
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
	<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
</head>
<body class="add-billboard">
	<div id="panel">
		<!-- <h1>Pretiahnite fotku billboardu na konkrétne miesto na mape</h1> -->
		<a href="<?php echo $base_url; ?>billboards/">Späť</a>
		<a id="add" href="#">Pridať</a>
	</div>

	<div id="map"></div>

	<form id="add-form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="lat">
		<input type="hidden" name="lng">

		<input name="photo" type="file" value="Vložiť fotku billboardu" required="true" accept="image/*"><br>
		<textarea name="text" placeholder="Môžete nám k nej niečo napísať."></textarea><br>
		<input type="submit">
	</form>

	<script src="<?php echo $assets_url; ?>js/main.js"></script>
</body>
</html>