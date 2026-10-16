<?php
// READ + bonus search (GET)
require __DIR__ . "/../config/db.php";

$q = trim((string) ($_GET["q"] ?? ""));

// Catatan: ATTR_EMULATE_PREPARES=false => nama placeholder TIDAK boleh dipakai dua kali.
$stmt = $pdo->prepare(
    "SELECT id, name, category, price, stock
     FROM products
     WHERE name LIKE :q1 OR category LIKE :q2
     ORDER BY id DESC"
);
$stmt->execute(["q1" => "%$q%", "q2" => "%$q%"]);
$products = $stmt->fetchAll();

$messages = [
    "created" => "Produk berhasil disimpan.",
    "updated" => "Produk berhasil diperbarui.",
    "deleted" => "Produk berhasil dihapus.",
];
$status = $messages[$_GET["status"] ?? ""] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Manager</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
  <header class="page-header">
    <h1>Product Manager</h1>
    <a href="create.php" class="btn btn-primary">+ Tambah produk</a>
  </header>

  <?php if ($status): ?><p class="alert"><?= e($status) ?></p><?php endif; ?>

  <form method="GET" class="search">
    <input name="q" placeholder="Cari nama atau kategori" value="<?= e($q) ?>">
    <button class="btn">Cari</button>
    <?php if ($q !== ""): ?><a href="index.php" class="btn">Reset</a><?php endif; ?>
  </form>

  <?php if (!$products): ?>
    <p class="empty">Belum ada produk<?= $q !== "" ? " yang cocok dengan pencarian." : "." ?></p>
  <?php endif; ?>

  <section class="products">
    <?php foreach ($products as $p): ?>
      <article class="card">
        <span class="badge"><?= e($p["category"]) ?></span>
        <h3><?= e($p["name"]) ?></h3>
        <p class="price">Rp <?= number_format((float) $p["price"], 0, ",", ".") ?></p>
        <p class="stock">Stok: <?= (int) $p["stock"] ?></p>
        <div class="actions">
          <a class="btn" href="edit.php?id=<?= (int) $p["id"] ?>">Edit</a>
          <form method="POST" action="delete.php"
                onsubmit="return confirm('Hapus produk ini?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $p["id"] ?>">
            <button class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
</main>
</body>
</html>
