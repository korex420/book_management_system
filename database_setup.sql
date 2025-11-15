-- Create database
CREATE DATABASE IF NOT EXISTS book_management;
USE book_management;

-- Create books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    publication_year INT NOT NULL,
    isbn VARCHAR(20),
    description TEXT,
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO books (title, author, genre, publication_year, isbn, description, price) VALUES
('The Midnight Library', 'Matt Haig', 'Fiction', 2020, '978-1786892737', 'A novel about regret, hope and second chances.', 12.99),
('Project Hail Mary', 'Andy Weir', 'Sci-Fi', 2021, '978-0593135204', 'A lone astronaut must save the earth from disaster.', 14.99),
('Klara and the Sun', 'Kazuo Ishiguro', 'Sci-Fi', 2021, '978-0571364879', 'A story about what it means to love.', 13.50),
('The Three-Body Problem', 'Liu Cixin', 'Sci-Fi', 2014, '978-0765377067', 'A cutting-edge sci-fi about contact with an alien civilization.', 11.99),
('Dune', 'Frank Herbert', 'Sci-Fi', 1965, '978-0441172719', 'A epic science fiction novel set in the distant future.', 9.99);

-- Create users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default users (passwords: admin123 and user123)
-- Note: These are for demonstration. The actual authentication uses hardcoded credentials in auth.php
INSERT IGNORE INTO users (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com'),
('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user@example.com');

