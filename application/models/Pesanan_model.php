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

    public function create_order($pelanggan_id, $cart)
    {
        $this->db->trans_start();

        $total = 0;
        $items = [];
        $error_message = null;

        foreach ($cart as $c) {
            // Lock the row for update
            $menu_item = $this->db->query("SELECT * FROM menu WHERE id_menu = ? FOR UPDATE", [$c['id_menu']])->row();

            if (!$menu_item || $menu_item->stok < $c['jumlah']) {
                $error_message = "Stok untuk menu '" . ($menu_item ? $menu_item->nama_menu : 'Item') . "' tidak mencukupi. Sisa stok: " . ($menu_item ? $menu_item->stok : 0) . ".";
                break; // Exit the loop immediately
            }

            // Reduce stock
            $this->db->where('id_menu', $c['id_menu'])->set('stok', 'stok - ' . $c['jumlah'], FALSE)->update('menu');

            $subtotal = $c['jumlah'] * $c['harga'];
            $total += $subtotal;
            $items[] = [
                'id_pesanan' => 0, // placeholder
                'id_menu' => $c['id_menu'],
                'jumlah' => $c['jumlah'],
                'subtotal' => $subtotal
            ];
        }

        if ($error_message) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => $error_message];
        }

        $pesanan_id = $this->insert([
            'id_pelanggan' => $pelanggan_id,
            'total' => $total,
            'status' => 'pending'
        ]);

        foreach ($items as &$it) {
            $it['id_pesanan'] = $pesanan_id;
        }
        $this->load->model('Detail_model');
        $this->Detail_model->insert_batch($items);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Terjadi kesalahan saat memproses pesanan.'];
        }

        return ['success' => true, 'order_id' => $pesanan_id];
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