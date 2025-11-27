este seria para el aumentarProducto.php

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$session = new Session();
$idUsuario = $session->getIdUsuario();
$idProducto = $_POST['idproducto'] ?? null;

$control = new ControlCompra();
$result = $control->aumentarCantidadProducto($idUsuario, $idProducto);

echo json_encode($result);
?>