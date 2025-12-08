<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_profil extends CI_Model {

    // Ambil data user sebagai OBJECT (row) agar cocok dengan View ($profil->namaLengkap)
    public function get_detail($user_id)
    {
        // Kita alias-kan kolom DB agar sesuai dengan variabel di View kamu
        $this->db->select('
            user_id,
            email,
            phone_number as telepon,
            full_name as namaLengkap,
            address as alamat,
            nama_pertanian as namaPertanian,
            bio,
            role,
            avatar
        ');
        $this->db->where('user_id', $user_id);
        return $this->db->get('users')->row(); // Return Object
    }

    // Hitung Statistik Petani (Real-time)
    public function get_stats($user_id)
    {
        // 1. Total Lahan
        $this->db->where('user_id', $user_id);
        $total_lahan = $this->db->count_all_results('lahan');

        // 2. Total Luas
        $this->db->select_sum('luas_lahan');
        $this->db->where('user_id', $user_id);
        $query_luas = $this->db->get('lahan')->row();
        $total_luas = $query_luas->luas_lahan ?? 0;

        // 3. Siklus Selesai (Panen)
        // Asumsi: Status 0 = Selesai/Panen (sesuai logika controller sebelumnya)
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->where('lahan.user_id', $user_id);
        $this->db->where('siklus_tanam.status_aktif', 0); // Sudah tidak aktif
        $siklus_selesai = $this->db->count_all_results();

        return (object) [
            'total_lahan' => $total_lahan,
            'total_luas'  => $total_luas,
            'siklus_selesai' => $siklus_selesai
        ];
    }

    // Update Data
    public function update($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }
}