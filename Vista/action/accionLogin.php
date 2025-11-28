<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$control = new ControlUsuario();
$res = $control->loginDesdeAction($_POST);

header("Location: " . $res['redirect']);
exit;
?>