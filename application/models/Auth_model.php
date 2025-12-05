<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    // Cek user untuk login
    public function get_user_by_username($username)
    {
        // Ambil data user berdasarkan username
        // Kita tidak cek password di query SQL demi keamanan (Hash verification di Controller)
        return $this->db->get_where('users', ['username' => $username])->row_array();
    }

    // Daftar user baru (Petani only)
    public function register_petani($data)
    {
        // Paksa role jadi 'petani' agar tidak ada yang iseng inject role 'dinas'
        $data['role'] = 'petani';
        
        return $this->db->insert('users', $data);
    }
}