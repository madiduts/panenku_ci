<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_d extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        // Load Model
        $this->load->model('M_dinas'); 
        $this->load->model('M_panen'); 
        
        // Gatekeeping: Cek Login & Role
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'dinas') {
            show_error('Akses Ditolak: Halaman ini hanya untuk Dinas Pertanian.', 403);
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

    public function validasi()
    {
        // Ambil Data Hama dari Model yang baru diperbaiki
        $data['laporanHama'] = $this->M_dinas->get_laporan_hama_pending();
        
        // Ambil Data Panen
        $data['laporanPanen'] = $this->M_panen->get_pending_panen(); 

        // Hitung Stats
        $data['stats'] = [
            'laporanPending'    => count($data['laporanHama']) + count($data['laporanPanen']),
            'disetujuiBulanIni' => 0,
            'ditolakBulanIni'   => 0
        ];

        $data['title'] = 'Validasi Laporan';
        
        $this->load->view('templates/header_dinas', $data); 
        $this->load->view('dinas/validasi', $data);         
        $this->load->view('templates/footer_dinas');        
    }

    // Function untuk memproses validasi (Action Form)
    public function submit_validasi_hama()
    {
        $id = $this->input->post('lapor_hama_id');
        $status_input = $this->input->post('status');
        $rekomendasi = $this->input->post('rekomendasi');

        if(!$id) {
            $this->session->set_flashdata('error', 'ID Laporan tidak ditemukan.');
            redirect('dinas/dashboard_d/validasi?tab=hama');
        }

        $status_db = ($status_input == 'Diterima') ? 'Valid' : 'Reject';

        $data = [
            'status_validasi'  => $status_db,
            'catatan_validasi' => $rekomendasi,
            'tgl_validasi'     => date('Y-m-d H:i:s'),
            'validator_id'     => $this->session->userdata('user_id')
        ];

        // Pastikan nama kolom Primary Key di tabel laporan_hama adalah 'laporan_id'
        $this->db->where('laporan_id', $id);
        $this->db->update('laporan_hama', $data);
        
        $this->session->set_flashdata('success', 'Validasi berhasil disimpan.');
        redirect('dinas/dashboard_d/validasi?tab=hama');
    }
    
    public function submit_validasi_panen() {
        // ... logika panen ...
        redirect('dinas/dashboard_d/validasi?tab=panen');
    }
}