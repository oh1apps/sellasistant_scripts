<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$code = $_GET['code'] ?? '';
if (empty($code)) {
    echo json_encode(['error' => 'No postal code provided']);
    exit;
}

$url = "http://kodpocztowy.intami.pl/api/" . urlencode($code);

$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data && isset($data[0]['miejscowosc'])) {
    echo json_encode(['city' => $data[0]['miejscowosc']]);
} else {
    echo json_encode(['error' => 'City not found']);
}
