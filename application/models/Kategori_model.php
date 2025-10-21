<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_model extends CI_Model
{
    protected $table = 'kategori';

    public function get_all()
    {
        return $this->db->order_by('nama_kategori', 'ASC')->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id_kategori' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_kategori', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id_kategori' => $id]);
    }
}
