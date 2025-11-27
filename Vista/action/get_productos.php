<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
header('Content-Type: application/json');

$session = new Session();
if (!$session->activa()) {
    $session->mensajeIniciarSesion();
    exit;
}

$control = new ControlCarrito();
$control->listarProductosAction();
?>