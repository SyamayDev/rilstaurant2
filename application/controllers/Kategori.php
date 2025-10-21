<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kategori_model');
        if (!$this->session->userdata('admin_logged')) redirect('admin');
    }

    public function index()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        $this->load->view('admin/kategori/index', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }
        $slug = url_title($this->input->post('nama_kategori'), '-', true);
        $data = ['nama_kategori' => $this->input->post('nama_kategori'), 'slug' => $slug];
        // handle image upload if kategori.gambar column exists or create helper
        if (!$this->db->field_exists('gambar', 'kategori')) {
            // try to add the column (non-destructive if exists)
            $this->db->query("ALTER TABLE `kategori` ADD COLUMN `gambar` VARCHAR(255) NULL AFTER `slug`");
        }
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/uploads/';
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = 'kategori_' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $file = $this->upload->data();
                $data['gambar'] = $file['file_name'];
            }
        }
        $this->Kategori_model->insert($data);
        $this->session->set_flashdata('success', 'Kategori ditambahkan');
        redirect('kategori');
    }

    public function edit($id)
    {
        $data = $this->Kategori_model->get($id);
        echo json_encode($data);
    }

    public function update($id)
    {
        $slug = url_title($this->input->post('nama_kategori'), '-', true);
        $data = ['nama_kategori' => $this->input->post('nama_kategori'), 'slug' => $slug];
        if (!$this->db->field_exists('gambar', 'kategori')) {
            $this->db->query("ALTER TABLE `kategori` ADD COLUMN `gambar` VARCHAR(255) NULL AFTER `slug`");
        }
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/uploads/';
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = 'kategori_' . $id . '_' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $file = $this->upload->data();
                $data['gambar'] = $file['file_name'];
            }
        }
        $this->Kategori_model->update($id, $data);
        $this->session->set_flashdata('success', 'Kategori diperbarui');
        redirect('kategori');
    }

    public function delete($id)
    {
        $this->Kategori_model->delete($id);
        $this->session->set_flashdata('success', 'Kategori dihapus');
        redirect('kategori');
    }
}
