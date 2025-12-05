<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // --- AUTH GUARD ---
        if ($this->session->userdata('role') != 'petani') {
            redirect('auth/login');
        }
        
        // Load model nanti jika sudah dibuat
        // $this->load->model('M_lahan');
        // $this->load->model('M_siklus');
    }

    public function index()
    {
        // 1. SETUP UI
        $data['title']       = 'Dashboard Petani';
        $data['active_menu'] = 'dashboard';
        $data['user_name']   = $this->session->userdata('nama_lengkap');

        // 2. PREPARE DATA (MOCK DATA / DATA PALSU)
        // Nanti diganti dengan: $this->M_lahan->count_all($id_petani);
        
        // Card 1: Total Lahan
        $data['card_total_lahan'] = 8; 
        $data['card_luas_total']  = 150; // Hektar

        // Card 2: Lahan Aktif
        $data['card_lahan_aktif']     = 5;
        $data['card_lahan_istirahat'] = 3;

        // Card 3: Siklus Tanam
        $data['card_siklus_total'] = 6;
        $data['card_siap_panen']   = 2;

        // Card 4: Cuaca (Biasanya pakai API, kita hardcode dulu)
        $data['cuaca_suhu'] = 28;
        $data['cuaca_desc'] = 'Cerah Berawan';

        // Progress Bar Lahan (Simulasi Array of Objects dari Database)
        $data['lahan_progress'] = [
            [
                'nama' => 'Lahan A-01',
                'komoditas' => 'Padi',
                'persen' => 45,
                'color' => 'bg-success' // Bisa diatur logic-nya di View atau Controller
            ],
            [
                'nama' => 'Lahan B-02',
                'komoditas' => 'Jagung',
                'persen' => 95,
                'color' => 'bg-warning'
            ],
            [
                'nama' => 'Lahan C-03',
                'komoditas' => 'Kedelai',
                'persen' => 60,
                'color' => 'bg-info'
            ],
            [
                'nama' => 'Lahan D-04',
                'komoditas' => 'Cabai',
                'persen' => 20,
                'color' => 'bg-danger'
            ]
        ];

        // 3. LOAD VIEWS
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('petani/v_dashboard', $data); // View yang sudah dimodifikasi
        $this->load->view('templates/footer');
    }
}