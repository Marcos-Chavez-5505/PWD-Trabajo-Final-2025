CREATE TABLE compraestado (
    idcompraestado BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    idcompra BIGINT(20),
    idcompraestadotipo INT(11),
    cefechaini TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cefechafin TIMESTAMP NULL,
    FOREIGN KEY (idcompra) REFERENCES compra(idcompra)
    FOREIGN KEY (idcompraestadotipo) REFERENCES compraestadotipo(idcompraestadotipo)
);


<?php


?>
