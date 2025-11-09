<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['settings'] = $this->Settings_model->get_settings();
        $this->load->view('admin/settings', $data);
    }

    public function update()
    {
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('jam_operasional', 'Jam Operasional', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = array(
                'alamat' => $this->input->post('alamat'),
                'google_maps_link' => $this->input->post('google_maps_link'),
                'jam_senin' => $this->input->post('jam_senin'),
                'jam_selasa' => $this->input->post('jam_selasa'),
                'jam_rabu' => $this->input->post('jam_rabu'),
                'jam_kamis' => $this->input->post('jam_kamis'),
                'jam_jumat' => $this->input->post('jam_jumat'),
                'jam_sabtu' => $this->input->post('jam_sabtu'),
                'jam_minggu' => $this->input->post('jam_minggu'),
                'jumlah_meja' => $this->input->post('jumlah_meja')
            );

            // Handle file uploads
            $config['upload_path'] = './assets/img/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp';
            $this->load->library('upload', $config);

            // Existing image uploads
            if ($this->upload->do_upload('logo')) {
                $data['logo'] = $this->upload->data('file_name');
            }

            if ($this->upload->do_upload('banner1')) {
                $data['banner1'] = $this->upload->data('file_name');
            }

            if ($this->upload->do_upload('banner2')) {
                $data['banner2'] = $this->upload->data('file_name');
            }

            if ($this->upload->do_upload('banner3')) {
                $data['banner3'] = $this->upload->data('file_name');
            }

            if ($this->upload->do_upload('all_categories_icon')) {
                $data['all_categories_icon'] = $this->upload->data('file_name');
            }

            // New background image upload
            if ($this->upload->do_upload('background_image')) {
                $data['background_image'] = $this->upload->data('file_name');
            }

            $this->Settings_model->update_settings($data);
            redirect('admin_settings');
        }
    }
}
