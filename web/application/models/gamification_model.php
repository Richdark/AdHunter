<?php

class Gamification_model extends CI_Model {

    // toto je povinna "sablona" konstruktora modelu
    function __construct() {
        parent::__construct();
    }

    /**
     * Get user's medals
     *
     * @param int $user_id ID of user
     *
     * @return array Array of all user's medals
    */
    function get_medals_by_user($user_id)
    {
        $medals = array('regular' => array(), 'owner' => array(), 'backing' => array());

        $query = $this->db->query("SELECT type, level FROM medals WHERE user_id = $user_id AND type IN('adhunter', 'web', 'mobile')");

        foreach ($query->result() as $row)
        {
            $medals['regular'][$row->type] = $row->level;
        }

        $query = $this->db->query("SELECT name, level FROM medals LEFT JOIN owners ON type_id = owners.id WHERE user_id = $user_id AND type = 'owner'");

        foreach ($query->result() as $row)
        {
            array_push($medals['owner'], array('name' => $row->name, 'level' => $row->level));
        }

        $query = $this->db->query("SELECT title, level FROM medals LEFT JOIN backing_types ON type_id = backing_types.id WHERE user_id = $user_id AND type = 'backing'");

        foreach ($query->result() as $row)
        {
            array_push($medals['backing'], array('title' => $row->title, 'level' => $row->level));
        }

        return $medals;
    }

    function get_activity_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, Cast(c.created as date) as catch_date '
                . 'FROM catches c JOIN users u on c.user_id=u.id WHERE u.id= "' . $id
                . '" and Cast(c.created as date)>DATE_SUB(Cast(CURDATE() as date),INTERVAL 30 DAY)'
                . ' GROUP BY catch_date ORDER BY catch_date ASC LIMIT 5');
        return $query->result();
    }
    
    function get_best_activity_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, Cast(c.created as date) as catch_date '
                . 'FROM catches c JOIN users u on c.user_id=u.id WHERE u.id="' . $id
                . '" GROUP BY catch_date ORDER BY bilboards desc LIMIT 1');
        return $query->result();
    }

    function get_top_10($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, u.name as user '
                . 'FROM catches c JOIN users u on c.user_id=u.id '
                . ' GROUP BY user ORDER BY bilboards DESC LIMIT 3');
        return $query->result();
    }

    function get_order_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, u.name as user '
                . 'FROM catches c JOIN users u on c.user_id=u.id '
                . 'WHERE u.id= "' . $id . '" GROUP BY user');
        return $query->result();
    }

}

?>