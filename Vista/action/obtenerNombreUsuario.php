<?php
// Se usa en modificarCuenta.php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$session = new Session();
$usuarioActual = $session->getUsuario() ?? null;
?>