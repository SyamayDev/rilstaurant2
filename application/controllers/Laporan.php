<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesanan_model');
        if (!$this->session->userdata('admin_logged')) redirect('admin');
    }

    public function index()
    {
        // simple report: orders and totals
        $data['pesanan'] = $this->Pesanan_model->get_all();
        $this->load->view('admin/laporan', $data);
    }
}
