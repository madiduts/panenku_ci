<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheduler extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_notifikasi');
    }

    // Akses URL ini untuk memicu pengecekan: 
    // localhost/panenku_ci/scheduler/run_daily
    public function run_daily() {
        echo "<h1>Menjalankan Scheduler Harian...</h1>";
        
        $count = $this->cek_pengingat_siklus();
        
        echo "<hr>Selesai. $count notifikasi dikirim.";
    }

    private function cek_pengingat_siklus() {
        // 1. Ambil semua tanaman yang statusnya AKTIF
        $query = "
            SELECT s.siklus_id, s.tanggal_tanam, k.nama_komoditas, u.user_id
            FROM siklus_tanam s
            JOIN lahan l ON l.lahan_id = s.lahan_id
            JOIN users u ON u.user_id = l.user_id
            JOIN ref_komoditas k ON k.komoditas_id = s.komoditas_id
            WHERE s.status_aktif = 1
        ";
        $tanaman_aktif = $this->db->query($query)->result();
        
        $notif_sent = 0;
        $hari_ini = new DateTime();

        foreach ($tanaman_aktif as $t) {
            $tgl_tanam = new DateTime($t->tanggal_tanam);
            $usia_hari = $hari_ini->diff($tgl_tanam)->days;

            // --- LOGIKA PENGINGAT (15, 30, 60 HST) ---
            
            // Pengingat 1: Usia 15 Hari (Pemupukan 1)
            if ($usia_hari == 15) {
                $this->M_notifikasi->send(
                    $t->user_id,
                    'warning', // Kuning
                    'Jadwal Pemupukan I',
                    "Tanaman {$t->nama_komoditas} Anda sudah 15 HST. Waktunya pemupukan tahap pertama.",
                    base_url('petani/siklus')
                );
                $notif_sent++;
            }
            
            // Pengingat 2: Usia 30 Hari (Pemupukan 2 / Cek Hama)
            else if ($usia_hari == 30) {
                $this->M_notifikasi->send(
                    $t->user_id,
                    'warning',
                    'Jadwal Perawatan',
                    "Tanaman {$t->nama_komoditas} usia 30 HST. Cek kondisi daun dan air.",
                    base_url('petani/siklus')
                );
                $notif_sent++;
            }

            // Pengingat 3: Usia 60 Hari (Persiapan Panen - tergantung komoditas)
            else if ($usia_hari == 60) {
                $this->M_notifikasi->send(
                    $t->user_id,
                    'info', // Biru
                    'Fase Generatif',
                    "Tanaman {$t->nama_komoditas} memasuki usia 60 HST. Pantau pengisian bulir/buah.",
                    base_url('petani/siklus')
                );
                $notif_sent++;
            }
        }
        
        return $notif_sent;
    }
}