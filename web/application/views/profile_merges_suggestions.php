<?php echo $profile_menu; ?>

<script src="<?php echo assets_url() ?>js/main.js"></script>
<div class="profile_content">
	<h2>Sú na obrázkoch rovnaké bilbordy?</h2>
	<div id="compare">
		<span class="left" data-id="<?php echo $candidates[0]['id']; ?>">
			<img src="<?php echo assets_url(). 'pics/'. $candidates[0]['filename']; ?>" />
		</span>
		<span class="right" data-id="<?php echo $candidates[1]['id']; ?>">
			<img src="<?php echo assets_url(). 'pics/'. $candidates[1]['filename']; ?>" />
		</span>
		<div style="clear: both;"></div>
		<div class="verdicts">
			<a href="javascript:void()" onclick="resolve_merge_candidates(1)" class="t">áno</a>
			<a href="javascript:void()" onclick="resolve_merge_candidates(0)" class="f">nie</a>
		</div>
	</div>
</div>

<div class="clear"></div>
