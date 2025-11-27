<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$controlCompra = new ControlCompra();

$resultado = $controlCompra->obtenerComprasConEstadoAdmin();

header('Content-Type: application/json');
echo json_encode($resultado);
?>
