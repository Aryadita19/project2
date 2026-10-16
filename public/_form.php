<?php
// Partial form dipakai create.php & edit.php
// Variabel: $values, $errors, $action, $submitLabel, $extraHidden
?>
<form method="POST" action="<?= e($action) ?>" class="card form" novalidate>
  <?= csrf_field() ?>
  <?= $extraHidden ?? "" ?>

  <label for="name">Nama produk</label>
  <input id="name" name="name" minlength="3" maxlength="100" required
         value="<?= e((string) $values["name"]) ?>">
  <?php if (isset($errors["name"])): ?><p class="error"><?= e($errors["name"]) ?></p><?php endif; ?>

  <label for="category">Kategori</label>
  <input id="category" name="category" maxlength="50" required
         value="<?= e((string) $values["category"]) ?>">
  <?php if (isset($errors["category"])): ?><p class="error"><?= e($errors["category"]) ?></p><?php endif; ?>

  <label for="price">Harga (Rp)</label>
  <input id="price" name="price" type="number" min="1" step="any" required
         value="<?= e((string) $values["price"]) ?>">
  <?php if (isset($errors["price"])): ?><p class="error"><?= e($errors["price"]) ?></p><?php endif; ?>

  <label for="stock">Stok</label>
  <input id="stock" name="stock" type="number" min="0" step="1" required
         value="<?= e((string) $values["stock"]) ?>">
  <?php if (isset($errors["stock"])): ?><p class="error"><?= e($errors["stock"]) ?></p><?php endif; ?>

  <div class="actions">
    <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
    <a href="index.php" class="btn">Batal</a>
  </div>
</form>
