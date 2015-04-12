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
		</fieldset>
		<input type="submit" name="send" value="Odoslať" />
	</form>
</div>

<div class="clear"></div>
