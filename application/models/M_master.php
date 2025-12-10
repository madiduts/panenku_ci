<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model {

    // ==================================================
    // BAGIAN 1: UNTUK PETANI (DROPDOWN / ARRAY)
    // ==================================================
    
    public function get_komoditas() {
        return $this->db->get('ref_komoditas')->result_array();
    }

    public function get_hama() {
        return $this->db->get('ref_jenis_hama')->result_array();
    }
    
    // [BARU] Untuk Dropdown Petani saat tambah lahan
    public function get_kategori_lahan() {
        return $this->db->get('ref_kategori_lahan')->result_array();
    }


    // ==================================================
    // BAGIAN 2: UNTUK DINAS (CRUD / OBJECT)
    // ==================================================

    // --- KOMODITAS ---
    public function get_all_komoditas() {
        return $this->db->get('ref_komoditas')->result();
    }
    public function insert_komoditas($data) {
        return $this->db->insert('ref_komoditas', $data);
    }
    public function delete_komoditas($id) {
        $this->db->where('komoditas_id', $id);
        return $this->db->delete('ref_komoditas');
    }

    // --- HAMA & PENYAKIT ---
    public function get_all_hama() {
        return $this->db->get('ref_jenis_hama')->result();
    }
    public function insert_hama($data) {
        return $this->db->insert('ref_jenis_hama', $data);
    }
    public function delete_hama($id) {
        $this->db->where('hama_id', $id);
        return $this->db->delete('ref_jenis_hama');
    }

    // --- KATEGORI LAHAN (BARU) ---
    public function get_all_kategori_lahan() {
        return $this->db->get('ref_kategori_lahan')->result();
    }
    public function insert_kategori_lahan($data) {
        return $this->db->insert('ref_kategori_lahan', $data);
    }
    public function delete_kategori_lahan($id) {
        $this->db->where('kategori_id', $id);
        return $this->db->delete('ref_kategori_lahan');
    }

    // --- DATA PETANI ---
    public function get_all_petani() {
        $this->db->select('
            users.*, 
            COUNT(lahan.lahan_id) as jumlah_lahan
        ');
        $this->db->from('users');
        $this->db->join('lahan', 'lahan.user_id = users.user_id', 'left');
        $this->db->where('users.role', 'petani');
        $this->db->where('users.is_active', 1);
        $this->db->group_by('users.user_id');
        return $this->db->get()->result();
    }
    public function insert_petani($data) {
        return $this->db->insert('users', $data);
    }
    public function delete_petani($id) {
        $this->db->where('user_id', $id);
        return $this->db->update('users', ['is_active' => 0]);
    }
}