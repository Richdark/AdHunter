<?php

class Ulovok_model extends CI_Model
{
	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}

	function get_ulovky() {
		// $query = $this->db->get('ulovky');
		$query = $this->db->query('SELECT nazov_suboru, nahrany, model_telefonu, typ, komentar, X(suradnice) AS x, Y(suradnice) AS y FROM ulovky;');
		return $query->result();
	}

	function get_ulovok_by_id($id)
	{
		// where id = $id
		$this->db->where('id', $id);

		// samotny select z tabulky ulovky
		$query = $this->db->get('ulovky');

		// spustenie query a vratenie hodnoty funkciou
		return $query->result();
	}

	function save_ulovok($pouzivatel_id, $reklama_id, $suradnice, $nazov_suboru, $model_telefonu, $typ, $komentar)
	{
		$data = array(
			'pouzivatel_id' => $pouzivatel_id,
			'reklama_id'    => $reklama_id,
			'nazov_suboru'  => $nazov_suboru,
			'typ'           => $typ,
			'komentar'      => $komentar
		);

		// ak by to bolo v $data, tak by to CI vyescapoval
		$this->db->set('nahrany', 'NOW()', false);
		$this->db->set('vytvoreny', 'NOW()', false);
		$this->db->set('suradnice', $suradnice, false);

		// insert into ...
		$this->db->insert('ulovky', $data);
	}
}

?>