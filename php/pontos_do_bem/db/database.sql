-- Create database if not exists
CREATE DATABASE IF NOT EXISTS pontos_do_bem;
USE pontos_do_bem;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14),
    whatsapp VARCHAR(20),
    status ENUM('Ativo', 'Pendente') DEFAULT 'Pendente',
    valor_mensal DECIMAL(10,2),
    pontos_acumulados INT DEFAULT 0,
    valor_acumulado DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    valor DECIMAL(10,2) NOT NULL,
    data_pagamento DATE,
    status ENUM('Pago', 'Pendente') DEFAULT 'Pendente',
    pontos_creditados INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin user
INSERT INTO admin (username, password) VALUES ('admin_ifc', 'REDACTED');
