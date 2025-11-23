<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/ControlCarrito.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";



if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['ok' => false, 'cantidad' => 0]);
    exit;
}

$idUsuario = $_SESSION['idusuario'];

$ctrl = new ControlCarrito();
$items = $ctrl->obtenerItemsSinEstado($idUsuario);

$cantidad = 0;
foreach ($items as $item) {
    $cantidad += (int)$item->getCicantidad();
}

echo json_encode(['ok' => true, 'cantidad' => $cantidad]);
