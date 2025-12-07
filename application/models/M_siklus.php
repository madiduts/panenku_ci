<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_siklus extends CI_Model {

    public function get_active_siklus($user_id)
    {
        $this->db->select('siklus_tanam.*, lahan.lokasi_desa, ref_komoditas.nama_komoditas');
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        $this->db->where('lahan.user_id', $user_id); // Pastikan user_id (bukan users_id)
        $this->db->where('siklus_tanam.status_aktif', 1);
        return $this->db->get()->result_array();
    }

    public function count_active_by_petani($user_id)
    {
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->where('lahan.user_id', $user_id);
        $this->db->where('siklus_tanam.status_aktif', 1);
        return $this->db->count_all_results();
    }

    // [BARU] Tambahkan fungsi ini untuk insert data
    public function insert($data)
    {
        return $this->db->insert('siklus_tanam', $data);
    }
}