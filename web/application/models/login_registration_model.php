<?php

class Ulovok_model extends CI_Model
{
	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}

	function get_password_for_login($email){

		$this->db->where('email', $email);

		// samotny select z tabulky ulovky
		$query = $this->db->get('pouzivatelia');

		// spustenie query a vratenie hodnoty funkciou
		return $query->result();
	}

	function save_user($id,$name,$surname,$email,$password,$salt){
	$data = array(
			'id' 		      => $id,
			'meno'  		  => $name,
			'priezvisko'	  => $surname,
			'email'           => $email,
			'heslo'           => $password,
			'salt'           => $salt
		);

	$this->db->insert('pouzivatelia',$data);
	}


}
?>