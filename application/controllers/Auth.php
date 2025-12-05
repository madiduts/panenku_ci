<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    // Halaman Login
    public function index()
    {
        // Jika sudah login, tendang ke dashboard masing-masing
        if ($this->session->userdata('user_id')) {
            $role = $this->session->userdata('role');
            if ($role == 'petani') redirect('petani/dashboard');
            else redirect('dinas/dashboard'); // Asumsi dashboard dinas
        }

        $this->load->view('login'); // File view login yang sudah diupdate
    }

    // Proses Login
    public function process_login()
    {
        $username = $this->input->post('username', true); // true = XSS filtering (English: Sanitization)
        $password = $this->input->post('password');

        $user = $this->Auth_model->get_user_by_username($username);

        // 1. Cek User Ada?
        if ($user) {
            // 2. Cek Password Benar? (Verify Hash)
            if (password_verify($password, $user['password'])) {
                // Password cocok! Buat Session.
                $session_data = [
                    'user_id'      => $user['user_id'],
                    'username'     => $user['username'],
                    'nama_lengkap' => $user['full_name'],
                    'role'         => $user['role']
                ];
                $this->session->set_userdata($session_data);

                // Redirect sesuai Role
                if ($user['role'] == 'petani') {
                    redirect('petani/dashboard');
                } else {
                    redirect('dinas/validasi'); // Atau dashboard dinas
                }

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak terdaftar!');
            redirect('auth');
        }
    }

    // Halaman Register
    public function register()
    {
        $this->load->view('register');
    }

    // Proses Register
    public function process_register()
    {
        // Validasi input sederhana
        $username = $this->input->post('username');
        
        // Cek apakah username sudah dipakai?
        $existing_user = $this->Auth_model->get_user_by_username($username);
        if ($existing_user) {
            $this->session->set_flashdata('error', 'Username sudah dipakai! Cari yang lain.');
            redirect('auth/register');
        }

        // Siapkan data
        $data = [
            'full_name' => $this->input->post('full_name'),
            'username'  => $username,
            // HASHING PASSWORD (English: Encryption)
            // Mengubah 'rahasia123' menjadi string acak '$2y$10$...'
            'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role'      => 'petani' // Default pasti petani
        ];

        if ($this->Auth_model->register_petani($data)) {
            $this->session->set_flashdata('success', 'Akun berhasil dibuat! Silakan Login.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar. Coba lagi.');
            redirect('auth/register');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}