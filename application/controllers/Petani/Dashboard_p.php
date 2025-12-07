<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_p extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load helper url untuk fungsi base_url()
        $this->load->helper('url');
        
        // Data User Global (Biasanya dari Session Login)
        $this->user_data = [
            'name' => 'Rzayyan Rizka',
            'role' => 'Pemilik Lahan', 
            'avatar' => 'R'
        ];
    }

    public function index()
    {
        redirect('petani/dashboard');
    }

    public function dashboard()
    {
        $data['title'] = 'Dashboard - AgriPlatform';
        $data['active_menu'] = 'dashboard';
        $data['user'] = $this->user_data;
        $data['content'] = 'petani/dashboard'; // Memanggil view dashboard.php

        // Data Mockup untuk Dashboard (Mirip data JS sebelumnya)
        $data['total_lahan'] = 8;
        $data['total_luas'] = 156;
        $data['total_lahan_aktif'] = 5;

        // Simulasi Data Lahan (Array of Objects)
        $data['lahan_list'] = json_decode(json_encode([
            ['id' => 'A-01', 'tanaman' => 'Padi', 'luas' => '25 ha', 'fase' => 'Fase Vegetatif', 'progress' => 45, 'status' => 'Aktif'],
            ['id' => 'B-02', 'tanaman' => 'Jagung', 'luas' => '30 ha', 'fase' => 'Siap Panen', 'progress' => 95, 'status' => 'Aktif'],
            ['id' => 'C-03', 'tanaman' => 'Kedelai', 'luas' => '20 ha', 'fase' => 'Pembungaan', 'progress' => 60, 'status' => 'Aktif'],
        ]));

        $this->load->view('petani/layout_petani', $data);
    }

    public function lahan()
    {
        $data['title'] = 'Kelola Lahan - AgriPlatform';
        $data['active_menu'] = 'lahan';
        $data['user'] = $this->user_data;
        $data['content'] = 'petani/lahan';

        // Data Mockup Lengkap untuk Halaman Lahan
        $data['lahan_list'] = json_decode(json_encode([
            ['id' => 'A-01', 'lokasi' => 'Desa Makmur', 'luas' => '25 ha', 'tanah' => 'Aluvial', 'tanaman' => 'Padi', 'status' => 'Aktif', 'progress' => 45],
            ['id' => 'B-02', 'lokasi' => 'Desa Sejahtera', 'luas' => '30 ha', 'tanah' => 'Latosol', 'tanaman' => 'Jagung', 'status' => 'Aktif', 'progress' => 95],
            ['id' => 'C-03', 'lokasi' => 'Desa Tani Maju', 'luas' => '20 ha', 'tanah' => 'Aluvial', 'tanaman' => 'Kedelai', 'status' => 'Aktif', 'progress' => 60],
            ['id' => 'D-04', 'lokasi' => 'Desa Sukamaju', 'luas' => '15 ha', 'tanah' => 'Andosol', 'tanaman' => '-', 'status' => 'Istirahat', 'progress' => 0],
            ['id' => 'E-05', 'lokasi' => 'Desa Mandiri', 'luas' => '18 ha', 'tanah' => 'Latosol', 'tanaman' => '-', 'status' => 'Istirahat', 'progress' => 0],
        ]));

        $this->load->view('petani/layout_petani', $data);
    }

    public function siklus()
    {
        $data['title'] = 'Siklus Tanam - AgriPlatform';
        $data['active_menu'] = 'siklus';
        $data['user'] = $this->user_data;
        $data['content'] = 'petani/siklus';
        
        // Logika Tab ada di View (menggunakan $_GET), jadi controller cukup load view saja
        $this->load->view('petani/layout_petani', $data);
    }

    public function laporan()
    {
        $data['title'] = 'Informasi & Bantuan - AgriPlatform';
        $data['active_menu'] = 'laporan'; // Sesuai ID di sidebar layout
        $data['user'] = $this->user_data;
        $data['content'] = 'petani/laporan';

        $this->load->view('petani/layout_petani', $data);
    }

    // --- FITUR BARU: PROFIL ---
    public function profil()
    {
        $data['title'] = 'Profil Saya - AgriPlatform';
        $data['active_menu'] = 'profil';
        $data['user'] = $this->user_data;
        $data['content'] = 'petani/profil';

        // Data Mockup Profil (Object)
        // Kita pakai json_decode(json_encode(...)) agar jadi object, bukan array
        // Supaya di view bisa panggil $profil->namaLengkap
        $data['profil'] = json_decode(json_encode([
            'namaLengkap' => 'Rzayyan Rizka',
            'role' => 'Kebun Makmur',
            'email' => 'petani@example.com',
            'alamat' => 'Desa Makmur, Kec. Subang, Jawa Barat',
            'namaPertanian' => 'Kebun Makmur',
            'telepon' => '0812-3456-7890',
            'bio' => 'Petani dengan pengalaman 15 tahun di bidang pertanian padi dan palawija.'
        ]));

        $this->load->view('petani/layout_petani', $data);
    }

    public function update_profil()
    {
        // Logika update ke database akan ditaruh di sini
        // $this->Model_petani->update_profil($this->input->post());
        
        // Redirect kembali ke halaman profil
        redirect('petani/profil');
    }
    
    // Fungsi Dummy untuk menangani Form Submit (Lahan)
    public function simpan_lahan()
    {
        // Di sini nanti logika $this->db->insert(...)
        // Setelah simpan, redirect kembali
        redirect('petani/lahan');
    }
}