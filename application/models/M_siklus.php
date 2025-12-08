<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_siklus extends CI_Model {

    // 1. Ambil Data Siklus Aktif (Updated dengan Luas & Kategori)
    public function get_active_siklus($user_id)
    {
        $this->db->select('
            siklus_tanam.*, 
            lahan.lokasi_desa, 
            lahan.luas_lahan, 
            lahan.kategori_lahan, 
            ref_komoditas.nama_komoditas
        ');
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        
        $this->db->where('lahan.user_id', $user_id);
        $this->db->where('siklus_tanam.status_aktif', 1);
        
        return $this->db->get()->result_array();
    }

    // 2. Hitung Jumlah (Untuk Dashboard)
    public function count_active_by_petani($user_id)
    {
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->where('lahan.user_id', $user_id);
        $this->db->where('siklus_tanam.status_aktif', 1);
        return $this->db->count_all_results();
    }

    // 3. Insert Siklus Baru
    public function insert($data)
    {
        return $this->db->insert('siklus_tanam', $data);
    }

    // 4. [BARU] Insert Riwayat Pupuk
    public function insert_pupuk($data) 
    {
        return $this->db->insert('riwayat_pupuk', $data);
    }

    // 5. [BARU] Insert Laporan Hama
    public function insert_hama($data) 
    {
        return $this->db->insert('laporan_hama', $data);
    }
}