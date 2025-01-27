<?php

require_once('config.php');
require_once('sa_connector.php');

session_start();

// Logowanie

if (isset($_POST['password']) && $_POST['password'] === $mapper_password || isset($_SESSION['authenticated'])) {

    $_SESSION['authenticated'] = true;
} else {
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
            <h2>Hasło</h2>
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

<!-- Panel łączenia -->

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
                <input type="text" class="form-control" id="productID" name="productID" value="<?php echo isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : ''; ?>">
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

            $product_id = $_POST['productID'];
            $external_id = $_POST['externalID'];
            $variant = $_POST['variant'];

            if (!empty($variant)) {
                $external_id .= '-' . $variant;
            }

            $data = [
                "external_shops_mappings" => [
                    [
                        "shop_id" => $shop_id,
                        "foreign_id" => $external_id
                    ]
                ]
            ];
            
            map_product($product_id, $data);
        }
        ?>
    </div>
</body>

</html>