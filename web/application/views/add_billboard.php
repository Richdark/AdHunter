<body class="add-billboard">
	<div id="panel">
		<!-- <h1>Pretiahnite fotku billboardu na konkrétne miesto na mape</h1> -->
		<a href="<?php echo base_url(); ?>billboards/">Späť</a>
		<a id="add" href="#">Pridať</a>
		<input id="search" type="text" placeholder="Zadajte objekt alebo adresu">
	</div>

	<div id="map"></div>
	<div id="info-content">
		<div class="info">
			<!-- <h2 class="title"></h2> -->
			<img class="billboard" src="">
			<table>
				<tr>
					<td>Vlastník: </td><td class="provider"></td>
				</tr>
				<tr>
					<td>Nahrané: </td><td class="uploaded"></td>
				</tr>
			</table>
		</div>
	</div>

	<form id="add-form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="lat">
		<input type="hidden" name="lng">

		<input name="photo" type="file" value="Vložiť fotku billboardu" required="true" accept="image/*"><br>
		<select name="provider">
			<?php
				foreach($owners as $owner)
				{
					echo "<option>$owner->nazov</option>";
				}
			?>
		</select><br>
		<textarea name="text" placeholder="Môžete nám k nej niečo napísať."></textarea><br>
		<input type="submit">
	</form>

	<script src="<?php echo assets_url(); ?>js/main.js"></script>
</body>