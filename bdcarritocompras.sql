CREATE DATABASE IF NOT EXISTS bdcarritocompras CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bdcarritocompras;

-- TABLA: ROL
CREATE TABLE rol (
  idrol BIGINT(20) NOT NULL AUTO_INCREMENT,
  rodescripcion VARCHAR(50) NOT NULL,
  PRIMARY KEY (idrol)
) ENGINE=InnoDB;

-- TABLA: MENU
CREATE TABLE menu (
  idmenu BIGINT(20) NOT NULL AUTO_INCREMENT,
  menombre VARCHAR(50) NOT NULL COMMENT 'Nombre del item del menu',
  medescripcion VARCHAR(124) NOT NULL COMMENT 'Descripcion detallada',
  idpadre BIGINT(20) DEFAULT NULL COMMENT 'Referencia al id del menu padre',
  medeshabilitado TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de deshabilitación',
  PRIMARY KEY (idmenu),
  KEY fkmenu_1 (idpadre),
  CONSTRAINT fkmenu_1 FOREIGN KEY (idpadre) REFERENCES menu (idmenu) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- TABLA: MENUROL
CREATE TABLE menurol (
  idmenu BIGINT(20) NOT NULL,
  idrol BIGINT(20) NOT NULL,
  PRIMARY KEY (idmenu, idrol),
  CONSTRAINT fkmenurol_1 FOREIGN KEY (idmenu) REFERENCES menu (idmenu) ON UPDATE CASCADE,
  CONSTRAINT fkmenurol_2 FOREIGN KEY (idrol) REFERENCES rol (idrol) ON UPDATE CASCADE
) ENGINE=InnoDB;


-- TABLA: USUARIO
CREATE TABLE usuario (
  idusuario BIGINT(20) NOT NULL AUTO_INCREMENT,
  usnombre VARCHAR(50) NOT NULL,
  uspass VARCHAR(100) NOT NULL, -- corregido, antes era int
  usmail VARCHAR(50) NOT NULL,
  usdeshabilitado TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (idusuario)
) ENGINE=InnoDB;


-- TABLA: USUARIOROL
CREATE TABLE usuariorol (
  idusuario BIGINT(20) NOT NULL,
  idrol BIGINT(20) NOT NULL,
  PRIMARY KEY (idusuario, idrol),
  CONSTRAINT fkusuariorol_1 FOREIGN KEY (idusuario) REFERENCES usuario (idusuario) ON UPDATE CASCADE,
  CONSTRAINT fkusuariorol_2 FOREIGN KEY (idrol) REFERENCES rol (idrol) ON UPDATE CASCADE
) ENGINE=InnoDB;


-- TABLA: PRODUCTO
CREATE TABLE producto (
  idproducto BIGINT(20) NOT NULL AUTO_INCREMENT,
  pronombre VARCHAR(50) NOT NULL, -- corregido, antes era int
  prodetalle VARCHAR(512) NOT NULL,
  procantstock INT(11) NOT NULL,
  proprecio DECIMAL(10,2) DEFAULT 0, -- agregado
  proimagen VARCHAR(255) DEFAULT NULL, -- agregado
  PRIMARY KEY (idproducto)
) ENGINE=InnoDB;


-- TABLA: COMPRA
CREATE TABLE compra (
  idcompra BIGINT(20) NOT NULL AUTO_INCREMENT,
  cofecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  idusuario BIGINT(20) NOT NULL,
  PRIMARY KEY (idcompra),
  CONSTRAINT fkcompra_1 FOREIGN KEY (idusuario) REFERENCES usuario (idusuario) ON UPDATE CASCADE
) ENGINE=InnoDB;


-- TABLA: COMPRAESTADOTIPO
CREATE TABLE compraestadotipo (
  idcompraestadotipo INT(11) NOT NULL AUTO_INCREMENT,
  cetdescripcion VARCHAR(50) NOT NULL,
  cetdetalle VARCHAR(256) NOT NULL,
  PRIMARY KEY (idcompraestadotipo)
) ENGINE=InnoDB;


-- TABLA: COMPRAESTADO
CREATE TABLE compraestado (
  idcompraestado BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  idcompra BIGINT(11) NOT NULL,
  idcompraestadotipo INT(11) NOT NULL,
  cefechaini TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  cefechafin TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (idcompraestado),
  KEY fkcompraestado_1 (idcompra),
  KEY fkcompraestado_2 (idcompraestadotipo),
  CONSTRAINT fkcompraestado_1 FOREIGN KEY (idcompra) REFERENCES compra (idcompra) ON UPDATE CASCADE,
  CONSTRAINT fkcompraestado_2 FOREIGN KEY (idcompraestadotipo) REFERENCES compraestadotipo (idcompraestadotipo) ON UPDATE CASCADE
) ENGINE=InnoDB;


-- TABLA: COMPRAITEM
CREATE TABLE compraitem (
  idcompraitem BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  idproducto BIGINT(20) NOT NULL,
  idcompra BIGINT(20) NOT NULL,
  cicantidad INT(11) NOT NULL,
  PRIMARY KEY (idcompraitem),
  CONSTRAINT fkcompraitem_1 FOREIGN KEY (idcompra) REFERENCES compra (idcompra) ON UPDATE CASCADE,
  CONSTRAINT fkcompraitem_2 FOREIGN KEY (idproducto) REFERENCES producto (idproducto) ON UPDATE CASCADE
) ENGINE=InnoDB;


-- DATOS INICIALES

-- Roles
INSERT INTO rol (rodescripcion) VALUES
('Administrador'),
('Cliente');

-- Menú
INSERT INTO menu (menombre, medescripcion, idpadre) VALUES
('Inicio', 'Página principal', NULL),
('Productos', 'Listado de productos disponibles', NULL),
('Usuarios', 'Gestión de usuarios', NULL),
('Compras', 'Gestión de compras', NULL);

-- Asociación menú-rol
INSERT INTO menurol (idmenu, idrol) VALUES
(1,1),(2,1),(3,1),(4,1),  -- Admin
(1,2),(2,2);              -- Cliente

-- Usuarios
INSERT INTO usuario (usnombre, uspass, usmail) VALUES
('admin', '1234', 'admin@tienda.com'),
('cliente1', '1234', 'cliente1@correo.com');

-- Usuario-Rol
INSERT INTO usuariorol (idusuario, idrol) VALUES
(1,1),
(2,2);

-- Productos
INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio, proimagen) VALUES
('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', 25, 15999.99, 'auriculares.jpg'),
('Mouse Gamer', 'Mouse óptico RGB con 6 botones', 40, 7999.50, 'mouse.jpg'),
('Teclado Mecánico', 'Teclado retroiluminado con switches azules', 15, 19999.00, 'teclado.jpg'),
('Monitor 24"', 'Monitor LED Full HD de 24 pulgadas', 10, 54999.90, 'monitor.jpg'),
('Parlantes Bluetooth', 'Parlantes portátiles con sonido envolvente', 30, 12999.00, 'parlantes.jpg');

-- Estados de compra
INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) VALUES
('Iniciada', 'Compra creada por el cliente.'),
('Aceptada', 'Compra aceptada por el sistema.'),
('Enviada', 'Compra despachada.'),
('Cancelada', 'Compra cancelada por el usuario o el sistema.');
