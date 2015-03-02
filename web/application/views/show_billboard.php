<div id="panel">
	<a id="add" href="#">Pridať</a>
	<input id="search" type="text" placeholder="Zadajte objekt alebo adresu">
</div>

<div id="map"></div>
<div id="map_sidebar">
	<h2>Billboardy na zlúčenie</h2>
	<div class="billboards"></div>
	<a href="javascript:void(0)" class="merge" onclick="merge_billboards()">zlúčiť vybrané billboardy</a>
</div>
<div id="info-content">
	<div class="info">
		<img class="billboard" src="" />

		<!-- NOTICES -->
		<div class="notices">
			<p>Tento úlovok bol používateľom zlúčený s iným úlovkom.</p>
		</div>
		
		<?php
		if ($logged)
		{
		?>

		<!-- OPTIONS -->
		<div class="options">
			<a href="#" class="edit" onclick="edit_billboard(this)">Upraviť údaje</a>
			<a href="" class="merge" onclick="add_merge_obj(this)">Zlúčiť</a>
		</div>
		<?php
		}
		?>

		<!-- INFO -->
		<table class="preview">
			<tr>
				<td>Vlastník: </td><td class="provider"></td>
			</tr>
			<tr>
				<td>Nahrané: </td><td class="uploaded"></td>
			</tr>
			<tr>
				<td>Komentár: </td><td class="comment"></td>
			</tr>
			<tr>
				<td>Typ nosiča:</td>
				<td class="type">
					<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
					<img src="<?php echo assets_url(); ?>img/types/citylight.png">
					<img src="<?php echo assets_url(); ?>img/types/standard.png">
					<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
					<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
				</td>
			</tr>
		</table>

		<form action="<?php echo base_url(); ?>billboards/update/" class="form" method="post" enctype="multipart/form-data">
			<input id="catch_id" name="catch_id" type="hidden" value="">

			<span class="center">
				<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
				<img src="<?php echo assets_url(); ?>img/types/citylight.png">
				<img src="<?php echo assets_url(); ?>img/types/standard.png">
				<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
				<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
				<br>
				<input type="radio" name="backing_type" value="1">
				<input type="radio" name="backing_type" value="2">
				<input type="radio" name="backing_type" value="3">
				<input type="radio" name="backing_type" value="4">
				<input type="radio" name="backing_type" value="5">
			</span>
			<br>

			<input type="submit" value="Odoslať">
			<input type="submit" value="Zmazať">
		</form>
	</div>
</div>

<form action="<?php echo base_url(); ?>billboards/add/" id="add-form" class="form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="lat">
	<input type="hidden" name="lng">

	<input name="photo" type="file" value="Vložiť fotku billboardu" required="true" accept="image/*"><br>

	<h2>Vyberte typ billboardu:</h2>
	<span class="center">
		<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
		<img src="<?php echo assets_url(); ?>img/types/citylight.png">
		<img src="<?php echo assets_url(); ?>img/types/standard.png">
		<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
		<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
	</span>

	<span class="center">
		<input type="radio" name="backing_type" value="1">
		<input type="radio" name="backing_type" value="2">
		<input type="radio" name="backing_type" value="3">
		<input type="radio" name="backing_type" value="4">
		<input type="radio" name="backing_type" value="5">
	</span>

	<select name="provider">
		<option value="0">Neznámy</option>
		<?php
			for($i=0; $i<count($owners); $i++)
			{
				echo '<option value="'.$i.'">'.$owners[$i]->name.'</option>';
			}
		?>
	</select>
	<br> 

	<textarea name="comment" placeholder="Môžete nám k nemu niečo napísať."></textarea>
	<br>
	
	<input type="submit" value="Odoslať">
</form>

<script src="<?php echo assets_url(); ?>js/main.js"></script>