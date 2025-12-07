<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_d extends CI_Controller {
    public function index()
    {
        // Memuat view khusus untuk dashboard dinas
        // Kita akan menamai file view-nya 'layout_dinas.php'
        $this->load->view('dinas/layout_dinas');
    }
}