<?php
session_start();

// Hasło dostępu do skryptu
$password = "admin";

// Adres panelu Sellasist
$url = "https://example.sellasist.pl";

// ID sklepu (znajdziesz w adresie URL, przchodząc do Integracje > Sklepy > Edytuj)
$shop_id = 2;

//Klucz API Sellasist
$api_key = "";

if (isset($_POST['password']) && $_POST['password'] === $password) {
    $_SESSION['authenticated'] = true;
}

// Sprawdzanie poprawności logowania
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zaloguj</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h2>Haslo</h2>
            <form method="post">
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Zaloguj</button>
            </form>
        </div>
    </body>
    </html>
<?php
    exit;
}
?>

<!-- Formularz łączenia -->

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapowanie Produktu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h3>Połącz produkty Sellasist ↔ Shoper</h3>
        <form method="post">

            <div class="mb-3">
                <label for="productID" class="form-label">ID produktu w Sellasist</label>
                <input type="text" class="form-control" id="productID" name="productID">
            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label for="externalID" class="form-label">ID produktu w Shoper</label>
                    <input type="text" class="form-control" id="externalID" name="externalID" required>
                </div>
                <div class="col">
                    <label for="variant" class="form-label">ID wariantu w Shoper</label>
                    <input type="text" class="form-control" id="variant" name="variant">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Połącz</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productID'], $_POST['externalID'])) {
            $productID = $_POST['productID'];
            $externalID = $_POST['externalID'];
            $variant = $_POST['variant'];

            $foreign_id = $externalID;
            if (!empty($variant)) {
                $foreign_id .= '-' . $variant;
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$url/api/v1/products/$productID",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode([
                    "external_shops_mappings" => [
                        [
                            "shop_id" => $shop_id,
                            "foreign_id" => $foreign_id
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER => array(
                    'apiKey: '. $api_key,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            if(curl_errno($curl)) {
                echo '<div class="alert alert-danger mt-3">Wystąpił błąd: ' . curl_error($curl) . '</div>';
            } else {
                echo '<div class="alert alert-success mt-3">Odpowiedź API: ' . htmlspecialchars($response) . '</div>';
            }

            curl_close($curl);
        }
        ?>
    </div>
</body>
</html>
