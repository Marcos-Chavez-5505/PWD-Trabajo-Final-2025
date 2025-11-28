<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

header('Content-Type: application/json');

$session = new Session();

if (!$session->activa()) {
    echo json_encode($session->mensajeIniciarSesion());
    exit;
}
$idUsuario = $session->getIdUsuario();
if ($idUsuario){
    $control = new ControlCarrito();
    echo json_encode($control->agregarProductoCarritoAction($idUsuario));
}
?>