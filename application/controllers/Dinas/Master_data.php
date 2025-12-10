<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'dinas') {
            redirect('auth/login');
        }
        $this->load->model('M_master');
    }

    public function index() {
        $data['title'] = 'Master Data Management';
        $data['active_menu'] = 'master_data';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];

        // Ambil Tab Aktif (Default: komoditas)
        $tab = $this->input->get('tab') ?? 'komoditas';
        
        // Inisialisasi Data
        $data['masterKomoditas'] = [];
        $data['masterHama'] = [];
        $data['masterPetani'] = [];
        $data['masterLahan'] = []; // BARU

        // Fetch Data Sesuai Tab
        if ($tab == 'komoditas') {
            $data['masterKomoditas'] = $this->M_master->get_all_komoditas();
        } elseif ($tab == 'hama') {
            $data['masterHama'] = $this->M_master->get_all_hama();
        } elseif ($tab == 'lahan') { // BARU
            $data['masterLahan'] = $this->M_master->get_all_kategori_lahan();
        } elseif ($tab == 'petani') {
            $data['masterPetani'] = $this->M_master->get_all_petani();
        }

        // Hitung Total (Untuk Cards)
        $data['total_komoditas'] = count($this->M_master->get_all_komoditas());
        $data['total_hama']      = count($this->M_master->get_all_hama());
        $data['total_petani']    = count($this->M_master->get_all_petani());
        $data['total_lahan_kat'] = count($this->M_master->get_all_kategori_lahan()); // BARU

        $data['content'] = 'dinas/master_data';
        $this->load->view('dinas/layout_dinas', $data);
    }

    // --- ACTION KOMODITAS ---
    public function tambah_komoditas() {
        $data = ['nama_komoditas' => $this->input->post('nama'), 'kategori' => $this->input->post('kategori')];
        $this->M_master->insert_komoditas($data);
        $this->session->set_flashdata('success', 'Komoditas berhasil ditambahkan.');
        redirect('dinas/master_data?tab=komoditas');
    }
    public function hapus_komoditas($id) {
        $this->M_master->delete_komoditas($id);
        $this->session->set_flashdata('success', 'Komoditas dihapus.');
        redirect('dinas/master_data?tab=komoditas');
    }

    // --- ACTION HAMA ---
    public function tambah_hama() {
        $data = ['nama_hama' => $this->input->post('nama'), 'deskripsi_penanganan' => $this->input->post('penanganan')];
        $this->M_master->insert_hama($data);
        $this->session->set_flashdata('success', 'Data hama berhasil ditambahkan.');
        redirect('dinas/master_data?tab=hama');
    }
    public function hapus_hama($id) {
        $this->M_master->delete_hama($id);
        $this->session->set_flashdata('success', 'Data hama dihapus.');
        redirect('dinas/master_data?tab=hama');
    }

    // --- ACTION KATEGORI LAHAN (BARU) ---
    public function tambah_kategori_lahan() {
        $data = ['nama_kategori' => $this->input->post('nama')];
        $this->M_master->insert_kategori_lahan($data);
        $this->session->set_flashdata('success', 'Kategori lahan baru ditambahkan.');
        redirect('dinas/master_data?tab=lahan');
    }
    public function hapus_kategori_lahan($id) {
        $this->M_master->delete_kategori_lahan($id);
        $this->session->set_flashdata('success', 'Kategori lahan dihapus.');
        redirect('dinas/master_data?tab=lahan');
    }

    // --- ACTION PETANI ---
    public function tambah_petani() {
        $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
        $data = [
            'full_name' => $this->input->post('nama'), 'email' => $this->input->post('email'),
            'phone_number' => $this->input->post('telp'), 'address' => $this->input->post('alamat'),
            'password' => $passwordHash, 'role' => 'petani', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')
        ];
        $this->M_master->insert_petani($data);
        $this->session->set_flashdata('success', 'Petani baru berhasil didaftarkan.');
        redirect('dinas/master_data?tab=petani');
    }
    public function hapus_petani($id) {
        $this->M_master->delete_petani($id);
        $this->session->set_flashdata('success', 'Akun petani dinonaktifkan.');
        redirect('dinas/master_data?tab=petani');
    }
}