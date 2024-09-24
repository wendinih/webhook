<?php
// Webhook verification
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ganti dengan token verifikasi yang sama dengan yang kamu masukkan di konsol WhatsApp API
    $verify_token = 'TOKEN_VERIFIKASI_ANDA';  

    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

    // Memverifikasi token dan mengirimkan challenge kembali jika token valid
    if ($mode === 'subscribe' && $token === $verify_token) {
        echo $challenge;
        http_response_code(200);
    } else {
        echo 'Verifikasi gagal';
        http_response_code(403);
    }
    exit();
}

// Menerima data webhook dari WhatsApp API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari body request
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Log data yang diterima (atau lakukan sesuatu dengan datanya)
    file_put_contents('whatsapp-webhook-log.txt', print_r($data, true), FILE_APPEND);

    // Memberikan respons sukses kembali ke WhatsApp
    http_response_code(200);
    echo json_encode(['status' => 'EVENT_RECEIVED']);
    exit();
}

// Jika bukan GET atau POST
http_response_code(405);
echo 'Metode tidak didukung';
exit();
?>
