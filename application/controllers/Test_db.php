<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_db extends CI_Controller {

    public function index()
    {
        echo "<div style='font-family: monospace; padding: 20px;'>";
        echo "<h1>DIAGNOSA DATABASE (TRUTH SERUM)</h1>";

        // 1. CEK KONEKSI & NAMA DATABASE
        $this->load->database();
        echo "<h3>1. Koneksi Database</h3>";
        echo "Connected Database Name: <strong style='color:blue'>" . $this->db->database . "</strong><br>";
        echo "Hostname: " . $this->db->hostname . "<br>";
        
        // 2. CEK EKSISTENSI TABEL
        echo "<h3>2. Cek Tabel 'laporan_hama'</h3>";
        if ($this->db->table_exists('laporan_hama')) {
            echo "Status: <span style='color:green; font-weight:bold;'>TABEL DITEMUKAN ✅</span><br>";
        } else {
            echo "Status: <span style='color:red; font-weight:bold;'>TABEL TIDAK ADA ❌</span><br>";
            die("Stop Diagnosa: Tabel tidak ditemukan. Cek nama database di application/config/database.php");
        }

        // 3. CEK RAW DATA (Tanpa Model)
        echo "<h3>3. Cek Isi Data Mentah</h3>";
        $count_all = $this->db->count_all('laporan_hama');
        echo "Total Baris di tabel: <strong>" . $count_all . "</strong><br>";

        // 4. CEK DATA PENDING (Query Manual)
        echo "<h3>4. Cek Data Status 'Pending'</h3>";
        $query = $this->db->query("SELECT * FROM laporan_hama WHERE status_validasi = 'Pending'");
        $result = $query->result_array();
        
        echo "Jumlah data 'Pending': <strong>" . count($result) . "</strong><br>";
        
        if (count($result) > 0) {
            echo "<pre style='background:#eee; padding:10px;'>";
            print_r($result);
            echo "</pre>";
        } else {
            echo "<p style='color:red'>Data Pending KOSONG. Cek apakah di database tulisannya 'Pending' atau 'pending' (Huruf kecil)?</p>";
        }

        // 5. CEK RELASI (LEFT JOIN MANUAL)
        echo "<h3>5. Tes Relasi (Left Join)</h3>";
        // Kita tes join ke 1 tabel dulu: siklus_tanam
        $sql_join = "
            SELECT lh.laporan_id, s.siklus_id 
            FROM laporan_hama lh
            LEFT JOIN siklus_tanam s ON s.siklus_id = lh.siklus_id
            WHERE lh.status_validasi = 'Pending'
        ";
        $join_result = $this->db->query($sql_join)->result_array();
        echo "<pre style='background:#eee; padding:10px;'>";
        print_r($join_result);
        echo "</pre>";

        echo "</div>";
    }
}