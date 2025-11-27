<?php
header('Content-Type: application/json');
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$session = new Session();

if (!$session->activa()) {
    $session->mensajeIniciarSesionCantidadCero();
    exit;
}
$idUsuario = $session->getIdUsuario();
if ($idUsuario){
    $control = new ControlCarrito();
    $control->obtenerCantidadTotalCarritoAction($idUsuario);
}

