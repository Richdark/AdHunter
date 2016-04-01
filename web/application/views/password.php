<div class="default-form">
	<div class="c">
		<?php if (isset($invalid_fields['email'])) {
			echo '<h3>Obnova hesla neprebehla úspešne</h3>';
		} ?>
		<form  method="post" action="../password/">
			<span class="with_hint">
				<input type="text" name="email" placeholder="E-mailová adresa, s ktorou ste sa registrovali" value=""<?php if (isset($invalid_fields['email'])) { echo ' class = "invalid"'; } ?>>
				<?php
				if (isset($invalid_fields['email']))
				{
					if ($invalid_fields['email'] == 'invform')
					{
						echo '<p class="warning">Emailová adresa nie je v správnom tvare!</p>';
					}
					elseif ($invalid_fields['email'] == 'notfound')
					{
						echo '<p class="warning">Neevidujeme účet registrovaný pod takouto adresou!</p>';
					}
				}
				?>
			</span><br />
			<input type="submit" name="send" value="Obnoviť heslo">
		</form>
	</div>
</div>

<script src="<?php echo assets_url(); ?>js/main.js"></script>