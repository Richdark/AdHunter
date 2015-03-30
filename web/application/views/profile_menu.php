<nav class="profile">
	<ul>
		<li><a href="<?php echo root_url(); ?>"<?php if ($this->uri->segment(2) == '') { echo ' class="current"'; } ?>>Nastavenia účtu</a></li>
		<li><a href="<?php echo root_url(); ?>ads/"<?php if ($this->uri->segment(2) == 'ads') { echo ' class="current"'; } ?>>Moje úlovky</a></li>
		<li><a href="<?php echo root_url(); ?>badges/"<?php if ($this->uri->segment(2) == 'badges') { echo ' class="current"'; } ?>>Moje odznaky</a></li>
	</ul>
</nav>