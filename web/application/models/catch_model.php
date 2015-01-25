<?php

class Catch_model extends CI_Model
{
	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}

	function get_all() {
		$query = $this->db->query('SELECT filename, uploaded, phone_model, type, comment, X(coordinates) AS x, Y(coordinates) AS y FROM catches');
		return $query->result();
	}

	function get_catch_by_id($id)
	{
		// where id = $id
		$this->db->where('id', $id);

		// samotny select z tabulky catches
		$query = $this->db->get('catches');

		// spustenie query a vratenie hodnoty funkciou
		return $query->result();
	}

	function save_catch($user_id, $ad_id, $coordinates, $filename, $phone_model, $type, $comment)
	{
		$data = array(
			'user_id'     => $user_id,
			'ad_id'       => $ad_id,
			'filename'    => $filename,
			'type'        => $type,
			'comment'     => $comment
		);

		// ak by to bolo v $data, tak by to CI vyescapoval
		$this->db->set('uploaded', 'NOW()', false);
		$this->db->set('created', 'NOW()', false);
		$this->db->set('coordinates', $coordinates, false);

		// insert into ...
		$this->db->insert('catches', $data);
	}
}

?>