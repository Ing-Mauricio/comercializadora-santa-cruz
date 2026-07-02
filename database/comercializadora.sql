-- ============================================================
-- Base de datos: comercializadora
-- Sistema de Comercializadora - MVC PHP + PDO
-- ============================================================

CREATE DATABASE IF NOT EXISTS comercializadora CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE comercializadora;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','vendedor') NOT NULL DEFAULT 'vendedor',
    estado TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_acceso DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contraseña para ambos usuarios: 123456  (hash bcrypt válido, compatible con password_verify de PHP)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador General', 'admin@comercializadora.com', '$2b$10$bfMzcOMKn4cb1JAF0Rzuz.bHIpwLI/byyWgMvmMM.d6A8m4Y48lki', 'admin'),
('Carlos Vendedor', 'vendedor@comercializadora.com', '$2b$10$bfMzcOMKn4cb1JAF0Rzuz.bHIpwLI/byyWgMvmMM.d6A8m4Y48lki', 'vendedor');

-- ------------------------------------------------------------
-- Tabla: categorias
-- ------------------------------------------------------------
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255) NULL
) ENGINE=InnoDB;

INSERT INTO categorias (nombre, descripcion) VALUES
('Abarrotes', 'Productos de consumo masivo'),
('Bebidas', 'Bebidas frías y calientes'),
('Limpieza', 'Artículos de limpieza y aseo'),
('Electrónica', 'Accesorios y dispositivos electrónicos'),
('Cuidado Personal', 'Higiene y cuidado personal');

-- ------------------------------------------------------------
-- Tabla: clientes
-- ------------------------------------------------------------
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    nit_ci VARCHAR(30) NULL,
    email VARCHAR(120) NULL,
    telefono VARCHAR(30) NULL,
    direccion VARCHAR(200) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO clientes (nombre, apellido, nit_ci, email, telefono, direccion) VALUES
('Juan', 'Pérez López', '4589621', 'juan.perez@mail.com', '77451236', 'Av. Siempre Viva #123'),
('María', 'Fernández Rojas', '5521478', 'maria.fernandez@mail.com', '76541289', 'Calle Sucre #456'),
('Empresa Andina S.R.L.', '-', '102547896', 'contacto@andina.com', '2452147', 'Zona Central, Edif. Torino Piso 3');

-- ------------------------------------------------------------
-- Tabla: productos  (con precio real, imagen y stock mínimo para alertas)
-- ------------------------------------------------------------
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion VARCHAR(255) NULL,
    categoria_id INT NULL,
    precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 10,
    imagen VARCHAR(255) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO productos (codigo, nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, stock_minimo, imagen) VALUES
('PRD-0001', 'Arroz Extra 1kg', 'Arroz blanco extra, paquete de 1kg', 1, 5.50, 7.80, 120, 15, 'no-image.svg'),
('PRD-0002', 'Aceite Fino 900ml', 'Aceite vegetal comestible', 1, 9.20, 12.50, 80, 15, 'no-image.svg'),
('PRD-0003', 'Coca Cola 2L', 'Bebida gaseosa botella 2 litros', 2, 6.00, 9.50, 60, 20, 'no-image.svg'),
('PRD-0004', 'Agua Mineral 600ml', 'Agua mineral sin gas', 2, 1.80, 3.00, 200, 30, 'no-image.svg'),
('PRD-0005', 'Detergente en Polvo 1kg', 'Detergente para ropa', 3, 8.00, 11.90, 8, 10, 'no-image.svg'),
('PRD-0006', 'Lavandina 1L', 'Cloro desinfectante', 3, 4.50, 6.90, 5, 10, 'no-image.svg'),
('PRD-0007', 'Audífonos Bluetooth', 'Audífonos inalámbricos manos libres', 4, 45.00, 79.90, 25, 5, 'no-image.svg'),
('PRD-0008', 'Cargador USB-C 20W', 'Cargador rápido tipo C', 4, 22.00, 39.90, 3, 5, 'no-image.svg'),
('PRD-0009', 'Shampoo 400ml', 'Shampoo para todo tipo de cabello', 5, 10.50, 15.90, 45, 10, 'no-image.svg'),
('PRD-0010', 'Jabón de Tocador', 'Jabón perfumado unidad', 5, 2.10, 3.50, 150, 25, 'no-image.svg');

-- ------------------------------------------------------------
-- Tabla: ventas
-- ------------------------------------------------------------
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(20) NOT NULL UNIQUE,
    cliente_id INT NULL,
    usuario_id INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    estado ENUM('completada','anulada') NOT NULL DEFAULT 'completada',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: venta_detalle
-- ------------------------------------------------------------
CREATE TABLE venta_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: movimientos_stock (entradas/salidas, quién movió el stock)
-- ------------------------------------------------------------
CREATE TABLE movimientos_stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('entrada','salida') NOT NULL,
    cantidad INT NOT NULL,
    stock_anterior INT NOT NULL,
    stock_nuevo INT NOT NULL,
    motivo VARCHAR(255) NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabla: auditoria (bitácora general del sistema)
-- ------------------------------------------------------------
CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    modulo VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO auditoria (usuario_id, modulo, accion, descripcion, ip) VALUES
(1, 'Sistema', 'Instalación', 'Base de datos creada e inicializada correctamente', '127.0.0.1');
