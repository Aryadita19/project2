<?php
// READ one + UPDATE
require __DIR__ . "/../config/db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit("ID tidak valid.");
}

$stmt = $pdo->prepare("SELECT id, name, category, price, stock FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit("Produk tidak ditemukan.");
}

$values = $product;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify();
    [$data, $errors] = validate_product($_POST);
    $values = array_map(fn($v) => $v === false ? "" : $v, $data);

    if (!$errors && name_exists($pdo, $data["name"], $id)) {
        $errors["name"] = "Nama produk sudah dipakai produk lain.";
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE products
                 SET name = :name, category = :category, price = :price, stock = :stock
                 WHERE id = :id"
            );
            $stmt->execute($data + ["id" => $id]);
            header("Location: index.php?status=updated"); // PRG
            exit;
        } catch (PDOException $ex) {
            if ($ex->getCode() === "23000") {
                $errors["name"] = "Nama produk sudah dipakai produk lain.";
            } else {
                throw $ex;
            }
        }
    }
}

$action = "edit.php?id=" . $id;
$submitLabel = "Simpan perubahan";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container narrow">
  <h1>Edit produk</h1>
  <?php include __DIR__ . "/_form.php"; ?>
</main>
</body>
</html>
