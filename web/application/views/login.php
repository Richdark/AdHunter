<div class="default-form">
	<div class="c">
		<form  method="post" action="../login_user">
			<input type="text" name="email" value="E-mail" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br>
			<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br>
			<input type="submit" value="Odoslať" />
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>