<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ulasan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ulasan_model');
    }

    public function submit()
    {
        $data = [
            'id_pelanggan' => $this->session->userdata('id_pelanggan') ?: 0,
            'id_pesanan' => $this->input->post('id_pesanan') ?: 0,
            'id_menu' => $this->input->post('id_menu') ?: null,
            'rating' => $this->input->post('rating'),
            'komentar' => $this->input->post('komentar'),
            'status_ulasan' => 'pending'
        ];
        $this->Ulasan_model->insert($data);
        $this->session->set_flashdata('success', 'Terima kasih atas ulasan Anda');
        redirect('/');
    }

    // AJAX endpoint to submit a review and return JSON
    public function ajax_submit()
    {
        // expect POST: id_menu, rating, komentar, nama_pelanggan
        $id_menu = $this->input->post('id_menu');
        $rating = $this->input->post('rating');
        $komentar = $this->input->post('komentar');
        $nama_pelanggan = $this->input->post('nama_pelanggan');

        // basic validation
        $errors = [];
        if (empty($id_menu) || !is_numeric($id_menu)) {
            $errors[] = 'Menu tidak ditemukan.';
        }
        if (empty($nama_pelanggan)) {
            $errors[] = 'Nama tidak boleh kosong.';
        }
        $ratingInt = intval($rating);
        if ($rating === null || $rating === '' || $ratingInt < 1 || $ratingInt > 5) {
            $errors[] = 'Rating harus antara 1 sampai 5.';
        }

        if (!empty($errors)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['success' => false, 'message' => implode(' ', $errors)]));
        }

        $data = [
            'nama_pelanggan' => $nama_pelanggan,
            'id_menu' => intval($id_menu),
            'id_pesanan' => null, // Set to null as it is no longer required for catalog reviews
            'rating' => $ratingInt,
            'komentar' => $komentar,
            'status_ulasan' => 'pending'
        ];

        $insertId = $this->Ulasan_model->insert($data);
        if ($insertId) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'message' => 'Ulasan dikirim dan menunggu moderasi.']));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['success' => false, 'message' => 'Terjadi kesalahan internal saat menyimpan ulasan.']));
        }
    }
}
