<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_ulasan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ulasan_model');
        $this->load->model('Menu_model');
        if (!$this->session->userdata('admin_logged')) redirect('admin');
    }

    public function index($filter = 'all')
    {
        $status = null;
        if ($filter === 'pending') $status = 'pending';
        if ($filter === 'disetujui') $status = 'disetujui';
        if ($filter === 'ditolak') $status = 'ditolak';
        $data['ulasan'] = $this->Ulasan_model->list_all($status);
        $this->load->view('admin/ulasan/index', $data);
    }

    public function set_status()
    {
        // expects POST: id, status
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if (!in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            echo json_encode(['success' => false, 'msg' => 'Invalid status']);
            return;
        }
        $this->Ulasan_model->set_status($id, $status);
        echo json_encode(['success' => true]);
    }
}
