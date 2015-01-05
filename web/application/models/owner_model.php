<?php

class Owner_model extends CI_Model
{
	function get_all($order = 'asc', $sortby = 'name')
	{
		$order  = in_array($order, array('asc', 'desc')) ? $order : 'asc';
		$sortby = in_array($sortby, array('id', 'name')) ? $sortby : 'name';

		/**
		 * @todo Toto zmazat az sa stlpce a tabulky prepisu do anglictiny
		*/
		$sortby = ($sortby == 'name') ? 'name' : $sortby;

		$this->db->order_by($sortby, $order);
		
		$query = $this->db->get('owners');

		return $query->result();
	}
}

?>