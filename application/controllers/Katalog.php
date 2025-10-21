<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Katalog extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        $this->load->model('Kategori_model');
        $this->load->model('Ulasan_model');
    }

    public function index()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        // show all menus by default (so category filter can work across entire catalog)
        $data['menus'] = $this->Menu_model->get_with_reviews();
        $data['ulasan_pelanggan'] = $this->Ulasan_model->get_approved_reviews(5);
        $this->load->view('pelanggan/katalog', $data);
    }

    public function all()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        // return all menus with reviews
        $data['menus'] = $this->Menu_model->get_with_reviews();
        $this->load->view('pelanggan/katalog', $data);
    }

    public function detail($id)
    {
        $menu = $this->Menu_model->get_by_id_with_reviews($id);
        echo json_encode($menu);
    }

    public function keranjang()
    {
        $this->load->view('pelanggan/keranjang');
    }

    public function status()
    {
        $this->load->view('pelanggan/status');
    }

    public function ulasan()
    {
        $this->load->view('pelanggan/ulasan');
    }

    public function riwayat()
    {
        $this->load->view('pelanggan/riwayat');
    }
}
