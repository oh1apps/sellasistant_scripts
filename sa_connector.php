<?php

require_once('config.php');

//Pobieranie zamówienia

function get_sa_order($order_id)
{

    global $sa_api_url, $sa_api_key;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $sa_api_url . 'orders/' . $order_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'apiKey: ' . $sa_api_key
        ),
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $sa_order = json_decode($response, true);

    curl_close($curl);

    if ($http_code == 200) {

        return $sa_order;
    } else {

        echo ("Nie udało się pbrać zamówienia $order_id: $http_code: $response");
        exit();
    }
}

// Mapowanie produktów

function map_product($product_id, $data)
{

    global $sa_api_url, $sa_api_key;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $sa_api_url . 'products/' . $product_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'apiKey: ' . $sa_api_key,
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    if (curl_errno($curl)) {
        echo '<div class="alert alert-danger mt-3">Wystąpił błąd: ' . curl_error($curl) . '</div>';
    } else {
        echo '<div class="alert alert-success mt-3">Odpowiedź API: ' . htmlspecialchars($response) . '</div>';
    }
}
