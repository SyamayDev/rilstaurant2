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
        $this->load->model('Settings_model');
    }

    public function index()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        // show all menus by default (so category filter can work across entire catalog)
        $data['menus'] = $this->Menu_model->get_with_reviews();
        $data['ulasan_pelanggan'] = $this->Ulasan_model->get_approved_reviews();
        $data['settings'] = $this->Settings_model->get_settings();
        $this->load->view('pelanggan/katalog', $data);
    }

    public function all()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        // return all menus with reviews
        $data['menus'] = $this->Menu_model->get_with_reviews();
        $data['settings'] = $this->Settings_model->get_settings();
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

    public function get_chatbot_context()
    {
        header('Content-Type: application/json');
        
        $menus = $this->Menu_model->get_all();
        $categories = $this->Kategori_model->get_all();
        $settings = $this->Settings_model->get_settings();
        $best_seller = $this->Menu_model->get_best_seller(5); // Get top 5 best seller

        $context = [
            'menus' => array_map(function($menu) {
                return [
                    'nama' => $menu->nama_menu,
                    'harga' => (int)$menu->harga,
                    'kategori' => $menu->kategori, // Assuming this is the category ID
                    'stok' => (int)$menu->stok,
                    'deskripsi' => $menu->deskripsi
                ];
            }, $menus),
            'kategori' => array_map(function($cat) {
                return [
                    'id' => $cat->id_kategori,
                    'nama' => $cat->nama_kategori
                ];
            }, $categories),
            'best_seller' => array_map(function($menu) {
                return $menu->nama_menu;
            }, $best_seller),
            'info_resto' => [
                'alamat' => $settings->alamat,
                'jam_buka' => [
                    'senin' => $settings->jam_senin,
                    'selasa' => $settings->jam_selasa,
                    'rabu' => $settings->jam_rabu,
                    'kamis' => $settings->jam_kamis,
                    'jumat' => $settings->jam_jumat,
                    'sabtu' => $settings->jam_sabtu,
                    'minggu' => $settings->jam_minggu,
                ]
            ]
        ];

        echo json_encode($context);
    }
}
