<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petani extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load URL Helper agar base_url() bisa dipakai
        $this->load->helper('url');
    }

    // 1. Halaman Dashboard Utama (http://localhost/panenku_ci/index.php/petani)
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('petani/v_dashboard'); // File view dashboard
        $this->load->view('templates/footer');
    }

    // 2. Halaman Daftar Lahan (http://localhost/panenku_ci/index.php/petani/lahan)
    public function lahan()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('petani/v_lahan');     // File view lahan (yang tadi kita bahas)
        $this->load->view('templates/footer');
    }

    // 3. Halaman Siklus Tanam (http://localhost/panenku_ci/index.php/petani/siklus)
    public function siklus()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('petani/v_siklus');    // File view siklus
        $this->load->view('templates/footer');
    }

    // 4. Halaman Bantuan (http://localhost/panenku_ci/index.php/petani/bantuan)
    public function bantuan()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        // Kita buat file kosong dulu kalau belum ada
        $this->load->view('petani/v_bantuan');   
        $this->load->view('templates/footer');
    }

    // 5. Halaman Profil (http://localhost/panenku_ci/index.php/petani/profil)
    public function profil()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        // Kita buat file kosong dulu kalau belum ada
        $this->load->view('petani/v_profil');    
        $this->load->view('templates/footer');
    }
}