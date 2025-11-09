<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    protected $table = 'menu';

    public function get_all()
    {
        // return menus with category name (if category stored as id)
        $this->db->select('m.*');
        $this->db->from($this->table . ' m');
        // check if the kategori column exists in menu table
        if ($this->db->field_exists('kategori', $this->table)) {
            $this->db->select('k.nama_kategori');
            $this->db->join('kategori k', 'k.id_kategori = m.kategori', 'left');
        }
        return $this->db->get()->result();
    }

    public function get_with_reviews()
    {
        // get menus with approved average rating and approved review count
        $hasKategori = $this->db->field_exists('kategori', $this->table);
        $this->db->select('m.*, COALESCE(AVG(u.rating),0) AS avg_rating, COUNT(u.id_ulasan) AS jumlah_ulasan');
        if ($hasKategori) {
            $this->db->select('k.nama_kategori');
            $this->db->join('kategori k', 'k.id_kategori = m.kategori', 'left');
        }
        $this->db->from($this->table . ' m');
        $this->db->join('ulasan u', 'u.id_menu = m.id_menu AND u.status_ulasan = \'disetujui\'', 'left');
        $this->db->group_by('m.id_menu');
        return $this->db->get()->result();
    }

    public function get_by_id_with_reviews($id)
    {
        $hasKategori = $this->db->field_exists('kategori', $this->table);
        $this->db->select('m.*, COALESCE(AVG(u.rating),0) AS avg_rating, COUNT(u.id_ulasan) AS jumlah_ulasan');
        if ($hasKategori) {
            $this->db->select('k.nama_kategori');
            $this->db->join('kategori k', 'k.id_kategori = m.kategori', 'left');
        }
        $this->db->from($this->table . ' m');
        $this->db->join('ulasan u', 'u.id_menu = m.id_menu AND u.status_ulasan = \'disetujui\'', 'left');
        $this->db->where('m.id_menu', $id);
        $this->db->group_by('m.id_menu');
        return $this->db->get()->row();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id_menu' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_menu', $id)->update($this->table, $data);
    }

    public function set_image($id, $filename)
    {
        return $this->db->where('id_menu', $id)->update($this->table, ['gambar' => $filename]);
    }

    public function top_rated($limit = 5)
    {
        // Calculate average rating per menu by joining ulasan directly
        $this->db->select('m.id_menu, m.nama_menu, m.gambar, m.harga, m.kategori, m.deskripsi, AVG(u.rating) as avg_rating, COUNT(u.id_ulasan) as jumlah_ulasan', false);
        $this->db->from($this->table . ' m');
        $this->db->join('ulasan u', 'u.id_menu = m.id_menu', 'left');
        $this->db->where('u.status_ulasan', 'disetujui');
        $this->db->group_by('m.id_menu');
        $this->db->order_by('avg_rating', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id_menu' => $id]);
    }

    public function get_best_seller($limit = 5)
    {
        $this->db->select('m.nama_menu, SUM(dp.jumlah) as total_terjual');
        $this->db->from('menu m');
        $this->db->join('detail_pesanan dp', 'm.id_menu = dp.id_menu');
        $this->db->group_by('m.id_menu');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}