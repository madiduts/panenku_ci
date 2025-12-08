<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_profil');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Profil Saya';
        $data['active_menu'] = 'profil';
        
        // 1. Ambil Data Profil (Object)
        $data['profil'] = $this->M_profil->get_detail($user_id);
        
        // 2. Ambil Statistik Real (Bukan hardcoded lagi!)
        $data['stats'] = $this->M_profil->get_stats($user_id);
        
        // Data user session untuk Sidebar
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => $data['profil']->avatar
        ];

        $data['content'] = 'petani/profil';
        $this->load->view('petani/layout_petani', $data);
    }

    public function update() {
        $user_id = $this->session->userdata('user_id');
        
        // Validasi
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('telepon', 'No Telepon', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('petani/profil?mode=edit');
        } else {
            // Mapping Input Form -> Kolom Database
            $update_data = [
                'full_name'      => $this->input->post('nama_lengkap', TRUE),
                'nama_pertanian' => $this->input->post('nama_pertanian', TRUE),
                'phone_number'   => $this->input->post('telepon', TRUE),
                'address'        => $this->input->post('alamat', TRUE),
                'bio'            => $this->input->post('bio', TRUE)
            ];

            // Upload Avatar Logic
            if (!empty($_FILES['avatar']['name'])) {
                $config['upload_path']   = './uploads/avatars/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'avatar_' . $user_id . '_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('avatar')) {
                    // Hapus foto lama jika bukan default
                    $current_user = $this->M_profil->get_detail($user_id);
                    if ($current_user->avatar != 'default.jpg') {
                        @unlink(FCPATH . 'uploads/avatars/' . $current_user->avatar);
                    }
                    
                    $uploadData = $this->upload->data();
                    $update_data['avatar'] = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('petani/profil?mode=edit');
                    return;
                }
            }

            // Update DB
            $this->M_profil->update($user_id, $update_data);

            // Update Session Data (Agar sidebar berubah langsung)
            $this->session->set_userdata('full_name', $update_data['full_name']);
            
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
            redirect('petani/profil'); // Kembali ke mode baca
        }
    }
}