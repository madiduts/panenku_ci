<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek Login di sini
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        $this->load->model('M_siklus');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Dashboard';
        $data['active_menu'] = 'dashboard';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // 1. Hitung Total Lahan (Sudah Benar)
        $lahan_petani = $this->M_lahan->get_by_petani($user_id);
        $data['total_lahan'] = count($lahan_petani);

        // 2. [PERBAIKAN] Hitung Total Luas (BAGIAN INI YANG HILANG)
        // Kita panggil fungsi sum_luas_lahan di Model
        $luas = $this->M_lahan->sum_luas_lahan($user_id);
        
        // Cek: Jika hasilnya NULL (belum ada lahan), set jadi 0
        $data['total_luas'] = empty($luas) ? 0 : $luas;

        // 3. Data Lahan Aktif untuk Widget (Sudah Benar)
        $siklus_aktif = $this->M_siklus->get_active_siklus($user_id);
        $data['total_lahan_aktif'] = count($siklus_aktif);
        
        // Mapping Lahan List untuk Widget Dashboard
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
}