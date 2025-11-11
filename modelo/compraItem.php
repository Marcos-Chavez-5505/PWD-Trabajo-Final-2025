CREATE TABLE compraitem (
    idcompraitem BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    idproducto BIGINT(20),
    idcompra BIGINT(20),
    cicantidad INT(11) NOT NULL,
    FOREIGN KEY (idproducto) REFERENCES producto(idproducto),
    FOREIGN KEY (idcompra) REFERENCES compra(idcompra)
);

<?php


?>
