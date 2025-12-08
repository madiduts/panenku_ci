<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // 1. Cek Login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Informasi & Bantuan';
        $data['active_menu'] = 'laporan'; // Agar sidebar menu ini menyala
        
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // Note: Saat ini data cuaca dan harga masih hardcoded di View.
        // Nanti jika ingin dinamis, kita panggil Model di sini.
        
        $data['content'] = 'petani/laporan';
        $this->load->view('petani/layout_petani', $data);
    }
}