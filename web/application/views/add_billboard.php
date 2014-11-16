<body class="add-billboard">
	<div id="panel">
		<!-- <h1>Pretiahnite fotku billboardu na konkrétne miesto na mape</h1> -->
		<a href="<?php echo base_url(); ?>billboards/">Späť</a>
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

	<script src="<?php echo assets_url(); ?>js/main.js"></script>
</body>