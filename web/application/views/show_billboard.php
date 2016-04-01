<div id="panel">
	<input id="search" type="text" placeholder="Zadajte objekt alebo adresu">
	<a id="add" class="right" href="#">Pridať</a>
	<?php
	if ($_user->logged)
	{
	?>
		<a id="mine" class="right" href="#">
			<span>Zobraziť iba moje billboardy</span>
			<span style="display:none">Zobraziť všetky billboardy</span>
		</a>
	<?php
	}
	?>
	<span class="right" style="display: none">Kliknite na mapu pre vyznačenie pozície nájdeného billboardu.</span>
</div>

<div id="map"></div>

<div id="merge-sidebar" class="sidebar">
	<a class="close" href="#" title="close">✖</a>
	<h2>Billboardy na zlúčenie</h2>
	<div class="billboards"></div>
	<a href="#" class="merge">zlúčiť vybrané billboardy</a>
</div>
<div id="edit-sidebar" class="sidebar">
	<a class="close" href="#" title="close">✖</a>
	<h2>Editácia billboardu</h2>
	<form action="<?php echo root_url(); ?>billboards/update/" class="form" method="post" enctype="multipart/form-data">
		Vlastník:
		<select name="owner_id">
			<option value="0">Neznámy</option>
			<?php
				for($i=0; $i<count($owners); $i++)
				{
					echo '<option value="'.$owners[$i]->id.'">'.$owners[$i]->name.'</option>';
				}
			?>
		</select><br>
		Typ nosiča:
		<div class="center">
			<img src="<?php echo assets_url(); ?>img/types/standard.png">
			<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
			<img src="<?php echo assets_url(); ?>img/types/citylight.png">
			<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
			<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
			<img src="<?php echo assets_url(); ?>img/types/other.png">
			<br>
			<input type="radio" name="backing_type" value="1">
			<input type="radio" name="backing_type" value="2">
			<input type="radio" name="backing_type" value="3">
			<input type="radio" name="backing_type" value="4">
			<input type="radio" name="backing_type" value="5">
			<input type="radio" name="backing_type" value="6">
		</div>
		<br>
		
		<textarea name="comment" placeholder="Môžete nám k nemu niečo napísať."></textarea>
		<br>

		<input id="move" type="button" value="Premiestniť">
		<input name="edit" type="submit" value="Uložiť">
		<input name="delete" type="submit" value="Zmazať">
	</form>
</div>

<div id="info-content">
	<table>
		<td>
			<img class="billboard" src="">
		</td>
		<td valign="top">
			<div class="info" style="min-width:200px">
				<!-- INFO -->
				<table class="preview">
					<tr>
						<td>Vlastník: </td><td class="owner"></td>
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
							<img src="<?php echo assets_url(); ?>img/types/standard.png">
							<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
							<img src="<?php echo assets_url(); ?>img/types/citylight.png">
							<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
							<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
							<img src="<?php echo assets_url(); ?>img/types/other.png">
						</td>
					</tr>
				</table>

				<!-- NOTICES -->
				<div class="notices">
					<p>Tento úlovok bol používateľom zlúčený s iným úlovkom.</p>
				</div>

				<!-- OPTIONS -->
				<?php
				if ($_user->logged)
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

<form action="<?php echo root_url(); ?>billboards/add/" id="add-form" class="form" method="post" enctype="multipart/form-data">
	<a class="close" href="#" title="close">✖</a>

	<input type="hidden" name="lat">
	<input type="hidden" name="lng">

	<input name="photo" type="file" value="Vložiť fotku billboardu" required="true" accept="image/jpeg"><br>

	<h2>Vyberte typ billboardu:</h2>
	<div class="center">
		<img src="<?php echo assets_url(); ?>img/types/standard.png">
		<img src="<?php echo assets_url(); ?>img/types/megaboard.png">
		<img src="<?php echo assets_url(); ?>img/types/citylight.png">
		<img src="<?php echo assets_url(); ?>img/types/hypercube.png">
		<img src="<?php echo assets_url(); ?>img/types/trojnozka.png">
		<img src="<?php echo assets_url(); ?>img/types/other.png">
		<br>
		<input type="radio" name="backing_type" value="1">
		<input type="radio" name="backing_type" value="2">
		<input type="radio" name="backing_type" value="3">
		<input type="radio" name="backing_type" value="4">
		<input type="radio" name="backing_type" value="5">
		<input type="radio" name="backing_type" value="6">
	</div>

	<select name="owner_id">
		<option value="0">Neznámy</option>
		<?php
			for($i=0; $i<count($owners); $i++)
			{
				echo '<option value="'.$owners[$i]->id.'">'.$owners[$i]->name.'</option>';
			}
		?>
	</select>
	<br> 

	<textarea name="comment" placeholder="Môžete nám k nemu niečo napísať."></textarea>
	<br>
	
	<input type="submit" value="Odoslať">
</form>

<script src="<?php echo assets_url(); ?>js/main.js"></script>