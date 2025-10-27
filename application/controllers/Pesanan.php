<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesanan_model');
        $this->load->model('Detail_model');
        $this->load->model('Menu_model');
    }

    // Customer checkout
    public function checkout()
    {
        // Expect POST with pelanggan data and cart items (JSON or form)
        $this->load->model('Pelanggan_model');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('no_meja', 'No Meja', 'required');
        // email optional
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Nama dan nomor meja wajib diisi');
            redirect('Katalog/keranjang');
            return;
        }

        $no_meja = $this->input->post('no_meja');

        // If pelanggan is logged in, reuse their id; otherwise insert new pelanggan
        $logged = $this->session->userdata('pelanggan_logged');
        if ($logged) {
            $pelanggan_id = $logged;
            // check meja occupied by someone else
            $occupied = $this->db->query("SELECT 1 FROM pesanan ps JOIN pelanggan p ON ps.id_pelanggan = p.id_pelanggan WHERE p.no_meja = ? AND COALESCE(ps.status,'') NOT IN ('selesai','done','completed') AND p.id_pelanggan != ? LIMIT 1", [$no_meja, $pelanggan_id])->row();
            if ($occupied) {
                $this->session->set_flashdata('error', 'Meja ' . $no_meja . ' sedang dipakai. Silakan pilih meja lain.');
                redirect('Katalog/keranjang');
                return;
            }

            // update pelanggan info (name/email) if provided
            $upd = [];
            if ($this->input->post('nama')) $upd['nama'] = $this->input->post('nama');
            if ($this->input->post('email')) $upd['email'] = $this->input->post('email');
            if ($this->input->post('no_meja')) $upd['no_meja'] = $this->input->post('no_meja');
            if ($upd) $this->Pelanggan_model->update($pelanggan_id, $upd);
        } else {
            // Server-side check: apakah meja sudah terpakai oleh pesanan yang belum selesai?
            $occupied = $this->db->query("SELECT 1 FROM pesanan ps JOIN pelanggan p ON ps.id_pelanggan = p.id_pelanggan WHERE p.no_meja = ? AND COALESCE(ps.status,'') NOT IN ('selesai','done','completed') LIMIT 1", [$no_meja])->row();
            if ($occupied) {
                $this->session->set_flashdata('error', 'Meja ' . $no_meja . ' sedang dipakai. Silakan pilih meja lain.');
                redirect('Katalog/keranjang');
                return;
            }

            $pelanggan_id = $this->Pelanggan_model->insert([
                'nama' => $this->input->post('nama'),
                'no_meja' => $no_meja,
                'email' => $this->input->post('email') ?: null
            ]);
        }

        $cart = json_decode($this->input->post('cart'), true);
        
        // Create order using the new model method
        $result = $this->Pesanan_model->create_order($pelanggan_id, $cart);

        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            redirect('Katalog/keranjang');
            return;
        }

        $pesanan_id = $result['order_id'];

        // If AJAX request or client asked for JSON, respond with JSON (so client can clear cart and redirect client-side)
        $acceptsJson = false;
        $acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
        if ($this->input->is_ajax_request() || stripos($acceptHeader, 'application/json') !== false) {
            $acceptsJson = true;
        }

        if ($acceptsJson) {
            $this->output->set_content_type('application/json');
            echo json_encode([
                'success' => true,
                'order_id' => $pesanan_id,
                'clear_cart' => true,
                'message' => 'Pesanan berhasil dibuat'
            ]);
            return;
        }

        // Set flashdata so the next page can show the order and request the client to clear the cart
        $this->session->set_flashdata('success', 'Pesanan berhasil dibuat. Silahkan Menunggu, Terima kasih!');
        $this->session->set_flashdata('recent_order_id', $pesanan_id);
        $this->session->set_flashdata('clear_cart', true);

        redirect('pesanan/saya');
    }

    // Public view for customer to see their order/status after checkout
    public function saya()
    {
        // Try flashdata first (recent order), then GET param id, then URI segment
        $recent = $this->session->flashdata('recent_order_id');
        $order_id = $recent ?: $this->input->get('id') ?: $this->uri->segment(3);

        if (!$order_id) {
            $this->session->set_flashdata('error', 'Tidak ada pesanan yang ditemukan.');
            redirect('/');
            return;
        }

        $pesanan = $this->Pesanan_model->get($order_id);
        if (!$pesanan) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan.');
            redirect('/');
            return;
        }

        $this->load->model('Pelanggan_model');
        $pelanggan = $this->Pelanggan_model->get($pesanan->id_pelanggan);
        $detail = $this->Detail_model->get_by_order($order_id);

        $data = [
            'pesanan' => $pesanan,
            'pelanggan' => $pelanggan,
            'detail' => $detail,
            // preserve clear_cart flag from flashdata (so view can clear localStorage)
            'clear_cart' => $this->session->flashdata('clear_cart') ? true : false,
            'is_recent' => $recent ? true : false,
            'recent_order_id' => $recent ? $recent : null
        ];

        $this->load->view('pelanggan/pesanan_saya', $data);
    }

    public function batalkan($id_pesanan)
    {
        // No specific user auth check seems to be in place for viewing, follow pattern
        $pesanan = $this->Pesanan_model->get($id_pesanan);

        if (!$pesanan) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan.');
            redirect('/'); // Redirect to home if order not found
            return;
        }

        // Only allow cancellation if status is 'pending'
        if (strtolower($pesanan->status) === 'pending') {
            
            $this->db->trans_start();

            // 1. Restore stock for each item in the order
            $detail_items = $this->Detail_model->get_by_order($id_pesanan);
            foreach ($detail_items as $item) {
                $this->db->where('id_menu', $item->id_menu);
                $this->db->set('stok', 'stok + ' . (int)$item->jumlah, FALSE);
                $this->db->update('menu');
            }

            // 2. Update order status to 'batal'
            $this->Pesanan_model->update_status($id_pesanan, 'batal');

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal membatalkan pesanan karena kesalahan database.');
            } else {
                $this->session->set_flashdata('success', 'Pesanan telah berhasil dibatalkan.');
            }

        } else {
            $this->session->set_flashdata('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Redirect back to the order status page
        redirect('pesanan/saya/' . $id_pesanan);
    }

    // Public API: return minimal JSON status for an order
    public function status($id = null)
    {
        // Allow only GET
        $this->output->set_content_type('application/json');
        if (!$id) {
            echo json_encode(['error' => 'missing_id']);
            return;
        }

        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan) {
            echo json_encode(['error' => 'not_found']);
            return;
        }

        echo json_encode(['status' => $pesanan->status, 'id_pesanan' => $pesanan->id_pesanan]);
    }

    // Return array of occupied table numbers (meja) for non-completed orders
    public function occupied_tables()
    {
        $this->output->set_content_type('application/json');
        // Consider orders not finished as occupying a table
        $query = "SELECT DISTINCT p.no_meja FROM pesanan ps JOIN pelanggan p ON ps.id_pelanggan = p.id_pelanggan WHERE COALESCE(ps.status, '') NOT IN ('selesai','done','completed') AND p.no_meja IS NOT NULL";
        $rows = $this->db->query($query)->result();
        $mejas = array_map(function ($r) {
            return $r->no_meja;
        }, $rows);
        echo json_encode(array_values($mejas));
    }

    // Admin: list orders
    public function index()
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        $data['pesanan'] = $this->Pesanan_model->get_all();
        $this->load->view('admin/pesanan', $data);
    }

    public function view($id)
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        $data['pesanan'] = $this->Pesanan_model->get($id);
        $data['detail'] = $this->Detail_model->get_by_order($id);
        $this->load->view('admin/pesanan_view', $data);
    }

    // Printable receipt for admin (or cashier) to print when order is completed
    public function receipt($id)
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan) show_404();
        $this->load->model('Pelanggan_model');
        $pelanggan = $this->Pelanggan_model->get($pesanan->id_pelanggan);
        $detail = $this->Detail_model->get_by_order($id);
        $this->load->view('admin/receipt', [
            'pesanan' => $pesanan,
            'pelanggan' => $pelanggan,
            'detail' => $detail
        ]);
    }

    public function update_status($id)
    {
        if (!$this->session->userdata('admin_logged')) redirect('admin');
        $status = strtolower(trim($this->input->post('status')));

        // Map common synonyms to the DB enum values
        $map = [
            'dimasak' => 'diproses',
            'diantar' => 'dikirim',
            'delivering' => 'dikirim',
            'delivered' => 'dikirim',
            'done' => 'selesai',
            'completed' => 'selesai',
            'processing' => 'diproses'
        ];

        if (isset($map[$status])) $status = $map[$status];

        // Allowed values in DB enum
        $allowed_enum = ['pending', 'diproses', 'dikirim', 'selesai', 'batal'];
        if (!in_array($status, $allowed_enum)) {
            $this->session->set_flashdata('error', 'Status tidak valid untuk penyimpanan ke database: ' . htmlspecialchars($status));
            redirect('pesanan/view/' . $id);
            return;
        }

        $ok = $this->Pesanan_model->update_status($id, $status);
        // Check affected rows to ensure update took place
        if ($ok && $this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Status diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada perubahan atau update gagal. Pastikan nilai status berbeda dari sebelumnya.');
        }
        redirect('pesanan/view/' . $id);
    }
}