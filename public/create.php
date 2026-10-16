<?php
// CREATE + validasi server-side + PRG
require __DIR__ . "/../config/db.php";

$values = ["name" => "", "category" => "Umum", "price" => "", "stock" => "0"];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify();
    [$data, $errors] = validate_product($_POST);
    $values = array_map(fn($v) => $v === false ? "" : $v, $data);

    if (!$errors && name_exists($pdo, $data["name"])) {
        $errors["name"] = "Nama produk sudah ada.";
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO products (name, category, price, stock)
                 VALUES (:name, :category, :price, :stock)"
            );
            $stmt->execute($data);
            header("Location: index.php?status=created"); // PRG
            exit;
        } catch (PDOException $ex) {
            // 23000 = pelanggaran UNIQUE (jaga-jaga race condition)
            if ($ex->getCode() === "23000") {
                $errors["name"] = "Nama produk sudah ada.";
            } else {
                throw $ex;
            }
        }
    }
}

$action = "create.php";
$submitLabel = "Simpan produk";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
  <h1>Tambah produk</h1>
  <?php include __DIR__ . "/_form.php"; ?>
</main>
</body>
</html>
