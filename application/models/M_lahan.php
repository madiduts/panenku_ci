<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_lahan extends CI_Model {

    // Ambil semua lahan milik satu petani spesifik
    public function get_by_petani($user_id)
    {
        // Query: SELECT * FROM lahan WHERE users_id = $user_id
        $this->db->where('users_id', $user_id);
        return $this->db->get('lahan')->result_array();
    }

    // Hitung total luas lahan (Untuk Card Dashboard)
    // English Term: Aggregation Query
    public function sum_luas_lahan($user_id)
    {
        $this->db->select_sum('luas_lahan');
        $this->db->where('users_id', $user_id);
        $query = $this->db->get('lahan');
        return $query->row()->luas_lahan; // Return single value (e.g., 150)
    }

    // Input lahan baru
    public function insert($data)
    {
        return $this->db->insert('lahan', $data);
    }
}