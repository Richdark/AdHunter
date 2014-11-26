<body class="show-billboards">
<!-- Include menu -->
<?php include 'menu.php';?>
	<div id="panel">
		<input id="search" type="text" placeholder="Zadajte objekt alebo adresu">
	</div>

	<div id="map"></div>
	<div id="info-content">
		<div class="info">
			<!-- <h2 class="title"></h2> -->
			<img class="billboard" src="">
			<table>
				<tr>
					<td>Vlastník: </td><td class="provider"></td>
				</tr>
				<tr>
					<td>Nahrané: </td><td class="uploaded"></td>
				</tr>
			</table>
		</div>
	</div>

	<script src="<?php echo assets_url(); ?>js/main.js"></script>
</body>