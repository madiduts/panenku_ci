<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petani extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load URL Helper agar base_url() bisa dipakai
        $this->load->helper('url');
    }

    public function index()
    {
        // Panggil templatenya urut dari atas ke bawah
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('petani/v_dashboard');
        $this->load->view('templates/footer');
    }
}