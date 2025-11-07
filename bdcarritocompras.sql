CREATE DATABASE IF NOT EXISTS bdcarritocompras;
USE bdcarritocompras;

-- Tabla: rol
CREATE TABLE rol (
    idrol BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    rodescripcion VARCHAR(50) NOT NULL
);

-- Tabla: menu
CREATE TABLE menu (
    idmenu BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    menombre VARCHAR(50),
    medescripcion VARCHAR(124),
    idpadre BIGINT(20),
    medeshabilitado TIMESTAMP NULL
);

-- Tabla: menuroL 
CREATE TABLE menuroL (
    idmenu BIGINT(20),
    idrol BIGINT(20),
    PRIMARY KEY (idmenu, idrol),
    FOREIGN KEY (idmenu) REFERENCES menu(idmenu),
    FOREIGN KEY (idrol) REFERENCES rol(idrol)
);

-- Tabla: usuario
CREATE TABLE usuario (
    idusuario BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    usnombre VARCHAR(50) NOT NULL,
    uspass VARCHAR(100) NOT NULL,
    usmail VARCHAR(50),
    usdeshabilitado TIMESTAMP NULL
);

-- Tabla: usuariorol 
CREATE TABLE usuariorol (
    idusuario BIGINT(20),
    idrol BIGINT(20),
    PRIMARY KEY (idusuario, idrol),
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario),
    FOREIGN KEY (idrol) REFERENCES rol(idrol)
);

-- Tabla: producto
CREATE TABLE producto (
    idproducto BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    pronombre VARCHAR(50) NOT NULL,
    prodetalle VARCHAR(512),
    procantstock INT(11),
    proprecio DECIMAL(10,2) DEFAULT 0,
    proimagen VARCHAR(255) DEFAULT NULL
);

-- Tabla: compra
CREATE TABLE compra (
    idcompra BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    coffecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idusuario BIGINT(20),
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
);

-- Tabla: compraitem 
CREATE TABLE compraitem (
    idcompraitem BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    idproducto BIGINT(20),
    idcompra BIGINT(20),
    cicantidad INT(11),
    FOREIGN KEY (idproducto) REFERENCES producto(idproducto),
    FOREIGN KEY (idcompra) REFERENCES compra(idcompra)
);

-- Tabla: compraestadotipo
CREATE TABLE compraestadotipo (
    idcompraestadotipo INT(11) PRIMARY KEY AUTO_INCREMENT,
    cetedescripcion VARCHAR(50),
    cetedetalle VARCHAR(256)
);

-- Tabla: compraestado
CREATE TABLE compraestado (
    idcompraestado BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    idcompra BIGINT(11),
    idcompraestadotipo INT(11),
    cefechaini TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cefechafin TIMESTAMP NULL,
    FOREIGN KEY (idcompra) REFERENCES compra(idcompra),
    FOREIGN KEY (idcompraestadotipo) REFERENCES compraestadotipo(idcompraestadotipo)
);




--INCERCIONES 

-- Tabla: rol
INSERT INTO rol (rodescripcion) VALUES 
('Administrador'),
('Cliente');

-- Tabla: menu
INSERT INTO menu (menombre, medescripcion, idpadre) VALUES
('Inicio', 'Página principal', NULL),
('Productos', 'Listado de productos', NULL),
('Usuarios', 'Gestión de usuarios', NULL),
('Compras', 'Gestión de compras', NULL);

-- Tabla: menuroL
INSERT INTO menuroL (idmenu, idrol) VALUES
(1, 1), (2, 1), (3, 1), (4, 1),  -- Admin
(1, 2), (2, 2);                  -- Cliente

-- Tabla: usuario
INSERT INTO usuario (usnombre, uspass, usmail)
VALUES 
('admin', '1234', 'admin@tienda.com'),
('cliente1', '1234', 'cliente1@correo.com');

-- Tabla: usuariorol
INSERT INTO usuariorol (idusuario, idrol)
VALUES
(1, 1),  -- admin es Administrador
(2, 2);  -- cliente1 es Cliente

-- Tabla: producto
INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio, proimagen)
VALUES
('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', 25, 15999.99, 'auriculares.jpg'),
('Mouse Gamer', 'Mouse óptico RGB con 6 botones', 40, 7999.50, 'mouse.jpg'),
('Teclado Mecánico', 'Teclado retroiluminado con switches azules', 15, 19999.00, 'teclado.jpg'),
('Monitor 24"', 'Monitor LED Full HD 24 pulgadas', 10, 54999.90, 'monitor.jpg'),
('Parlantes Bluetooth', 'Parlantes portátiles con sonido envolvente', 30, 12999.00, 'parlantes.jpg');

-- Tabla: compraestadotipo
INSERT INTO compraestadotipo (cetedescripcion, cetedetalle)
VALUES 
('Iniciada', 'Compra creada por el cliente.'),
('Aceptada', 'Compra aceptada por el sistema.'),
('Enviada', 'Compra despachada.'),
('Cancelada', 'Compra cancelada por el usuario o el sistema.');
