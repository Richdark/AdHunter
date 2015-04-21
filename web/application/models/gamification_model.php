<?php

class Gamification_model extends CI_Model {

    // toto je povinna "sablona" konstruktora modelu
    function __construct() {
        parent::__construct();
    }

    function get_all_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,u.name as name, 
            u.surname as surname,u.id as uid,c.type as source, c.backing_type_id as type 
            FROM catches c JOIN users u on c.user_id=u.id WHERE u.id= ' . $id);
        return $query->result();
    }

    function get_types_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,c.backing_type_id as type, 
            bt.title as title FROM catches c JOIN users u on c.user_id=u.id 
            JOIN backing_types bt on bt.id=c.backing_type_id WHERE u.id= ' . $id . ' 
            GROUP BY c.backing_type_id ORDER BY bilboards DESC');
        return $query->result();
    }

    function get_sources_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards,c.type as source FROM catches c '
                . 'JOIN users u on c.user_id=u.id WHERE u.id= ' . $id . ' GROUP BY c.type ORDER BY bilboards DESC');
        return $query->result();
    }

    function get_owners_by_id($id) {
        $query = $this->db->query('SELECT count(c.id) as bilboards, c.owner_id as owner, o.name as name '
                . 'FROM catches c JOIN users u on c.user_id=u.id JOIN owners o ON c.owner_id=o.id '
                . 'WHERE u.id= ' . $id . ' GROUP BY c.owner_id ORDER BY bilboards DESC');
        return $query->result();
    }
    
}

?>