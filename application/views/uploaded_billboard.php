<?php

$base_url   = base_url();
$assets_url = $base_url. 'assets/';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Upload billboardu</title>
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/reset.css">
	<link rel="stylesheet" href="<?php echo $assets_url; ?>css/style.css">
	<script src="<?php echo $assets_url; ?>js/jquery-2.1.1.min.js"></script>
</head>
<body class="uloaded-billboard">
	<h1>Billboard bol úspešne nahraný na server</h1>
	<a href="<?php echo $base_url; ?>billboards/">Späť</a>
</body>
</html>