<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_siklus extends CI_Model {

    // Ambil data siklus aktif beserta Nama Lahan & Nama Komoditas
    // English Term: Eager Loading (mengambil data relasi sekaligus)
    public function get_active_siklus($user_id)
    {
        $this->db->select('siklus_tanam.*, lahan.nama_lahan, ref_komoditas.nama_komoditas');
        $this->db->from('siklus_tanam');
        // JOIN 1: Ambil nama lahan
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        // JOIN 2: Ambil nama komoditas
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id');
        
        $this->db->where('lahan.users_id', $user_id); // Filter punya petani ini saja
        $this->db->where('siklus_tanam.status', 'aktif'); // Hanya yang sedang ditanam
        
        return $this->db->get()->result_array();
    }

    // Untuk menghitung progress bar di dashboard
    public function count_active_by_petani($user_id)
    {
        $this->db->from('siklus_tanam');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id');
        $this->db->where('lahan.users_id', $user_id);
        $this->db->where('siklus_tanam.status', 'aktif');
        return $this->db->count_all_results();
    }
}