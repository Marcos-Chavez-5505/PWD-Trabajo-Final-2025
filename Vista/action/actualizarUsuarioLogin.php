<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

$control = new ControlUsuario();

$resultado = $control->manejarEdicion($_REQUEST);

if ($resultado['tipo'] === 'redirect') {
    header("Location: {$resultado['destino']}");
    exit();
}

$usuario = $resultado['usuario'];
$listaRoles = $resultado['roles'];
$idRolActual = $resultado['rolActual'];
$datosVista = $resultado['datosVista'];
?>