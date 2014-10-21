<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pridanie nového billboardu</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-2.1.1.min.js"></script>
</head>
<body class="add-billboard">
	<div id="panel">
		<!-- <h1>Pretiahnite fotku billboardu na konkrétne miesto na mape</h1> -->
		<a href=".">Späť</a>
		<a id="add" href="#">Pridať</a>
		<!-- <h1 class="title">Pretiahnutím tohto markera na mapu určíte, kde sa nachádza Vami nájdený billboard.</h1> -->
	</div>

	<div id="map"></div>

	<form id="add-form" method="post">
		<input name="photo" type="file" value="Vložiť fotku billboardu"><br>
		<textarea name="text" placeholder="Môžte nám k nej niečo napísať."></textarea><br>
		<input type="submit">
	</form>

	<script src="js/main.js"></script>
</body>
</html>