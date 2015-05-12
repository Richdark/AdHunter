<?php

class Gamification_model extends CI_Model {

    // toto je povinna "sablona" konstruktora modelu
    function __construct() {
        parent::__construct();
    }

    function get_all_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,u.name as name, 
            u.surname as surname,u.id as uid,c.type as source, c.backing_type_id as type 
            FROM catches c JOIN users u on c.user_id=u.id WHERE u.id= "' . $id.'"');
        return $query->result();
    }

    function get_level_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,u.name as name, 
            u.surname as surname,u.id as uid,c.type as source, c.backing_type_id as type 
            FROM catches c JOIN users u on c.user_id=u.id WHERE u.id= "' . $id.'"');

        $b = $query->result()[0]->bilboards;

        switch ($b) {
            case ($b >= 0 && $b < 10):
                $level= "Level: Nováčik<br />";
                $to_next_level = 10 - $b;
                break;
            case ($b >= 10 && $b < 20):
                $level= "Level: Začiatočník<br />";
                $to_next_level = 20 - $b;
                break;
            case ($b >= 20 && $b < 35):
                $level= "Level: Špión<br />";
                $to_next_level = 35 - $b;
                break;
            case ($b >= 35 && $b < 60):
                $level= "Level: Lovec<br />";
                $to_next_level = 60 - $b;
                break;
            case ($b >= 60 && $b < 100):
                $level= "Level: Reformátor<br />";
                $to_next_level = 100 - $b;
                break;
            case ($b >= 100 && $b < 200):
                $level= "Level: Batman<br />";
                $to_next_level = 200 - $b;
                break;
            case ($b >= 200 && $b < 400):
                $level= "Level: Záchranca svojho okolia<br />";
                $to_next_level = 400 - $b;
                break;
            case ($b >= 400):
                $level= "Level: PROFESIONÁLNY ADHUNTER<br />";
                break;
        }
        return $level;
    }

    function get_types_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,c.backing_type_id as type, 
            bt.title as title FROM catches c JOIN users u on c.user_id=u.id 
            JOIN backing_types bt on bt.id=c.backing_type_id WHERE u.id= "' . $id . '" 
            GROUP BY c.backing_type_id ORDER BY bilboards DESC');
        return $query->result();
    }

    function get_sources_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,c.type as source FROM catches c '
                . 'JOIN users u on c.user_id=u.id WHERE u.id= "' . $id . '" GROUP BY c.type ORDER BY bilboards DESC');
        return $query->result();
    }

    function get_owners_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, c.owner_id as owner, o.name as name '
                . 'FROM catches c JOIN users u on c.user_id=u.id JOIN owners o ON c.owner_id=o.id '
                . 'WHERE u.id= "' . $id . '" GROUP BY c.owner_id ORDER BY bilboards DESC');
        return $query->result();
    }

    function get_activity_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, Cast(c.created as date) as catch_date '
                . 'FROM catches c JOIN users u on c.user_id=u.id WHERE u.id= "' . $id
                . '" and Cast(c.created as date)>DATE_SUB(Cast(CURDATE() as date),INTERVAL 30 DAY)'
                . ' GROUP BY catch_date ORDER BY catch_date ASC LIMIT 7');
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
                . ' GROUP BY user ORDER BY bilboards DESC LIMIT 10');
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