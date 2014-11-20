<?php

class Login_registration_model extends CI_Model
{
	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}
/**
* Modelova funkcia na ziskavanie hashovaneho hesla z databazy na zaklade pouzivatelskeho emailu
* @param string email pouzivatelsky email na zaklade ktoreho vyhladame heslo
* @return string result vysledok query do databazy
*/
	function get_password_for_login($email){

	$query = $this->db->query("SELECT heslo,salt FROM pouzivatelia WHERE pouzivatelia.email='$email'");
		// spustenie query a vratenie hodnoty funkciou
		return $query->result();
		// spustenie query a vratenie hodnoty funkciou
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