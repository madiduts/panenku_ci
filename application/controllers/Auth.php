<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan Model ini nanti dibuat juga!
        // $this->load->model('Auth_model'); 
    }

    // UBAH DARI index() JADI login()
    // Agar sesuai dengan redirect('auth/login')
    public function login() {
        if ($this->session->userdata('logged_in')) {
            $this->redirect_based_on_role();
        }
        $this->load->view('login'); // Pastikan view ini dibuat
    }
    
    // Tambahkan index() untuk berjaga-jaga jika user akses /auth saja
    public function index() {
        $this->login();
    }

    public function process() {
        // ... (Kode proses login kamu tetap sama)
        // Hati-hati: $this->Auth_model harus ada dulu!
        
        // Untuk SEMENTARA (Testing tanpa Database/Model), pakai dummy dulu:
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if($username == 'madi' && $password == '123') {
             $session_data = array(
                'user_id'   => 1,
                'username'  => 'madi',
                'full_name' => 'Madi Serius',
                'role'      => 'petani', // Sesuai logika dashboard
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);
            $this->redirect_based_on_role();
        } else {
            $this->session->set_flashdata('error', 'Login Gagal (Mode Testing)');
            redirect('auth/login');
        }
    }

    private function redirect_based_on_role() {
        $role = $this->session->userdata('role');
        if ($role == 'petani') {
            redirect('petani/dashboard');
        } else {
            redirect('auth/login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}