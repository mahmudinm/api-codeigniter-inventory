<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
	public function check_login($username, $password)
	{
	    $this->db->where('username', $username);
	    $this->db->where('password', $password);
	    $user = $this->db->get('users')->row_array();
	    return $user;
	}

	public function register($data)
	{
	    return $this->db->insert('users', $data);
	}
    
}
