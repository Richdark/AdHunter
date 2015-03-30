<?php

class Online_user_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Modelova funkcia, ktora prida konkretne zariadenie do zoznamu prihlasenych zariadeni
	 * @param int - id unikatny identifikator v databaze
	 * @param int user_id id pouzivatela
	 * @param string uid jedinecny identifikator zariadenia
	 * @param char type 'w' alebo 'm'
	*/
	function login_user($id, $user_id, $uid, $type)
	{
		$data = array(
			'id'       => $id,
			'user_id'  => $user_id,
			'uid'      => $uid,
			'type'     => $type
		);

		// insert into ...
		$this->db->insert('online_users', $data);
	}

	function logout_user($uid, $type)
	{
		$this->db->where('uid', $uid);
		$this->db->where('type', $type);

		// delete from ...
		$this->db->delete('online_users');
	}

	function is_logged($uid, $type)
	{
		$this->db->where('uid', $uid);
		$this->db->where('type', $type);
		$query = $this->db->get('online_users');

		return $query->num_rows() > 0 ? true : false;
	}

	function get_user_id($uid)
	{
		$query = $this->db->get_where('online_users', array('uid' => $uid))->result();

		if (count($query) > 0)
		{
			return $query[0]->user_id;
		}
		else
		{
			return false;
		}
	}

	function get_user_info($uid)
	{
		$this->db->select('user_id, email')->from('online_users')->join('users', 'online_users.user_id = users.id')->where('uid', $uid);
		$query = $this->db->get()->result();

		if (count($query) > 0)
		{
			return $query[0];
		}
		else
		{
			return false;
		}
	}
}

?>