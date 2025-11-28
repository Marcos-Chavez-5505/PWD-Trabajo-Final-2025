<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
header("Content-Type: application/json");

$session = new Session();
$data = data_submitted();

$control = new ControlUsuario();
echo json_encode($control->cambiarPassDesdeAction($session, $data));
?>