<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek Sesi Dinas
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'dinas') {
            redirect('auth/login');
        }
        $this->load->model('M_validasi');
    }

    public function index() {
        $data['title'] = 'Validasi Data';
        $data['active_menu'] = 'validasi';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];

        // 1. Tangkap Parameter Tab (Default ke 'lahan' agar muncul pertama)
        $tab = $this->input->get('tab') ?? 'lahan'; 
        
        // 2. SAFETY FIRST: Inisialisasi Array Kosong
        // Agar View tidak error jika variabel ini belum diisi
        $data['laporanLahan'] = [];
        $data['laporanPanen'] = [];
        $data['laporanHama']  = [];

        // 3. LOGIKA PENGAMBILAN DATA (Efisiensi Query)
        // Hanya ambil data berat (Get Data) sesuai tab yang aktif
        if ($tab === 'lahan') {
            $data['laporanLahan'] = $this->M_validasi->get_lahan('Pending');
        } elseif ($tab === 'panen') {
            $data['laporanPanen'] = $this->M_validasi->get_panen('Pending');
        } elseif ($tab === 'hama') {
            $data['laporanHama'] = $this->M_validasi->get_hama('Pending');
        }
        
        // 4. BADGES COUNTER (Real-time Notification)
        // Hitung jumlah pending untuk SEMUA kategori agar badge merah tetap muncul
        $data['countLahan'] = $this->M_validasi->count_pending('lahan');
        $data['countPanen'] = $this->M_validasi->count_pending('hasil_panen');
        $data['countHama']  = $this->M_validasi->count_pending('laporan_hama');

        // Kirim ke View
        $data['content'] = 'dinas/validasi';
        $this->load->view('dinas/layout_dinas', $data);
    }

    // --- ACTION: SUBMIT VALIDASI LAHAN ---
    public function submit_validasi_lahan() {
        $id = $this->input->post('lahan_id');
        $status = $this->input->post('status'); // Valid / Reject
        $catatan = $this->input->post('catatan');

        $data = [
            'status_validasi' => $status,
            'catatan_validasi' => $catatan,
            'validator_id' => $this->session->userdata('user_id'),
            'tgl_validasi' => date('Y-m-d H:i:s')
        ];

        // Update via Model
        $this->M_validasi->update_status('lahan', 'lahan_id', $id, $data);
        
        $msg = ($status == 'Valid') ? 'Lahan berhasil diverifikasi.' : 'Pengajuan lahan ditolak.';
        $this->session->set_flashdata('success', $msg);
        redirect('dinas/validasi?tab=lahan');
    }

    // --- ACTION: SUBMIT PANEN ---
    public function submit_validasi_panen() {
        $id = $this->input->post('panen_id');
        $status = $this->input->post('status');
        $catatan = $this->input->post('catatan');

        $data = [
            'status_validasi' => $status,
            'catatan_validasi' => $catatan,
            'validator_id' => $this->session->userdata('user_id'),
            'tgl_validasi' => date('Y-m-d H:i:s')
        ];
        $this->M_validasi->update_status('hasil_panen', 'panen_id', $id, $data);
        $this->session->set_flashdata('success', 'Laporan panen diproses.');
        redirect('dinas/validasi?tab=panen');
    }

    // --- ACTION: SUBMIT HAMA ---
    public function submit_validasi_hama() {
        $id = $this->input->post('lapor_hama_id');
        $status = ($this->input->post('status') == 'Diterima') ? 'Valid' : 'Reject';
        $catatan = $this->input->post('rekomendasi');

        $data = [
            'status_validasi' => $status,
            'catatan_validasi' => $catatan,
            'validator_id' => $this->session->userdata('user_id'),
            'tgl_validasi' => date('Y-m-d H:i:s')
        ];
        $this->M_validasi->update_status('laporan_hama', 'laporan_id', $id, $data);
        $this->session->set_flashdata('success', 'Laporan hama diproses.');
        redirect('dinas/validasi?tab=hama');
    }
}