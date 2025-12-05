<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    // Fungsi untuk cek user di tabel 'users'
    public function check_login($username) {
        // Ambil data user berdasarkan username
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        
        // Jika data ditemukan (baris > 0)
        if ($query->num_rows() > 0) {
            return $query->row(); // Kembalikan objek user
        } else {
            return FALSE; // User tidak ditemukan
        }
    }
}