<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'dinas') {
            redirect('auth/login');
        }
        $this->load->model('M_validasi');
        $this->load->model('M_notifikasi'); // WAJIB LOAD INI
    }

    // ... (Function index tetap sama, tidak perlu diubah) ...
    public function index() {
        $data['title'] = 'Validasi Data';
        $data['active_menu'] = 'validasi';
        $data['user'] = ['name' => $this->session->userdata('full_name'), 'role' => 'Admin Dinas', 'avatar' => 'default.jpg'];
        $tab = $this->input->get('tab') ?? 'lahan'; 
        
        $data['laporanLahan'] = []; $data['laporanPanen'] = []; $data['laporanHama']  = [];

        if ($tab === 'lahan') $data['laporanLahan'] = $this->M_validasi->get_lahan('Pending');
        elseif ($tab === 'panen') $data['laporanPanen'] = $this->M_validasi->get_panen('Pending');
        elseif ($tab === 'hama') $data['laporanHama'] = $this->M_validasi->get_hama('Pending');
        
        $data['countLahan'] = $this->M_validasi->count_pending('lahan');
        $data['countPanen'] = $this->M_validasi->count_pending('hasil_panen');
        $data['countHama']  = $this->M_validasi->count_pending('laporan_hama');

        $data['content'] = 'dinas/validasi';
        $this->load->view('dinas/layout_dinas', $data);
    }

    // ... (Function submit lahan & panen tetap sama) ...
    public function submit_validasi_lahan() {
        $id = $this->input->post('lahan_id');
        $status = $this->input->post('status');
        $catatan = $this->input->post('catatan');
        $data = ['status_validasi' => $status, 'catatan_validasi' => $catatan, 'validator_id' => $this->session->userdata('user_id'), 'tgl_validasi' => date('Y-m-d H:i:s')];
        $this->M_validasi->update_status('lahan', 'lahan_id', $id, $data);
        $this->session->set_flashdata('success', 'Status lahan diperbarui.');
        redirect('dinas/validasi?tab=lahan');
    }

    public function submit_validasi_panen() {
        $id = $this->input->post('panen_id');
        $status = $this->input->post('status');
        $catatan = $this->input->post('catatan');
        $data = ['status_validasi' => $status, 'catatan_validasi' => $catatan, 'validator_id' => $this->session->userdata('user_id'), 'tgl_validasi' => date('Y-m-d H:i:s')];
        $this->M_validasi->update_status('hasil_panen', 'panen_id', $id, $data);
        $this->session->set_flashdata('success', 'Laporan panen diperbarui.');
        redirect('dinas/validasi?tab=panen');
    }

    // --- [LOGIKA EWS IMPLEMENTATION] ---
    public function submit_validasi_hama() {
        $id_laporan = $this->input->post('lapor_hama_id');
        $status = ($this->input->post('status') == 'Diterima') ? 'Valid' : 'Reject';
        $catatan = $this->input->post('rekomendasi');

        // 1. Update Data Utama
        $data = [
            'status_validasi' => $status,
            'catatan_validasi' => $catatan,
            'validator_id' => $this->session->userdata('user_id'),
            'tgl_validasi' => date('Y-m-d H:i:s')
        ];
        $this->M_validasi->update_status('laporan_hama', 'laporan_id', $id_laporan, $data);

        // 2. JIKA VALID => KIRIM NOTIFIKASI BROADCAST (EWS)
        if ($status == 'Valid') {
            // A. Ambil Detail Laporan (Lokasi Desa & Jenis Hama)
            $query_detail = "
                SELECT l.lokasi_desa, r.nama_hama, u.user_id as pelapor_id, u.full_name as pelapor_nama
                FROM laporan_hama lh
                JOIN siklus_tanam s ON s.siklus_id = lh.siklus_id
                JOIN lahan l ON l.lahan_id = s.lahan_id
                JOIN users u ON u.user_id = l.user_id
                JOIN ref_jenis_hama r ON r.hama_id = lh.hama_id
                WHERE lh.laporan_id = ?
            ";
            $detail = $this->db->query($query_detail, array($id_laporan))->row();

            if ($detail) {
                // B. Cari Petani Lain di Desa yang SAMA (Kecuali Pelapor)
                $query_tetangga = "
                    SELECT DISTINCT u.user_id 
                    FROM users u
                    JOIN lahan l ON l.user_id = u.user_id
                    WHERE l.lokasi_desa = ? 
                    AND u.user_id != ? 
                    AND u.role = 'petani'
                ";
                $tetangga = $this->db->query($query_tetangga, array($detail->lokasi_desa, $detail->pelapor_id))->result();

                // C. Broadcast Loop
                foreach($tetangga as $t) {
                    $judul = "Waspada Hama {$detail->nama_hama}!";
                    $pesan = "Hama terdeteksi valid di desa Anda ({$detail->lokasi_desa}). Segera cek lahan.";
                    
                    $this->M_notifikasi->send($t->user_id, 'danger', $judul, $pesan, base_url('petani/siklus'));
                }

                // D. Kirim Notif Balik ke Pelapor (Bahwa laporannya diterima)
                // [PERBAIKAN] Tambahkan query string ?hama_id=... di link
                $link_tujuan = base_url('petani/siklus?hama_id=' . $id_laporan);
                
                $this->M_notifikasi->send(
                    $detail->pelapor_id, 
                    'success', 
                    'Laporan Hama Diterima', 
                    "Laporan Anda tentang {$detail->nama_hama} telah diverifikasi Dinas. Klik untuk lihat rekomendasi.", 
                    $link_tujuan // Link sekarang spesifik
                );
            }
        }
        
        $this->session->set_flashdata('success', 'Laporan hama diproses & peringatan dikirim.');
        redirect('dinas/validasi?tab=hama');
    }
}