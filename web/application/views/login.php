<div class="default-form">
	<div class="c">
		<?php if (!(empty($invalid_fields))) { echo '<h3>Prihlásenie neprebehlo úspešne</h3>'; } ?>
		<form  method="post" action="">
			<input type="text" name="email" value="E-mail"<?php if (array_search('email', $invalid_fields) !== false) { echo ' class = "invalid"'; } ?> onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br>
			<input type="password" name="password" value="Heslo"<?php if (array_search('password', $invalid_fields) !== false) { echo ' class = "invalid"'; } ?> onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}"><br>
			<input type="submit" name="send" value="Prihlásiť" />
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>