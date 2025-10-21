<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan_model extends CI_Model
{
    protected $table = 'pesanan';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id_pesanan' => $id])->row();
    }

    public function get_all()
    {
        return $this->db->order_by('tanggal', 'DESC')->get($this->table)->result();
    }

    public function update_status($id, $status)
    {
        return $this->db->where('id_pesanan', $id)->update($this->table, ['status' => $status]);
    }

    public function sales_over_time($days = 7)
    {
        $this->db->select("DATE(tanggal) as tgl, SUM(total) as total_sum, COUNT(*) as orders_count", false);
        $this->db->where('tanggal >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        $this->db->group_by('DATE(tanggal)');
        $this->db->order_by('DATE(tanggal)', 'ASC');
        return $this->db->get($this->table)->result();
    }
}
