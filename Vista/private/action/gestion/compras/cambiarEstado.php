<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$response = ['success' => false];

if (isset($_POST['idcompra']) && isset($_POST['accion'])) {
    $idCompra = $_POST['idcompra'];
    $accion = $_POST['accion'];
    $controlCompraEstado = new ControlCompraEstado();
    $response = $controlCompraEstado->procesarEstadoCompra($idCompra, $accion);
} else {
    $response['error'] = 'Datos incompletos';
}

header('Content-Type: application/json');
echo json_encode($response);
?>