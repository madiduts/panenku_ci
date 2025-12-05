<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model {

    // Ambil data dropdown
    public function get_komoditas() {
        return $this->db->get('ref_komoditas')->result_array();
    }

    public function get_hama() {
        return $this->db->get('ref_jenis_hama')->result_array();
    }
}