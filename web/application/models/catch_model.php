<?php

class Catch_model extends CI_Model
{
	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}

	function get_all($user_id) {
		$query = $this->db->query('SELECT id, (user_id='.$user_id.') AS privileged, filename, uploaded, phone_model, type, comment, X(coordinates) AS x, Y(coordinates) AS y, state, backing_type_id, owner_id FROM catches');
		return $query->result();
	}

	/**
	 * Merge catches from $merged_arr into $main
	 *
	 * @param int $user_id ID of user performing merging
	 * @param int $main ID of catch that other catches will be merged into
	 * @param array $merged_arr Array of catch IDs that will be merged into $main
	*/
	function merge_catches($user_id, $main, $merged_arr)
	{
		// merge_state:
		//    '0' => not approved
		//    '1' => approved

		$updated = 0;

		foreach ($merged_arr as $merged)
		{
			if ($merged != -1)
			{
				// check if weren't already merged
				$query = $this->db->get_where('catches', array('id' => $merged, 'state' => '0'));

				if (count($query->result()) == 0)
				{
					$merger = array(
						'merged_from_id' => $merged,
						'merged_into_id' => $main,
						'user_id'        => $user_id,
						'merge_state'    => '0'
					);

					$this->db->insert('catch_merges', $merger);

					// update merged catch state
					$this->db->where('id', $merged);
					$this->db->update('catches', array('state' => '0'));

					$updated++;
				}
			}
		}

		return $updated;
	}

	function update_catch($catch_id, $comment, $owner_id, $backing_type)
	{
		$data = array(
			'comment'         => $comment,
			'owner_id' => $owner_id,
			'backing_type_id' => $backing_type
		);

		$this->db->where('id', $catch_id);
		$this->db->update('catches', $data); 
	}

	function delete_catch($catch_id)
	{
		$this->db->where('id', $catch_id);
		$this->db->delete('catches'); 
	}

	function save_catch($user_id, $ad_id, $coordinates, $filename, $phone_model, $type, $comment, $backing_type, $owner_id)
	{
		$data = array(
			'user_id'         => $user_id,
			'ad_id'           => $ad_id,
			'filename'        => $filename,
			'type'            => $type,
			'comment'         => $comment,
			'state'           => '1',
			'backing_type_id' => $backing_type,
			'owner_id'        => $owner_id
		);

		// ak by to bolo v $data, tak by to CI vyescapoval
		$this->db->set('uploaded', 'NOW()', false);
		$this->db->set('created', 'NOW()', false);
		$this->db->set('coordinates', $coordinates, false);

		// insert into ...
		$this->db->insert('catches', $data);
	}

	/**
	 * Get catches list by user ID
	 *
	 * @param int $user_id ID of user
	 *
	 * @return array Array of all user catches
	*/
	function get_catches_by_user_id($user_id)
	{
		$this->db->select('X(coordinates) AS x, Y(coordinates) AS y, filename, uploaded, type, comment, title');
		$this->db->from('catches')->join('backing_types', 'catches.backing_type_id = backing_types.id');
		$this->db->where('catches.user_id', $user_id)->order_by('catches.id', 'DESC');

		return $this->db->get();
	}
}

?>