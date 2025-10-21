<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function index()
    {
        // login page
        if ($this->session->userdata('admin_logged')) {
            redirect('admin/dashboard');
        }
        $this->load->view('admin/login');
    }

    public function login_action()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $admin = $this->Admin_model->check_login($username, $password);
        if ($admin) {
            $this->session->set_userdata('admin_logged', true);
            $this->session->set_userdata('admin_id', $admin->id_admin);
            redirect('admin/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Login gagal');
            redirect('admin');
        }
    }

    public function dashboard()
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        $this->load->model('Menu_model');
        $this->load->model('Pesanan_model');
        $data = [];
        $data['top_menus'] = $this->Menu_model->top_rated(5);
        $data['sales'] = $this->Pesanan_model->sales_over_time(14);
        $this->load->view('admin/dashboard', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('admin_logged');
        $this->session->unset_userdata('admin_id');
        redirect('admin');
    }
}
