<?php

class Registration_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_password_for_login($email)
	{
		$query = $this->db->query("SELECT heslo, salt FROM pouzivatelia WHERE pouzivatelia.email = '$email'");
		
		return $query->result();
	}

	function save_user($id,$name,$surname,$email,$password,$salt)
	{
		$data = array(
			'id'         => $id,
			'meno'       => $name,
			'priezvisko' => $surname,
			'email'      => $email,
			'heslo'      => $password,
			'salt'       => $salt
		);

		$this->db->insert('pouzivatelia',$data);
	}
}

?>