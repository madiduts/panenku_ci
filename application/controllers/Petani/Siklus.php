<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siklus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_siklus');
        $this->load->model('M_lahan');
        $this->load->model('M_master');
        $this->load->model('M_notifikasi'); // Pastikan Model Notifikasi diload
        $this->load->library('form_validation');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Siklus Tanam';
        $data['active_menu'] = 'siklus';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'), 
            'role' => 'Petani',
            'avatar' => 'default.jpg'
        ];

        // 1. Data Utama (List)
        $data['siklus_list'] = $this->M_siklus->get_active_siklus($user_id);
        $data['lahan_opsi'] = $this->M_lahan->get_by_petani($user_id);
        $data['komoditas_opsi'] = $this->M_master->get_komoditas();
        $data['hama_opsi'] = $this->M_master->get_hama(); 

        // 2. [LOGIKA INI YANG HILANG DI KODEMU]
        // Cek URL: apakah ada ?hama_id=123 ?
        $hama_id_notif = $this->input->get('hama_id');
        
        if ($hama_id_notif) {
            // Ambil data spesifik dari Model
            $detail = $this->M_siklus->get_detail_hama($hama_id_notif);
            
            // Kirim ke View dengan nama variabel $highlight_hama
            if($detail) {
                $data['highlight_hama'] = $detail;
            }
        }

        $data['content'] = 'petani/siklus';
        $this->load->view('petani/layout_petani', $data);
    }

    // Method Tambah Siklus Tanam
    public function tambah() {
        $this->form_validation->set_rules('lahan_id', 'Lahan', 'required|numeric');
        $this->form_validation->set_rules('komoditas_id', 'Komoditas', 'required|numeric');
        $this->form_validation->set_rules('tanggal_tanam', 'Tanggal Tanam', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Data tidak lengkap.');
            redirect('petani/siklus');
        } else {
            $tgl_tanam = $this->input->post('tanggal_tanam');
            $data = [
                'lahan_id'      => $this->input->post('lahan_id'),
                'komoditas_id'  => $this->input->post('komoditas_id'),
                'tanggal_tanam' => $tgl_tanam,
                'estimasi_panen'=> date('Y-m-d', strtotime($tgl_tanam . ' + 90 days')),
                'status_aktif'  => 1 
            ];
            $this->M_siklus->insert($data);
            $this->session->set_flashdata('success', 'Siklus tanam dimulai.');
            redirect('petani/siklus');
        }
    }

    // [BARU] Method Simpan Riwayat Pupuk
    public function simpan_pupuk() {
        $data = [
            'siklus_id'     => $this->input->post('siklus_id'),
            'jenis_pupuk'   => $this->input->post('jenis_pupuk', TRUE),
            'jumlah_sebar'  => $this->input->post('jumlah_sebar', TRUE),
            'satuan'        => $this->input->post('satuan'),
            'tanggal_sebar' => $this->input->post('tanggal_sebar'),
            'catatan'       => $this->input->post('catatan', TRUE)
        ];
        $this->M_siklus->insert_pupuk($data);
        $this->session->set_flashdata('success', 'Riwayat pupuk dicatat.');
        redirect('petani/siklus');
    }

    // [BARU] Method Lapor Hama
    public function lapor_hama() {
        // ... (Kode upload foto kamu yang lama) ...
        // Agar aman, saya tulis ulang basic-nya
        $data = [
            'siklus_id' => $this->input->post('siklus_id'),
            'hama_id'   => $this->input->post('hama_id'),
            'tingkat_keparahan' => $this->input->post('tingkat_keparahan'),
            'tanggal_lapor' => date('Y-m-d H:i:s'),
            'status_validasi' => 'Pending' // Pastikan default pending
        ];

        if (!empty($_FILES['foto_bukti']['name'])) {
            $config['upload_path']   = './uploads/hama/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name']     = 'hama_' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('foto_bukti')) {
                $uploadData = $this->upload->data();
                $data['foto_bukti'] = $uploadData['file_name'];
            }
        }
        
        $this->M_siklus->insert_hama($data);
        $this->session->set_flashdata('success', 'Laporan hama dikirim.');
        redirect('petani/siklus');
    }
}