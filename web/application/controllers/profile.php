<?php

class Profile extends MY_Controller
{
	/**
	 * User account settings
	*/
	public function index()
	{
		$vars['page_title']   = 'Nastavenia účtu';
		$vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

		// form was sent
		if (isset($_POST['send']))
		{
			$name    = $_POST['name'];
			$surname = $_POST['surname'];

			$this->load->model('User_model');
			$this->User_model->set_user_info($this->user->id, $name, $surname);

			$vars['success'] = true;
			$vars['name']    = $this->user->name    = $name;
			$vars['surname'] = $this->user->surname = $surname;
		}
		else
		{
			$vars['name']    = $this->user->name;
			$vars['surname'] = $this->user->surname;
		}

		$this->load->template('profile_main', $vars);
	}

	/**
	 * User added catches list
	*/
	public function catches()
	{
		$vars['page_title']   = 'Moje úlovky';
		$vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

		$this->load->model('Catch_model');
		$vars['catches_list'] = $this->Catch_model->get_catches_by_user_id($this->user->id);

		$this->load->template('profile_catches', $vars);
	}
}

?>