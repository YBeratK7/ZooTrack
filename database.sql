-- Hayvanat Bahçesi Hayvan Takip Sistemi - Veritabanı
-- Charset: utf8mb4

CREATE DATABASE IF NOT EXISTS zoo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
USE zoo_db;

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'keeper') NOT NULL DEFAULT 'keeper',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Hayvanlar tablosu
CREATE TABLE IF NOT EXISTS animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    gender ENUM('Erkek', 'Dişi', 'Bilinmiyor') NOT NULL DEFAULT 'Bilinmiyor',
    birth_date DATE,
    enclosure VARCHAR(100),
    diet VARCHAR(255),
    status ENUM('Sağlıklı', 'Tedavide', 'Karantinada', 'Vefat Etti') NOT NULL DEFAULT 'Sağlıklı',
    weight_kg DECIMAL(6,2),
    notes TEXT,
    added_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Sağlık kayıtları tablosu
CREATE TABLE IF NOT EXISTS health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    record_date DATE NOT NULL,
    diagnosis VARCHAR(255),
    treatment TEXT,
    vet_name VARCHAR(100),
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Örnek admin kullanıcısı (şifre: Admin123!)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@zoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sistem Yöneticisi', 'admin');
