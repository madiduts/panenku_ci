<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petani extends CI_Controller {

    public function index()
    {
        // Memuat view utama petani
        // Kita akan menamai file view-nya 'layout_petani.php'
        $this->load->view('petani/layout_petani');
    }
}