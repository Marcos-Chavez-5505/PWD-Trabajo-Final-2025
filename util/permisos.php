<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

return [
    '/PWD-TP-FINAL/Vista/admin/listarCompras.php'         => [controlUsuarioRol::ADMIN],
    '/PWD-TP-FINAL/Vista/admin/menus.php'                 => [controlUsuarioRol::ADMIN],
    '/PWD-TP-FINAL/Vista/admin/productos.php'             => [controlUsuarioRol::ADMIN],
    '/PWD-TP-FINAL/Vista/admin/usuarios.php'              => [controlUsuarioRol::ADMIN],
    '/PWD-TP-FINAL/Vista/private/modificarCuenta.php'     => [controlUsuarioRol::CLIENTE, controlUsuarioRol::ADMIN],
    '/PWD-TP-FINAL/Vista/private/misCompras.php'           => [controlUsuarioRol::CLIENTE],
];
?>