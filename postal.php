<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if (isset($_GET['code'])) {

    $post_code_api = "http://kodpocztowy.intami.pl/api/" . urlencode($_GET['code']);

    $response = file_get_contents($post_code_api);

    $address_data = json_decode($response, true);

    if ($address_data && isset($address_data[0]['miejscowosc'])) {
        echo json_encode(['city' => $address_data[0]['miejscowosc']]);
    } else {
        echo json_encode(['error' => 'Nie odnaleziono miejscowości']);
    }
}
