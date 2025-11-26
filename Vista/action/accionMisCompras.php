<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PWD-TP-FINAL/configuracion.php";

$session = new Session();

$idUsuario = $session->getIdusuario();

if ($idUsuario){
  $controlCompra = new ControlCompra();
  $compras = $controlCompra->obtenerComprasDetalladasPorUsuario($idUsuario);
}
?>