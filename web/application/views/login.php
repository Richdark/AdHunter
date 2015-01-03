<header id="header" style="height:77%">
	<div class="row" >
		<div class="login-form">
			<h1 style="text-align: center">Prihlásenie</h1>
			<form  method="post" action="../authentificate_user" class="login-form">
				<input type="text" name="email" value="E-mail" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br><br>
				<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br><br>
				<input type="submit">
			</form>
		</div>
	</div>
</header>