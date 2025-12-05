<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index()
    {
        // Tugas Satpam: Langsung lempar ke Petani Dashboard
        // Karena ini controller biasa, redirect() akan bekerja sempurna
        redirect('petani/dashboard');
    }
}