<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_siklus extends CI_Model {

    public function get_active_siklus($user_id)
    {
        // Kita ambil lokasi_desa karena nama_lahan tidak ada di DB
        $this->db->select('siklus_tanam.*, lahan.lokasi_desa, lahan.kategori_lahan, ref_komoditas.nama_komoditas');
        $this->db->from('siklus_tanam');
        
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        
        // FIX: Filter berdasarkan user_id (bukan users_id)
        $this->db->where('lahan.user_id', $user_id); 
        
        // FIX: Di DB status_aktif itu TINYINT (1 = aktif, 0 = tidak)
        $this->db->where('siklus_tanam.status_aktif', 1); 
        
        return $this->db->get()->result_array();
    }
}