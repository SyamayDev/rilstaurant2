<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function get_settings() {
        return $this->db->get('tbl_settings')->row();
    }

    public function update_settings($data) {
        return $this->db->update('tbl_settings', $data, array('id' => 1));
    }
}