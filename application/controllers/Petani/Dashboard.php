<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek Login di sini
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        $this->load->model('M_siklus');
        $this->load->model('M_notifikasi');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Dashboard Petani';
        $data['active_menu'] = 'dashboard';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // 2. AMBIL DATA NOTIFIKASI
        // Ambil 5 notifikasi terbaru & jumlah yang belum dibaca
        $data['notifikasi_list'] = $this->M_notifikasi->get_by_user($user_id, 5);
        $data['notif_unread']    = $this->M_notifikasi->count_unread($user_id);

        // --- Data Statistik Lainnya (Tetap Sama) ---
        $lahan_petani = $this->M_lahan->get_by_petani($user_id);
        $data['total_lahan'] = count($lahan_petani);

        $luas = $this->M_lahan->sum_luas_lahan($user_id);
        $data['total_luas'] = empty($luas) ? 0 : $luas;

        $siklus_aktif = $this->M_siklus->get_active_siklus($user_id);
        $data['total_lahan_aktif'] = count($siklus_aktif);
        
        $data['lahan_list'] = [];
        foreach($siklus_aktif as $row) {
             $persen = $this->_hitung_progress($row['tanggal_tanam'], $row['estimasi_panen']);
             $data['lahan_list'][] = (object) [
                'id' => $row['kategori_lahan'], 
                'tanaman' => $row['nama_komoditas'],
                'luas' => $row['luas_lahan'] . ' Ha',
                'fase' => 'Masa Tanam', 
                'progress' => $persen, 
                'status' => 'Aktif'
            ];
        }

        $data['content'] = 'petani/dashboard'; 
        $this->load->view('petani/layout_petani', $data);
    }


    // Helper biar gak nulis ulang terus
    private function _get_user_data() {
        return [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];
    }

    private function _hitung_progress($tgl_tanam_str, $tgl_panen_str) {
        if(empty($tgl_tanam_str) || empty($tgl_panen_str)) return 0;
        try {
            $tgl_tanam = new DateTime($tgl_tanam_str);
            $tgl_panen = new DateTime($tgl_panen_str);
            $tgl_skrg  = new DateTime();
            $total_hari = $tgl_tanam->diff($tgl_panen)->days;
            $hari_jalan = $tgl_tanam->diff($tgl_skrg)->days;
            if ($total_hari <= 0) return 0;
            $persen = round(($hari_jalan / $total_hari) * 100);
            return ($persen > 100) ? 100 : (($persen < 0) ? 0 : $persen);
        } catch (Exception $e) { return 0; }
    }
}