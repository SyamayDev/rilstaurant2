<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pelanggan_model');
    }

    public function register()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            return;
        }

        $email = $this->input->post('email');
        // check existing
        $exists = $this->db->get_where('pelanggan', ['email' => $email])->row();
        if ($exists) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar, silakan login.');
            redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            return;
        }

        $id = $this->Pelanggan_model->insert([
            'nama' => $this->input->post('nama'),
            'email' => $email,
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
        ]);

        // login after register
        $this->session->set_userdata('pelanggan_logged', $id);
        $this->session->set_flashdata('success', 'Daftar berhasil. Anda sekarang masuk.');
        redirect($_SERVER['HTTP_REFERER'] ?? base_url());
    }

    public function login()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            return;
        }

        $email = $this->input->post('email');
        $u = $this->db->get_where('pelanggan', ['email' => $email])->row();
        if (!$u || !isset($u->password) || !password_verify($this->input->post('password'), $u->password)) {
            $this->session->set_flashdata('error', 'Email atau password salah');
            redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            return;
        }

        $this->session->set_userdata('pelanggan_logged', $u->id_pelanggan);
        $this->session->set_flashdata('success', 'Berhasil masuk');
        redirect($_SERVER['HTTP_REFERER'] ?? base_url());
    }

    public function logout()
    {
        $this->session->unset_userdata('pelanggan_logged');
        $this->session->set_flashdata('success', 'Berhasil logout');
        redirect($_SERVER['HTTP_REFERER'] ?? base_url());
    }

    // Simple endpoint to let client know if pelanggan is logged in
    public function check()
    {
        $this->output->set_content_type('application/json');
        $id = $this->session->userdata('pelanggan_logged');
        if ($id) {
            $p = $this->Pelanggan_model->get($id);
            echo json_encode(['logged' => true, 'id' => $id, 'nama' => $p->nama ?? null]);
        } else {
            echo json_encode(['logged' => false]);
        }
    }
}
