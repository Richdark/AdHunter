<header id="header" style="height:77%">
	<div class="row" >
		<div class="login-form">
		<h1 style="text-align: center">Registrácia</h1>
			<form  method="post" action="../add_user" class="login-form">
				<input type="text" name="email" value="E-mail" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}"><br><br>
				<input type="text" name="password" value="Heslo" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Heslo'}" onkeyup="this.type = (this.value == '') ? 'text' : 'password'"><br><br>
				<input type="text" name="name" value="Meno" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Meno';}"><br><br>
				<input type="text" name="surrname" value="Priezvisko" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Priezvisko';}"><br><br>
				<input type="submit" value="Registrovať">
			</form>
		</div>
	</div>
</header>