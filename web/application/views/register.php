<div class="default-form">
	<div class="c">
		<?php if (isset($invalid_fields['email'])) {
			echo '<h3>Registrácia neprebehla úspešne</h3>';
		} ?>
		<form  method="post" action="../register/">
			<span class="with_hint">
				<input type="text" name="email" value="E-mail"<?php if (isset($invalid_fields['email'])) { echo ' class = "invalid"'; } ?> onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}">
				<?php
				if (isset($invalid_fields['email']))
				{
					if ($invalid_fields['email'] == 'invform')
					{
						echo '<p class="warning">Emailová adresa nie je v správnom tvare!</p>';
					}
					elseif ($invalid_fields['email'] == 'alrdreg')
					{
						echo '<p class="warning">Táto emailová adresa už je zaregistrovaná!</p>';
					}
				}
				?>
			</span><br />
			<span class="with_hint">
				<input type="password" name="password" value="Heslo"<?php if (isset($invalid_fields['password'])) { echo ' class = "invalid"'; } ?> onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}">
				<?php
				if (isset($invalid_fields['password']) and ($invalid_fields['password'] == 'invform'))
				{
					echo '<p class="warning">Musí mať min. 6 znakov a obsahovať písmeno aj číslicu!</p>';
				}
				?>
			</span><br />
			<input type="text" name="name" value="Meno" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Meno';}"><br>
			<input type="text" name="surrname" value="Priezvisko" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Priezvisko';}"><br>
			<input type="submit" name="send" value="Registrovať">
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>