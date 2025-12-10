<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    // 1. STATISTIK UMUM (KARTU ATAS)
    public function get_dashboard_stats() {
        // Hitung Total Lahan (Sum Luas)
        $this->db->select_sum('luas_lahan');
        $this->db->where('status_validasi', 'Valid'); // Hanya yang valid
        $lahan = $this->db->get('lahan')->row()->luas_lahan;

        // Hitung Total Petani (Count User Petani)
        $this->db->where('role', 'petani');
        $this->db->where('is_active', 1);
        $petani = $this->db->count_all_results('users');

        // Hitung Total Produksi per Komoditas (Padi, Jagung, Kedelai)
        // Menggunakan Helper Query Manual agar efisien
        $sql_prod = "
            SELECT 
                SUM(CASE WHEN k.nama_komoditas LIKE '%Padi%' THEN hp.jumlah_ton ELSE 0 END) as padi,
                SUM(CASE WHEN k.nama_komoditas LIKE '%Jagung%' THEN hp.jumlah_ton ELSE 0 END) as jagung,
                SUM(CASE WHEN k.nama_komoditas LIKE '%Kedelai%' THEN hp.jumlah_ton ELSE 0 END) as kedelai
            FROM hasil_panen hp
            JOIN siklus_tanam s ON s.siklus_id = hp.siklus_id
            JOIN ref_komoditas k ON k.komoditas_id = s.komoditas_id
            WHERE hp.status_validasi = 'Valid'
        ";
        $prod = $this->db->query($sql_prod)->row();

        return [
            'total_lahan' => $lahan ?? 0,
            'total_petani' => $petani ?? 0,
            'prod_padi' => $prod->padi ?? 0,
            'prod_jagung' => $prod->jagung ?? 0,
            'prod_kedelai' => $prod->kedelai ?? 0
        ];
    }

    // 2. REKAP PER DESA (TABEL)
    // Teknik Pivot Query: Mengubah baris komoditas menjadi kolom
    public function get_rekap_per_desa() {
        $sql = "
            SELECT 
                l.lokasi_desa as desa,
                COUNT(DISTINCT l.lahan_id) as jumlah_lahan,
                SUM(l.luas_lahan) as luas_total,
                COUNT(DISTINCT l.user_id) as jumlah_petani,
                
                -- Pivot: Hitung Tonase per Komoditas Valid
                SUM(CASE WHEN k.nama_komoditas LIKE '%Padi%' AND hp.status_validasi='Valid' THEN hp.jumlah_ton ELSE 0 END) as padi_ton,
                SUM(CASE WHEN k.nama_komoditas LIKE '%Jagung%' AND hp.status_validasi='Valid' THEN hp.jumlah_ton ELSE 0 END) as jagung_ton,
                SUM(CASE WHEN k.nama_komoditas LIKE '%Kedelai%' AND hp.status_validasi='Valid' THEN hp.jumlah_ton ELSE 0 END) as kedelai_ton,
                
                -- Hitung Produktivitas Rata-rata (Total Hasil / Total Luas)
                -- IFNULL untuk mencegah error division by zero
                ROUND(IFNULL(SUM(hp.jumlah_ton) / NULLIF(SUM(l.luas_lahan), 0), 0), 2) as produktivitas

            FROM lahan l
            -- Left Join ke Siklus -> Hasil Panen -> Komoditas
            LEFT JOIN siklus_tanam s ON s.lahan_id = l.lahan_id
            LEFT JOIN hasil_panen hp ON hp.siklus_id = s.siklus_id
            LEFT JOIN ref_komoditas k ON k.komoditas_id = s.komoditas_id
            
            WHERE l.status_validasi = 'Valid'
            GROUP BY l.lokasi_desa
            ORDER BY luas_total DESC
        ";

        return $this->db->query($sql)->result();
    }
}