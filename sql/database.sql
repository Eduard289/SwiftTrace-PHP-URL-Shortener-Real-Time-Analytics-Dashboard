CREATE DATABASE IF NOT EXISTS swift_trace;
USE swift_trace;

-- Tabla principal: Guarda el enlace original y su versión corta
CREATE TABLE IF NOT EXISTS links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    short_code VARCHAR(10) NOT NULL UNIQUE,
    long_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (short_code)
) ENGINE=InnoDB;

-- Tabla de analítica: Registra cada clic de forma independiente
CREATE TABLE IF NOT EXISTS analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device_type VARCHAR(50),
    browser VARCHAR(50),
    os VARCHAR(50),
    referer TEXT,
    click_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE
) ENGINE=InnoDB;
