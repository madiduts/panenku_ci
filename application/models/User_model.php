<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    // Cari user berdasarkan email
    public function get_user_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }
}