<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

$objControl = new AbmMenu();
$combo = $objControl->listarOpciones(null);

include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/Vista/private/admin/menus.php"
?>