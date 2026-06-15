CREATE DATABASE clinica_veterinaria;
USE clinica_veterinaria;

-- =========================
-- TABLA: CLIENTES
-- =========================
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(20),
  correo VARCHAR(100),
  direccion VARCHAR(150),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLA: MASCOTAS
-- =========================
CREATE TABLE mascotas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  especie VARCHAR(50),
  raza VARCHAR(50),
  sexo ENUM('Macho','Hembra'),
  fecha_nacimiento DATE,
  peso DECIMAL(5,2),
  foto VARCHAR(255),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- =========================
-- TABLA: VETERINARIOS
-- =========================
CREATE TABLE veterinarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  especialidad VARCHAR(100),
  telefono VARCHAR(20)
);

-- =========================
-- TABLA: CITAS
-- =========================
CREATE TABLE citas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mascota_id INT NOT NULL,
  veterinario_id INT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  motivo VARCHAR(200),
  estado ENUM('Pendiente','Confirmada','Atendida','Cancelada') DEFAULT 'Pendiente',
  observaciones TEXT,
  FOREIGN KEY (mascota_id) REFERENCES mascotas(id) ON DELETE CASCADE,
  FOREIGN KEY (veterinario_id) REFERENCES veterinarios(id) ON DELETE SET NULL
);

-- =========================
-- TABLA: HISTORIALES MÉDICOS
-- =========================
CREATE TABLE historiales_medicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mascota_id INT NOT NULL,
  cita_id INT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo TEXT,
  diagnostico TEXT,
  tratamiento TEXT,
  alergias TEXT,
  vacunas TEXT,
  peso DECIMAL(5,2),
  FOREIGN KEY (mascota_id) REFERENCES mascotas(id) ON DELETE CASCADE,
  FOREIGN KEY (cita_id) REFERENCES citas(id) ON DELETE SET NULL
);

-- =========================
-- TABLA: INVENTARIO
-- =========================
CREATE TABLE inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto VARCHAR(120) NOT NULL,
  categoria VARCHAR(80),
  cantidad INT NOT NULL DEFAULT 0,
  stock_minimo INT NOT NULL DEFAULT 5,
  precio_compra DECIMAL(10,2) DEFAULT 0,
  precio_venta DECIMAL(10,2) DEFAULT 0,
  fecha_vencimiento DATE NULL
);

-- =========================
-- TABLA: FACTURAS
-- =========================
CREATE TABLE facturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  mascota_id INT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
  descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
  impuesto DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  estado ENUM('Pendiente','Pagada','Parcial') DEFAULT 'Pendiente',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (mascota_id) REFERENCES mascotas(id) ON DELETE SET NULL
);

-- =========================
-- TABLA: PAGOS
-- =========================
CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  factura_id INT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  monto DECIMAL(10,2) NOT NULL,
  metodo_pago ENUM('Efectivo','Tarjeta','Transferencia','QR') NOT NULL,
  referencia VARCHAR(100),
  FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE
);

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS PARA EDITAR Y ELIMINAR
-- =====================================================

-- =========================
-- CLIENTES
-- =========================

DELIMITER $$

CREATE PROCEDURE editar_cliente(
  IN p_id INT,
  IN p_nombre VARCHAR(100),
  IN p_telefono VARCHAR(20),
  IN p_correo VARCHAR(100),
  IN p_direccion VARCHAR(150)
)
BEGIN
  UPDATE clientes
  SET nombre = p_nombre,
      telefono = p_telefono,
      correo = p_correo,
      direccion = p_direccion
  WHERE id = p_id;
END $$

CREATE PROCEDURE eliminar_cliente(
  IN p_id INT
)
BEGIN
  DELETE FROM clientes
  WHERE id = p_id;
END $$

-- =========================
-- MASCOTAS
-- =========================

CREATE PROCEDURE editar_mascota(
  IN p_id INT,
  IN p_cliente_id INT,
  IN p_nombre VARCHAR(100),
  IN p_especie VARCHAR(50),
  IN p_raza VARCHAR(50),
  IN p_sexo ENUM('Macho','Hembra'),
  IN p_fecha_nacimiento DATE,
  IN p_peso DECIMAL(5,2),
  IN p_foto VARCHAR(255)
)
BEGIN
  UPDATE mascotas
  SET cliente_id = p_cliente_id,
      nombre = p_nombre,
      especie = p_especie,
      raza = p_raza,
      sexo = p_sexo,
      fecha_nacimiento = p_fecha_nacimiento,
      peso = p_peso,
      foto = p_foto
  WHERE id = p_id;
END $$

CREATE PROCEDURE eliminar_mascota(
  IN p_id INT
)
BEGIN
  DELETE FROM mascotas
  WHERE id = p_id;
END $$

-- =========================
-- VETERINARIOS
-- =========================

CREATE PROCEDURE editar_veterinario(
  IN p_id INT,
  IN p_nombre VARCHAR(100),
  IN p_especialidad VARCHAR(100),
  IN p_telefono VARCHAR(20)
)
BEGIN
  UPDATE veterinarios
  SET nombre = p_nombre,
      especialidad = p_especialidad,
      telefono = p_telefono
  WHERE id = p_id;
END $$

CREATE PROCEDURE eliminar_veterinario(
  IN p_id INT
)
BEGIN
  DELETE FROM veterinarios
  WHERE id = p_id;
END $$

-- =========================
-- CITAS
-- =========================

CREATE PROCEDURE editar_cita(
  IN p_id INT,
  IN p_mascota_id INT,
  IN p_veterinario_id INT,
  IN p_fecha DATE,
  IN p_hora TIME,
  IN p_motivo VARCHAR(200),
  IN p_estado ENUM('Pendiente','Confirmada','Atendida','Cancelada'),
  IN p_observaciones TEXT
)
BEGIN
  UPDATE citas
  SET mascota_id = p_mascota_id,
      veterinario_id = p_veterinario_id,
      fecha = p_fecha,
      hora = p_hora,
      motivo = p_motivo,
      estado = p_estado,
      observaciones = p_observaciones
  WHERE id = p_id;
END $$

CREATE PROCEDURE eliminar_cita(
  IN p_id INT
)
BEGIN
  DELETE FROM citas
  WHERE id = p_id;
END $$

DELIMITER ;