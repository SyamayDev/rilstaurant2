<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_email extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('Pelanggan_model'); // Assuming a model to get customer data
        $this->load->library('form_validation');
        $this->load->library('email'); // CodeIgniter's email library
    }

    public function index() {
        // Fetch all customers with email addresses for selection
        $data['customers'] = $this->Pelanggan_model->get_customers_with_email();
        $this->load->view('admin/email', $data);
    }

    public function send() {
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');
        $this->form_validation->set_rules('recipients[]', 'Recipients', 'required'); // recipients[] for multiple selection

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the form with errors
            $this->index();
        } else {
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $recipient_ids = $this->input->post('recipients');
            $send_to_all = $this->input->post('send_to_all');

            $to_emails = [];
            if ($send_to_all) {
                $recipients_data = $this->Pelanggan_model->get_customers_with_email();
            } else {
                // Get email addresses for the selected recipient IDs
                $recipients_data = $this->Pelanggan_model->get_customers_by_ids($recipient_ids);
            }
            
            foreach ($recipients_data as $customer) {
                if (!empty($customer->email)) {
                    $to_emails[] = $customer->email;
                }
            }

            if (empty($to_emails)) {
                $this->session->set_flashdata('error', 'Tidak ada penerima email yang valid dipilih.');
                redirect('admin_email');
                return;
            }

            // Email configuration (ensure this is set up in application/config/email.php or config.php)
            // For demonstration, assuming basic config is done.
            // IMPORTANT: Create and configure application/config/email.php
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'ssl://smtp.gmail.com'; // Example for Gmail
            $config['smtp_port'] = 465;
            $config['smtp_user'] = 'xrpltritech@gmail.com'; // CHANGE THIS to your sender email
            $config['smtp_pass'] = 'rpl12345'; // CHANGE THIS to your email password or app password
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n"; // Important for some servers

            $this->email->initialize($config);

            $this->email->set_mailtype("html"); // Set mailtype to HTML
            $this->email->from($config['smtp_user'], 'Rilstaurant Admin');
            $this->email->to(implode(',', $to_emails)); // Send to all selected valid emails
            $this->email->subject($subject);
            $this->email->message($message);

            if ($this->email->send()) {
                $this->session->set_flashdata('success', 'Email berhasil dikirim ke ' . count($to_emails) . ' pelanggan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim email. Error: ' . $this->email->print_debugger());
            }
            redirect('admin_email');
        }
    }

    public function upload_ckeditor_image() {
        if (!empty($_FILES['upload']['name'])) {
            $config['upload_path'] = './assets/uploads/ckeditor/'; // Ensure this directory exists and is writable
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = uniqid('ckeditor_');

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('upload')) {
                $data = $this->upload->data();
                $url = base_url('assets/uploads/ckeditor/' . $data['file_name']);
                $message = '';
                $function_number = $this->input->get('CKEditorFuncNum');
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
            } else {
                $message = $this->upload->display_errors('', '');
                $function_number = $this->input->get('CKEditorFuncNum');
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '', '$message');</script>";
            }
        }
    }
}
