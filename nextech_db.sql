CREATE DATABASE IF NOT EXISTS nextech_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE nextech_db;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    country VARCHAR(50) DEFAULT NULL,
    interest VARCHAR(100) NOT NULL,
    current_level VARCHAR(50) DEFAULT NULL,
    learning_method VARCHAR(50) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;