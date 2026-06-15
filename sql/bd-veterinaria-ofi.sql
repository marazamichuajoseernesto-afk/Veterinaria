CREATE DATABASE IF NOT EXISTS clinica_veterinaria;
USE clinica_veterinaria;

-- CLIENTES
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(200),
    email VARCHAR(100)
);

-- MASCOTAS
CREATE TABLE mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    especie VARCHAR(50),
    raza VARCHAR(80),
    sexo ENUM('Macho','Hembra'),
    fecha_nacimiento DATE,
    peso DECIMAL(5,2),
    color VARCHAR(50),

    CONSTRAINT fk_mascota_cliente
    FOREIGN KEY (cliente_id)
    REFERENCES clientes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- VETERINARIOS
CREATE TABLE veterinarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100),
    telefono VARCHAR(20)
);

-- CITAS
CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mascota_id INT NOT NULL,
    veterinario_id INT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    motivo VARCHAR(200),
    estado ENUM(
        'Pendiente',
        'Confirmada',
        'Atendida',
        'Cancelada'
    ) DEFAULT 'Pendiente',
    observaciones TEXT,

    FOREIGN KEY (mascota_id)
    REFERENCES mascotas(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    FOREIGN KEY (veterinario_id)
    REFERENCES veterinarios(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);

-- HISTORIALES MEDICOS
CREATE TABLE historiales_medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mascota_id INT NOT NULL,
    cita_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo TEXT,
    diagnostico TEXT,
    tratamiento TEXT,
    alergias TEXT,
    vacunas TEXT,
    peso DECIMAL(5,2),

    FOREIGN KEY (mascota_id)
    REFERENCES mascotas(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    FOREIGN KEY (cita_id)
    REFERENCES citas(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);

-- INVENTARIO
CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto VARCHAR(120) NOT NULL,
    categoria VARCHAR(80),
    cantidad INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    precio_compra DECIMAL(10,2) DEFAULT 0,
    precio_venta DECIMAL(10,2) DEFAULT 0,
    fecha_vencimiento DATE
);

-- FACTURAS
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    mascota_id INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) DEFAULT 0,
    descuento DECIMAL(10,2) DEFAULT 0,
    impuesto DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) DEFAULT 0,
    estado ENUM(
        'Pendiente',
        'Pagada',
        'Parcial'
    ) DEFAULT 'Pendiente',

    FOREIGN KEY (cliente_id)
    REFERENCES clientes(id)
    ON UPDATE CASCADE,

    FOREIGN KEY (mascota_id)
    REFERENCES mascotas(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);

-- PAGOS
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM(
        'Efectivo',
        'Tarjeta',
        'Transferencia',
        'QR'
    ) DEFAULT 'Efectivo',

    FOREIGN KEY (factura_id)
    REFERENCES facturas(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- USUARIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM(
        'Administrador',
        'Veterinario',
        'Recepcionista'
    ) DEFAULT 'Recepcionista',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);