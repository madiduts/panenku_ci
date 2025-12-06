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
        if ($this->session->userdata('role')) {
            $this->_redirect_user($this->session->userdata('role'));
        }
        $this->load->view('auth/login');
    }

    public function register()
    {
        $this->load->view('auth/register');
    }

    public function process_login()
    {
        // Ambil input email & password
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        // Cek user di database berdasarkan EMAIL
        $user = $this->User_model->get_user_by_email($email);

        if ($user) {
            // Cek Password
            if (md5($password) === $user['password']) {
                
                // Set Session data
                $session_data = [
                    'user_id'   => $user['id'],
                    'email'     => $user['email'],
                    'full_name' => $user['full_name'],
                    'role'      => $user['role'], // Role diambil dari database
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);

                // Redirect otomatis sesuai role
                $this->_redirect_user($user['role']);

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Email tidak terdaftar!');
            redirect('auth');
        }
    }

    public function process_register()
    {
        // Validasi Email unik
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]', [
            'is_unique' => 'Email ini sudah terdaftar!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/register');
        } else {
            $data = [
                'full_name' => $this->input->post('full_name'),
                'email'     => $this->input->post('email'),
                'password'  => md5($this->input->post('password')),
                'role'      => 'petani' // Default role petani
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

    private function _redirect_user($role)
    {
        if ($role === 'petani') {
            redirect('petani');
        } elseif ($role === 'dinas') {
            redirect('dinas');
        }
    }
}