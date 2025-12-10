<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'dinas') {
            redirect('auth/login');
        }
        $this->load->model('M_laporan');
    }

    public function index() {
        $data['title'] = 'Laporan & Reporting';
        $data['active_menu'] = 'laporan';
        $data['user'] = [
            'name' => $this->session->userdata('full_name'),
            'role' => 'Admin Dinas', 
            'avatar' => 'default.jpg'
        ];

        $data['stats'] = $this->M_laporan->get_dashboard_stats();
        $data['rekapDesa'] = $this->M_laporan->get_rekap_per_desa();

        $data['content'] = 'dinas/laporan';
        $this->load->view('dinas/layout_dinas', $data);
    }

    // --- LOGIKA EXPORT EXCEL (NATIVE) ---
    public function export_excel() {
        // 1. Ambil data (Bisa ditambahkan filter dari input->get)
        $data['rekap'] = $this->M_laporan->get_rekap_per_desa();
        $tahun = $this->input->get('tahun') ?? date('Y');

        // 2. Set Header HTTP untuk memaksa download Excel
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Panen_$tahun.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // 3. Render View Khusus Excel (Tanpa Header/Footer Web)
        // Kita buat view sederhana langsung di sini atau load view terpisah
        echo "
        <center>
            <h3>LAPORAN REKAPITULASI HASIL PANEN TAHUN $tahun</h3>
        </center>
        <table border='1' width='100%'>
            <thead>
                <tr style='background-color: #f2f2f2;'>
                    <th>No</th>
                    <th>Desa</th>
                    <th>Total Lahan (Ha)</th>
                    <th>Jumlah Petani</th>
                    <th>Padi (Ton)</th>
                    <th>Jagung (Ton)</th>
                    <th>Kedelai (Ton)</th>
                    <th>Produktivitas (Ton/Ha)</th>
                </tr>
            </thead>
            <tbody>";
        
        $no = 1;
        if(!empty($data['rekap'])) {
            foreach($data['rekap'] as $row) {
                echo "
                <tr>
                    <td>$no</td>
                    <td>{$row->desa}</td>
                    <td>{$row->luas_total}</td>
                    <td>{$row->jumlah_petani}</td>
                    <td>{$row->padi_ton}</td>
                    <td>{$row->jagung_ton}</td>
                    <td>{$row->kedelai_ton}</td>
                    <td>{$row->produktivitas}</td>
                </tr>";
                $no++;
            }
        }
        echo "</tbody></table>";
    }

    // --- LOGIKA EXPORT PDF ---
    public function export_pdf() {
        // [IMPORTANT NOTE]
        // Fitur ini membutuhkan library 'Dompdf' atau 'MPDF'.
        // Karena saya tidak bisa menginstall library di tempatmu, 
        // saya akan gunakan cara paling sederhana: JAVASCRIPT PRINT.
        // Jika kamu sudah install Dompdf, kodenya beda lagi.
        
        $data['rekap'] = $this->M_laporan->get_rekap_per_desa();
        $data['tahun'] = $this->input->get('tahun') ?? date('Y');
        
        // Load view khusus cetak
        $this->load->view('dinas/laporan_cetak', $data);
    }
}