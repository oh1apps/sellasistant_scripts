<?php
require_once ('config.php');
require_once ('sa_connector.php');

$db = new SQLite3(__DIR__ . '/plusmatching.db');
$db->exec("
    CREATE TABLE IF NOT EXISTS plusmatching (
        mail TEXT PRIMARY KEY,
        orders TEXT
    )
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    $order_id = $data['order_id'];
    $order_data = get_sa_order($order_id);
    $email = $order_data['email'];

    $stmt = $db->prepare("SELECT orders FROM plusmatching WHERE mail = :mail");
    $stmt->bindValue(':mail', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $orders = json_decode($row['orders'], true);
        if (!in_array($order_id, $orders)) {
            $orders[] = $order_id;
            $stmt = $db->prepare("UPDATE plusmatching SET orders = :orders WHERE mail = :mail");
            $stmt->bindValue(':orders', json_encode($orders), SQLITE3_TEXT);
            $stmt->bindValue(':mail', $email, SQLITE3_TEXT);
            $stmt->execute();
        }
    } else {
        $orders = [$order_id];
        $stmt = $db->prepare("INSERT INTO plusmatching (mail, orders) VALUES (:mail, :orders)");
        $stmt->bindValue(':mail', $email, SQLITE3_TEXT);
        $stmt->bindValue(':orders', json_encode($orders), SQLITE3_TEXT);
        $stmt->execute();
    }

    http_response_code(200);
    echo json_encode(['status' => 'ok']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email'])) {

    $input_email = $_GET['email'];
    
    $prefix = explode('@', $input_email)[0];
    $domain = explode('@', $input_email)[1];

    $query = $db->prepare("
        SELECT orders FROM plusmatching 
        WHERE mail LIKE :pattern
    ");
    $query->bindValue(':pattern', $prefix . '+%@' . $domain, SQLITE3_TEXT);
    $result = $query->execute();

    $found = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo json_encode(json_decode($row['orders'], true));
        $found = true;
        break;
    }

    if (!$found) {
        echo json_encode([]);
    }

    exit;
}

// Jeśli nie POST ani GET z parametrem email:
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;
