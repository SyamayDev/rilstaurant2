<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load config and necessary models
        $this->config->load('config');
        $this->load->model('Menu_model');
        $this->load->model('Kategori_model');
    }

    private function get_restaurant_context()
    {
        // Fetch data from models
        $kategori = $this->Kategori_model->get_all();
        $menu = $this->Menu_model->get_all();

        // Build a context string
        $context = "Anda adalah RIL, asisten AI cerdas dan ramah dari Rilstaurant. Tugas Anda adalah menjawab pertanyaan pelanggan seputar menu, kategori, dan informasi umum tentang restoran. Gunakan informasi berikut sebagai basis pengetahuan Anda:\n\n";

        $context .= "Kategori Menu yang Tersedia:\n";
        foreach ($kategori as $kat) {
            $context .= "- " . html_escape($kat->nama_kategori) . "\n";
        }

        $context .= "\nDaftar Menu (Nama - Harga - Deskripsi):\n";
        foreach ($menu as $item) {
            $harga = "Rp " . number_format($item->harga, 0, ',', '.');
            $context .= "- " . html_escape($item->nama_menu) . " - " . $harga . " - " . html_escape($item->deskripsi) . "\n";
        }

        $context .= "\nInformasi Tambahan: Rilstaurant adalah restoran keluarga yang menyajikan masakan khas Indonesia dengan resep turun-temurun. Kami buka setiap hari dari jam 10:00 pagi hingga 22:00 malam. Lokasi kami ada di Jl. Merdeka No. 123, Jakarta. Selalu jawab dengan ramah, sopan, dan dalam Bahasa Indonesia.";

        return $context;
    }


    public function chat()
    {
        header('Content-Type: application/json');

        // Get request body
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($input['message'])) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Invalid JSON or message format.']);
            return;
        }

        $user_message = $input['message'];
        $history = isset($input['history']) ? $input['history'] : [];
        $api_key = $this->config->item('gemini_api_key');

        // Use the recommended model for the free tier.
        $model = 'gemini-2.5-flash';
        $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;

        // Construct the conversation history in the format expected by the API.
        $contents = [];

        // Add the system context/instruction as the first message from the 'user'
        // and a canned 'model' response to set the persona.
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $this->get_restaurant_context()]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Tentu, saya RIL, asisten AI dari Rilstaurant. Ada yang bisa saya bantu?']]
        ];

        // Add the existing chat history
        foreach ($history as $msg) {
            $role = ($msg['sender'] === 'user') ? 'user' : 'model';
            $contents[] = ['role' => $role, 'parts' => [['text' => $msg['text']]]];
        }

        // Add the new user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $user_message]]
        ];

        $payload_data = ['contents' => $contents];
        $payload = json_encode($payload_data);

        // Use cURL to send the request to the Gemini API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE); // It's important to verify the SSL certificate in production

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($httpcode !== 200) {
            $this->output->set_status_header($httpcode > 0 ? $httpcode : 500);
            $error_details = json_decode($response, true);
            echo json_encode([
                'error' => 'Failed to get response from AI service.',
                'http_code' => $httpcode,
                'details' => $error_details,
                'curl_error' => $curl_error ? $curl_error : 'No cURL error.'
            ]);
            return;
        }

        $result = json_decode($response, true);

        // Check for the correct response structure
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $reply = $result['candidates'][0]['content']['parts'][0]['text'];
            echo json_encode(['reply' => $reply]);
        } else {
            $this->output->set_status_header(500);
            // Provide more detailed error information for debugging
            echo json_encode([
                'error' => 'Unexpected response format from AI service.',
                'details' => $result
            ]);
        }
    }

    public function clear_chat()
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Chat history cleared.']);
    }

    public function get_jumlah_meja()
    {
        header('Content-Type: application/json');
        $this->load->model('Settings_model');
        $settings = $this->Settings_model->get_settings();
        $jumlah_meja = isset($settings->jumlah_meja) ? (int)$settings->jumlah_meja : 20; // Default 20

        echo json_encode(['jumlah_meja' => $jumlah_meja]);
    }
}
