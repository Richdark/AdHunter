<?php
$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>
<html>
<head>
	<meta charset="utf-8">
	<title>registracia</title>
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
	<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
</head>
<body>
<form id="login_form" method="post" action="../add_user">
	E-mail: <input type="text" name="email"><br>
	Heslo: <input type="password" name="password"><br>
	Meno: <input type="text" name="name"><br>
	Priezvisko: <input type="text" name="surrname"><br>
	<input type="submit">
</form>

</body>

</html>