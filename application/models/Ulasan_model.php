<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ulasan_model extends CI_Model
{
    protected $table = 'ulasan';

    public function insert($data)
    {
        // Explicitly set columns to avoid errors if the data array contains extra fields
        $valid_data = [];
        $fields = $this->db->list_fields($this->table);
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $valid_data[$field] = $data[$field];
            }
        }

        $this->db->insert($this->table, $valid_data);
        return $this->db->insert_id();
    }

    public function get_by_order($id_pesanan)
    {
        return $this->db->get_where($this->table, ['id_pesanan' => $id_pesanan])->row();
    }

    public function list_all($status = null)
    {
        if ($status) $this->db->where('status_ulasan', $status);
        return $this->db->get($this->table)->result();
    }

    public function set_status($id, $status)
    {
        return $this->db->where('id_ulasan', $id)->update($this->table, ['status_ulasan' => $status]);
    }

    public function get_approved_reviews($limit = 5)
    {
        $this->db->select('u.*, m.nama_menu');
        $this->db->from($this->table . ' u');
        $this->db->join('menu m', 'm.id_menu = u.id_menu', 'left');
        $this->db->where('u.status_ulasan', 'disetujui');
        $this->db->order_by('u.id_ulasan', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function count_approved_for_menu($id_menu)
    {
        $this->db->select('COUNT(u.id_ulasan) as cnt, AVG(u.rating) as avg', false);
        $this->db->from('ulasan u');
        $this->db->join('detail_pesanan d', 'd.id_pesanan = u.id_pesanan', 'inner');
        $this->db->where('d.id_menu', $id_menu);
        $this->db->where("u.status_ulasan = 'disetujui'");
        $q = $this->db->get()->row();
        return $q ?: (object)['cnt' => 0, 'avg' => 0];
    }
}