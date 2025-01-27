<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$post_code = $_GET['code'] ?? '';

if (empty($post_code)) {
    echo json_encode(['error' => 'Nie podano kodu']);
    exit;
}

$post_code_api = "http://kodpocztowy.intami.pl/api/" . urlencode($post_code);

$response = file_get_contents($post_code_api);

$address_data = json_decode($response, true);

if ($address_data && isset($data[0]['miejscowosc'])) {
    echo json_encode(['city' => $data[0]['miejscowosc']]);
} else {
    echo json_encode(['error' => 'Nie odnaleziono miejscowości']);
}
