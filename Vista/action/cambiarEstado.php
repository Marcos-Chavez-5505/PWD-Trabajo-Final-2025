<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$valor = new ValorEncapsulado();

$idCompra = $valor->obtenerValor('idcompra');
$accion = $valor->obtenerValor('accion');

$control = new ControlCompraEstado();
$response = $control->procesar($idCompra, $accion);

header('Content-Type: application/json');
echo json_encode($response);
?>