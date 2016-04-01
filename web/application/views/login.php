<div class="default-form">
	<div class="c">
		<?php if (!(empty($invalid_fields))) { echo '<h3>Prihlásenie neprebehlo úspešne</h3>'; } ?>
		<form  method="post" action="">
			<input type="text" name="email" placeholder="E-mail" value=""<?php if (array_search('email', $invalid_fields) !== false) { echo ' class = "invalid"'; } ?>><br>
			<input type="password" name="password" placeholder="Heslo" value=""<?php if (array_search('password', $invalid_fields) !== false) { echo ' class = "invalid"'; } ?>><br>
			<input type="submit" name="send" value="Prihlásiť" /><br />
			Zabudli ste heslo? <a href="<?php echo root_url(). 'auth/password/'; ?>">Tu</a> si ho môžete obnoviť.
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>