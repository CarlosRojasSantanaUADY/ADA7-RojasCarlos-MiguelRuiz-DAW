CREATE DATABASE IF NOT EXISTS tecnofix_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tecnofix_db;

-- Tabla 1: Usuarios 
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    perfil VARCHAR(30) NOT NULL DEFAULT 'cliente',
    notificaciones VARCHAR(20) NOT NULL DEFAULT 'diaria',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla 2: Ordenes de reparacion
CREATE TABLE IF NOT EXISTS ordenes_reparacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cliente_nombre VARCHAR(100) NOT NULL,
    equipo VARCHAR(100) NOT NULL,
    falla TEXT NOT NULL,
    estado VARCHAR(40) NOT NULL DEFAULT 'Recibido',
    costo_estimado DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla 3: Servicios
CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00
);

INSERT INTO usuarios (nombre, correo, perfil, notificaciones) VALUES
('Carlos Rojas Santana', 'carlos@tecnofix.com', 'administrador', 'diaria');

INSERT INTO ordenes_reparacion (usuario_id, cliente_nombre, equipo, falla, estado, costo_estimado) VALUES
(1, 'Carlos Rojas Santana', 'Laptop Dell Inspiron 15', 'No enciende tras descarga eléctrica, revisar fuente', 'En diagnóstico', 450.00);

INSERT INTO servicios (nombre, descripcion, precio) VALUES
('Diagnostico General', 'Revision completa de hardware y sistema operativo', 200.00),
('Mantenimiento Preventivo', 'Limpieza interna de componentes y cambio de pasta termica', 350.00),
('Formateo e Instalacion de SO', 'Respaldo de archivos, instalacion de Windows y paqueteria basica', 450.00);