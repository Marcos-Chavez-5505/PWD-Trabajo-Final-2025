<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
$valorEncapsulado = new ValorEncapsulado();
$idUsuario = intval($valorEncapsulado->obtenerValor('idUsuario'));

$control = new ControlUsuario();
$res = $control->eliminarUsuarioDesdeAction($idUsuario);

header("Location: " . $res['redirect']);
exit;
?>