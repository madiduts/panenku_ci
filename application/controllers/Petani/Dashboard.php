<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek Login di sini
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        $this->load->model('M_siklus');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Dashboard';
        $data['active_menu'] = 'dashboard';
        $data['user'] = $this->_get_user_data(); // Helper private

        // ... Logika hitung total lahan, luas, dll (Copy dari kode lamamu) ...
        $lahan_petani = $this->M_lahan->get_by_petani($user_id);
        $data['total_lahan'] = count($lahan_petani);
        // ... dst ...

        $data['content'] = 'petani/dashboard'; 
        $this->load->view('petani/layout_petani', $data);
    }

    // Helper biar gak nulis ulang terus
    private function _get_user_data() {
        return [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];
    }
}