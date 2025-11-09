<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Promo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin_logged')) {
            redirect('admin');
        }
        $this->load->model('Promo_model');
        $this->load->model('Menu_model'); // To get menu items for promo packages
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['promos'] = $this->Promo_model->get_all_promos();
        $this->load->view('admin/promo/list', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('nama_promo', 'Nama Promo', 'required|trim');
        $this->form_validation->set_rules('deskripsi_promo', 'Deskripsi Promo', 'trim');
        $this->form_validation->set_rules('harga_paket', 'Harga Paket', 'required|numeric');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required|valid_date');
        $this->form_validation->set_rules('tanggal_berakhir', 'Tanggal Berakhir', 'required|valid_date');
        $this->form_validation->set_rules('menu_items[]', 'Item Menu', 'required'); // At least one menu item

        if ($this->form_validation->run() == FALSE) {
            $data['menus'] = $this->Menu_model->get_all(); // Fetch all menus for selection
            $this->load->view('admin/promo/add', $data);
        } else {
            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'promo_' . time();

            $this->upload->initialize($config);

            $gambar_promo = '';
            if ($this->upload->do_upload('gambar_promo')) {
                $gambar_promo = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                $data['menus'] = $this->Menu_model->get_all();
                $this->load->view('admin/promo/add', $data);
                return;
            }

            $promo_data = [
                'nama_promo' => $this->input->post('nama_promo'),
                'deskripsi_promo' => $this->input->post('deskripsi_promo'),
                'harga_paket' => $this->input->post('harga_paket'),
                'gambar_promo' => $gambar_promo,
                'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                'tanggal_berakhir' => $this->input->post('tanggal_berakhir'),
                'status' => $this->input->post('status') ? 'active' : 'inactive',
            ];

            $promo_id = $this->Promo_model->insert_promo($promo_data);

            if ($promo_id) {
                $menu_items = $this->input->post('menu_items');
                $quantities = $this->input->post('quantities');
                $promo_items_data = [];
                foreach ($menu_items as $key => $menu_id) {
                    $promo_items_data[] = [
                        'id_promo' => $promo_id,
                        'id_menu' => $menu_id,
                        'quantity' => $quantities[$key]
                    ];
                }
                $this->Promo_model->insert_promo_items($promo_items_data);
                $this->session->set_flashdata('success', 'Promo berhasil ditambahkan.');
                redirect('admin_promo');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan promo.');
                redirect('admin_promo/add');
            }
        }
    }

    public function edit($id_promo)
    {
        $this->form_validation->set_rules('nama_promo', 'Nama Promo', 'required|trim');
        $this->form_validation->set_rules('deskripsi_promo', 'Deskripsi Promo', 'trim');
        $this->form_validation->set_rules('harga_paket', 'Harga Paket', 'required|numeric');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required|valid_date');
        $this->form_validation->set_rules('tanggal_berakhir', 'Tanggal Berakhir', 'required|valid_date');
        $this->form_validation->set_rules('menu_items[]', 'Item Menu', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['promo'] = $this->Promo_model->get_promo_by_id($id_promo);
            $data['promo_items'] = $this->Promo_model->get_promo_items($id_promo);
            $data['menus'] = $this->Menu_model->get_all();
            if (!$data['promo']) {
                $this->session->set_flashdata('error', 'Promo tidak ditemukan.');
                redirect('admin_promo');
            }
            $this->load->view('admin/promo/edit', $data);
        } else {
            $promo_data = [
                'nama_promo' => $this->input->post('nama_promo'),
                'deskripsi_promo' => $this->input->post('deskripsi_promo'),
                'harga_paket' => $this->input->post('harga_paket'),
                'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                'tanggal_berakhir' => $this->input->post('tanggal_berakhir'),
                'status' => $this->input->post('status') ? 'active' : 'inactive',
            ];

            // Handle image upload if a new image is provided
            if (!empty($_FILES['gambar_promo']['name'])) {
                $config['upload_path'] = './assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|webp';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'promo_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('gambar_promo')) {
                    $promo_data['gambar_promo'] = $this->upload->data('file_name');
                    // Delete old image if exists
                    $old_promo = $this->Promo_model->get_promo_by_id($id_promo);
                    if ($old_promo && $old_promo->gambar_promo && file_exists('./assets/uploads/' . $old_promo->gambar_promo)) {
                        unlink('./assets/uploads/' . $old_promo->gambar_promo);
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin_promo/edit/' . $id_promo);
                    return;
                }
            }

            if ($this->Promo_model->update_promo($id_promo, $promo_data)) {
                // Update promo items
                $this->Promo_model->delete_promo_items($id_promo); // Delete existing items
                $menu_items = $this->input->post('menu_items');
                $quantities = $this->input->post('quantities');
                $promo_items_data = [];
                foreach ($menu_items as $key => $menu_id) {
                    $promo_items_data[] = [
                        'id_promo' => $id_promo,
                        'id_menu' => $menu_id,
                        'quantity' => $quantities[$key]
                    ];
                }
                $this->Promo_model->insert_promo_items($promo_items_data);

                $this->session->set_flashdata('success', 'Promo berhasil diperbarui.');
                redirect('admin_promo');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui promo.');
                redirect('admin_promo/edit/' . $id_promo);
            }
        }
    }

    public function delete($id_promo)
    {
        $promo = $this->Promo_model->get_promo_by_id($id_promo);
        if ($promo) {
            if ($this->Promo_model->delete_promo($id_promo)) {
                // Delete promo image
                if ($promo->gambar_promo && file_exists('./assets/uploads/' . $promo->gambar_promo)) {
                    unlink('./assets/uploads/' . $promo->gambar_promo);
                }
                $this->session->set_flashdata('success', 'Promo berhasil dihapus.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus promo.');
            }
        } else {
            $this->session->set_flashdata('error', 'Promo tidak ditemukan.');
        }
        redirect('admin_promo');
    }

    public function toggle_status($id_promo)
    {
        $promo = $this->Promo_model->get_promo_by_id($id_promo);
        if ($promo) {
            $new_status = ($promo->status == 'active') ? 'inactive' : 'active';
            if ($this->Promo_model->update_promo($id_promo, ['status' => $new_status])) {
                $this->session->set_flashdata('success', 'Status promo berhasil diubah menjadi ' . $new_status . '.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengubah status promo.');
            }
        } else {
            $this->session->set_flashdata('error', 'Promo tidak ditemukan.');
        }
        redirect('admin_promo');
    }
}
