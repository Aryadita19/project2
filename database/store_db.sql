-- database/store_db.sql
-- Import lewat phpMyAdmin (tab Import) atau: mysql -u root < database/store_db.sql
CREATE DATABASE IF NOT EXISTS store_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100)  NOT NULL UNIQUE,
  category   VARCHAR(50)   NOT NULL DEFAULT 'Umum',
  price      DECIMAL(12,2) NOT NULL,
  stock      INT           NOT NULL DEFAULT 0,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh
INSERT IGNORE INTO products (name, category, price, stock) VALUES
  ('Keyboard Mekanik', 'Elektronik', 450000, 12),
  ('Mouse Wireless',   'Elektronik', 175000, 30),
  ('Buku Tulis A5',    'ATK',          8500, 200);
