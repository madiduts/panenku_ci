<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Auth Check (Duplikasi dari Dashboard_d, ini acceptable untuk decoupling)
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'dinas') {
            show_error('Akses Ditolak', 403);
        }
        
        // Load Model Khusus Validasi
        $this->load->model('M_validasi');
    }

    public function index()
    {
        // Setup UI Data
        $data['title'] = 'Validasi Data - Dinas Pertanian';
        $data['active_menu'] = 'validasi'; // Highlight menu Validasi
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];
        
        // Ambil Data Statistik & Table
        $data['stats'] = [
            'laporanPending'    => $this->M_validasi->count_pending(),
            'disetujuiBulanIni' => $this->M_validasi->count_monthly_stats('Valid'),
            'ditolakBulanIni'   => $this->M_validasi->count_monthly_stats('Reject')
        ];

        $data['laporanMasuk']    = $this->M_validasi->get_panen_by_status('pending');
        $data['riwayatValidasi'] = $this->M_validasi->get_panen_by_status('history');
        
        $data['content'] = 'dinas/validasi';
        $this->load->view('dinas/layout_dinas', $data);
    }

    public function submit()
    {
        // Tangkap Input
        $panen_id = $this->input->post('panen_id');
        $action   = $this->input->post('action');
        $catatan  = $this->input->post('catatan');
        
        if (!$panen_id || !$action) {
            redirect('dinas/validasi');
        }

        // Siapkan Data
        $update_data = [
            'status_validasi'  => $action,
            'catatan_validasi' => $catatan,
            'validator_id'     => $this->session->userdata('user_id'),
            'tgl_validasi'     => date('Y-m-d H:i:s')
        ];

        // Eksekusi via Model
        $this->M_validasi->update_status($panen_id, $update_data);

        // Redirect ke tab riwayat agar user langsung lihat hasilnya
        redirect('dinas/validasi?tab=riwayat');
    }
}