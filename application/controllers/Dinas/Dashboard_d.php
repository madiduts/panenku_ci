<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_d extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        // Mock User Session
        $this->user_data = [
            'name' => 'Rzayyan Rizka',
            'role' => 'Admin Dinas', 
            'avatar' => 'RZ'
        ];
    }

    public function index()
    {
        redirect('dinas/dashboard');
    }

    public function dashboard()
    {
        $data['title'] = 'Monitoring & Visualisasi - Dinas Pertanian';
        $data['active_menu'] = 'monitoring';
        $data['user'] = $this->user_data;
        $data['content'] = 'dinas/dashboard';

        $data['stats'] = [
            'totalPetani' => 1250,
            'totalLahan' => 4500,
            'laporanPending' => 12,
            'warningHama' => 3
        ];

        $data['ews'] = json_decode(json_encode([
            ['lokasi' => 'Desa Sukamaju', 'hama' => 'Wereng Coklat', 'level' => 'Waspada', 'luas' => '15 Ha'],
            ['lokasi' => 'Desa Bojong', 'hama' => 'Tikus', 'level' => 'Bahaya', 'luas' => '25 Ha']
        ]));

        $this->load->view('dinas/layout_dinas', $data);
    }

    public function validasi()
    {
        $data['title'] = 'Validasi Data - Dinas Pertanian';
        $data['active_menu'] = 'validasi';
        $data['user'] = $this->user_data;
        $data['content'] = 'dinas/validasi';
        
        $data['stats'] = ['laporanPending' => 4];

        $data['laporanMasuk'] = json_decode(json_encode([
            ['id' => 'RPT001', 'petani' => 'Budi Santoso', 'lokasi' => 'Desa Makmur', 'jenis' => 'Panen', 'komoditas' => 'Padi', 'luas' => '2.5', 'hasil' => '12.5', 'tanggal' => '01 Des', 'produktivitas' => '5.00'],
            ['id' => 'RPT002', 'petani' => 'Siti Aminah', 'lokasi' => 'Desa Sejahtera', 'jenis' => 'Tanam', 'komoditas' => 'Jagung', 'luas' => '1.8', 'hasil' => '-', 'tanggal' => '28 Nov', 'produktivitas' => '-'],
            ['id' => 'RPT003', 'petani' => 'Ahmad Yani', 'lokasi' => 'Desa Subur', ']()
