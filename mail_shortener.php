<?php
require_once ('config.php');
require_once ('sa_connector.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$order_id = $data['order_id'];

$order_data = get_sa_order($order_id);

$email = $order_data['email'];
$parts = explode('+', $email);
$short_mail = $parts[0] . '@' . explode('@', $parts[1])[1];

$update = [
    'email' => $short_mail
];

update_order($update, $order_id);

