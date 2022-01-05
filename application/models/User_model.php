<?php
class User_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }
    public function getUserdata()
    {
            $this->load->database();
            $q = $this->db->query("select * from users");
               return $q->result();
               
    }
    function fetch_all_event(){
	
	  $query=$this->db->get('events');
        return $query->result();
    }
}