CREATE TABLE menu (
    idmenu BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    menombre VARCHAR(50),
    medescripcion VARCHAR(124),
    idpadre BIGINT(20),
    medeshabilitado TIMESTAMP NULL
);
<?php

?>