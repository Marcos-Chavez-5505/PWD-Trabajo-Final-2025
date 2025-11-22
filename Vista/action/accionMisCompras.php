<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/Vista/action/accionRolesPermitidos.php";
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/configuracion.php";

$session = new Session();
$session->validar(2);

$idUsuario = $session->getIdusuario();

$controlCompra = new ControlCompra();
$compras = $controlCompra->obtenerComprasDetalladasPorUsuario($idUsuario);
?>