<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // DEBUGGING: Pastikan model ini ada di application/models/M_auth.php
        $this->load->model('M_auth'); 
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('user_id')) {
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
        $email    = $this->input->post('email', true);
        $password = $this->input->post('password');

        $user = $this->M_auth->get_user_by_email($email);

        if ($user) {
            // Jika password masih MD5 (Legacy Support)
            // Hapus blok ini jika database sudah bersih
            if (strlen($user['password']) == 32 && ctype_xdigit($user['password'])) {
                 if (md5($password) === $user['password']) {
                     $this->_set_session($user);
                     return;
                 }
            }

            // Verifikasi Password Hash (Modern)
            if (password_verify($password, $user['password'])) {
                $this->_set_session($user);
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Email tidak terdaftar!');
            redirect('auth');
        }
    }

    private function _set_session($user) {
        $session_data = [
            'user_id'   => $user['user_id'],
            'email'     => $user['email'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
            'avatar'    => $user['avatar'] ?? 'default.jpg',
            'logged_in' => TRUE
        ];
        $this->session->set_userdata($session_data);
        $this->_redirect_user($user['role']);
    }

    public function process_register()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/register');
        } else {
            $data = [
                'full_name' => $this->input->post('full_name', true),
                'email'     => $this->input->post('email', true),
                'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'      => 'petani',
                'created_at'=> date('Y-m-d H:i:s')
            ];

            $this->M_auth->register($data);
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
        if ($role === 'petani') redirect('petani/dashboard');
        elseif ($role === 'dinas') redirect('dinas/dashboard');
        else redirect('auth');
    }
}