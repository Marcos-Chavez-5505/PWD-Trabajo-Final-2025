<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/configuracion.php';

$data = data_submitted();
$retorno = [];
$respuesta = false;
$mensaje = null;

if (isset($data['idmenu'])) {
    $objC = new AbmMenu();
    $resultado = $objC->baja($data);

    $respuesta = $resultado['respuesta'];
    $mensaje = $resultado['mensaje'];
}

$retorno['respuesta'] = $respuesta;

if (!empty($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);
?>