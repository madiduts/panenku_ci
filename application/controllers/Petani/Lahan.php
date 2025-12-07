<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lahan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek sesi login
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        $this->load->library('form_validation');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Kelola Lahan';
        $data['active_menu'] = 'lahan';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // Ambil Data Lahan
        // Kita pakai get_lahan_details agar bisa tahu status aktif/istirahat
        $raw_data = $this->M_lahan->get_lahan_details($user_id);
        
        // Mapping Data (Agar sesuai dengan View)
        $mapped_lahan = [];
        foreach($raw_data as $row) {
            $is_active = !empty($row['siklus_id']); 
            
            $mapped_lahan[] = (object) [
                'id' => 'L-' . $row['lahan_id'],
                'lokasi' => $row['lokasi_desa'],
                'luas' => $row['luas_lahan'] . ' ha',
                'tanah' => $row['kategori_lahan'], 
                'tanaman' => $is_active ? $row['nama_komoditas'] : '-',
                'status' => $is_active ? 'Aktif' : 'Istirahat',
                'progress' => 0 // Sementara 0 dulu
            ];
        }
        $data['lahan_list'] = $mapped_lahan;

        $data['content'] = 'petani/lahan';
        $this->load->view('petani/layout_petani', $data);
    }

    // METHOD BARU: Menangani Proses Tambah Data
    public function tambah() {
        // 1. Validasi Input
        $this->form_validation->set_rules('lokasi_desa', 'Lokasi', 'required|trim');
        $this->form_validation->set_rules('luas_lahan', 'Luas', 'required|numeric');
        $this->form_validation->set_rules('kategori_lahan', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke halaman lahan dengan pesan error
            // Kita set Flashdata 'error' agar muncul notifikasi
            $this->session->set_flashdata('error', 'Gagal menambah lahan. Periksa inputan Anda.');
            redirect('petani/lahan');
        } else {
            // 2. Siapkan Data
            $data = [
                'user_id' => $this->session->userdata('user_id'),
                'lokasi_desa' => $this->input->post('lokasi_desa', TRUE),
                'luas_lahan' => $this->input->post('luas_lahan', TRUE),
                'kategori_lahan' => $this->input->post('kategori_lahan', TRUE)
            ];

            // 3. Simpan ke Database
            if ($this->M_lahan->insert($data)) {
                $this->session->set_flashdata('success', 'Lahan berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan database.');
            }

            // 4. Redirect kembali
            redirect('petani/lahan');
        }
    }
}