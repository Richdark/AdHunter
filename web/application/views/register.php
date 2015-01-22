<div class="default-form">
	<div class="c">
		<h1 style="text-align: center">Registrácia</h1>
		<form  method="post" action="../add_user">
			<input type="text" name="email" value="E-mail" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br>
			<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br>
			<input type="text" name="name" value="Meno" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Meno';}"><br>
			<input type="text" name="surrname" value="Priezvisko" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Priezvisko';}"><br>
			<input type="submit" value="Registrovať">
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>