<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    // Ambil user berdasarkan email
    public function get_user_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    // Register user baru
    public function register($data)
    {
        return $this->db->insert('users', $data);
    }
}