-- Auto Detox Studio — database schema
-- Run once: /opt/lampp/bin/mysql -u root < database/schema.sql

CREATE DATABASE IF NOT EXISTS autodetoxstudio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE autodetoxstudio;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(50) NOT NULL UNIQUE,
  tag VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  examples VARCHAR(255) NOT NULL DEFAULT '',
  price DECIMAL(10,2) NOT NULL,
  strike_price DECIMAL(10,2) NOT NULL,
  note VARCHAR(255) NOT NULL DEFAULT '',
  features TEXT,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  display_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(150) DEFAULT NULL,
  is_blocked TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  package_id INT DEFAULT NULL,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  car_model VARCHAR(100) NOT NULL DEFAULT '',
  preferred_date DATE NOT NULL,
  preferred_time VARCHAR(20) NOT NULL,
  notes TEXT,
  status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- seed packages (matches the pricing already shown on the site)
INSERT INTO packages (slug, tag, name, examples, price, strike_price, note, features, is_featured, display_order) VALUES
('hatchback', 'Compact', 'Hatchback', 'Swift · Baleno · i20 · Altroz · Punch · WagonR', 1999, 3999, 'After 50% introductory discount',
 'Full 23-step detox\nInterior + Exterior + Engine bay\nPremium liquid wax finish\nComplete steam sanitization', 0, 1),
('sedan', 'Most Booked', 'Sedan', 'Dzire · Amaze · City · Verna · Slavia · Virtus', 2499, 4999, 'After 50% introductory discount',
 'Full 23-step detox\nInterior + Exterior + Engine bay\nPremium liquid wax finish\nComplete steam sanitization', 1, 2),
('suv', 'Full-Size', 'SUV / XUV', 'Creta · Seltos · XUV700 · Nexon · Fortuner · Scorpio', 2999, 5999, 'After 50% introductory discount',
 'Full 23-step detox\nInterior + Exterior + Engine bay\nPremium liquid wax finish\nComplete steam sanitization', 0, 3)
ON DUPLICATE KEY UPDATE slug = slug;

-- Interactive gallery, enquiry popup and enquiry submissions
CREATE TABLE IF NOT EXISTS gallery_media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  media_type ENUM('image','video') NOT NULL DEFAULT 'image',
  file_path VARCHAR(500) NOT NULL,
  title VARCHAR(160) NOT NULL DEFAULT '',
  subtitle VARCHAR(160) NOT NULL DEFAULT '',
  display_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS enquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  service VARCHAR(120) DEFAULT NULL,
  message TEXT,
  status ENUM('new','contacted','closed') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('enquiry_popup_enabled','1'),
('enquiry_popup_title','Need help choosing the right service?'),
('enquiry_popup_message','Leave your details and our team will call you with the right recommendation.')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
