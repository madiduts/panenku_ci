<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_validasi extends CI_Model {

    // 1. Ambil Data Panen (Pending vs History)
    public function get_panen_by_status($status_type = 'pending') {
        $this->db->select('
            hasil_panen.panen_id AS id,
            hasil_panen.tanggal_realisasi AS tanggal,
            hasil_panen.jumlah_ton AS hasil,
            hasil_panen.status_validasi AS status,
            hasil_panen.catatan_validasi AS catatan,
            users.full_name AS petani,
            lahan.lokasi_desa AS lokasi,
            lahan.kategori_lahan AS jenis,
            lahan.luas_lahan AS luas,
            ref_komoditas.nama_komoditas AS komoditas,
            validator.full_name AS validator
        ');

        $this->db->from('hasil_panen');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = hasil_panen.siklus_id');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('users', 'users.user_id = lahan.user_id'); // Petani
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id', 'left');
        $this->db->join('users AS validator', 'validator.user_id = hasil_panen.validator_id', 'left'); // Validator

        if ($status_type === 'pending') {
            $this->db->where('hasil_panen.status_validasi', 'Pending');
            $this->db->order_by('hasil_panen.tanggal_realisasi', 'ASC');
        } else {
            $this->db->where_in('hasil_panen.status_validasi', ['Valid', 'Reject']);
            $this->db->order_by('hasil_panen.tgl_validasi', 'DESC');
        }

        return $this->db->get()->result();
    }

    // 2. Update Status Validasi
    public function update_status($panen_id, $data) {
        $this->db->where('panen_id', $panen_id);
        return $this->db->update('hasil_panen', $data);
    }
    
    // 3. Hitung Statistik Bulanan (Untuk Card di Halaman Validasi)
    public function count_monthly_stats($status) {
        $this->db->where('status_validasi', $status);
        $this->db->where('MONTH(tgl_validasi)', date('m'));
        $this->db->where('YEAR(tgl_validasi)', date('Y'));
        return $this->db->count_all_results('hasil_panen');
    }

    // 4. Hitung Pending (Untuk Card juga)
    public function count_pending() {
        $this->db->where('status_validasi', 'Pending');
        return $this->db->count_all_results('hasil_panen');
    }
}