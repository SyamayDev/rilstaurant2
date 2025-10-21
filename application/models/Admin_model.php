<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    protected $table = 'admin';

    public function check_login($username, $password)
    {
        // password stored as MD5 or plain for simplicity; prefer password_hash in real apps
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        return $this->db->get($this->table)->row();
    }
}
