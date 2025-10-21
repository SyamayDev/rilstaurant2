<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_model extends CI_Model
{
    protected $table = 'detail_pesanan';

    public function insert_batch($items)
    {
        return $this->db->insert_batch($this->table, $items);
    }

    public function get_by_order($id_pesanan)
    {
        $this->db->select('d.*, m.nama_menu');
        $this->db->from($this->table . ' d');
        $this->db->join('menu m', 'm.id_menu = d.id_menu', 'left');
        $this->db->where('d.id_pesanan', $id_pesanan);
        return $this->db->get()->result();
    }
}
