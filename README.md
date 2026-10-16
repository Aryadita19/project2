# Product Manager (PHP + MySQL + PDO)

Praktikum Pemrograman Web — Pertemuan 3.
Aplikasi CRUD produk dengan validasi server-side, PRG, prepared statement, escaping output, CSRF pada aksi ubah/hapus, dan UI responsif (Box Model + Flexbox).

## Kebutuhan
- PHP 8.0+ (butuh operator `??=` dan `declare(strict_types=1)`)
- MySQL / MariaDB
- XAMPP / Laragon / MAMP (atau PHP built-in server)

## Cara menjalankan
1. Jalankan **Apache** dan **MySQL** di XAMPP/Laragon.
2. Buka phpMyAdmin → tab **Import** → pilih `database/store_db.sql` → **Go**.
   (atau CLI: `mysql -u root < database/store_db.sql`)
3. Cek `config/db.php`; sesuaikan user/password MySQL bila berbeda (default: `root` tanpa password).
4. Salin folder ini ke `htdocs/` (XAMPP), lalu buka
   `http://localhost/product-manager/public/index.php`
   — atau jalankan dari folder `public/`: `php -S localhost:8000`, lalu buka `http://localhost:8000`.

## Fitur
| Fitur | File |
|---|---|
| Read (card responsif) + Search (GET) | `public/index.php` |
| Create + validasi + PRG | `public/create.php` |
| Read by ID + Update | `public/edit.php` |
| Delete (POST + CSRF) | `public/delete.php` |
| Koneksi PDO + helper (escape, CSRF, validasi) | `config/db.php` |
| Skema + data contoh | `database/store_db.sql` |

## Aturan validasi
Nama ≥ 3 karakter dan unik · Kategori wajib · Harga > 0 · Stok ≥ 0.

## Kontrol keamanan
- **SQL Injection**: semua query berparameter memakai `prepare()` + `execute()`.
- **XSS**: setiap output teks user lewat `e()` = `htmlspecialchars(..., ENT_QUOTES, "UTF-8")`.
- **CSRF**: token acak di session, diverifikasi dengan `hash_equals()` pada create/edit/delete.
- **Resubmit**: pola Post–Redirect–Get setelah create/update/delete.
- **Validasi**: dilakukan di server (atribut HTML hanya bantuan UX).
