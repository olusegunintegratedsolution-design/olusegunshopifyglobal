-- Create Database (Uncomment if needed on VPS; shared hosts create databases via panel)
-- CREATE DATABASE IF NOT EXISTS olusegun_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE olusegun_cms;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `visitors`, `media_library`, `contact_messages`, `testimonials`, `blogs`, `blog_categories`, `services`, `pages`, `website_settings`, `admins`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Admins Table
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `reset_token` VARCHAR(100) DEFAULT NULL,
  `reset_expiry` DATETIME DEFAULT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Website Settings & SEO Parameters
CREATE TABLE `website_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `meta_title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `meta_keywords` VARCHAR(255) NOT NULL,
  `og_title` VARCHAR(255) DEFAULT NULL,
  `og_description` TEXT DEFAULT NULL,
  `favicon_path` VARCHAR(255) DEFAULT 'assets/favicon.png',
  `contact_email` VARCHAR(100) NOT NULL,
  `whatsapp_number` VARCHAR(30) NOT NULL,
  `telegram_username` VARCHAR(50) NOT NULL,
  `footer_copyright` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Dynamic Section Pages (Hero & About content as parsed structured storage)
CREATE TABLE `pages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `section_key` VARCHAR(50) NOT NULL UNIQUE,
  `content_json` LONGTEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Services Table
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `service_key` VARCHAR(50) NOT NULL UNIQUE,
  `title` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'fa-solid fa-cube',
  `description` TEXT NOT NULL,
  `benefits_json` TEXT NOT NULL, -- JSON array of bullet points
  `expected_results` TEXT NOT NULL,
  `why_needed` TEXT NOT NULL,
  `problems_solved` TEXT NOT NULL,
  `sort_order` INT DEFAULT 0,
  INDEX `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Blog Categories Table
CREATE TABLE `blog_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Blog Posts Table
CREATE TABLE `blogs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `summary` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) DEFAULT 'uploads/blog-default.jpg',
  `status` ENUM('draft', 'published') DEFAULT 'draft',
  `seo_title` VARCHAR(255) DEFAULT NULL,
  `seo_description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Testimonials Table
CREATE TABLE `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(100) NOT NULL,
  `client_role` VARCHAR(100) NOT NULL,
  `star_rating` INT DEFAULT 5,
  `project_type` VARCHAR(100) NOT NULL,
  `feedback` TEXT NOT NULL,
  `client_image` VARCHAR(255) DEFAULT 'uploads/avatar-default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Unified Contact Messages Table (unifies regular contact, SEO audits, and store conversion audits)
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `message_type` ENUM('contact', 'seo_audit', 'store_audit') NOT NULL DEFAULT 'contact',
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `whatsapp_number` VARCHAR(50) DEFAULT NULL,
  `business_name` VARCHAR(150) DEFAULT NULL,
  `service_needed` VARCHAR(100) DEFAULT NULL,
  `budget_range` VARCHAR(50) DEFAULT NULL,
  `project_deadline` VARCHAR(50) DEFAULT NULL,
  `project_description` TEXT DEFAULT NULL,
  `goals_to_achieve` TEXT DEFAULT NULL,
  -- For Audits
  `target_url` VARCHAR(255) DEFAULT NULL,
  `industry` VARCHAR(100) DEFAULT NULL,
  `platform_used` VARCHAR(50) DEFAULT NULL,
  `monthly_revenue` VARCHAR(50) DEFAULT NULL,
  `main_challenge` TEXT DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_read` (`is_read`),
  INDEX `idx_type` (`message_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Media Library Table
CREATE TABLE `media_library` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT NOT NULL,
  `file_type` VARCHAR(50) NOT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Visitor Traffic Table
CREATE TABLE `visitors` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `ip_hash` VARCHAR(64) NOT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `visit_date` DATE NOT NULL,
  UNIQUE KEY `idx_visit_unique` (`ip_hash`, `visit_date`),
  INDEX `idx_date` (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Admin Credentials
-- Default credentials initialized below:
-- Username: admin
-- Password: AdminPassword2025!
INSERT INTO `admins` (`username`, `email`, `password_hash`, `role`)
VALUES ('admin', 'olusegunintegratedsolution@gmail.com', '$2y$10$gE3l/nKkK.q3e3v4GZkZJu/3oY/XWz96o6R.u8jJv7Pez.qD7C3W6', 'admin');

-- Seed Standard Categories
INSERT INTO `blog_categories` (`name`, `slug`) VALUES 
('E-commerce Strategy', 'e-commerce-strategy'),
('Shopify Setup', 'shopify-setup'),
('Search Engine Optimization', 'seo'),
('UI/UX Optimization', 'ui-ux-optimization');