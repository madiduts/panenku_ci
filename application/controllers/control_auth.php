<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    // Halaman Login
    public function index() {
        // Jika sudah login, jangan kasih akses ke halaman login lagi
        if ($this->session->userdata('logged_in')) {
            $this->redirect_based_on_role();
        }
        
        $this->load->view('login');
    }

    // Proses Login
    public function process() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // 1. Cek User di Database
        $user = $this->Auth_model->check_login($username);

        if ($user) {
            // 2. Cek Password (disini kita pakai plain text dulu biar cepat buat Madi)
            // Nanti kalau sempat, ubah pakai password_verify()
            if ($password == $user->password) {
                
                // 3. Set Session Data
                $session_data = array(
                    'user_id'   => $user->user_id,
                    'username'  => $user->username,
                    'full_name' => $user->full_name,
                    'role'      => $user->role,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);

                // 4. Redirect sesuai Role
                $this->redirect_based_on_role();

            } else {
                $this->session->set_flashdata('error', 'Password Salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('auth');
        }
    }

    // Fungsi Redirect Pintar
    private function redirect_based_on_role() {
        $role = $this->session->userdata('role');
        
        if ($role == 'dinas') {
            redirect('admin/dashboard'); // Arahkan ke Controller Admin
        } elseif ($role == 'petani') {
            redirect('petani/dashboard'); // Arahkan ke Controller Petani
        } else {
            echo "Role tidak dikenali.";
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}