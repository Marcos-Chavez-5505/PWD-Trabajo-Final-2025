<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    $session->mensajeIniciarSesion();
    exit;
}
$idUsuario = $session->getIdUsuario();
if ($idUsuario){
    $control = new ControlCarrito();
    $control->agregarProductoCarritoAction($idUsuario);
}
?>