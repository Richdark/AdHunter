<div class="default-form">
	<div class="c">
		<?php if (isset($invalid_fields['email'])) {
			echo '<h3>Zmena hesla neprebehla úspešne</h3>';
		} ?>
		<form  method="post" action="../save/">
			<span class="with_hint">
				<input type="password" name="password" placeholder="Nové heslo" value=""<?php if (isset($invalid_fields['password'])) { echo ' class = "invalid"'; } ?>>
				<?php
				if (isset($invalid_fields['password']) and ($invalid_fields['password'] == 'invform'))
				{
					echo '<p class="warning">Musí mať min. 6 znakov a obsahovať písmeno aj číslicu!</p>';
				}
				?>
			</span><br />
			<input type="hidden" name="code" value="<?php echo $code; ?>" />
			<input type="submit" name="send" value="Uložiť heslo">
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>