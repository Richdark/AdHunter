<?php
$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Prihlasenie</title>
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
	<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
</head>
<body>
<form id="login_form" method="post" action="../authentificate_user">
	E-mail: <input type="text" name="email"><br>
	Heslo: <input type="text" name="password"><br>
	<input type="submit">
</form>

</body>

</html>