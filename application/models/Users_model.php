<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

    function authenticate($email , $password){
        $query = $this->db->query("SELECT *  FROM `admintable` where email='$email' AND password = '$password' ");
        if ($query->num_rows() > 0) {
            return $query->result();
        }
            return 0;

    }
    public function delete_user($resident_id) {
        $this->db->delete('resident', array('resident_id' => $resident_id));

        if ($this->db->affected_rows() > 0) {
            return true; // Deletion successful
        } else {
            return false; // No rows deleted
        }
    }

    function fetch_all($table){

        $query = $this->db->query("SELECT * FROM $table   " );
        return $query->result();

    }

    function set_update($table,$id,$data)
		{

			$this->db->set($data);
			$this->db->where('resident_id', $id);
			$this->db->update($table);
			$afftectedRows = $this->db->affected_rows();
	        if ($afftectedRows > 0) {
	            return TRUE;
	        } else {
	            return FALSE;
	        }

		}

		public function getSettings() {
					$query = $this->db->get_where('resident', array('resident_id' => 1));
					return $query->row_array();
				}

    



}

?>
