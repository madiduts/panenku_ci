<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siklus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // 1. Security Gate: Pastikan User Login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // 2. Load All Dependencies
        $this->load->model('M_siklus');
        $this->load->model('M_lahan');  // Untuk dropdown lahan
        $this->load->model('M_master'); // Untuk dropdown komoditas
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

        // --- DATA FETCHING (MENGISI DROPDOWN) ---
        
        // 1. Ambil List Siklus Aktif (Untuk Card Utama)
        $data['siklus_list'] = $this->M_siklus->get_active_siklus($user_id);
        
        // 2. Ambil Opsi Lahan (Hanya lahan milik user ini)
        // Digunakan di <select name="lahan_id">
        $data['lahan_opsi'] = $this->M_lahan->get_by_petani($user_id);
        
        // 3. Ambil Opsi Komoditas (Semua komoditas tersedia)
        // Digunakan di <select name="komoditas_id">
        $data['komoditas_opsi'] = $this->M_master->get_komoditas();

        $data['content'] = 'petani/siklus';
        $this->load->view('petani/layout_petani', $data);
    }

    // METHOD BARU: Menangani Form Tambah Siklus
    public function tambah() {
        // 1. Validasi Input
        $this->form_validation->set_rules('lahan_id', 'Lahan', 'required|numeric');
        $this->form_validation->set_rules('komoditas_id', 'Komoditas', 'required|numeric');
        $this->form_validation->set_rules('tanggal_tanam', 'Tanggal Tanam', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan dengan pesan error
            $this->session->set_flashdata('error', 'Mohon lengkapi semua data form.');
            redirect('petani/siklus');
        } else {
            // 2. Business Logic: Hitung Estimasi Panen
            // Karena di DB kolom estimasi_panen wajib, tapi di form tidak ada inputnya.
            // Asumsi: Masa tanam rata-rata adalah 3 bulan (90 hari)
            
            $tgl_tanam = $this->input->post('tanggal_tanam');
            $estimasi_panen = date('Y-m-d', strtotime($tgl_tanam . ' + 90 days'));

            // 3. Prepare Data
            $data = [
                'lahan_id'      => $this->input->post('lahan_id'),
                'komoditas_id'  => $this->input->post('komoditas_id'),
                'tanggal_tanam' => $tgl_tanam,
                'estimasi_panen'=> $estimasi_panen, // Calculated field
                'status_aktif'  => 1 // 1 = Aktif, 0 = Panen/Batal
            ];

            // 4. Insert ke Database
            if ($this->M_siklus->insert($data)) {
                $this->session->set_flashdata('success', 'Siklus tanam berhasil dimulai! Selamat berjuang.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan data ke database.');
            }

            redirect('petani/siklus');
        }
    }
}