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
	function get_password_for_login($email)
	{
		$query = $this->db->query("SELECT password, salt FROM users WHERE users.email = '$email'");
		
		return $query->result();
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
}

?>