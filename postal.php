<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    try {

        $dbPath = __DIR__ . '/pna.db';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT DISTINCT city FROM pna_2025 WHERE code = :code');
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows && count($rows) > 0) {
            if (count($rows) === 1) {
                echo json_encode(['city' => $rows[0]['city']]);
            } else {
                $result = array_map(function($row) {
                    return ['city' => $row['city']];
                }, $rows);
                echo json_encode($result);
            }
        } else {
            echo json_encode(['city' => 'Nie odnaleziono miejscowości']);
        }
    } catch (PDOException $e) {
        echo json_encode(['city' => 'Błąd połączenia z bazą danych: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['city' => 'Nie podano kodu']);
}
?>
