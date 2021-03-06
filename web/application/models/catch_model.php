<?php

class Catch_model extends CI_Model
{
	/**
	 * @var float $max_distance Maximum distance of possibly same catches in metres
	 */
	private $max_distance = 100;

	// toto je povinna "sablona" konstruktora modelu
	function __construct()
	{
		parent::__construct();
	}

	function get_all($user_id) {
		$this->db->select('id, (user_id='. $this->db->escape($user_id). ') AS privileged, filename, uploaded, phone_model, type, comment, X(coordinates) AS x, Y(coordinates) AS y, state, backing_type_id, owner_id');
		$this->db->from('catches');
		$query = $this->db->get();

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
		//    '0' => not approved merge
		//    '1' => approved merge

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
			'owner_id'        => $owner_id,
			'backing_type_id' => $backing_type
		);

		$this->db->where('id', $catch_id);
		$this->db->update('catches', $data); 
	}

	function move_catch($catch_id, $coordinates)
	{
		$this->db->where('id', $catch_id);
		$this->db->set('coordinates', $coordinates, false);
		$this->db->update('catches');
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

		$this->add_or_update_medal($user_id, 'adhunter');
		
		if (!(empty($owner_id)))
			$this->add_or_update_medal($user_id, 'owner', $owner_id);
		
		if (!(empty($backing_type)))
			$this->add_or_update_medal($user_id, 'backing', $backing_type);

		if ($type == 'w')
		{
			$this->add_or_update_medal($user_id, 'web');
		}
		else
		{
			$this->add_or_update_medal($user_id, 'mobile');
		}
	}

	/**
	 * Add new or update already existing medal reference
	 *
	 * @param int $user_id ID of user
	 * @param string $type Medal type
	 * @param int $type_id ID of "type" (owner ID or backing type ID or ...)
	*/
	function add_or_update_medal($user_id, $type, $type_id = 0)
	{
		$this->db->select('id')->from('medals')->where('user_id', $user_id)->where('type', $type)->where('type_id', $type_id);
		
		$query = $this->db->query("SELECT id FROM medals WHERE user_id = $user_id AND type = '$type' AND type_id = $type_id");
		$row = $query->row();
		$medal_exists = (gettype($row) == 'array')? false : true;

		if ($type == 'adhunter')
		{
			$query = $this->db->query("SELECT COUNT(*) AS count FROM catches WHERE user_id = $user_id");
			$row = $query->row();
			$level = $this->get_medal_level('adhunter', $row->count);
			
			if ($medal_exists)
			{
				$final_query = "UPDATE medals SET level = $level WHERE user_id = $user_id AND type = 'adhunter'";
			}
			else
			{
				$final_query = "INSERT INTO medals VALUES(NULL, $user_id, 'adhunter', 0, $level)";
			}
		}
		elseif ($type == 'web' or $type == 'mobile')
		{
			$catch_type = ($type == 'web')? 'w' : 'm';

			$query = $this->db->query("SELECT COUNT(*) AS count FROM catches WHERE user_id = $user_id AND type = '$catch_type'");
			$row = $query->row();
			$level = $this->get_medal_level($type, $row->count);

			if ($medal_exists)
			{
				$final_query = "UPDATE medals SET level = $level WHERE user_id = $user_id AND type = '$type'";
			}
			else
			{
				$final_query = "INSERT INTO medals VALUES(NULL, $user_id, '$type', 0, $level)";
			}
		}
		elseif ($type == 'owner')
		{
			$query = $this->db->query("SELECT COUNT(*) AS count FROM catches WHERE user_id = $user_id AND owner_id = $type_id");
			$row = $query->row();
			$level = $this->get_medal_level('owner', $row->count);
			
			if ($medal_exists)
			{
				$final_query = "UPDATE medals SET level = $level WHERE user_id = $user_id AND type = 'owner' AND type_id = $type_id";
			}
			else
			{
				$final_query = "INSERT INTO medals VALUES(NULL, $user_id, 'owner', $type_id, $level)";
			}
		}
		elseif ($type == 'backing')
		{
			$query = $this->db->query("SELECT COUNT(*) AS count FROM catches WHERE user_id = $user_id AND backing_type_id = $type_id");
			$row = $query->row();
			$level = $this->get_medal_level('backing', $row->count);
			
			if ($medal_exists)
			{
				$final_query = "UPDATE medals SET level = $level WHERE user_id = $user_id AND type = 'backing' AND type_id = $type_id";
			}
			else
			{
				$final_query = "INSERT INTO medals VALUES(NULL, $user_id, 'backing', $type_id, $level)";
			}
		}

		// add or update medal
		$query = $this->db->query($final_query);
	}

	/**
	 * Get level for particular medal type and number of catches
	 *
	 * @param string $type Medal type
	 * @param int $count Number of added catches of specified type
	 *
	 * @return int Medal level
	*/
	function get_medal_level($type, $count)
	{
		$level_counts = array(
			'adhunter' => array(10, 20, 35, 60, 100, 200, 400),
			'web'      => array(20, 50, 100, 200),
			'mobile'   => array(20, 50, 100, 200),
			'owner'    => array(10, 20, 50),
			'backing'  => array(10, 20, 50)
		);

		$level = 0;
		$level_set = false;

		for ($i = 0; $i < count($level_counts[$type]); $i++)
		{
			$level++;

			if ($count < $level_counts[$type][$i])
			{
				$level_set = true;

				break;
			}
		}

		return ($level_set)? $level : ($level + 1);
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

	/**
	 * Get possible catch duplicates by their mutual distance
	 *
	 * @return array Array of possible duplicates
	*/
	function get_merge_candidates()
	{
		// 1°    ~= 111.325km
		// 1km   ~= 1 / 111.325° = 0.008982708°
		// 100m  ~= 0.000898271°
		
		// get number of catches
		$count = $this->db->select('COUNT(*) AS total')->from('catches')->get()->result()[0]->total;

		do
		{
			$candidate_id = rand(1, $count);
			$query        = $this->db->query("SELECT id, user_id, owner_id, backing_type_id, filename FROM catches WHERE id = ". $candidate_id. " UNION SELECT c2.id, c2.user_id, c2.owner_id, c2.backing_type_id, c2.filename FROM catches AS c1, catches AS c2 WHERE GLength(LineString(c1.coordinates, c2.coordinates)) < ". $this->max_distance_degrees(). " AND c1.id = ". $candidate_id. " AND c2.id != ". $candidate_id);
			$candidates   = $query->result();
		}
		while (count($candidates) <= 1);

		return $candidates;
	}

	 /**
     * Resolve merge suggestions
     *
     * @param integer $user_id ID of user who resolved this merge suggestion
     * @param integer $c1 ID of first merge candidate
     * @param integer $c2 ID of second merge candidate
     * @param string $verdict 1 if images are the same, 0 otherwise
     */
    public function resolve_merge_candidates($user_id, $c1, $c2, $verdict)
    {
    	$c1 = (is_numeric($c1))? $c1 : -1;
    	$c2 = (is_numeric($c2))? $c2 : -1;

    	// same or not same - no other option possible :)
    	if (in_array($verdict, array('1', '2', '3', '4', '5')))
    	{
    		// are they close enough?
	        $distance = $this->db->query("SELECT GLength(LineString(c1.coordinates, c2.coordinates)) AS distance FROM catches AS c1, catches AS c2 WHERE c1.id = ". $c1. " AND c2.id = ". $c2)->result();
	        
	        if (count($distance) == 1)
	        {
	        	$distance = $distance[0]->distance;

		        // save verdict
		        if ($distance < $this->max_distance_degrees())
		        {
		        	$this->db->query("INSERT INTO merge_suggestions VALUES(NULL, $user_id, $c1, $c2, '$verdict', NOW())");
		        }
	        }
    	}
    }

    /**
     * Return maximum distance in degrees
     */
    private function max_distance_degrees()
    {
    	return (1 / 111325) * $this->max_distance;
    }
}

?>