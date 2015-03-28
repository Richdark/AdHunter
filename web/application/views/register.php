<div class="default-form">
	<div class="c">
		<?php if (!(empty($invalid_fields))) { echo '<h3>Registrácia neprebehla úspešne</h3>'; } ?>
		<form  method="post" action="../register/">
			<input type="text" name="email" value="E-mail"<?php if (array_search('email', $invalid_fields) !== false) { echo ' class = "invalid"'; } ?> onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br>
			<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br>
			<input type="text" name="name" value="Meno" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Meno';}"><br>
			<input type="text" name="surrname" value="Priezvisko" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Priezvisko';}"><br>
			<input type="submit" name="send" value="Registrovať">
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>