<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dinas extends CI_Controller {

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
        $data['active_menu'] = 'monitoring'; // ID sidebar
        $data['user'] = $this->user_data;
        $data['content'] = 'dinas/dashboard';

        // Mock Data Stats
        $data['stats'] = [
            'totalPetani' => 1250,
            'totalLahan' => 4500,
            'laporanPending' => 12,
            'warningHama' => 3
        ];

        // Mock Data EWS (Looping)
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
        
        // Mock Data Stats
        $data['stats'] = ['laporanPending' => 4];

        // Mock Laporan Masuk
        $data['laporanMasuk'] = json_decode(json_encode([
            ['id' => 'RPT001', 'petani' => 'Budi Santoso', 'lokasi' => 'Desa Makmur', 'jenis' => 'Panen', 'komoditas' => 'Padi', 'luas' => '2.5', 'hasil' => '12.5', 'tanggal' => '01 Des', 'produktivitas' => '5.00'],
            ['id' => 'RPT002', 'petani' => 'Siti Aminah', 'lokasi' => 'Desa Sejahtera', 'jenis' => 'Tanam', 'komoditas' => 'Jagung', 'luas' => '1.8', 'hasil' => '-', 'tanggal' => '28 Nov', 'produktivitas' => '-'],
            ['id' => 'RPT003', 'petani' => 'Ahmad Yani', 'lokasi' => 'Desa Subur', 'jenis' => 'Panen', 'komoditas' => 'Kedelai', 'luas' => '1.2', 'hasil' => '3.6', 'tanggal' => '30 Nov', 'produktivitas' => '3.00'],
        ]));

        // Mock Riwayat
        $data['riwayatValidasi'] = json_decode(json_encode([
            ['id' => 'RPT005', 'petani' => 'Suparman', 'jenis' => 'Panen', 'komoditas' => 'Padi', 'tanggal' => '27 Nov 2025', 'status' => 'Disetujui', 'validator' => 'Dr. Agus Wijaya', 'catatan' => 'Data sesuai dengan lapangan'],
            ['id' => 'RPT006', 'petani' => 'Rina Wati', 'jenis' => 'Tanam', 'komoditas' => 'Jagung', 'tanggal' => '22 Nov 2025', 'status' => 'Disetujui', 'validator' => 'Dr. Agus Wijaya', 'catatan' => 'Verified on site'],
            ['id' => 'RPT007', 'petani' => 'Hendra Kusuma', 'jenis' => 'Panen', 'komoditas' => 'Padi', 'tanggal' => '20 Nov 2025', 'status' => 'Ditolak', 'validator' => 'Ir. Siti Nurhaliza', 'catatan' => 'Data produktivitas tidak sesuai'],
        ]));

        $this->load->view('dinas/layout_dinas', $data);
    }

    public function laporan()
    {
        $data['title'] = 'Laporan - Dinas Pertanian';
        $data['active_menu'] = 'laporan';
        $data['user'] = $this->user_data;
        $data['content'] = 'dinas/laporan';

        // Mock Rekap Desa
        $data['rekapDesa'] = json_decode(json_encode([
            ['desa' => 'Desa Makmur', 'lahan' => 120, 'petani' => 45, 'padi_ton' => 300, 'jagung_ton' => 160, 'kedelai_ton' => 40, 'prod' => '4.17'],
            ['desa' => 'Desa Sejahtera', 'lahan' => 95, 'petani' => 38, 'padi_ton' => 250, 'jagung_ton' => 120, 'kedelai_ton' => 30, 'prod' => '4.21'],
            ['desa' => 'Desa Subur', 'lahan' => 150, 'petani' => 52, 'padi_ton' => 400, 'jagung_ton' => 200, 'kedelai_ton' => 40, 'prod' => '4.27'],
        ]));

        $this->load->view('dinas/layout_dinas', $data);
    }

    public function master_data()
    {
        $data['title'] = 'Master Data - Dinas Pertanian';
        $data['active_menu'] = 'master';
        $data['user'] = $this->user_data;
        $data['content'] = 'dinas/master_data';

        // Mock Master Data
        $data['masterKomoditas'] = json_decode(json_encode([
            ['id' => 1, 'nama' => 'Padi', 'varietas' => 'IR64', 'musim' => 'Sepanjang tahun', 'estimasi' => '100-120 hari'],
            ['id' => 2, 'nama' => 'Padi', 'varietas' => 'Ciherang', 'musim' => 'Sepanjang tahun', 'estimasi' => '110-120 hari'],
            ['id' => 3, 'nama' => 'Jagung', 'varietas' => 'Hibrida', 'musim' => 'Kemarau', 'estimasi' => '90-100 hari'],
        ]));

        $data['masterHama'] = json_decode(json_encode([
            ['id' => 1, 'nama' => 'Wereng Coklat', 'latin' => 'Nilaparvata lugens', 'komoditas' => 'Padi', 'bahaya' => 'Tinggi', 'penanganan' => 'Insektisida sistemik'],
            ['id' => 2, 'nama' => 'Penggerek Batang', 'latin' => 'Scirpophaga incertulas', 'komoditas' => 'Padi', 'bahaya' => 'Sedang', 'penanganan' => 'Sanitasi lahan'],
        ]));

        $data['masterPetani'] = json_decode(json_encode([
            ['id' => 1, 'nama' => 'Budi Santoso', 'nik' => '320101...', 'desa' => 'Desa Makmur', 'lahan' => '2.5 Ha', 'komoditas' => 'Padi, Jagung', 'telp' => '08123...', 'status' => 'Aktif'],
            ['id' => 2, 'nama' => 'Siti Aminah', 'nik' => '320102...', 'desa' => 'Desa Sejahtera', 'lahan' => '1.8 Ha', 'komoditas' => 'Jagung', 'telp' => '08124...', 'status' => 'Aktif'],
        ]));

        $this->load->view('dinas/layout_dinas', $data);
    }
}
