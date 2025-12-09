<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_d extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('M_dinas'); 
        $this->load->model('M_panen'); 
        
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'dinas') {
            show_error('Akses Ditolak', 403);
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $this->load->model('M_dinas');

        $data['title'] = 'Monitoring & Visualisasi - Dinas Pertanian';
        $data['active_menu'] = 'monitoring'; 
        
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg' 
        ];
        
        $data['content'] = 'dinas/dashboard';

        // Stats Dashboard
        $data['stats'] = [
            'totalPetani'    => $this->M_dinas->count_petani(),
            'totalLahan'     => $this->M_dinas->sum_luas_lahan(),
            'laporanPending' => $this->M_dinas->count_pending_panen(), // Tetap butuh ini untuk ringkasan
            'warningHama'    => $this->M_dinas->count_active_hama()
        ];

        // EWS Logic
        $raw_ews = $this->M_dinas->get_ews_list();
        $mapped_ews = [];
        foreach($raw_ews as $row) {
            $level = 'Waspada';
            if ($row->tingkat_keparahan == 'Berat/Puso') $level = 'Bahaya';
            elseif ($row->tingkat_keparahan == 'Sedang') $level = 'Siaga';

            $mapped_ews[] = (object) [
                'hama'      => $row->hama,       
                'lokasi'    => $row->lokasi,     
                'komoditas' => $row->komoditas,
                'level'     => $level,
                'luas'      => $row->luas_lahan . ' Ha',
                'waktu'     => date('d M Y', strtotime($row->tanggal_lapor))
            ];
        }
        $data['ews'] = $mapped_ews;

        $data['chart_data'] = json_encode($this->M_dinas->get_commodity_distribution());
        $data['sebaran_desa'] = $this->M_dinas->get_village_stats();

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

    public function validasi() {
        // 1. Ambil Data Panen (Kode Lama Kamu)
        $data['laporanPanen'] = $this->M_panen->get_pending_panen(); 
        
        // 2. AMBIL DATA HAMA (INI YANG KEMARIN HILANG!)
        // Variabel ini ($laporanHama) yang ditunggu oleh View validasi.php
        $data['laporanHama'] = $this->M_dinas->get_laporan_hama_pending();

        // 3. Debugging (Opsional - Hapus nanti)
        // Jika ingin cek apakah data ketarik, uncomment baris bawah:
        // echo '<pre>'; print_r($data['laporanHama']); die;

        // 4. Load View
        $data['title'] = 'Validasi Laporan';
        $this->load->view('templates/header', $data);
        $this->load->view('dinas/validasi', $data); // View yang sudah kamu perbaiki tadi
        $this->load->view('templates/footer');
    }
}