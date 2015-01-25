<div class="default-form">
	<div class="c">
		<h1 style="text-align: center">Prihlásenie</h1>
		<form  method="post" action="../authentificate_user">
			<input type="text" name="email" value="E-mail" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br>
			<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br>
			<input type="submit">
		</form>
	</div>
</div>