<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'dinas') {
            redirect('auth/login');
        }
        $this->load->model('M_validasi');
    }

    public function index() {
        $data['title'] = 'Validasi Data - Dinas Pertanian';
        $data['active_menu'] = 'validasi';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];

        // 1. Ambil Parameter Kategori (Default: panen)
        $category = $this->input->get('category') ?? 'panen';
        $tab = $this->input->get('tab') ?? 'masuk'; // masuk vs riwayat

        $data['current_category'] = $category;
        $data['current_tab'] = $tab;

        // 2. Hitung Badges (Untuk UI notifikasi angka merah)
        $data['counts'] = [
            'panen' => $this->M_validasi->count_pending('hasil_panen'),
            'lahan' => $this->M_validasi->count_pending('lahan'),
            'hama'  => $this->M_validasi->count_pending('laporan_hama')
        ];

        // 3. Switch Logic untuk Load Data
        $status_query = ($tab == 'masuk') ? 'Pending' : 'History';
        
        switch ($category) {
            case 'lahan':
                $data['table_data'] = $this->M_validasi->get_lahan($status_query);
                break;
            case 'hama':
                $data['table_data'] = $this->M_validasi->get_hama($status_query);
                break;
            case 'panen':
            default:
                $data['table_data'] = $this->M_validasi->get_panen($status_query);
                break;
        }

        $data['content'] = 'dinas/validasi';
        $this->load->view('dinas/layout_dinas', $data);
    }

    public function submit() {
        $category = $this->input->post('category');
        $id       = $this->input->post('id');
        $action   = $this->input->post('action');
        $catatan  = $this->input->post('catatan');

        // Tentukan Tabel & Primary Key berdasarkan kategori
        $table = ''; 
        $pk = '';

        if($category == 'panen') { 
            $table = 'hasil_panen'; $pk = 'panen_id'; 
        } elseif($category == 'lahan') { 
            $table = 'lahan'; $pk = 'lahan_id'; 
        } elseif($category == 'hama') { 
            $table = 'laporan_hama'; $pk = 'laporan_id'; 
        }

        $update_data = [
            'status_validasi' => $action,
            'catatan_validasi' => $catatan,
            'validator_id' => $this->session->userdata('user_id'),
            'tgl_validasi' => date('Y-m-d H:i:s')
        ];

        $this->M_validasi->update_status($table, $pk, $id, $update_data);
        redirect("dinas/validasi?category=$category&tab=riwayat");
    }
}