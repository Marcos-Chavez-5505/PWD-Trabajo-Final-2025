<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

header('Content-Type: application/json');

$session = new Session();
$idUsuario = $session->getIdUsuario();
$idProducto = $_POST['idproducto'] ?? null;

$carrito = new ControlCarrito();
$result = $carrito->eliminarDelCarrito($idUsuario, $idProducto);

echo json_encode($result);
