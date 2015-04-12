<?php

class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Modelova funkcia na ziskavanie hashovaneho hesla z databazy na zaklade pouzivatelskeho emailu
	 * @param string email pouzivatelsky email na zaklade ktoreho vyhladame heslo
	 * @return string result vysledok query do databazy
	*/
	function get_user_by_login($email)
	{
		$query = $this->db->query("SELECT id, password, salt FROM users WHERE users.email = '$email'");
		
		return $query->result();
	}

	/**
	 * Check if provided email isn't already registered
	 * @param string $email Provided email address
	 * @return boolean True if email is registered, false otherwise
	 */
	function email_exists($email)
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		
		if ($this->db->count_all_results() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Modelova funkcia nukladajuca uzivatela na zaklade zadanych parametrov
	 * @param int - id unikatny identifikator v databaze
	 * @param string name realne meno pouzivatela
	 * @param string surname realne priezvisko pouzivatela
	 * @param string email pouzivatelsky email
	 * @param string password hashovane pouzivatelske heslo
	 * @param string salt snahodne vygenerovany retazec unikatny pre pouzivatela
	*/
	function save_user($id,$name,$surname,$email,$password,$salt)
	{
		$data = array(
			'id'       => $id,
			'name'     => $name,
			'surname'  => $surname,
			'email'    => $email,
			'password' => $password,
			'salt'     => $salt
		);

		$this->db->insert('users', $data);
	}

	/**
	 * Set optional user info
	 * @param integer $id ID of user whose info is being modified
	 * @param string $name User's first name
	 * @param string $surname User's last name
	 */
	function set_user_info($id, $name, $surname)
	{
		$this->db->where('id', $id);
		$this->db->update('users', array('name' => $name, 'surname' => $surname));
	}
}

?>