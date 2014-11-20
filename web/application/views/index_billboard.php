<?php

$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pridanie nového billboardu</title>
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
	<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
</head>
<body class="add-billboard">
	<a href="<?php echo $base_url; ?>billboards/show/">Mapa s billboardmi</a><br>
	<a href="<?php echo $base_url; ?>billboards/add/">Pridanie bodu na mapu</a><br>
	<a href="<?php echo $base_url; ?>login_registration/login/">Prihlasenie</a><br>
	<a href="<?php echo $base_url; ?>login_registration/register/">Registracia</a><br>
</body>
</html>