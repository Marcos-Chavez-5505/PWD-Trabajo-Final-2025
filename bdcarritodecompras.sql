CREATE DATABASE IF NOT EXISTS bdcarritocompras;
USE bdcarritocompras;

-- Tabla: rol
CREATE TABLE rol (
    idrol BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    rodescripcion VARCHAR(50)
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
    usnombre VARCHAR(50),
    uspass INT(11),
    usmail VARCHAR(50),
    usdeshabilitado TIMESTAMP NULL
);

-- Tabla: usuariorol (relación muchos a muchos entre usuario y rol)
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
    pronombre VARCHAR(50),
    prodetalle VARCHAR(512),
    procantstock INT(11)
);

-- Tabla: compra
CREATE TABLE compra (
    idcompra BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    coffecha TIMESTAMP NULL,
    idusuario BIGINT(20),
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
);

-- Tabla: compraitem (detalle de compra)
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
    cefechaini TIMESTAMP NULL,
    cefechafin TIMESTAMP NULL,
    FOREIGN KEY (idcompra) REFERENCES compra(idcompra),
    FOREIGN KEY (idcompraestadotipo) REFERENCES compraestadotipo(idcompraestadotipo)
);
