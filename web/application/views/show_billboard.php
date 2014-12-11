<div id="panel">
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
			<tr>
				<td>Komentár: </td><td class="comment"></td>
			</tr>
		</table>
	</div>
</div>

<form action="<?php echo base_url(); ?>billboards/add" id="add-form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="lat">
	<input type="hidden" name="lng">

	<input name="photo" type="file" value="Vložiť fotku billboardu" required="true" accept="image/*"><br>

	Vyberte typ billboardu:<br>
	<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
	<img src="<?php echo assets_url(); ?>img/types/citylight.png">
	<img src="<?php echo assets_url(); ?>img/types/standard.png">
	<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
	<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
	<br>

	<input type="radio" name="typ_nosica" value="1">
	<input type="radio" name="typ_nosica" value="2">
	<input type="radio" name="typ_nosica" value="3">
	<input type="radio" name="typ_nosica" value="4">
	<input type="radio" name="typ_nosica" value="5">
	<br>

	<select name="provider">
		<?php
			foreach($owners as $owner)
			{
				echo "<option>$owner->nazov</option>";
			}
		?>
	</select>
	<br> 

	<textarea name="comment" placeholder="Môžete nám k nemu niečo napísať."></textarea>
	<br>
	
	<input type="submit">
</form>

<script src="<?php echo assets_url(); ?>js/main.js"></script>