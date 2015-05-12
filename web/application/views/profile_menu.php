<nav class="profile">
	<ul>
		<li><a href="<?php echo root_url(); ?>profile/"<?php if ($this->uri->segment(2) == '') { echo ' class="current"'; } ?>>Nastavenia účtu</a></li>
		<li><a href="<?php echo root_url(); ?>profile/catches/"<?php if ($this->uri->segment(2) == 'ads') { echo ' class="current"'; } ?>>Moje úlovky</a></li>
		<li><a href="<?php echo root_url(); ?>profile/badges/"<?php if ($this->uri->segment(2) == 'badges') { echo ' class="current"'; } ?>>Moje ocenenia</a></li>
		<li><a href="<?php echo root_url(); ?>profile/statistics/"<?php if ($this->uri->segment(2) == 'statistics') { echo ' class="current"'; } ?>>Štatistiky</a></li>
	</ul>
</nav>