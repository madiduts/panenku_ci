<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dinas extends CI_Model {

    // 1. Hitung Total Petani (Role = petani)
    public function count_petani() {
        $this->db->where('role', 'petani');
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('users');
    }

    // 2. Hitung Total Luas Lahan (Semua Desa)
    public function sum_luas_lahan() {
        $this->db->select_sum('luas_lahan');
        $query = $this->db->get('lahan');
        return $query->row()->luas_lahan ?? 0;
    }

    // 3. Hitung Laporan Panen Pending (Butuh Validasi)
    public function count_pending_panen() {
        $this->db->where('status_validasi', 'menunggu'); // Sesuaikan dengan enum DB jika perlu
        return $this->db->count_all_results('hasil_panen');
    }

    // 4. Hitung Peringatan Hama Aktif (EWS)
    public function count_active_hama() {
        // Hama dalam 7 hari terakhir
        $seven_days_ago = date('Y-m-d', strtotime('-7 days'));
        $this->db->where('tanggal_lapor >=', $seven_days_ago);
        return $this->db->count_all_results('laporan_hama');
    }

    // 5. Data EWS (List Peringatan) - JOIN Table & Alias
    public function get_ews_list($limit = 5) {
        $this->db->select('
            laporan_hama.tanggal_lapor,
            laporan_hama.tingkat_keparahan,
            ref_jenis_hama.nama_hama AS hama,
            lahan.lokasi_desa AS lokasi,
            lahan.luas_lahan,
            COALESCE(ref_komoditas.nama_komoditas, "Tanaman") AS komoditas
        ');
        
        $this->db->from('laporan_hama');
        
        // Join ke Master Hama
        $this->db->join('ref_jenis_hama', 'ref_jenis_hama.hama_id = laporan_hama.hama_id');
        
        // Join ke Siklus -> Lahan -> Komoditas
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = laporan_hama.siklus_id');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        
        // LEFT JOIN untuk Komoditas (Safety jika data master terhapus)
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id', 'left');
        
        $this->db->order_by('laporan_hama.tanggal_lapor', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    // 6. Data Chart Distribusi Komoditas
    public function get_commodity_distribution() {
        $this->db->select('ref_komoditas.nama_komoditas, COUNT(siklus_tanam.siklus_id) as total');
        $this->db->from('siklus_tanam');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        $this->db->where('siklus_tanam.status_aktif', 1);
        $this->db->group_by('ref_komoditas.nama_komoditas');
        return $this->db->get()->result();
    }

    public function get_village_stats() {
        // Logika: Kelompokkan data berdasarkan nama desa, lalu hitung total luas & total orang
        $this->db->select('lokasi_desa, SUM(luas_lahan) as total_luas, COUNT(DISTINCT user_id) as total_petani');
        $this->db->from('lahan');
        $this->db->group_by('lokasi_desa'); // Kuncinya di sini
        return $this->db->get()->result();
    }
}