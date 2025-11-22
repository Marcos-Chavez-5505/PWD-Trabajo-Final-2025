<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
$permisos = include $_SERVER['DOCUMENT_ROOT'].'/PWD-TP-FINAL/util/permisos.php';

$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);

$rolesPermitidos = $permisos[$path] ?? [];

AuthMiddleware::requireRole($rolesPermitidos);
?>