<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_model extends CI_Model
{

    protected $table_promos = 'tbl_promos';
    protected $table_promo_items = 'tbl_promo_items';
    protected $table_menu = 'menu'; // Assuming 'menu' is the table for menu items

    public function get_all_promos()
    {
        $this->db->select('p.*, GROUP_CONCAT(m.nama_menu SEPARATOR ", ") as menu_items_names');
        $this->db->from($this->table_promos . ' p');
        $this->db->join($this->table_promo_items . ' pi', 'p.id_promo = pi.id_promo', 'left');
        $this->db->join($this->table_menu . ' m', 'pi.id_menu = m.id_menu', 'left');
        $this->db->group_by('p.id_promo');
        $this->db->order_by('p.id_promo', 'DESC');
        return $this->db->get()->result();
    }

    public function get_promo_by_id($id_promo)
    {
        return $this->db->get_where($this->table_promos, ['id_promo' => $id_promo])->row();
    }

    public function insert_promo($data)
    {
        $this->db->insert($this->table_promos, $data);
        return $this->db->insert_id();
    }

    public function update_promo($id_promo, $data)
    {
        return $this->db->where('id_promo', $id_promo)->update($this->table_promos, $data);
    }

    public function get_promo_items($id_promo)
    {
        $this->db->select('tpi.*, m.nama_menu');
        $this->db->from($this->table_promo_items . ' tpi');
        $this->db->join($this->table_menu . ' m', 'm.id_menu = tpi.id_menu');
        $this->db->where('tpi.id_promo', $id_promo);
        return $this->db->get()->result();
    }

    public function insert_promo_items($data)
    {
        return $this->db->insert_batch($this->table_promo_items, $data);
    }

    public function delete_promo_items($id_promo)
    {
        return $this->db->where('id_promo', $id_promo)->delete($this->table_promo_items);
    }

    public function delete_promo($id_promo)
    {
        // Menghapus item promo terkait terlebih dahulu karena ada foreign key constraint
        $this->delete_promo_items($id_promo);
        // Baru menghapus promo utamanya
        return $this->db->where('id_promo', $id_promo)->delete($this->table_promos);
    }
}
