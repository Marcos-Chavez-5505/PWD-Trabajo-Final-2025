<?php
// Se usa en modificarCuenta.php e index.php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$session = new Session();
$usuarioActual = $session->getUsuario() ?? null;
$usuarioActivo = $session->activa();
?>