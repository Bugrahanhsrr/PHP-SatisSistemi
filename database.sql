-- Product Selling System Database
-- Neo Glass Dark Theme E-commerce Platform

CREATE DATABASE IF NOT EXISTS `product_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `product_system`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Bekliyor','Teslim Edildi','İptal Edildi') NOT NULL DEFAULT 'Bekliyor',
  `admin_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `status` (`status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin table
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `iban` varchar(100) DEFAULT NULL,
  `papara` varchar(100) DEFAULT NULL,
  `telegram_bot` varchar(255) DEFAULT NULL,
  `telegram_chatid` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: password)
-- IMPORTANT: Run install.php to set your own admin credentials
-- Default password hash is for "password" - CHANGE THIS!
INSERT INTO `admin` (`username`, `password`, `iban`, `papara`, `telegram_bot`, `telegram_chatid`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL);

-- Insert sample products
INSERT INTO `products` (`name`, `price`, `description`, `image_url`, `category`) VALUES
('Premium Ürün Paketi', 299.99, 'Özel tasarlanmış premium ürün paketi. Yüksek kalite garantisi.', 'https://via.placeholder.com/400x300/57F287/0f1720?text=Premium+Product', 'Paket'),
('Standart Ürün', 149.99, 'Kaliteli standart ürün. Uygun fiyat, yüksek performans.', 'https://via.placeholder.com/400x300/57F287/0f1720?text=Standard+Product', 'Standart'),
('Basic Ürün', 79.99, 'Temel ihtiyaçlarınız için ideal ürün. Ekonomik çözüm.', 'https://via.placeholder.com/400x300/57F287/0f1720?text=Basic+Product', 'Basic'),
('Deluxe Paket', 499.99, 'En kapsamlı ürün paketi. Tüm özellikler dahil.', 'https://via.placeholder.com/400x300/57F287/0f1720?text=Deluxe+Package', 'Paket');

