CREATE TABLE compra (
    idcompra BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    cofecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idusuario BIGINT(20),
    FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
);

<?php


?>