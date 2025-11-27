<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/control/ControlMenu.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/vista/estructura/menuHeader.php";

$ctrl = new ControlMenu();
$menus = $ctrl->obtenerMenuParaHeader();

ob_start();
imprimirMenu($menus);
$menuHTML = ob_get_clean();

echo $menuHTML;
echo "<script>document.dispatchEvent(new Event('menuCargado'));</script>";



?>
