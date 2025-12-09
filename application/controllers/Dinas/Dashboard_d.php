<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_d extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        // Cek Login & Role
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        if ($this->session->userdata('role') !== 'dinas') {
            show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses Ditolak');
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // 1. Load Model
        $this->load->model('M_dinas');

        // 2. Setup Data Dasar (INI YANG HILANG SEBELUMNYA)
        $data['title'] = 'Monitoring & Visualisasi - Dinas Pertanian';
        $data['active_menu'] = 'monitoring'; // <-- Variable ini wajib ada agar layout tidak error
        
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg' 
        ];
        
        $data['content'] = 'dinas/dashboard';

        // 3. Ambil Data Statistik (Stats Cards)
        $data['stats'] = [
            'totalPetani'    => $this->M_dinas->count_petani(),
            'totalLahan'     => $this->M_dinas->sum_luas_lahan(),
            'laporanPending' => $this->M_dinas->count_pending_panen(),
            'warningHama'    => $this->M_dinas->count_active_hama()
        ];

        // 4. Logika EWS (Early Warning System)
        // Mengambil data mentah dari Model yang sudah diperbaiki query-nya
        $raw_ews = $this->M_dinas->get_ews_list();
        $mapped_ews = [];
        
        foreach($raw_ews as $row) {
            // Presentation Logic: Menentukan Level Bahaya
            $level = 'Waspada'; // Default
            if ($row->tingkat_keparahan == 'Berat/Puso') {
                $level = 'Bahaya';
            } elseif ($row->tingkat_keparahan == 'Sedang') {
                $level = 'Siaga';
            }

            // Data Mapping untuk View
            $mapped_ews[] = (object) [
                'hama'      => $row->hama,       // Dari alias SQL
                'lokasi'    => $row->lokasi,     // Dari alias SQL
                'komoditas' => $row->komoditas,  // Dari alias SQL & COALESCE
                'level'     => $level,
                'luas'      => $row->luas_lahan . ' Ha',
                'waktu'     => date('d M Y', strtotime($row->tanggal_lapor))
            ];
        }
        $data['ews'] = $mapped_ews; // Kirim data yang sudah rapi ke View

        // 5. Data Chart
        $chart_data = $this->M_dinas->get_commodity_distribution();
        $data['chart_data'] = json_encode($chart_data);

        // 6. [BARU] Data GIS / Sebaran Desa
        $data['sebaran_desa'] = $this->M_dinas->get_village_stats();

        // 7. Load View Utama
        $this->load->view('dinas/layout_dinas', $data);
    }

    // --- Method Lainnya (Mock Data / Dummy) ---
    
    public function validasi()
    {
        $data['title'] = 'Validasi Data - Dinas Pertanian';
        $data['active_menu'] = 'validasi';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];
        $data['content'] = 'dinas/validasi';
        
        $data['stats'] = ['laporanPending' => 4];
        $data['laporanMasuk'] = json_decode(json_encode([])); 
        $data['riwayatValidasi'] = json_decode(json_encode([]));

        $this->load->view('dinas/layout_dinas', $data);
    }

    public function laporan()
    {
        $data['title'] = 'Laporan - Dinas Pertanian';
        $data['active_menu'] = 'laporan';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];
        $data['content'] = 'dinas/laporan';
        $data['rekapDesa'] = json_decode(json_encode([])); 

        $this->load->view('dinas/layout_dinas', $data);
    }

    public function master_data()
    {
        $data['title'] = 'Master Data - Dinas Pertanian';
        $data['active_menu'] = 'master';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];
        $data['content'] = 'dinas/master_data';
        
        $data['masterKomoditas'] = [];
        $data['masterHama'] = [];
        $data['masterPetani'] = [];

        $this->load->view('dinas/layout_dinas', $data);
    }
}