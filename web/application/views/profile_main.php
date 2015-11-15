<?php echo $profile_menu; ?>

<div class="profile_content">
	<?php
	if (isset($success))
	{
		echo '<span class="info">Údaje boli úspešne aktualizované.</span>';
	}
	?>
	<form method="post" action="">
		<fieldset>
			<legend>Osobné informácie:</legend>
			<span class="field_descr">Email</span><input type="text" value="<?php echo $_user->email; ?>" disabled /><br />
			<span class="field_descr">Meno</span><input type="text" name="name" value="<?php echo $name; ?>" /><br />
			<span class="field_descr">Priezvisko</span><input type="text" name="surname" value="<?php echo $surname; ?>" /><br />
			<!--<span class="field_descr">Obrázok</span><input type="file" name="picture" /><br />-->
		</fieldset>
		<input type="submit" name="send" value="Odoslať" />
	</form>
</div>

<div class="clear"></div>
<script src="<?php echo assets_url(); ?>js/main.js"></script>
