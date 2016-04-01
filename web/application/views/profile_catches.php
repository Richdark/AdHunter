<?php echo $profile_menu; ?>

<div class="profile_content">
	<table>
		<tr>
			<th>Poloha</th>
			<th>Typ</th>
			<th>Nahraný</th>
			<th>Odkiaľ</th>
			<th>Poznámka</th>
		</tr>
	<?php
	foreach ($catches_list->result() as $catch)
	{
		$type = ($catch->type == 'w')? 'web' : 'aplikácia';

		echo '
		<tr>
			<td><a href="'. assets_url(). 'pics/'. $catch->filename. '">'. number_format(round($catch->x, 3), 3). ', '. number_format(round($catch->y, 3), 3). '</a></td>
			<td>'. $catch->title. '</td>
			<td>'. $catch->uploaded. '</td>
			<td>'. $type. '</td>
			<td><em>'. $catch->comment. '</em></td>
		</tr>
		';
	}
	?>
	</table>
</div>

<div class="clear"></div>
<script src="<?php echo assets_url(); ?>js/main.js"></script>
