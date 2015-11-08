<?php echo $profile_menu; ?>

<div class="profile_content">
	<?php
	if (isset($success))
	{
		echo '<span class="info">Vlastník bol úspešne pridaný.</span>';
	}
	?>
	<form method="post" action="">
		<fieldset>
			<legend>Údaje o vlastníkovi:</legend>
			<span class="field_descr"><span
			<?php
			if (isset($invalid_fields['name']))
			{
				echo ' class="error"';
			}
			?>
			>Názov/meno</span>
			</span><input type="text" name="name" />
		</fieldset>
		<input type="submit" name="send" value="Odoslať" />
	</form>
</div>

<div class="clear"></div>
