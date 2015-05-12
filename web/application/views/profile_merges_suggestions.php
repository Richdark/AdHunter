<?php echo $profile_menu; ?>

<script src="<?php echo assets_url() ?>js/main.js"></script>
<div class="profile_content">
	<h2>Sú na obrázkoch rovnaké nosiče*?</h2>
	<div id="compare">
		<span class="left" data-id="<?php echo $candidates[0]['id']; ?>">
			<img src="<?php echo assets_url(). 'pics/'. $candidates[0]['filename']; ?>" />
		</span>
		<span class="right" data-id="<?php echo $candidates[1]['id']; ?>">
			<img src="<?php echo assets_url(). 'pics/'. $candidates[1]['filename']; ?>" />
		</span>
		<div style="clear: both;"></div>
		<div class="verdicts">
			<a href="javascript:void()" onclick="resolve_merge_candidates(5)" class="t">určite áno</a>
			<a href="javascript:void()" onclick="resolve_merge_candidates(4)" class="t">asi áno</a>
			<a href="javascript:void()" onclick="resolve_merge_candidates(3)" class="n">neviem</a>
			<a href="javascript:void()" onclick="resolve_merge_candidates(2)" class="f">asi nie</a>
			<a href="javascript:void()" onclick="resolve_merge_candidates(1)" class="f">určite nie</a>
		</div>
		<div class="note">* pod nosičom rozumieme konštrukciu bilbordu/reklamy - je potrebné označiť rovnaké reklamy na základe ich <strong>zhodnej polohy</strong>, nie rovnakého "typu" reklamy</div>
	</div>
</div>

<div class="clear"></div>
