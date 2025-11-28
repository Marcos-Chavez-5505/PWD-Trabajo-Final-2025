<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/ControlMenu.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/estructura/menuHeader.php";

$ctrl = new ControlMenu();
$menus = $ctrl->obtenerMenuParaHeader();

// Esto hace que imprimirMenu se guarde en memoria
ob_start();
imprimirMenu($menus);
$menuHTML = ob_get_clean(); 

// Enviar HTML del menú
echo $menuHTML;

// Esto permite que otros scripts se ejecuten cuando el menú esté listo
echo "<script>document.dispatchEvent(new Event('menuCargado'));</script>";
?>