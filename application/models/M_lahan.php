<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_lahan extends CI_Model {

    // 1. Ambil Lahan + Status Tanamnya (Join Table)
    public function get_lahan_details($user_id)
    {
        $this->db->select('
            lahan.lahan_id, 
            lahan.luas_lahan, 
            lahan.lokasi_desa, 
            lahan.kategori_lahan,
            siklus_tanam.siklus_id,
            siklus_tanam.tanggal_tanam,
            siklus_tanam.estimasi_panen,
            ref_komoditas.nama_komoditas
        ');
        $this->db->from('lahan');
        
        // Join ke Siklus & Komoditas
        $this->db->join('siklus_tanam', 'siklus_tanam.lahan_id = lahan.lahan_id AND siklus_tanam.status_aktif = 1', 'left');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id', 'left');
        
        $this->db->where('lahan.user_id', $user_id);
        $this->db->order_by('lahan.lahan_id', 'DESC'); // Urutkan terbaru
        
        return $this->db->get()->result_array();
    }

    // 2. Ambil data sederhana (hanya tabel lahan)
    public function get_by_petani($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get('lahan')->result_array();
    }

    // 3. Hitung Total Luas
    public function sum_luas_lahan($user_id)
    {
        $this->db->select_sum('luas_lahan');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('lahan');
        return $query->row()->luas_lahan; 
    }

    // 4. Insert Lahan Baru
    public function insert($data)
    {
        return $this->db->insert('lahan', $data);
    }

    // 5. [BARU] Ambil Opsi Kategori Lahan dari Master Data
    public function get_kategori_opsi()
    {
        // Mengambil dari tabel baru 'ref_kategori_lahan'
        // Hasilnya array untuk dropdown di View
        return $this->db->get('ref_kategori_lahan')->result_array();
    }
}