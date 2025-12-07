<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siklus extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) redirect('auth/login');
        
        $this->load->model('M_lahan');
        // Load library form validation di construct agar siap pakai
        $this->load->library('form_validation');
    }
    public function index() {
        // ... Load view petani/siklus ...
    }
}