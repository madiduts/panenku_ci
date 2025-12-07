<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lahan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        // Load library form validation di construct agar siap pakai
        $this->load->library('form_validation');
    }

    // Diakses via: localhost/panenku/petani/lahan
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Kelola Lahan';
        $data['active_menu'] = 'lahan'; // Sidebar akan menyala di menu 'lahan'
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // Ambil data (Copy logika mapping dari kode lamamu)
        $raw_data = $this->M_lahan->get_lahan_details($user_id);
        
        // ... Lakukan mapping data di sini ...
        $data['lahan_list'] = $raw_data; // Atau hasil mapping

        $data['content'] = 'petani/lahan';
        $this->load->view('petani/layout_petani', $data);
    }

    // Diakses via: localhost/panenku/petani/lahan/simpan
    public function simpan() {
        // ... Copy logika simpan_lahan() dari kode lamamu di sini ...
        // Ganti redirect-nya jadi: redirect('petani/lahan');
    }
}