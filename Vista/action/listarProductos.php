<?php
// Este archivo se usa en index.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$productoControl = new ControlProducto();
$productos = $productoControl->productosParaVista();

?>