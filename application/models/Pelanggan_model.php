<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_model extends CI_Model
{
    protected $table = 'pelanggan';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_pelanggan', $id)->update($this->table, $data);
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id_pelanggan' => $id])->row();
    }

    public function get_customers_with_email()
    {
        $this->db->where('email IS NOT NULL');
        $this->db->where('email !=', '');
        return $this->db->get($this->table)->result();
    }

    public function get_customers_by_ids($ids)
    {
        if (empty($ids)) {
            return [];
        }
        $this->db->where_in('id_pelanggan', $ids);
        $this->db->where('email IS NOT NULL');
        $this->db->where('email !=', '');
        return $this->db->get($this->table)->result();
    }
}