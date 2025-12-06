<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Halaman Login
        if ($this->session->userdata('role')) {
            $this->_redirect_user($this->session->userdata('role'));
        }
        $this->load->view('auth/login');
    }

    public function register()
    {
        // Halaman Register
        $this->load->view('auth/register');
    }

    public function process_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $role     = $this->input->post('role'); // Input dari dropdown

        // Cek user di database
        $user = $this->User_model->get_user_by_username($username);

        if ($user) {
            // Verifikasi Password (menggunakan MD5 sederhana sesuai contoh database)
            // Di production sebaiknya pakai password_verify()
            if (md5($password) === $user['password']) {
                
                // Cek apakah role yang dipilih sesuai dengan database
                if ($user['role'] === $role) {
                    // Set Session
                    $session_data = [
                        'user_id'   => $user['id'],
                        'username'  => $user['username'],
                        'full_name' => $user['full_name'],
                        'role'      => $user['role'],
                        'logged_in' => TRUE
                    ];
                    $this->session->set_userdata($session_data);

                    // Redirect sesuai role
                    $this->_redirect_user($role);
                } else {
                    $this->session->set_flashdata('error', 'Role tidak sesuai! Anda terdaftar sebagai ' . ucfirst($user['role']));
                    redirect('auth');
                }

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('auth');
        }
    }

    public function process_register()
    {
        // Validasi input sederhana
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
            'is_unique' => 'Username ini sudah terpakai!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/register');
        } else {
            // Data untuk disimpan
            $data = [
                'full_name' => $this->input->post('full_name'),
                'username'  => $this->input->post('username'),
                'password'  => md5($this->input->post('password')), // Encrypt MD5
                'role'      => 'petani' // Default register hanya untuk petani
            ];

            $this->User_model->insert_user($data);
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }

    // Helper redirect private
    private function _redirect_user($role)
    {
        if ($role === 'petani') {
            redirect('petani'); // Mengarah ke Controllers/Petani.php
        } elseif ($role === 'dinas') {
            redirect('dinas');  // Mengarah ke Controllers/Dinas.php
        }
    }
}