<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loader extends CI_Controller {

    public function index() {
        $this->load->helper('directory');

        $images = array();
        $img_path = 'assets/img/';
        $upload_path = 'assets/uploads/';

        $img_files = directory_map($img_path);
        $upload_files = directory_map($upload_path);

        if ($img_files) {
            foreach ($img_files as $file) {
                if (is_string($file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = base_url($img_path . $file);
                }
            }
        }

        if ($upload_files) {
            foreach ($upload_files as $file) {
                if (is_string($file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = base_url($upload_path . $file);
                }
            }
        }

        $data['images'] = $images;
        $this->load->view('loading_view', $data);
    }
}
