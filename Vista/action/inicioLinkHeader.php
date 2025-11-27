<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$session = new Session();
$salida = $session->linkLogoHeader();
?>