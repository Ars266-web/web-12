-- TechSpace E-Commerce Database
-- Pakistani Technology Store

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:00";

-- Create Database
CREATE DATABASE IF NOT EXISTS `techspace` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `techspace`;

-- ============================================
-- TABLE: admins
-- ============================================
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','staff') DEFAULT 'admin',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin (Password: Admin@12345)
INSERT INTO `admins` (`name`, `email`, `password`, `role`, `status`) VALUES
('Super Admin', 'admin@techspace.pk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 1);

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phone` varchar(20),
  `password` varchar(255) NOT NULL,
  `province` varchar(50),
  `city` varchar(50),
  `area` varchar(100),
  `address` text,
  `postal_code` varchar(10),
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: categories
-- ============================================
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `description` text,
  `image` varchar(255),
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Laptops', 'laptops', 'High-performance laptops for work and gaming', 1),
('Smartphones', 'smartphones', 'Latest smartphones from top brands', 2),
('Tablets', 'tablets', 'Portable tablets for entertainment and productivity', 3),
('Gaming', 'gaming', 'Gaming accessories and peripherals', 4),
('PC Components', 'pc-components', 'Build your dream PC with our components', 5),
('Monitors', 'monitors', 'Crystal clear displays for work and gaming', 6),
('Keyboards', 'keyboards', 'Mechanical and wireless keyboards', 7),
('Mice', 'mice', 'Precision mice for gaming and office', 8),
('Headphones', 'headphones', 'Premium audio experience', 9),
('Smart Watches', 'smart-watches', 'Stay connected with smart wearables', 10),
('Cameras', 'cameras', 'Capture moments with professional cameras', 11),
('Storage', 'storage', 'SSDs, HDDs and external storage solutions', 12),
('Networking', 'networking', 'Routers, switches and networking gear', 13),
('Mobile Accessories', 'mobile-accessories', 'Chargers, cases and more', 14),
('Power Banks', 'power-banks', 'Portable power solutions', 15);

-- ============================================
-- TABLE: brands
-- ============================================
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `logo` varchar(255),
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample brands
INSERT INTO `brands` (`name`, `slug`) VALUES
('HP', 'hp'),
('Dell', 'dell'),
('Lenovo', 'lenovo'),
('Apple', 'apple'),
('Samsung', 'samsung'),
('Xiaomi', 'xiaomi'),
('Logitech', 'logitech'),
('ASUS', 'asus'),
('MSI', 'msi'),
('Sony', 'sony'),
('JBL', 'jbl'),
('Seagate', 'seagate'),
('Western Digital', 'western-digital'),
('TP-Link', 'tp-link'),
('Anker', 'anker');

-- ============================================
-- TABLE: products
-- ============================================
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL UNIQUE,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11),
  `short_description` text,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0,
  `stock_quantity` int(11) DEFAULT 0,
  `featured_image` varchar(255),
  `warranty` varchar(100),
  `tags` text,
  `status` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `sales_count` int(11) DEFAULT 0,
  `seo_title` varchar(255),
  `meta_description` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `slug` (`slug`),
  KEY `sku` (`sku`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: product_images
-- ============================================
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_product_image` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: product_specs
-- ============================================
CREATE TABLE `product_specs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_product_spec` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: cart
-- ============================================
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11),
  `session_id` varchar(100),
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: wishlist
-- ============================================
CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`),
  CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: orders
-- ============================================
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL UNIQUE,
  `user_id` int(11),
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `area` varchar(100),
  `address` text NOT NULL,
  `postal_code` varchar(10),
  `delivery_instructions` text,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0,
  `discount_amount` decimal(10,2) DEFAULT 0,
  `coupon_code` varchar(50),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cod','bank_transfer','jazzcash','easypaisa') DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `transaction_id` varchar(100),
  `order_status` enum('pending','confirmed','processing','packed','shipped','out_for_delivery','delivered','cancelled','returned') DEFAULT 'pending',
  `tracking_number` varchar(100),
  `notes` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_number` (`order_number`),
  KEY `order_status` (`order_status`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: order_items
-- ============================================
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `sku` varchar(50),
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_item_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: payments
-- ============================================
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(100),
  `payment_details` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: reviews
-- ============================================
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `title` varchar(255),
  `comment` text,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: coupons
-- ============================================
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `description` text,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT 0,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `expiry_date` date,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample coupons
INSERT INTO `coupons` (`code`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `status`) VALUES
('TECH10', 'Get 10% off on all products', 'percentage', 10.00, 5000, 5000, 1),
('WELCOME500', 'Welcome discount Rs. 500', 'fixed', 500, 3000, NULL, 1),
('NEWYEAR20', 'New Year Special 20% Off', 'percentage', 20.00, 10000, 10000, 1);

-- ============================================
-- TABLE: coupon_usage
-- ============================================
CREATE TABLE `coupon_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11),
  `order_id` int(11) NOT NULL,
  `used_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_coupon_usage_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coupon_usage_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_coupon_usage_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: shipping
-- ============================================
CREATE TABLE `shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `courier_name` varchar(100),
  `tracking_number` varchar(100),
  `shipped_date` datetime,
  `delivered_date` datetime,
  `status` varchar(50) DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_shipping_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE: settings
-- ============================================
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text,
  `setting_type` varchar(50) DEFAULT 'text',
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('store_name', 'TechSpace', 'text'),
('store_tagline', 'Your Space for Smarter Technology', 'text'),
('store_email', 'info@techspace.pk', 'text'),
('store_phone', '+92 300 1234567', 'text'),
('store_address', 'Office 123, Tech Plaza, Blue Area, Islamabad, Pakistan', 'text'),
('facebook_url', 'https://facebook.com/techspacepk', 'text'),
('instagram_url', 'https://instagram.com/techspacepk', 'text'),
('tiktok_url', 'https://tiktok.com/@techspacepk', 'text'),
('youtube_url', 'https://youtube.com/@techspacepk', 'text'),
('bank_name', 'Habib Bank Limited (HBL)', 'text'),
('bank_account_title', 'TechSpace Pvt Ltd', 'text'),
('bank_account_number', 'PK12HBL0001234567890', 'text'),
('bank_iban', 'PK12HBL0001234567890', 'text'),
('jazzcash_number', '03001234567', 'text'),
('easypaisa_number', '03001234567', 'text'),
('shipping_fee', '250', 'number'),
('free_shipping_threshold', '5000', 'number'),
('top_notification', 'Free delivery on orders above Rs. 5,000', 'text'),
('footer_info', 'TechSpace - Your trusted technology partner in Pakistan since 2024.', 'text');

-- ============================================
-- TABLE: notifications
-- ============================================
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11),
  `admin_id` int(11),
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notification_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INSERT SAMPLE PRODUCTS
-- ============================================

-- Laptops
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('HP-EB-840-G10', 'HP EliteBook 840 G10', 'hp-elitebook-840-g10', 1, 1, 'Premium business laptop with Intel Core i7', 'The HP EliteBook 840 G10 is a premium business laptop featuring the latest Intel Core i7 processor, 16GB RAM, and 512GB SSD. Perfect for professionals who demand performance and reliability.', 245000.00, 229999.00, 6.12, 15, 'hp-elitebook-840.jpg', '1 Year Official Warranty', 'laptop,business,hp,intel', 1, 1, 0),
('DL-LAT-5540', 'Dell Latitude 5540', 'dell-latitude-5540', 1, 2, 'Reliable business laptop with great battery life', 'Dell Latitude 5540 offers exceptional performance with Intel Core i5, 8GB RAM, and 256GB SSD. Built for business professionals.', 185000.00, 175000.00, 5.41, 20, 'dell-latitude-5540.jpg', '1 Year Official Warranty', 'laptop,business,dell', 1, 0, 0),
('LN-TP-X1-C9', 'Lenovo ThinkPad X1 Carbon Gen 9', 'lenovo-thinkpad-x1-carbon-gen9', 1, 3, 'Ultra-lightweight premium business laptop', 'ThinkPad X1 Carbon Gen 9 combines power and portability. Features Intel Core i7, 16GB RAM, 1TB SSD in a carbon fiber chassis.', 295000.00, NULL, 0, 8, 'lenovo-x1-carbon.jpg', '2 Years Official Warranty', 'laptop,business,lenovo,ultrabook', 1, 1, 1),
('AP-MBP-14-M3', 'Apple MacBook Pro 14" M3', 'apple-macbook-pro-14-m3', 1, 4, 'Revolutionary M3 chip for professionals', 'MacBook Pro 14" with M3 chip delivers groundbreaking performance. 16GB unified memory, 512GB SSD, stunning Liquid Retina XDR display.', 449999.00, 429999.00, 4.44, 5, 'macbook-pro-14.jpg', '1 Year Apple Warranty', 'laptop,apple,macbook,m3,premium', 1, 1, 1),
('AS-ZEN-14-OLED', 'ASUS ZenBook 14 OLED', 'asus-zenbook-14-oled', 1, 8, 'Stunning OLED display laptop', 'ASUS ZenBook 14 features a gorgeous OLED display, AMD Ryzen 7 processor, 16GB RAM, and 512GB SSD in an elegant design.', 195000.00, 185000.00, 5.13, 12, 'asus-zenbook-14.jpg', '1 Year Official Warranty', 'laptop,asus,oled,ryzen', 1, 0, 0);

-- Smartphones
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('SM-S24U-256', 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 2, 5, 'Ultimate flagship smartphone with AI features', 'Samsung Galaxy S24 Ultra redefines smartphone excellence with Snapdragon 8 Gen 3, 200MP camera, S Pen, and Galaxy AI features.', 289999.00, 279999.00, 3.45, 10, 'samsung-s24-ultra.jpg', '1 Year Official Samsung Warranty', 'smartphone,samsung,flagship,5g', 1, 1, 1),
('AP-IP15PM-256', 'Apple iPhone 15 Pro Max', 'apple-iphone-15-pro-max', 2, 4, 'Most powerful iPhone ever made', 'iPhone 15 Pro Max features titanium design, A17 Pro chip, 48MP camera system, and Action button. The ultimate iPhone experience.', 449999.00, NULL, 0, 6, 'iphone-15-pro-max.jpg', '1 Year Apple Warranty', 'smartphone,apple,iphone,flagship', 1, 1, 0),
('XM-14PRO-256', 'Xiaomi 14 Pro', 'xiaomi-14-pro', 2, 6, 'Flagship killer with Leica camera', 'Xiaomi 14 Pro brings Leica-engineered camera system, Snapdragon 8 Gen 3, and hyper-fast charging at an incredible value.', 189999.00, 179999.00, 5.26, 18, 'xiaomi-14-pro.jpg', '1 Year Official Warranty', 'smartphone,xiaomi,leica,flagship', 1, 0, 1);

-- Gaming
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('LG-GPW-2', 'Logitech G Pro Wireless Mouse', 'logitech-g-pro-wireless-mouse', 8, 7, 'Esports-grade wireless gaming mouse', 'Logitech G Pro Wireless is designed for esports professionals. HERO 25K sensor, ultra-lightweight design, 80-hour battery life.', 32999.00, 29999.00, 9.09, 25, 'logitech-gpro.jpg', '2 Years Official Warranty', 'mouse,gaming,logitech,wireless', 1, 1, 0),
('LG-MX-KYBD', 'Logitech MX Keys Keyboard', 'logitech-mx-keys-keyboard', 7, 7, 'Premium wireless keyboard for productivity', 'Logitech MX Keys features perfect-stroke keys, smart backlighting, and multi-device connectivity for ultimate productivity.', 24999.00, 22999.00, 8.00, 30, 'logitech-mx-keys.jpg', '1 Year Official Warranty', 'keyboard,logitech,wireless,productivity', 1, 0, 0),
('AS-ROG-STRIX', 'ASUS ROG Strix Gaming Headset', 'asus-rog-strix-headset', 9, 8, 'Immersive gaming audio experience', 'ASUS ROG Strix delivers premium gaming audio with 7.1 surround sound, noise-canceling mic, and RGB lighting.', 18999.00, 16999.00, 10.53, 22, 'asus-rog-strix.jpg', '1 Year Official Warranty', 'headset,gaming,asus,rgb', 1, 0, 0);

-- Monitors
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('AS-27-4K', 'ASUS ProArt 27" 4K Monitor', 'asus-proart-27-4k-monitor', 6, 8, 'Professional 4K display for creators', 'ASUS ProArt PA279CV offers 100% sRGB/Rec.709 color accuracy, USB-C connectivity, and ergonomic design for creative professionals.', 65999.00, 59999.00, 9.09, 10, 'asus-proart-27.jpg', '3 Years Official Warranty', 'monitor,asus,4k,professional', 1, 1, 0),
('SM-32-CURV', 'Samsung 32" Curved Gaming Monitor', 'samsung-32-curved-gaming-monitor', 6, 5, 'Immersive curved gaming display', 'Samsung 32" curved monitor with 144Hz refresh rate, 1ms response time, and AMD FreeSync for smooth gaming.', 54999.00, 49999.00, 9.09, 15, 'samsung-curved-32.jpg', '1 Year Official Warranty', 'monitor,samsung,gaming,curved', 1, 0, 0);

-- Storage
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('SM-990-1TB', 'Samsung 990 PRO 1TB NVMe SSD', 'samsung-990-pro-1tb-nvme-ssd', 12, 5, 'Blazing fast PCIe 4.0 SSD', 'Samsung 990 PRO delivers sequential read/write speeds up to 7,450/6,900 MB/s. Perfect for gaming and content creation.', 24999.00, 22999.00, 8.00, 40, 'samsung-990-pro.jpg', '5 Years Samsung Warranty', 'ssd,samsung,nvme,storage', 1, 1, 0),
('WD-BLK-2TB', 'Western Digital Black 2TB HDD', 'western-digital-black-2tb-hdd', 12, 13, 'High-performance desktop hard drive', 'WD Black 2TB offers excellent performance for gaming PCs with 7200 RPM and 256MB cache.', 18999.00, NULL, 0, 35, 'wd-black-2tb.jpg', '5 Years WD Warranty', 'hdd,western-digital,storage,desktop', 1, 0, 0);

-- Power Banks
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('AK-20K-PD', 'Anker PowerCore 20000mAh PD', 'anker-powercore-20000mah-pd', 15, 15, 'High-capacity portable charger', 'Anker PowerCore 20000mAh with Power Delivery charges laptops, tablets, and phones. Dual USB ports for simultaneous charging.', 12999.00, 11499.00, 11.54, 50, 'anker-powercore-20k.jpg', '18 Months Anker Warranty', 'powerbank,anker,portable,charging', 1, 1, 0),
('XM-10K-WRLS', 'Xiaomi 10000mAh Wireless Power Bank', 'xiaomi-10000mah-wireless-power-bank', 15, 6, 'Convenient wireless charging on the go', 'Xiaomi wireless power bank supports 10W wireless charging plus 18W wired fast charging. Compact and portable.', 6999.00, 5999.00, 14.29, 45, 'xiaomi-wireless-pb.jpg', '1 Year Official Warranty', 'powerbank,xiaomi,wireless,portable', 1, 0, 0);

-- Smart Watches
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('AP-AW-U9', 'Apple Watch Ultra 2', 'apple-watch-ultra-2', 10, 4, 'Ultimate sports and adventure watch', 'Apple Watch Ultra 2 features titanium case, precision dual-frequency GPS, up to 36 hours battery life, and advanced health sensors.', 149999.00, 139999.00, 6.67, 8, 'apple-watch-ultra.jpg', '1 Year Apple Warranty', 'smartwatch,apple,fitness,premium', 1, 1, 1),
('SM-GW6-44', 'Samsung Galaxy Watch6 Classic', 'samsung-galaxy-watch6-classic', 10, 5, 'Elegant smartwatch with rotating bezel', 'Galaxy Watch6 Classic combines timeless design with advanced health tracking. Rotating bezel, sleep coaching, and heart rate monitoring.', 69999.00, 64999.00, 7.14, 12, 'samsung-watch6.jpg', '1 Year Samsung Warranty', 'smartwatch,samsung,fitness,health', 1, 0, 0);

-- PC Components
INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `brand_id`, `short_description`, `description`, `price`, `sale_price`, `discount_percent`, `stock_quantity`, `featured_image`, `warranty`, `tags`, `status`, `is_featured`, `is_new`) VALUES
('NV-RTX4070', 'NVIDIA GeForce RTX 4070', 'nvidia-geforce-rtx-4070', 5, NULL, 'Next-gen graphics card for 1440p gaming', 'RTX 4070 delivers exceptional 1440p gaming performance with DLSS 3, ray tracing, and 12GB GDDR6X memory.', 139999.00, 129999.00, 7.14, 6, 'rtx-4070.jpg', '3 Years Manufacturer Warranty', 'gpu,nvidia,graphics-card,gaming', 1, 1, 0),
('AMD-R7-7800X3D', 'AMD Ryzen 7 7800X3D Processor', 'amd-ryzen-7-7800x3d', 5, NULL, 'Best gaming processor with 3D V-Cache', 'Ryzen 7 7800X3D features revolutionary 3D V-Cache technology for unmatched gaming performance. 8 cores, 16 threads.', 79999.00, 74999.00, 6.25, 10, 'amd-7800x3d.jpg', '3 Years AMD Warranty', 'processor,amd,cpu,gaming', 1, 1, 1);

COMMIT;
