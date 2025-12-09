<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dinas extends CI_Model {

    // 1. Total Petani (Belum tentu perlu validasi, tapi biasanya user registrasi bebas)
    public function count_petani() {
        $this->db->where('role', 'petani');
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('users');
    }

    // 2. Total Luas Lahan (HANYA YANG VALID)
    public function sum_luas_lahan() {
        $this->db->select_sum('luas_lahan');
        $this->db->where('status_validasi', 'Valid'); // <-- FILTER PENTING
        $query = $this->db->get('lahan');
        return $query->row()->luas_lahan ?? 0;
    }

    // 3. Pending Panen (Untuk Stats Card Dashboard)
    public function count_pending_panen() {
        $this->db->where('status_validasi', 'Pending'); 
        return $this->db->count_all_results('hasil_panen');
    }

    // 4. EWS Hama (HANYA YANG VALID)
    public function count_active_hama() {
        $seven_days_ago = date('Y-m-d', strtotime('-7 days'));
        $this->db->where('tanggal_lapor >=', $seven_days_ago);
        $this->db->where('status_validasi', 'Valid'); // <-- FILTER PENTING
        return $this->db->count_all_results('laporan_hama');
    }

    // 5. Data List EWS (HANYA YANG VALID)
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
        $this->db->join('ref_jenis_hama', 'ref_jenis_hama.hama_id = laporan_hama.hama_id');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = laporan_hama.siklus_id');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id', 'left');
        
        $this->db->where('laporan_hama.status_validasi', 'Valid'); // <-- FILTER PENTING
        $this->db->order_by('laporan_hama.tanggal_lapor', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    // 6. GIS Data (HANYA YANG VALID)
    public function get_village_stats() {
        $this->db->select('lokasi_desa, SUM(luas_lahan) as total_luas, COUNT(DISTINCT user_id) as total_petani');
        $this->db->from('lahan');
        $this->db->where('status_validasi', 'Valid'); // <-- FILTER PENTING
        $this->db->group_by('lokasi_desa');
        return $this->db->get()->result();
    }

    // 7. Chart Distribusi Komoditas (Asumsikan siklus tanam mengikuti status lahan yang valid)
    public function get_commodity_distribution() {
        $this->db->select('ref_komoditas.nama_komoditas, COUNT(siklus_tanam.siklus_id) as total');
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id'); // Join Lahan untuk cek status
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        
        $this->db->where('siklus_tanam.status_aktif', 1);
        $this->db->where('lahan.status_validasi', 'Valid'); // <-- FILTER PENTING
        $this->db->group_by('ref_komoditas.nama_komoditas');
        return $this->db->get()->result();
    }

    public function get_laporan_hama_pending()
    {
        $this->db->select('
            laporan_hama.id,
            laporan_hama.tingkat_keparahan,
            laporan_hama.tanggal_lapor,
            laporan_hama.foto_bukti,
            laporan_hama.status_validasi,
            ref_hama.nama_hama,
            users.full_name as nama_petani,
            lahan.lokasi_desa
        ');
        $this->db->from('laporan_hama');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = laporan_hama.siklus_id');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('users', 'users.user_id = lahan.user_id');
        $this->db->join('ref_hama', 'ref_hama.hama_id = laporan_hama.hama_id');
        $this->db->order_by('laporan_hama.tanggal_lapor', 'DESC');

        return $this->db->get()->result();
    }
}