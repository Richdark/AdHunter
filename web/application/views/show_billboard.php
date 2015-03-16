<div id="panel">
	<a id="add" href="#">Pridať</a>
	<input id="search" type="text" placeholder="Zadajte objekt alebo adresu">
</div>

<div id="map"></div>

<div id="merge-sidebar" class="sidebar">
	<h2>Billboardy na zlúčenie</h2>
	<div class="billboards"></div>
	<a href="#" class="merge">zlúčiť vybrané billboardy</a>
</div>
<div id="edit-sidebar" class="sidebar">
	<h2>Editácia billboardu</h2>

	<form action="<?php echo base_url(); ?>billboards/update/" class="form" method="post" enctype="multipart/form-data">
		Typ nosiča:
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
		
		<textarea name="comment" placeholder="Môžete nám k nemu niečo napísať."></textarea>
		<br>

		<input name="edit" type="submit" value="Odoslať">
		<input name="delete" type="submit" value="Zmazať">
	</form>
</div>

<div id="info-content">
	<table>
		<td>
			<img class="billboard" src="">
		</td>
		<td valign="top">
			<div class="info">
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

				<!-- NOTICES -->
				<div class="notices">
					<p>Tento úlovok bol používateľom zlúčený s iným úlovkom.</p>
				</div>

				<!-- OPTIONS -->
				<?php
				if ($logged)
				{
				?>
				<div class="options">
					<a href="#" class="edit">Upraviť údaje</a>
					<a href="#" class="merge">Zlúčiť</a>
				</div>
				<?php
				}
				?>
			</div>
		</td>
	</table>
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