<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_panen extends CI_Model {

    // Untuk Dinas: Ambil SEMUA laporan yang belum divalidasi
    // English Term: Back-Office Queue
    public function get_pending_validation()
    {
        $this->db->select('hasil_panen.*, users.nama_lengkap, ref_komoditas.nama_komoditas, lahan.lokasi_desa');
        $this->db->from('hasil_panen');
        // Join berantai untuk dapat nama petani & lokasi
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = hasil_panen.siklus_id');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->join('users', 'users.users_id = lahan.users_id');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        
        $this->db->where('hasil_panen.status_validasi', 'menunggu');
        return $this->db->get()->result_array();
    }

    public function insert($data)
    {
        return $this->db->insert('hasil_panen', $data);
    }

    // Validasi Dinas (Update Status)
    public function update_status($panen_id, $status)
    {
        $this->db->where('panen_id', $panen_id);
        return $this->db->update('hasil_panen', ['status_validasi' => $status]);
    }
}