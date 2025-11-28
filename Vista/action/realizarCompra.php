<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$idUsuario = $input['idUsuario'] ?? null;

$control = new ControlCarrito();
$respuesta = $control->procesarCompra($idUsuario);

$session = new Session();
$session->manejarFlash($respuesta['flash']);

echo json_encode($respuesta);
?>