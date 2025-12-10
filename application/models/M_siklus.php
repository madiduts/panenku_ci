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

    public function get_detail_hama($laporan_id) 
    {
        $this->db->select('
            laporan_hama.*,
            ref_jenis_hama.nama_hama,
            ref_jenis_hama.deskripsi_penanganan as penanganan_umum,
            siklus_tanam.lahan_id
        ');
        $this->db->from('laporan_hama');
        // Join agar dapat nama hama & deskripsi umum
        $this->db->join('ref_jenis_hama', 'ref_jenis_hama.hama_id = laporan_hama.hama_id', 'left');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = laporan_hama.siklus_id', 'left');
        
        $this->db->where('laporan_hama.laporan_id', $laporan_id);
        
        return $this->db->get()->row_array();
    }
}