<?php
// config/db.php — koneksi PDO + helper keamanan (dipakai semua halaman)
declare(strict_types=1);

session_start();

$pdo = new PDO(
    "mysql:host=localhost;dbname=store_db;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);

/** Escape output HTML (anti-XSS). */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

/** Token CSRF disimpan di session. */
function csrf_token(): string
{
    $_SESSION["csrf"] ??= bin2hex(random_bytes(32));
    return $_SESSION["csrf"];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Hentikan request jika token tidak cocok. */
function csrf_verify(): void
{
    if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        http_response_code(403);
        exit("Token tidak valid");
    }
}

/**
 * Normalisasi + validasi input produk (dipakai create & edit).
 * Mengembalikan [data, errors].
 */
function validate_product(array $input): array
{
    $name     = trim((string) ($input["name"] ?? ""));
    $category = trim((string) ($input["category"] ?? ""));
    $price    = filter_var($input["price"] ?? "", FILTER_VALIDATE_FLOAT);
    $stock    = filter_var($input["stock"] ?? "", FILTER_VALIDATE_INT);

    $errors = [];
    if (mb_strlen($name) < 3)   $errors["name"] = "Nama minimal 3 karakter.";
    if (mb_strlen($name) > 100) $errors["name"] = "Nama maksimal 100 karakter.";
    if ($category === "")       $errors["category"] = "Kategori wajib diisi.";
    if (mb_strlen($category) > 50) $errors["category"] = "Kategori maksimal 50 karakter.";
    if ($price === false || $price <= 0)  $errors["price"] = "Harga harus > 0.";
    if ($stock === false || $stock < 0)   $errors["stock"] = "Stok tidak boleh negatif.";

    $data = [
        "name"     => $name,
        "category" => $category,
        "price"    => $price,
        "stock"    => $stock,
    ];
    return [$data, $errors];
}

/** Cek apakah nama produk sudah dipakai (abaikan id tertentu saat edit). */
function name_exists(PDO $pdo, string $name, int $ignoreId = 0): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = :name AND id <> :id");
    $stmt->execute(["name" => $name, "id" => $ignoreId]);
    return (int) $stmt->fetchColumn() > 0;
}
