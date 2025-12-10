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

        // 1. Ambil Data Lahan
        $raw_data = $this->M_lahan->get_lahan_details($user_id);
        
        // 2. Mapping Data untuk View
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
                'progress' => 0 // Progress bar logic bisa ditambahkan nanti
            ];
        }
        $data['lahan_list'] = $mapped_lahan;

        // 3. [BARU] Ambil Opsi Kategori untuk Modal Tambah Lahan
        // Data ini akan dipakai di <select> pada view
        $data['kategori_opsi'] = $this->M_lahan->get_kategori_opsi();

        $data['content'] = 'petani/lahan';
        $this->load->view('petani/layout_petani', $data);
    }

    // Method Tambah Data
    public function tambah() {
        $this->form_validation->set_rules('lokasi_desa', 'Lokasi', 'required|trim');
        $this->form_validation->set_rules('luas_lahan', 'Luas', 'required|numeric');
        $this->form_validation->set_rules('kategori_lahan', 'Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Gagal menambah lahan. Pastikan data terisi benar.');
            redirect('petani/lahan');
        } else {
            $data = [
                'user_id' => $this->session->userdata('user_id'),
                'lokasi_desa' => $this->input->post('lokasi_desa', TRUE),
                'luas_lahan' => $this->input->post('luas_lahan', TRUE),
                
                // Value ini harus sesuai dengan apa yang dikirim dari <option value="...">
                // Jika DB lahan masih pakai string (Enum), pastikan value option adalah Nama Kategori.
                'kategori_lahan' => $this->input->post('kategori_lahan', TRUE)
            ];

            if ($this->M_lahan->insert($data)) {
                $this->session->set_flashdata('success', 'Lahan berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan database.');
            }

            redirect('petani/lahan');
        }
    }
}