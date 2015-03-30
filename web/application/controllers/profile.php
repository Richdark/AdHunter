<?php

class Profile extends MY_Controller
{
	public function index()
	{
		$vars['page_title']   = 'Nastavenia účtu';
		$vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);
		$this->load->template('profile_main', $vars);
	}
}

?>