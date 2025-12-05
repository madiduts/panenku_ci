<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load library yang wajib ada untuk login
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('Auth_model');
    }

    // HALAMAN UTAMA (Bisa diakses lewat /auth atau /auth/login)
    public function index()
    {
        $this->login(); // Lempar ke fungsi login biar satu pintu
    }

    // MENAMPILKAN HALAMAN LOGIN
    public function login()
    {
        // Jika sudah login, tendang ke dashboard masing-masing
        if ($this->session->userdata('user_id')) {
            $role = $this->session->userdata('role');
            if ($role == 'petani') redirect('petani');
            else redirect('dinas'); 
        }

        $this->load->view('login'); 
    }

    // PROSES LOGIN (Aksi dari Form)
    public function process_login()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password');

        $user = $this->Auth_model->get_user_by_username($username);

        // 1. Cek User Ada?
        if ($user) {
            // 2. Cek Password Benar? (Verify Hash)
            if (password_verify($password, $user['password'])) {
                // Password cocok! Buat Session.
                $session_data = [
                    'user_id'      => $user['id'], // Sesuaikan dengan nama kolom di database (id/user_id)
                    'username'     => $user['username'],
                    'nama_lengkap' => $user['full_name'],
                    'role'         => $user['role'],
                    'logged_in'    => TRUE
                ];
                $this->session->set_userdata($session_data);

                // Redirect sesuai Role
                if ($user['role'] == 'petani') {
                    redirect('petani');
                } else {
                    redirect('dinas');
                }

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth/login');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak terdaftar!');
            redirect('auth/login');
        }
    }

    // HALAMAN REGISTER
    public function register()
    {
        $this->load->view('register');
    }

    // PROSES REGISTER
    public function process_register()
    {
        $username = $this->input->post('username');
        
        // Cek username kembar
        $existing_user = $this->Auth_model->get_user_by_username($username);
        if ($existing_user) {
            $this->session->set_flashdata('error', 'Username sudah dipakai! Cari yang lain.');
            redirect('auth/register');
        }

        // Siapkan data
        $data = [
            'full_name' => $this->input->post('full_name'),
            'username'  => $username,
            'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role'      => 'petani', // Default role
            'created_at'=> date('Y-m-d H:i:s')
        ];

        if ($this->Auth_model->register_petani($data)) {
            $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan Login.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar. Coba lagi.');
            redirect('auth/register');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}