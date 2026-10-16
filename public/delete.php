<?php
// DELETE: hanya POST + CSRF + prepared statement + PRG
require __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method tidak diizinkan.");
}

csrf_verify();

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit("ID tidak valid.");
}

$pdo->prepare("DELETE FROM products WHERE id = :id")->execute(["id" => $id]);

header("Location: index.php?status=deleted");
exit;
