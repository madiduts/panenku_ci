<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siklus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        $this->load->model('M_siklus');
        $this->load->model('M_lahan');
        $this->load->model('M_master'); // Penting untuk ambil data Hama
        $this->load->library('form_validation');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Siklus Tanam';
        $data['active_menu'] = 'siklus';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // Fetch Data
        $data['siklus_list'] = $this->M_siklus->get_active_siklus($user_id);
        $data['lahan_opsi'] = $this->M_lahan->get_by_petani($user_id);
        $data['komoditas_opsi'] = $this->M_master->get_komoditas();
        
        // [BARU] Ambil data Hama untuk Dropdown Modal Lapor Hama
        $data['hama_opsi'] = $this->M_master->get_hama(); 

        $data['content'] = 'petani/siklus';
        $this->load->view('petani/layout_petani', $data);
    }

    // Method Tambah Siklus Tanam
    public function tambah() {
        $this->form_validation->set_rules('lahan_id', 'Lahan', 'required|numeric');
        $this->form_validation->set_rules('komoditas_id', 'Komoditas', 'required|numeric');
        $this->form_validation->set_rules('tanggal_tanam', 'Tanggal Tanam', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Data tidak lengkap.');
            redirect('petani/siklus');
        } else {
            $tgl_tanam = $this->input->post('tanggal_tanam');
            // Estimasi Panen +90 Hari
            $estimasi_panen = date('Y-m-d', strtotime($tgl_tanam . ' + 90 days'));

            $data = [
                'lahan_id'      => $this->input->post('lahan_id'),
                'komoditas_id'  => $this->input->post('komoditas_id'),
                'tanggal_tanam' => $tgl_tanam,
                'estimasi_panen'=> $estimasi_panen,
                'status_aktif'  => 1 
            ];

            if ($this->M_siklus->insert($data)) {
                $this->session->set_flashdata('success', 'Siklus tanam dimulai.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan.');
            }
            redirect('petani/siklus');
        }
    }

    // [BARU] Method Simpan Riwayat Pupuk
    public function simpan_pupuk() {
        $this->form_validation->set_rules('jenis_pupuk', 'Jenis Pupuk', 'required');
        $this->form_validation->set_rules('jumlah_sebar', 'Jumlah', 'required|numeric');

        if ($this->form_validation->run() != FALSE) {
            $data = [
                'siklus_id'     => $this->input->post('siklus_id'),
                'jenis_pupuk'   => $this->input->post('jenis_pupuk', TRUE),
                'jumlah_sebar'  => $this->input->post('jumlah_sebar', TRUE),
                'satuan'        => $this->input->post('satuan'),
                'tanggal_sebar' => $this->input->post('tanggal_sebar'),
                'catatan'       => $this->input->post('catatan', TRUE)
            ];
            
            $this->M_siklus->insert_pupuk($data);
            $this->session->set_flashdata('success', 'Riwayat pupuk dicatat.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mencatat pupuk.');
        }
        redirect('petani/siklus');
    }

    // [BARU] Method Lapor Hama
    public function lapor_hama() {
        $this->form_validation->set_rules('hama_id', 'Jenis Hama', 'required');
        
        if ($this->form_validation->run() != FALSE) {
            
            // 1. Siapkan Data Dasar
            $data = [
                'siklus_id' => $this->input->post('siklus_id'),
                'hama_id'   => $this->input->post('hama_id'),
                'tingkat_keparahan' => $this->input->post('tingkat_keparahan'),
                'tanggal_lapor' => date('Y-m-d H:i:s'),
                'foto_bukti' => null // Default null jika tidak ada foto
            ];

            // 2. Logika Upload Foto
            // Cek apakah ada file yang diupload (namanya 'foto_bukti')
            if (!empty($_FILES['foto_bukti']['name'])) {
                
                // Konfigurasi Upload CI3
                $config['upload_path']   = './uploads/hama/'; // Pastikan folder ini ada!
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'hama_' . time(); // Rename file agar unik

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_bukti')) {
                    // Jika berhasil, ambil nama filenya
                    $uploadData = $this->upload->data();
                    $data['foto_bukti'] = $uploadData['file_name'];
                } else {
                    // Jika gagal upload (misal file terlalu besar), set error flashdata
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'Gagal upload foto: ' . $error);
                    redirect('petani/siklus');
                    return; // Stop proses
                }
            }
            
            // 3. Simpan ke Database
            $this->M_siklus->insert_hama($data);
            $this->session->set_flashdata('success', 'Laporan hama berhasil dikirim ke Dinas.');

        } else {
             $this->session->set_flashdata('error', 'Gagal melapor hama. Pastikan data lengkap.');
        }
        redirect('petani/siklus');
    }
}