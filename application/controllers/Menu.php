<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        $this->load->model('Kategori_model');
        if (!$this->session->userdata('admin_logged')) redirect('admin');
    }

    public function index()
    {
        $data['menus'] = $this->Menu_model->get_all();
        $data['categories'] = $this->Kategori_model->get_all();
        $this->load->view('admin/menu/index', $data);
    }

    public function add()
    {
        $data['categories'] = $this->Kategori_model->get_all();
        $this->load->view('admin/menu/form', $data);
    }

    public function create()
    {
        $this->form_validation->set_rules('nama_menu', 'Nama', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga' => $this->input->post('harga'),
            'deskripsi' => $this->input->post('deskripsi'),
            'detail_lengkap' => $this->input->post('detail_lengkap'),
            'stok' => $this->input->post('stok')
        ];
        // include kategori only if the column exists in the menu table
        if ($this->db->field_exists('kategori', 'menu')) {
            $data['kategori'] = $this->input->post('kategori');
        }
        $id = $this->Menu_model->insert($data);
        // handle image upload
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/uploads/';
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = 'menu_' . $id . '_' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $file = $this->upload->data();
                $this->Menu_model->set_image($id, $file['file_name']);
            }
        }
        $this->session->set_flashdata('success', 'Menu ditambahkan');
        redirect('menu');
    }

    public function edit($id)
    {
        $data['menu'] = $this->Menu_model->get($id);
        $data['categories'] = $this->Kategori_model->get_all();
        $this->load->view('admin/menu/form', $data);
    }

    public function update($id)
    {
        $data = [
            'nama_menu' => $this->input->post('nama_menu'),
            'harga' => $this->input->post('harga'),
            'deskripsi' => $this->input->post('deskripsi'),
            'detail_lengkap' => $this->input->post('detail_lengkap'),
            'stok' => $this->input->post('stok')
        ];
        // include kategori only if the column exists in the menu table
        if ($this->db->field_exists('kategori', 'menu')) {
            $data['kategori'] = $this->input->post('kategori');
        }
        $this->Menu_model->update($id, $data);
        // handle image upload if any
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/uploads/';
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = 'menu_' . $id . '_' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $file = $this->upload->data();
                $this->Menu_model->set_image($id, $file['file_name']);
            }
        }
        $this->session->set_flashdata('success', 'Menu diperbarui');
        redirect('menu');
    }

    public function delete($id)
    {
        // delete image file if exists
        $item = $this->Menu_model->get($id);
        if ($item && !empty($item->gambar)) {
            $path = FCPATH . 'assets/uploads/' . $item->gambar;
            if (file_exists($path)) @unlink($path);
        }
        $this->Menu_model->delete($id);
        $this->session->set_flashdata('success', 'Menu dihapus');
        redirect('menu');
    }

    public function add_kategori_column()
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        // only add if not exists
        if (!$this->db->field_exists('kategori', 'menu')) {
            $this->db->query("ALTER TABLE `menu` ADD COLUMN `kategori` INT NULL AFTER `deskripsi`");
            $this->session->set_flashdata('success', 'Kolom kategori berhasil dibuat. Silakan refresh halaman.');
        } else {
            $this->session->set_flashdata('info', 'Kolom kategori sudah ada.');
        }
        redirect('menu');
    }
}
